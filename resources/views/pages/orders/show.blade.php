@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">🧾 Chi tiết đơn hàng #{{ $order->id }}</h1>

    {{-- Thông báo --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    {{-- Thông tin chung --}}
    <div class="bg-white rounded shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h2 class="text-lg font-semibold mb-2">📅 Thông tin đơn hàng</h2>
                <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> 
                    <span class="px-2 py-1 rounded text-sm 
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ $order->status_label }}
                    </span>
                </p>
            </div>
            <div>
                <h2 class="text-lg font-semibold mb-2">📍 Địa chỉ giao hàng</h2>
                <p>{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="bg-white rounded shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">🛍️ Sản phẩm</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3 border-b">Sản phẩm</th>
                        <th class="p-3 border-b">Giá</th>
                        <th class="p-3 border-b">Số lượng</th>
                        <th class="p-3 border-b">Tổng</th>
                        <th class="p-3 border-b">Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="p-3 border-b flex items-center gap-3">
                                <img src="{{ $item->product_image ?? asset('images/no-image.png') }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded">
                                <span>{{ $item->product_name }}</span>
                            </td>
                            <td class="p-3 border-b">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                            <td class="p-3 border-b">{{ $item->quantity }}</td>
                            <td class="p-3 border-b font-semibold">
                                {{ number_format($item->subtotal, 0, ',', '.') }}₫
                            </td>
                            <td class="p-3 border-b">
                                @if($item->size)
                                    <div class="text-sm">Size: {{ $item->size }}</div>
                                @endif
                                @if($item->color)
                                    <div class="text-sm">Màu: {{ $item->color }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Thanh toán --}}
    <div class="bg-white rounded shadow p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">💳 Thanh toán</h2>
        <p><strong>Phương thức:</strong> {{ $order->payment->method ?? 'Chưa có' }}</p>
        <p><strong>Trạng thái:</strong> {{ $order->payment->status ?? 'Chưa thanh toán' }}</p>
        <p class="mt-2 text-xl font-bold">Tổng tiền: {{ number_format($order->total, 0, ',', '.') }}₫</p>
    </div>

    {{-- Nút điều hướng --}}
    <div class="flex justify-between">
        <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
            ← Quay lại danh sách
        </a>

        @if (in_array($order->status, ['pending', 'processing']))
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này không?')">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    ❌ Hủy đơn hàng
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
