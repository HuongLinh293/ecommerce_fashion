<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string'
        ]);

        // Ở đây bạn có thể gửi mail hoặc lưu DB
        // Ví dụ gửi mail:
        // Mail::to('admin@vivillan.vn')->send(new ContactMail($data));

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn đã liên hệ!');
    }
}