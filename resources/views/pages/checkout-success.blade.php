@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-white px-6 py-20">
    <h1 class="text-3xl mb-6 font-serif tracking-widest">🎉 CẢM ƠN BẠN!</h1>
    <p class="mb-8 text-gray-600">Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ giao hàng sớm nhất.</p>
    <a href="{{ route('home') }}" class="px-8 py-3 bg-black text-white hover:bg-gray-800 transition">
        Quay về Trang Chủ
    </a>
</div>
@endsection
