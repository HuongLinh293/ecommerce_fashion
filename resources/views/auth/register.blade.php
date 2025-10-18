@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-16">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-light mb-3" style="font-family: 'Playfair Display', serif">
                    Create Account
                </h1>
                <p class="text-sm opacity-60">Tham gia cộng đồng VIVILLAN ngay hôm nay</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-red-600 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Họ và tên
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Nguyễn Văn A"
                        required
                        class="w-full pl-4 pr-4 py-3 border border-gray-300 focus:border-black focus:outline-none text-sm"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="your@email.com"
                        required
                        class="w-full pl-4 pr-4 py-3 border border-gray-300 focus:border-black focus:outline-none text-sm"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Mật khẩu
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="••••••••"
                        required
                        class="w-full pl-4 pr-4 py-3 border border-gray-300 focus:border-black focus:outline-none text-sm"
                    >
                    <p class="text-xs opacity-60 mt-1">Tối thiểu 6 ký tự</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs uppercase tracking-[0.15em] mb-2 opacity-60">
                        Xác nhận mật khẩu
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="••••••••"
                        required
                        class="w-full pl-4 pr-4 py-3 border border-gray-300 focus:border-black focus:outline-none text-sm"
                    >
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="terms" name="terms" class="mt-1 w-4 h-4 border-gray-300 rounded focus:ring-black" required>
                    <label for="terms" class="text-xs opacity-80 leading-relaxed">
                        Tôi đồng ý với
                        <a href="#" class="underline hover:opacity-60">Điều khoản sử dụng</a> và
                        <a href="#" class="underline hover:opacity-60">Chính sách bảo mật</a> của VIVILLAN
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-black text-white py-4 text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors">
                    Đăng ký
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase tracking-[0.15em]">
                    <span class="bg-white px-4 opacity-60">hoặc</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm opacity-60">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-black underline hover:opacity-60 transition-opacity">
                        Đăng nhập
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
