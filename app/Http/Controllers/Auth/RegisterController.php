<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms' => 'accepted',
        ], [
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Also ensure there's a Customer record for admin views
        try {
            Customer::firstOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'phone' => $request->input('phone') ?? null,
                'total_spent' => 0,
            ]);
        } catch (\Exception $e) {
            // don't block registration on customer table issues
            \Illuminate\Support\Facades\Log::warning('Failed creating customer for new user: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với VIVILLAN');
    }
}