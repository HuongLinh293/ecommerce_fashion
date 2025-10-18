<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class VisaController extends Controller
{
    public function visaPayment()
    {
        $order = Session::get('order');
        if (!$order) return redirect()->route('checkout.index');

        // Giả lập thanh toán thành công
        Session::forget('cart');
        return redirect()->route('checkout.success')->with('success', 'Thanh toán bằng thẻ Visa thành công!');
    }
}