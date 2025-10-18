@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-16">
        <div class="max-w-md mx-auto">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-3xl font-light mb-3" style="font-family: 'Playfair Display', serif;">
                    Welcome Back
                </h1>
                <p class="text-sm opacity-60">
                    Đăng nhập vào tài khoản VIVILLAN của bạn
                </p>
            </div>

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 p-3 rounded">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Email
                    </label>
                    <div class="relative">
                        <!-- <x-lucide-mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-40" /> -->
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="your@email.com"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 focus:border-black focus:outline-none transition-colors text-sm"
                            required
                        />
                    </div>
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <label for="password" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <!-- <x-lucide-lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-40" /> -->
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full pl-12 pr-12 py-3 border border-gray-300 focus:border-black focus:outline-none transition-colors text-sm"
                            required
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors"
                        >
                            <!-- icon mắt đóng/mở, có thể thêm SVG hoặc bỏ qua nếu chưa cần -->
                        </button>
                    </div>
                </div>

                {{-- Forgot password --}}
                <div class="text-right">
                    <a href="#" class="text-xs uppercase tracking-[0.15em] opacity-60 hover:opacity-100 underline" style="pointer-events:none;opacity:0.5;">
                        Quên mật khẩu?
                    </a>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-black text-white py-4 text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors"
                >
                    Đăng nhập
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase tracking-[0.15em]">
                    <span class="bg-white px-4 opacity-60">hoặc</span>
                </div>
            </div>

            {{-- Register Link --}}
            <div class="text-center space-y-4">
                <p class="text-sm opacity-60">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-black underline hover:opacity-60 transition-opacity">
                        Đăng ký ngay
                    </a>
                </p>
            </div>

            {{-- Admin Access --}}
            <!-- Admin Dashboard link đã bị vô hiệu hóa vì chưa có route -->

            {{-- Demo Info --}}
            <div class="mt-12 p-6 bg-gray-50 border border-gray-200">
                <h3 class="text-xs uppercase tracking-[0.15em] mb-3 opacity-60">Demo Account</h3>
                <p class="text-xs leading-relaxed opacity-60 mb-3">
                    Đây là demo authentication. Bạn có thể đăng ký tài khoản mới hoặc sử dụng:
                </p>
                <div class="space-y-1 text-xs opacity-80 font-mono">
                    <p>Email: demo@vivillan.com</p>
                    <p>Password: demo123</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
