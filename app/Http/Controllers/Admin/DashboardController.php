<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Kiểm tra quyền truy cập
        if (!Auth::check() || !Auth::user()->is_admin) {
            return redirect('/login')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        // ==== Tổng quan hiện tại ====
    $totalRevenue = Order::sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::count();
        $totalProducts = Product::count();

        // ==== Tổng quan tháng trước ====
        $lastMonth = Carbon::now()->subMonth();
        $prevRevenue = Order::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('total');
        $prevOrders = Order::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        $prevCustomers = User::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        $prevProducts = Product::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        // ==== % thay đổi ==== 
        $stats = [
            'revenue' => $totalRevenue,
            'orders' => $totalOrders,
            'customers' => $totalCustomers,
            'products' => $totalProducts,
            'revenueChange' => $prevRevenue ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0,
            'ordersChange' => $prevOrders ? round((($totalOrders - $prevOrders) / $prevOrders) * 100, 1) : 0,
            'customersChange' => $prevCustomers ? round((($totalCustomers - $prevCustomers) / $prevCustomers) * 100, 1) : 0,
            'productsChange' => $prevProducts ? round((($totalProducts - $prevProducts) / $prevProducts) * 100, 1) : 0,
        ];

        // ==== Đơn hàng mới nhất (5 đơn gần nhất) ====
        $recentOrders = Order::with(['items', 'user'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function($order) {
                $firstItem = $order->items->first();
                return (object) [
                    'order_number' => $order->id,
                    'customer_name' => $order->user->name ?? $order->shipping_name ?? '-',
                    'product_name' => $firstItem ? $firstItem->product_name : '-',
                    'total_amount' => $order->total_amount ?? $order->total ?? 0,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'created_at' => $order->created_at,
                ];
            });

        // ==== Sản phẩm bán chạy (top 5 theo số lượng bán) ====
        $topProducts = Product::withCount('orderItems')
            ->with('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get()
            ->map(function($product) {
                $totalRevenue = $product->orderItems->sum('subtotal');
                return (object) [
                    'name' => $product->name,
                    'order_items_count' => $product->order_items_count,
                    'total_revenue' => $totalRevenue,
                ];
            });

        // ==== Doanh thu theo ngày trong tuần ====
        $revenueByDay = Order::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('SUM(total) as total')
            )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        // ==== Đơn hàng theo ngày ====
        $ordersByDay = Order::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        // ==== Doanh thu theo tháng ====
        $revenueByMonth = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // ==== Revenue data: support period selection (day, week, month) ====
        $periodParam = $request->get('period', 'day');
        $period = in_array($periodParam, ['day', 'week', 'month']) ? $periodParam : 'day';

        $revenueData = collect();

        if ($period === 'day') {
            // Last 7 days (daily)
            $start = Carbon::now()->subDays(6)->startOfDay();
            $end = Carbon::now()->endOfDay();
            $raw = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as revenue')
                )
                ->whereBetween('created_at', [$start, $end])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            for ($i = 0; $i < 7; $i++) {
                $d = $start->copy()->addDays($i)->toDateString();
                $row = $raw->firstWhere('date', $d);
                $revenueData->push((object)[
                    'label' => Carbon::parse($d)->format('d/m'),
                    'revenue' => $row ? (float) $row->revenue : 0,
                ]);
            }
        } elseif ($period === 'week') {
            // Last 12 weeks (weekly sum)
            $weeks = 12;
            $currentWeekStart = Carbon::now()->startOfWeek();
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $startWeek = $currentWeekStart->copy()->subWeeks($i)->startOfWeek();
                $endWeek = $startWeek->copy()->endOfWeek();
                $sum = Order::whereBetween('created_at', [$startWeek, $endWeek])->sum('total');
                $label = 'Tuần ' . $startWeek->format('W');
                $revenueData->push((object)[
                    'label' => $label,
                    'revenue' => (float) $sum,
                ]);
            }
        } else {
            // month (last 12 months)
            $months = 12;
            $currentMonthStart = Carbon::now()->startOfMonth();
            for ($i = $months - 1; $i >= 0; $i--) {
                $startMonth = $currentMonthStart->copy()->subMonths($i)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();
                $sum = Order::whereBetween('created_at', [$startMonth, $endMonth])->sum('total');
                $label = $startMonth->format('m/Y');
                $revenueData->push((object)[
                    'label' => $label,
                    'revenue' => (float) $sum,
                ]);
            }
        }

        // ==== Orders by status ====
        $ordersByStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // ==== Payment methods count ====
        $paymentMethods = [
            'cod' => Order::where('payment_method', 'cod')->count(),
            'momo' => Order::where('payment_method', 'momo')->count(),
            'vnpay' => Order::where('payment_method', 'vnpay')->count(),
            'visa' => Order::where('payment_method', 'visa')->count(),
        ];

        // ==== Category sales (sum of order_items.subtotal grouped by products.category) ====
        $categorySales = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.category', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('products.category')
            ->get();

        // ✅ Truyền toàn bộ dữ liệu sang view
        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'revenueByDay' => $revenueByDay,
            'ordersByDay' => $ordersByDay,
            'revenueByMonth' => $revenueByMonth,
            'revenueData' => $revenueData,
            'ordersByStatus' => $ordersByStatus,
            'paymentMethods' => $paymentMethods,
            'categorySales' => $categorySales,
        ]);
    }
}