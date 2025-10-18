<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Trang checkout
     */
    public function index(Request $request)
    {
        // ✅ Lấy danh sách sản phẩm trong giỏ
        $cartItems = Cart::getContent()->map(function ($item) {
            // Normalize attributes whether stored as array or object
            $image = data_get($item, 'attributes.image') ?? data_get($item, 'image') ?? '';
            $size = data_get($item, 'attributes.size') ?? data_get($item, 'size') ?? '';
            $color = data_get($item, 'attributes.color') ?? data_get($item, 'color') ?? '';

            return [
                'id'       => data_get($item, 'id'),
                'name'     => data_get($item, 'name'),
                'quantity' => data_get($item, 'quantity'),
                'price'    => data_get($item, 'price'),
                'image'    => $image,
                'size'     => $size,
                'color'    => $color,
            ];
        })->toArray(); // ⚠️ thêm toArray ở đây

        // ✅ Tổng tiền
        $cart = [
            'items'      => $cartItems,
            'totalPrice' => Cart::getTotal(),
        ];

        // ✅ Nếu giỏ hàng rỗng → điều hướng về trang giỏ
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // ✅ Lấy phương thức thanh toán (nếu có từ query string)
        $paymentMethod = $request->query('payment', null);

        return view('pages.checkout', compact('cart', 'paymentMethod'));
    }

    /**
     * Xử lý đặt hàng & điều hướng phương thức thanh toán
     */
    public function process(Request $request)
    {
        $method = $request->input('payment_method');
        $amount = (int) Cart::getTotal();

        if ($amount <= 0) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Lưu đơn hàng vào database
        $order = new \App\Models\Order();
        $order->user_id = Auth::id();
        // persist both shipping and customer fields so admin views have the data
        $order->shipping_name = $request->input('name');
        $order->shipping_phone = $request->input('phone');
        $order->shipping_address = $request->input('address');
        $order->email = $request->input('email');
        // customer_* fields used across admin panels and syncing
        $order->customer_name = $request->input('name');
        $order->customer_email = $request->input('email');
        $order->customer_phone = $request->input('phone');
        $order->status = 'pending';
        $order->payment_method = $method;
        $order->total = $amount;
        $order->save();

        // Lưu từng sản phẩm vào order_items
        foreach (Cart::getContent() as $item) {
            $order->items()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'product_image' => data_get($item, 'attributes.image') ?? data_get($item, 'image') ?? '',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'size' => data_get($item, 'attributes.size') ?? data_get($item, 'size') ?? '',
                'color' => data_get($item, 'attributes.color') ?? data_get($item, 'color') ?? '',
                'subtotal' => $item->price * $item->quantity,
            ]);
        }
        // If frontend sent a confirmation that customer completed Momo/VNPay flow (after scanning QR), treat as paid
        $paymentConfirmed = $request->input('payment_confirmed');

        if (in_array($method, ['momo', 'vnpay']) && $paymentConfirmed) {
            // create payment record as 'paid' to match DB enum values
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'method' => $method,
                'status' => 'paid',
                'amount' => $amount,
                'transaction_id' => $request->input('transaction_id') ?? null,
            ]);

            Cart::clear();
            return redirect()->route('checkout.success')
                ->with('success', 'Thanh toán thành công!');
        }

        switch ($method) {
            case 'vnpay':
                return $this->createVnpayUrl($order->id, $amount);

            case 'visa':
                Cart::clear();
                return redirect()->route('checkout.success')
                    ->with('success', 'Thanh toán bằng thẻ Visa thành công (demo).');

            case 'cod':
                Cart::clear();
                return redirect()->route('checkout.success')
                    ->with('success', 'Đặt hàng thành công! Thanh toán khi nhận hàng.');

            default:
                return back()->with('error', 'Vui lòng chọn phương thức thanh toán.');
        }
    }

    /**
     * Tạo URL thanh toán VNPAY
     */
    protected function createVnpayUrl($orderId, $amount)
    {
        $vnp_Url        = config('app.vnpay_url');
        $vnp_ReturnUrl  = config('app.vnpay_return_url');
        $vnp_TmnCode    = config('app.vnpay_tmn_code');
        $vnp_HashSecret = config('app.vnpay_hash_secret');

        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_TmnCode"   => $vnp_TmnCode,
            "vnp_Amount"    => $amount * 100,
            "vnp_Command"   => "pay",
            "vnp_CreateDate"=> date('YmdHis'),
            "vnp_CurrCode"  => "VND",
            "vnp_IpAddr"    => request()->ip(),
            "vnp_Locale"    => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng #" . $orderId,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef"    => $orderId,
        ];

        ksort($inputData);
        $query = [];
        foreach ($inputData as $key => $value) {
            $query[] = urlencode($key) . "=" . urlencode($value);
        }
        $hashData = implode('&', $query);
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        return redirect()->away($vnp_Url . "?" . $hashData . "&vnp_SecureHash=" . $vnpSecureHash);
    }

    /**
     * Kết quả trả về từ VNPAY
     */
    public function vnpayReturn(Request $request)
    {
        if ($request->vnp_ResponseCode == "00") {
            Cart::clear();
            return redirect()->route('checkout.success')
                ->with('success', 'Thanh toán VNPAY thành công!');
        }

        return redirect()->route('checkout.fail')
            ->with('error', 'Thanh toán VNPAY thất bại!');
    }

    public function success()
    {
        return view('pages.checkout-success');
    }

    public function fail()
    {
        return view('pages.checkout-fail');
    }
}