{{-- Admin Order Detail --}}
@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng - Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Đơn hàng {{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Quay lại</a>
    </div>

    {{-- Customer Info --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-sm uppercase tracking-wide mb-4">Thông tin khách hàng</h3>
        <div class="bg-gray-50 p-4 rounded space-y-2 text-sm">
            <div class="flex justify-between">
                 <span class="text-gray-600">Tên:</span>
                 <span>{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</span>
            </div>
            <div class="flex justify-between">
                 <span class="text-gray-600">Email:</span>
                 <span>{{ $order->customer_email ?: ($order->customer->email ?? '-') }}</span>
            </div>
            <div class="flex justify-between">
                 <span class="text-gray-600">Số điện thoại:</span>
                 <span>{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Địa chỉ giao hàng:</span>
            </div>
            <div class="text-sm">{{ $order->shipping_address }}</div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-sm uppercase tracking-wide mb-4">Sản phẩm</h3>
        <div class="border rounded overflow-hidden">
            @foreach($order->items as $item)
                <div class="p-4 flex justify-between border-b last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                        <div>
                            <p class="text-sm">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                            <p class="text-xs text-gray-500">{{ $item->size }} / {{ $item->color }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                        <p class="text-xs text-gray-500">= {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="bg-gray-50 p-4 rounded space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Phương thức thanh toán:</span>
                <span class="px-2 py-1 text-xs rounded-full
                    @if($order->payment_method === 'cod') bg-green-100 text-green-800
                    @elseif($order->payment_method === 'momo') bg-purple-100 text-purple-800
                    @elseif($order->payment_method === 'vnpay') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ strtoupper($order->payment_method) }}
                </span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Trạng thái thanh toán:</span>
                <span class="px-2 py-1 text-xs rounded-full
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Trạng thái đơn hàng:</span>
                <span class="px-2 py-1 text-xs rounded-full
                    @if($order->status === 'completed') bg-green-100 text-green-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ $order->status_label }}
                </span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tạm tính:</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Phí vận chuyển:</span>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between">
                <span>Tổng cộng:</span>
                <span class="text-lg">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
            </div>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-sm uppercase tracking-wide mb-4">Cập nhật trạng thái</h3>
        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
    @csrf

            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Cập nhật</button>
        </form>
    </div>
</div>
@endsection
