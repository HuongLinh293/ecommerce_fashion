{{-- 
  Cart Page
  Converted from: /pages/CartPage.tsx
--}}

@extends('layouts.app')

@section('title', 'Giỏ Hàng - VIVILLAN')

@section('content')
<div 
    x-data="{
        selectedPayment: 'cod',
        showPaymentModal: false,
        modalPaymentMethod: '',
        
        formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
            }).format(price);
        },
        
        getPaymentInfo(method) {
            const info = {
                'momo': {
                    name: 'Ví điện tử Momo',
                    icon: 'M',
                    color: 'bg-purple-600',
                    accountInfo: 'SĐT: 0987654321 - Tên: VIVILLAN Store'
                },
                'vnpay': {
                    name: 'VNPay',
                    icon: 'VN',
                    color: 'bg-blue-600',
                    accountInfo: 'Mã merchant: VIVILLAN2025'
                }
            };
            return info[method] || {};
        },
        
        openPaymentModal(method) {
            this.modalPaymentMethod = method;
            this.showPaymentModal = true;
        },
        
        proceedToCheckout() {
            window.location.href = `/checkout?payment=${this.selectedPayment}`;
        }
    }"
    class="min-h-screen bg-white text-black pt-20"
>
    <div class="container mx-auto px-6 lg:px-8 py-12">
       @php
        $cartItems = $items ?? collect();
        $cartTotal = $total ?? 0;
        $cart = [
            'totalItems' => is_iterable($cartItems) ? count($cartItems) : 0
        ];
    @endphp


        @if(count($cartItems) === 0)
            {{-- Empty Cart --}}
            <div class="text-center max-w-md mx-auto py-16">
                <svg class="w-16 h-16 mx-auto mb-6 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h2 class="text-2xl mb-4" style="font-family: 'Playfair Display', serif; font-weight: 300;">
                    Giỏ hàng của bạn hiện đang trống
                </h2>
                <p class="text-sm opacity-60 mb-8 leading-relaxed">
                    Thêm những món bạn yêu thích vào giỏ hàng và hoàn tất đơn hàng khi sẵn sàng.
                </p>
                <a 
                    href="{{ route('home') }}" 
                    class="inline-block bg-black text-white px-8 py-3 text-xs uppercase tracking-wide hover:bg-gray-800 transition-colors"
                >
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            {{-- Cart Header --}}
            <div class="mb-12">
                <h1 class="text-sm uppercase tracking-[0.2em] mb-2">Giỏ hàng</h1>
                <p class="text-xs opacity-60">{{ $cart['totalItems'] }} sản phẩm</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-8">
                    @foreach($cartItems as $itemId => $item)
                        <div>
                            <div class="flex gap-6">
                                {{-- Product Image --}}
                                <div class="w-32 h-40 bg-gray-100 flex-shrink-0">
                                    <img
                                        src="{{ data_get($item, 'image') ?? data_get($item, 'attributes.image') ?? asset('assets/products/default.png') }}"
                                        alt="{{ data_get($item, 'name') ?? 'No name' }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>

                                {{-- Product Details --}}
                                <div class="flex-1 space-y-4">
                                    <div>
                                        <h3 class="text-sm mb-1">{{ $item['name'] }}</h3>
                                        @php
                                            $itemSize = data_get($item, 'size') ?? data_get($item, 'attributes.size') ?? null;
                                            $itemColor = data_get($item, 'color') ?? data_get($item, 'attributes.color') ?? null;
                                        @endphp
                                        @if($itemSize)
                                            <p class="text-xs opacity-60">Size: {{ $itemSize }}</p>
                                        @endif
                                        @if($itemColor)
                                            <p class="text-xs opacity-60">Color: {{ $itemColor }}</p>
                                        @endif
                                    </div>

                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center gap-4">
                                        <form action="{{ route('cart.update', $itemId) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center border border-gray-300">
                                                <button
                                                    type="submit"
                                                    name="quantity"
                                                    value="{{ max(1, $item['quantity'] - 1) }}"
                                                    class="p-2 hover:bg-gray-100"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="px-4 text-sm">{{ $item['quantity'] }}</span>
                                                <button
                                                    type="submit"
                                                    name="quantity"
                                                    value="{{ $item['quantity'] + 1 }}"
                                                    class="p-2 hover:bg-gray-100"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </form>

                                        <form action="{{ route('cart.remove', $itemId) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-xs uppercase tracking-wide opacity-60 hover:opacity-100"
                                            >
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="text-sm">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</p>
                                </div>
                            </div>

                            @if(!$loop->last)
                                <hr class="mt-8 border-gray-200" />
                            @endif
                        </div>
                    @endforeach

                    {{-- Delivery Options --}}
                    <div class="pt-8 border-t border-gray-200">
                        <h3 class="text-xs uppercase tracking-[0.2em] mb-4">Delivery options</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="delivery" class="w-4 h-4" checked />
                                <div>
                                    <p class="text-sm">Standard delivery</p>
                                    <p class="text-xs opacity-60">7-10 working days - Free delivery option all week</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="delivery" class="w-4 h-4" />
                                <div>
                                    <p class="text-sm">Express delivery</p>
                                    <p class="text-xs opacity-60">2-3 working days - Additional cost applies</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Payment methods moved to checkout page --}}
                </div>

                {{-- Order Summary --}}
                    <div class="space-y-8">
                        <div class="bg-gray-50 p-6 space-y-4">
                            <h3 class="text-xs uppercase tracking-[0.2em]">Tóm tắt đơn hàng</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <p>{{ number_format($cartTotal, 0, ',', '.') }}₫</p>
                            </div>
                            <div class="flex justify-between opacity-60">
                                <span>Delivery</span>
                                <span>Free</span>
                            </div>
                        </div>

                        <hr class="border-gray-300" />

                        <div class="flex justify-between">
                            <span class="text-sm uppercase tracking-[0.15em]">Total to Pay</span>
                            <p>{{ number_format($cartTotal, 0, ',', '.') }}₫</p>
                        </div>

                        <button
                            @click="proceedToCheckout()"
                            class="w-full bg-black text-white py-4 text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors"
                        >
                            Tiếp tục thanh toán
                        </button>
                    </div>

                        <div class="text-xs opacity-60 leading-relaxed">
                            <p>
                                Đăng ký nhận bản tin để nhận ưu đãi độc quyền, thông báo sản phẩm mới và tin tức từ cửa hàng.
                            </p>
                            <p class="mt-4">Dịch vụ khách hàng</p>
                            <a href="{{ url('help') }}" class="underline hover:opacity-100">Liên hệ</a>
                        </div>
                </div>
            </div>

            {{-- You may also like --}}
            @php
                // Try to pick up to 4 products that are active and in stock
                $suggestedProducts = \App\Models\Product::active()->inStock()->inRandomOrder()->take(4)->get();

                // If there aren't enough, fill the rest with any random products (excluding already chosen)
                if ($suggestedProducts->count() < 4) {
                    $needed = 4 - $suggestedProducts->count();
                    $exclude = $suggestedProducts->pluck('id')->all();

                    // Avoid using PHP arrow functions in compiled Blade (compatibility)
                    if (count($exclude)) {
                        $more = \App\Models\Product::inRandomOrder()
                            ->whereNotIn('id', $exclude)
                            ->take($needed)
                            ->get();
                    } else {
                        $more = \App\Models\Product::inRandomOrder()
                            ->take($needed)
                            ->get();
                    }

                    $suggestedProducts = $suggestedProducts->concat($more);
                }
            @endphp

            @if($suggestedProducts->count() > 0)
                <section class="mt-24 pt-16 border-t border-gray-200">
                    <h2 class="text-xs uppercase tracking-[0.2em] mb-12 opacity-60">You may also like</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @foreach($suggestedProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </section>
            @endif

          
        @endif
    </div>

    {{-- Payment QR Modal --}}
    <div 
        x-show="showPaymentModal"
        @click.away="showPaymentModal = false"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
        style="display: none;"
    >
        <div 
            @click.stop
            class="bg-white rounded-lg max-w-md w-full p-6 space-y-6"
            style="max-height: calc(100vh - 96px); overflow-y: auto;"
        >
            {{-- Header --}}
            <div class="flex items-center gap-3">
                <div :class="getPaymentInfo(modalPaymentMethod).color" class="w-10 h-10 rounded flex items-center justify-center text-white">
                    <span x-text="getPaymentInfo(modalPaymentMethod).icon"></span>
                </div>
                <div>
                    <p class="text-lg" x-text="getPaymentInfo(modalPaymentMethod).name"></p>
                    <p class="text-xs opacity-60">Quét mã để thanh toán</p>
                </div>
            </div>

            {{-- QR Code Placeholder --}}
            <div class="bg-gray-100 p-8 flex items-center justify-center">
                <div class="w-48 h-48 md:w-64 md:h-64 bg-white p-4 flex items-center justify-center border-2 border-gray-300" style="transform: scale(0.85); transform-origin: center;">
                    <div class="text-center text-xs opacity-40">
                        <p>QR CODE</p>
                        <p class="mt-2">{{ number_format($cartTotal, 0, ',', '.') }}₫</p>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="space-y-3 text-center">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-xs uppercase tracking-wide opacity-60 mb-1">Số tiền thanh toán</p>
                    <p class="text-2xl">{{ number_format($cartTotal, 0, ',', '.') }}₫</p>
                </div>
                
                <div class="text-xs opacity-60">
                    <p x-text="getPaymentInfo(modalPaymentMethod).accountInfo"></p>
                </div>

                <div class="flex items-center justify-center gap-2 text-sm text-blue-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <p>Đang chờ thanh toán...</p>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded text-xs space-y-2">
                <p class="uppercase tracking-wide opacity-80">Hướng dẫn:</p>
                <ol class="space-y-1 list-decimal list-inside opacity-70">
                    <li>Mở ứng dụng <span x-text="getPaymentInfo(modalPaymentMethod).name"></span></li>
                    <li>Chọn "Quét mã QR"</li>
                    <li>Quét mã QR trên màn hình</li>
                    <li>Xác nhận thanh toán</li>
                </ol>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button
                    @click="showPaymentModal = false"
                    class="flex-1 border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50"
                >
                    Đóng
                </button>
                <button
                    @click="showPaymentModal = false; proceedToCheckout()"
                    class="flex-1 bg-black text-white px-4 py-2 text-sm hover:bg-gray-800"
                >
                    Tiếp tục thanh toán
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
