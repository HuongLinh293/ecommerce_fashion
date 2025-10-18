{{-- 
  Orders Index Page
  Converted from: App.tsx placeholder
--}}

@extends('layouts.app')

@section('title', 'Đơn Hàng - VIVILLAN')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-16">
        @auth
            @php
                $orders = auth()->user()->orders()->with(['items.product', 'payment'])->latest()->paginate(10);
            @endphp

            @if($orders->count() > 0)
                {{-- Page Header --}}
                <div class="mb-12">
                    <h1 class="text-3xl mb-2" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                        Đơn hàng của bạn
                    </h1>
                    <p class="text-sm opacity-60">Quản lý và theo dõi đơn hàng của bạn</p>
                </div>

                {{-- Orders List --}}
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                            {{-- Order Header --}}
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-100">
                                <div>
                                    <h3 class="text-sm uppercase tracking-[0.15em] mb-1">{{ $order->order_number }}</h3>
                                    <p class="text-xs opacity-60">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-4 mt-4 md:mt-0">
                                    <span class="px-3 py-1 text-xs uppercase tracking-wider rounded-full
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $order->status_label }}
                                    </span>
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-xs uppercase tracking-wider hover:opacity-60 transition-opacity">
                                        Chi tiết →
                                    </a>
                                </div>
                            </div>

                            {{-- Order Items Preview --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Products --}}
                                <div class="space-y-3">
                                    @foreach($order->items->take(2) as $item)
                                        <div class="flex gap-4">
                                            <div class="w-16 h-20 bg-gray-100 flex-shrink-0">
                                                <img 
                                                    src="{{ $item->product_image }}" 
                                                    alt="{{ $item->product_name }}"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm mb-1">{{ $item->product_name }}</p>
                                                <p class="text-xs opacity-60">Số lượng: {{ $item->quantity }}</p>
                                                @if($item->size)
                                                    <p class="text-xs opacity-60">Size: {{ $item->size }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <p class="text-xs opacity-60">+ {{ $order->items->count() - 2 }} sản phẩm khác</p>
                                    @endif
                                </div>

                                {{-- Order Summary --}}
                                <div class="bg-gray-50 p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="opacity-60">Tạm tính</span>
                                        <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="opacity-60">Phí vận chuyển</span>
                                        <span>
                                            @if($order->shipping_fee > 0)
                                                {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                                            @else
                                                Miễn phí
                                            @endif
                                        </span>
                                    </div>
                                    <hr class="border-gray-200" />
                                    <div class="flex justify-between">
                                        <span class="uppercase tracking-[0.15em]">Tổng</span>
                                        <span class="text-lg">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                                    </div>
                                    <div class="pt-2">
                                        <p class="text-xs opacity-60">
                                            Thanh toán: {{ $order->payment->payment_method_label ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Order Actions --}}
                            @if(in_array($order->status, ['pending', 'processing']))
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button 
                                            type="submit"
                                            onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')"
                                            class="text-xs uppercase tracking-wider text-red-600 hover:text-red-800 transition-colors"
                                        >
                                            Hủy đơn hàng
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $orders->links('components.pagination') }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto mb-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h2 class="text-2xl mb-4" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                        Chưa có đơn hàng nào
                    </h2>
                    <p class="text-sm opacity-60 mb-8">
                        Bạn chưa có đơn hàng nào. Khám phá và mua sắm ngay!
                    </p>
                    <a 
                        href="{{ route('home') }}" 
                        class="inline-block bg-black text-white px-8 py-3 text-xs uppercase tracking-wide hover:bg-gray-800 transition-colors"
                    >
                        Mua sắm ngay
                    </a>
                </div>
            @endif
        @else
            {{-- Not Logged In --}}
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h2 class="text-2xl mb-4" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                    Vui lòng đăng nhập
                </h2>
                <p class="text-sm opacity-60 mb-8">
                    Bạn cần đăng nhập để xem đơn hàng của mình
                </p>
                <a 
                    href="{{ route('login') }}" 
                    class="inline-block bg-black text-white px-8 py-3 text-xs uppercase tracking-wide hover:bg-gray-800 transition-colors"
                >
                    Đăng nhập
                </a>
            </div>
        @endauth
    </div>
</div>
@endsection
