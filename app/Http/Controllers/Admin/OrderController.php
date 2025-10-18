<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng
     */
 public function index()
{
    // Lấy dữ liệu thống kê
    $stats = [
        'total' => \App\Models\Order::count(),
        'pending' => \App\Models\Order::where('status', 'pending')->count(),
        'shipping' => \App\Models\Order::where('status', 'shipped')->count(),
        'completed' => \App\Models\Order::where('status', 'completed')->count(),
    ];

    // Lấy danh sách đơn hàng (include item count and items relationship)
    $orders = \App\Models\Order::withCount('items')
        ->with(['items.product', 'customer'])
        ->latest()
        ->paginate(10);

    // Truyền cả hai biến vào view
    return view('admin.orders.index', compact('orders', 'stats'));
}


    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng (pending → shipped, ...)
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Xóa đơn hàng
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã xóa đơn hàng.');
    }

    /**
     * Tính lại tổng tiền nếu cần (ví dụ khi admin chỉnh sửa item)
     */
    public function recalc($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $order->updateTotal();

        return redirect()->back()->with('success', 'Đã tính lại tổng tiền đơn hàng.');
    }
    public function updateStatus(\Illuminate\Http\Request $request, \App\Models\Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,completed,cancelled',
    ]);

    $order->update([
        'status' => $request->status,
    ]);

    // After updating status, ensure customer record exists/linked for this order
    try {
        $order->upsertCustomerRecord();
    } catch (\Exception $e) {
        // swallow to avoid blocking admin action; could log in future
    }

    return redirect()
        ->route('admin.orders.show', $order->id)
        ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
}

    // helper removed; uses Order::upsertCustomerRecord

}