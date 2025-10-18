{{-- Admin Orders List --}}
@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl uppercase tracking-[0.2em] mb-2">Đơn hàng</h1>
            <p class="text-sm text-gray-600">Quản lý và theo dõi tất cả đơn hàng</p>
        </div>
        <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Xuất Excel
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Tổng đơn hàng</p>
            <p class="text-2xl">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Chờ xác nhận</p>
            <p class="text-2xl text-yellow-600">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Đang giao</p>
            <p class="text-2xl text-purple-600">{{ number_format($stats['shipping']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <p class="text-xs uppercase tracking-wide text-gray-600 mb-2">Hoàn thành</p>
            <p class="text-2xl text-green-600">{{ number_format($stats['completed']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <form method="GET" class="flex flex-wrap gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Tìm mã đơn, tên khách hàng..." 
                value="{{ request('search') }}"
                class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg"
            >
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            <select name="payment" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả thanh toán</option>
                <option value="cod" {{ request('payment') == 'cod' ? 'selected' : '' }}>COD</option>
                <option value="momo" {{ request('payment') == 'momo' ? 'selected' : '' }}>Momo</option>
                <option value="vnpay" {{ request('payment') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                <option value="visa" {{ request('payment') == 'visa' ? 'selected' : '' }}>Visa</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Lọc</button>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs uppercase tracking-wider text-gray-600">Ngày đặt</th>
                    <th class="px-6 py-3 text-right text-xs uppercase tracking-wider text-gray-600">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm">{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_phone ?: ($order->customer->phone ?? '') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php $firstItem = $order->items->first(); @endphp
                            @if($firstItem)
                                {{ $firstItem->product_name ?? ($firstItem->product->name ?? '-') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ number_format($order->total_amount ?? $order->total, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->payment_method === 'cod') bg-green-100 text-green-800
                                @elseif($order->payment_method === 'momo') bg-purple-100 text-purple-800
                                @elseif($order->payment_method === 'vnpay') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ strtoupper($order->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Không có đơn hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $orders->links('components.pagination') }}
    </div>
</div>
@endsection
