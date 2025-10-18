<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Lọc tìm kiếm nếu có
        $query = Payment::with('order');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('transaction_id', 'like', "%$search%")
                  ->orWhereHas('order', function ($q) use ($search) {
                      $q->where('order_number', 'like', "%$search%");
                  });
        }

        $payments = $query->latest()->paginate(10);

        // Thống kê ví dụ
        $stats = [
            'total_amount' => Payment::sum('amount'),
            'total_count' => Payment::count(),
            'momo_amount' => Payment::where('method', 'momo')->sum('amount'),
            'momo_count' => Payment::where('method', 'momo')->count(),
            'vnpay_amount' => Payment::where('method', 'vnpay')->sum('amount'),
            'vnpay_count' => Payment::where('method', 'vnpay')->count(),
            'cod_amount' => Payment::where('method', 'cod')->sum('amount'),
            'cod_count' => Payment::where('method', 'cod')->count(),
        ];

        // Phân bố thanh toán
        $totalAmount = $stats['total_amount'] ?: 1;
        $paymentDistribution = [
            ['method' => 'Momo', 'amount' => $stats['momo_amount'], 'percentage' => round($stats['momo_amount'] / $totalAmount * 100), 'color' => 'bg-purple-500'],
            ['method' => 'VNPay', 'amount' => $stats['vnpay_amount'], 'percentage' => round($stats['vnpay_amount'] / $totalAmount * 100), 'color' => 'bg-blue-500'],
            ['method' => 'COD', 'amount' => $stats['cod_amount'], 'percentage' => round($stats['cod_amount'] / $totalAmount * 100), 'color' => 'bg-green-500'],
        ];

        // Lấy 5 giao dịch gần nhất
        $recentPayments = Payment::with('order')->latest()->take(5)->get();

        return view('admin.payments.index', compact('payments', 'stats', 'paymentDistribution', 'recentPayments'));
    }
}