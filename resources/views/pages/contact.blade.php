@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-center mb-8">Liên hệ với chúng tôi</h1>

    {{-- Form liên hệ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-semibold mb-4">Gửi tin nhắn</h2>
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Họ và tên</label>
                    <input type="text" name="name" id="name" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium mb-1">Tin nhắn</label>
                    <textarea name="message" id="message" rows="4" required
                              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Gửi
                </button>
            </form>
        </div>

        {{-- Thông tin liên hệ --}}
        <div>
            <h2 class="text-xl font-semibold mb-4">Thông tin liên hệ</h2>
            <ul class="space-y-2 text-gray-700">
                <li><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP.HCM</li>
                <li><strong>Email:</strong> support@vivillan.vn</li>
                <li><strong>Điện thoại:</strong> 0123 456 789</li>
            </ul>

            {{-- Google Maps --}}
            <div class="mt-6">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5023057397885!2d106.7004238742875!3d10.773374259236254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f469f94cb0f%3A0x75bcb440bd7d1ec5!2zMTIzIMSQLiBBQkMsIFF14bqtbiAxLCBUUC5IQ00!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
                    width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
