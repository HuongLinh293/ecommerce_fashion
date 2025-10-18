<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ✅ Xác thực đầu vào
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ✅ Kiểm tra đăng nhập
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            // ✅ Nếu là admin → về /admin/dashboard
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/dashboard');
            }

            // ✅ Nếu không phải admin → về trang chủ
            return redirect()->intended('/');
        }

        // ❌ Sai tài khoản hoặc mật khẩu
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}