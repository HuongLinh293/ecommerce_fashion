<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VNPayController extends Controller
{
    /**
     * Tạo URL thanh toán VNPay từ thông tin đơn hàng trong session
     */
    public function payment()
    {
        $order = Session::get('order');

        if (!$order) {
            return redirect()->route('checkout.index')
                ->with('error', 'Không tìm thấy thông tin đơn hàng để thanh toán!');
        }

        // ✅ Lấy cấu hình từ .env
        $vnp_TmnCode    = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url        = env('VNPAY_URL');
        $vnp_ReturnUrl  = env('VNPAY_RETURN_URL');

        $vnp_TxnRef    = $order['order_id']; // Mã đơn hàng
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $order['order_id'];
        $vnp_OrderType = "billpayment";
        $vnp_Amount    = $order['total'] * 100; // nhân 100 theo yêu cầu VNPAY
        $vnp_Locale    = 'vn';
        $vnp_BankCode  = ''; // để trống để hiển thị tất cả ngân hàng
        $vnp_IpAddr    = request()->ip();

        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_TmnCode"   => $vnp_TmnCode,
            "vnp_Amount"    => $vnp_Amount,
            "vnp_Command"   => "pay",
            "vnp_CreateDate"=> date('YmdHis'),
            "vnp_CurrCode"  => "VND",
            "vnp_IpAddr"    => $vnp_IpAddr,
            "vnp_Locale"    => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef"    => $vnp_TxnRef,
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // ✅ Sắp xếp key trước khi hash
        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $paymentUrl = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect()->away($paymentUrl);
    }

    /**
     * Xử lý phản hồi từ VNPay sau khi thanh toán
     */
    public function return(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $inputData = $request->all();

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = urldecode(http_build_query($inputData));
        $calculatedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($calculatedHash === $vnp_SecureHash) {
            if (isset($inputData['vnp_ResponseCode']) && $inputData['vnp_ResponseCode'] == '00') {
                // ✅ Thanh toán thành công
                // Persist a Payment record if not already present
                try {
                    $orderId = $inputData['vnp_TxnRef'] ?? null;
                    $amountRaw = $inputData['vnp_Amount'] ?? null; // usually multiplied by 100
                    $amount = null;
                    if ($amountRaw !== null) {
                        // vnpay often returns amount in VND * 100
                        $amount = (int) $amountRaw;
                        // if it looks like it's multiplied by 100, divide
                        if ($amount > 1000 && $amount % 100 === 0) {
                            $amount = intdiv($amount, 100);
                        }
                    }

                    $tx = $inputData['vnp_TransactionNo'] ?? $inputData['vnp_BankTranNo'] ?? $inputData['vnp_TransNo'] ?? null;

                    if ($orderId) {
                        $orderModel = \App\Models\Order::find($orderId);
                        if ($orderModel) {
                            // avoid creating duplicate "paid" payments for same order/method/tx
                            $exists = \App\Models\Payment::where('order_id', $orderModel->id)
                                ->where('method', 'vnpay')
                                ->where(function ($q) use ($tx) {
                                    if ($tx) {
                                        $q->where('transaction_id', $tx);
                                    } else {
                                        $q->where('status', 'paid');
                                    }
                                })->exists();

                            if (! $exists) {
                                \App\Models\Payment::create([
                                    'order_id' => $orderModel->id,
                                    'method' => 'vnpay',
                                    'status' => 'paid',
                                    'amount' => $amount ?? ($orderModel->total ?? 0),
                                    'transaction_id' => $tx,
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // swallow errors to avoid blocking user flow; log for diagnostics
                    \Illuminate\Support\Facades\Log::error('VNPay return handler error: ' . $e->getMessage());
                }

                Session::forget('cart');
                Session::forget('order');

                return redirect()->route('checkout.success')
                    ->with('success', 'Thanh toán VNPay thành công!');
            } else {
                return redirect()->route('checkout.index')
                    ->with('error', 'Thanh toán thất bại. Mã lỗi: ' . ($inputData['vnp_ResponseCode'] ?? ''));
            }
        } else {
            return redirect()->route('checkout.index')
                ->with('error', 'Chữ ký VNPay không hợp lệ!');
        }
    }
}