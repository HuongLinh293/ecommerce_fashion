<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng của user
     */
    public function index()
    {
    return view('pages.orders.index');
    }

    /**
     * Trang checkout
     */
    public function checkout()
    {
        // Nếu chưa đăng nhập → chuyển sang login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy giỏ hàng từ session (hoặc bạn có thể đổi sang Cart model nếu có)
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống.');
        }

        return view('pages.checkout', [
            'cart' => $cart,
            'total' => $this->calculateCartTotal($cart),
        ]);
    }

    /**
     * Xử lý đặt hàng
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'payment_method'   => 'required|string',
            'email'           => 'nullable|email|max:255',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống.');
        }

        try {
            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id'          => Auth::id(),
                'customer_name'    => $request->shipping_name,
                'customer_phone'   => $request->shipping_phone,
                'customer_email'   => $request->email ?? Auth::user()->email,
                'shipping_name'    => $request->shipping_name,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'status'          => 'pending',
                'total'           => $this->calculateCartTotal($cart),
            ]);

          
            foreach ($cart as $productId => $item) {
                // Normalize various possible cart shapes: some carts store attributes under 'attributes',
                // others store flat keys. Use null-coalescing to pick available values.
                $productName = $item['name'] ?? $item['product_name'] ?? '';
                $productImage = $item['attributes']['image'] ?? $item['image'] ?? null;
                $size = $item['attributes']['size'] ?? $item['size'] ?? null;
                $color = $item['attributes']['color'] ?? $item['color'] ?? null;
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $productId,
                    'product_name'  => $productName,
                    'product_image' => $productImage,
                    'price'         => $price,
                    'quantity'      => $quantity,
                    'size'          => $size,
                    'color'         => $color,
                    'subtotal'      => ($price * $quantity),
                ]);
            }

            // Tạo thông tin thanh toán
            Payment::create([
                'order_id'       => $order->id,
                'method'         => $request->payment_method,
                'status'         => 'pending',
                'amount'         => $order->total ?? 0,
                'transaction_id' => null,
            ]);

            // Upsert customer info using model helper
            try {
                $order->upsertCustomerRecord();
            } catch (\Exception $e) {
                // ignore to avoid blocking checkout
            }

            // Xóa giỏ hàng
            session()->forget('cart');

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    /**
     * Trang chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pages.orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if (in_array($order->status, ['pending', 'processing'])) {
            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Đơn hàng đã được hủy.');
        }

        return back()->with('error', 'Không thể hủy đơn hàng này.');
    }

    
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}