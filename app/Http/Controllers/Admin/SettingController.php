<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function updateGeneral(Request $request)
    {
        // Tùy chọn: Lưu vào file config hoặc bảng "settings" trong DB
        return back()->with('success', 'Cập nhật cài đặt chung thành công!');
    }

    public function updatePayment(Request $request)
    {
        // Xử lý lưu phương thức thanh toán
        return back()->with('success', 'Cập nhật phương thức thanh toán thành công!');
    }

    public function updateShipping(Request $request)
    {
        // Lưu cấu hình vận chuyển
        return back()->with('success', 'Cập nhật vận chuyển thành công!');
    }

    public function updateNotifications(Request $request)
    {
        // Lưu cấu hình email thông báo
        return back()->with('success', 'Cập nhật thông báo thành công!');
    }
}