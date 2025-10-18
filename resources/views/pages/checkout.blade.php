{{-- resources/views/pages/checkout.blade.php --}}
@extends('layouts.app')

@section('title', 'Thanh Toán - VIVILLAN')

@section('content')
@php
    // Defensive defaults so the view doesn't blow up if controller omits $cart
    $cart = $cart ?? ['items' => [], 'totalPrice' => 0];
    $cart['items'] = is_array($cart['items']) ? $cart['items'] : (is_iterable($cart['items']) ? collect($cart['items'])->toArray() : []);
    $cart['totalPrice'] = $cart['totalPrice'] ?? 0;
@endphp

<div x-data="checkout()" class="min-h-screen bg-white pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-8">
        <h1 class="text-3xl lg:text-4xl mb-12 text-center" style="font-family: 'Playfair Display', serif; letter-spacing: 0.2em;">
            THANH TOÁN
        </h1>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12" @submit.prevent="onCheckoutSubmit($event)" x-ref="checkoutForm">
            @csrf

            {{-- Checkout Form --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Customer Information --}}
                <div>
                    <h2 class="text-xl mb-6" style="font-family: 'Playfair Display', serif;">Thông tin khách hàng</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs tracking-wider mb-2 opacity-50">HỌ TÊN *</label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', auth()->user()->name ?? '') }}"
                                required
                                class="w-full px-4 py-3 border border-black text-sm"
                            >
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs tracking-wider mb-2 opacity-50">EMAIL *</label>
                                <input 
                                    type="email" 
                                    id="email"
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email ?? '') }}"
                                    required
                                    class="w-full px-4 py-3 border border-black text-sm"
                                >
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs tracking-wider mb-2 opacity-50">SỐ ĐIỆN THOẠI *</label>
                                <input 
                                    type="tel" 
                                    id="phone"
                                    name="phone" 
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    required
                                    class="w-full px-4 py-3 border border-black text-sm"
                                >
                                @error('phone')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Information --}}
                <div>
                    <h2 class="text-xl mb-6" style="font-family: 'Playfair Display', serif;">Địa chỉ giao hàng</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-xs tracking-wider mb-2 opacity-50">ĐỊA CHỈ *</label>
                            <textarea 
                                id="address"
                                name="address" 
                                rows="3"
                                required
                                class="w-full px-4 py-3 border border-black text-sm"
                            >{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-xs tracking-wider mb-2 opacity-50">TỈNH/THÀNH PHỐ *</label>
                            <select 
                                id="city"
                                name="city" 
                                required
                                class="w-full px-4 py-3 border border-black text-sm"
                            >
                                <option value="">Chọn tỉnh/thành phố</option>
                                <option value="Hà Nội" {{ old('city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                <option value="TP. Hồ Chí Minh" {{ old('city') == 'TP. Hồ Chí Minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                <option value="Đà Nẵng" {{ old('city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                <option value="Cần Thơ" {{ old('city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                <option value="Hải Phòng" {{ old('city') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                            </select>
                            @error('city')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-xs tracking-wider mb-2 opacity-50">GHI CHÚ (TÙY CHỌN)</label>
                            <textarea 
                                id="notes"
                                name="notes" 
                                rows="3"
                                class="w-full px-4 py-3 border border-black text-sm"
                                placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."
                            >{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div>
                    <h2 class="text-xl mb-6" style="font-family: 'Playfair Display', serif;">Phương thức thanh toán</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 p-4 border border-black cursor-pointer hover:bg-gray-50">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="cod"
                                x-model="selectedPayment"
                                {{ ($paymentMethod ?? '') == 'cod' ? 'checked' : '' }}
                                class="w-4 h-4 mt-1"
                            >
                            <div>
                                <p class="text-sm">Thanh toán khi nhận hàng (COD)</p>
                                <p class="text-xs opacity-50 mt-1">Thanh toán bằng tiền mặt khi nhận hàng</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-4 border border-black cursor-pointer hover:bg-gray-50">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="momo"
                                x-model="selectedPayment"
                                {{ ($paymentMethod ?? '') == 'momo' ? 'checked' : '' }}
                                class="w-4 h-4 mt-1"
                            >
                            <div class="flex-1">
                                <p class="text-sm">Ví MoMo</p>
                                <p class="text-xs opacity-50 mt-1">Thanh toán qua ví điện tử MoMo</p>
                            </div>
                            <div class="ml-4">
                                <button type="button" @click.prevent="openPaymentModal('momo')" class="text-xs underline">Xem mã QR</button>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-4 border border-black cursor-pointer hover:bg-gray-50">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="vnpay"
                                x-model="selectedPayment"
                                {{ ($paymentMethod ?? '') == 'vnpay' ? 'checked' : '' }}
                                class="w-4 h-4 mt-1"
                            >
                            <div class="flex-1">
                                <p class="text-sm">VNPay</p>
                                <p class="text-xs opacity-50 mt-1">Thanh toán qua cổng VNPay</p>
                            </div>
                            <div class="ml-4">
                                <button type="button" @click.prevent="openPaymentModal('vnpay')" class="text-xs underline">Xem mã QR</button>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-4 border border-black cursor-pointer hover:bg-gray-50">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="visa"
                                x-model="selectedPayment"
                                {{ ($paymentMethod ?? '') == 'visa' ? 'checked' : '' }}
                                class="w-4 h-4 mt-1"
                            >
                            <div>
                                <p class="text-sm">Thẻ Visa/Mastercard</p>
                                <p class="text-xs opacity-50 mt-1">Thanh toán qua thẻ quốc tế</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="border border-black p-6 sticky top-24">
                    <h3 class="text-lg mb-6 tracking-wider">ĐƠN HÀNG</h3>
                    
                    {{-- Cart Items --}}
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        @foreach($cart['items'] as $item)
                            <div class="flex gap-3">
                                    <div class="w-16 h-20 flex-shrink-0 bg-gray-100">
                                    <img 
                                        src="{{ $item['image'] ?? asset('assets/products/default.png') }}" 
                                        alt="{{ $item['name'] ?? 'No name' }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                                <div class="flex-1 text-xs">
                                    <p class="mb-1">{{ $item['name'] }}</p>
                                    @if($item['size'])
                                        <p class="opacity-50">Size: {{ $item['size'] }}</p>
                                    @endif
                                    @if($item['color'])
                                        <p class="opacity-50">Màu: {{ $item['color'] }}</p>
                                    @endif
                                    <p class="mt-1">{{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pricing --}}
                    <div class="border-t border-gray-200 pt-4 space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="opacity-50">Tạm tính</span>
                            <span>{{ number_format($cart['totalPrice'] ?? 0, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="opacity-50">Phí vận chuyển</span>
                            <span>Miễn phí</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between">
                            <span>Tổng cộng</span>
                            <span class="text-xl">{{ number_format($cart['totalPrice'] ?? 0, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-3 tracking-widest hover:bg-gray-800 transition-colors"
                    >
                        ĐẶT HÀNG
                    </button>

                    <p class="text-xs opacity-50 text-center mt-4">
                        Bằng cách đặt hàng, bạn đồng ý với 
                        <a href="#" class="underline">Điều khoản sử dụng</a>
                    </p>
                </div>
            </div>
        </form>
    
    {{-- Payment QR Modal (reuse from cart) --}}
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
                        <p class="mt-2">{{ number_format($cart['totalPrice'] ?? 0, 0, ',', '.') }}₫</p>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="space-y-3 text-center">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-xs uppercase tracking-wide opacity-60 mb-1">Số tiền thanh toán</p>
                    <p class="text-2xl">{{ number_format($cart['totalPrice'] ?? 0, 0, ',', '.') }}₫</p>
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

                <div class="mt-3 text-xs">
                    <label class="block text-xs mb-1">Mã giao dịch (nếu có)</label>
                    <input type="text" x-model="transactionId" placeholder="Nhập mã giao dịch / transaction id" class="w-full px-3 py-2 border border-gray-200 text-xs" />
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
                    @click.prevent="confirmPayment()"
                    class="flex-1 bg-black text-white px-4 py-2 text-sm hover:bg-gray-800"
                >
                    Đã thanh toán
                </button>
            </div>
        </div>
    </div>
    </div>
</div>
    <script>
        function checkout() {
            // Use JSON to safely inject server-side default into JS (do not use a Blade directive name in this comment)
                const initialPayment = @json($paymentMethod ?? 'cod');

            return {
                selectedPayment: initialPayment,
                showPaymentModal: false,
                modalPaymentMethod: '',

                getPaymentInfo(method) {
                    const info = {
                        momo: {
                            name: 'Ví điện tử Momo',
                            icon: 'M',
                            color: 'bg-purple-600',
                            accountInfo: 'SĐT: 0987654321 - Tên: VIVILLAN Store'
                        },
                        vnpay: {
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

                // optional transaction id user can paste after scanning/confirming QR
                transactionId: '',

                onCheckoutSubmit(e) {
                    const method = this.selectedPayment || initialPayment;
                    if (method === 'momo' || method === 'vnpay') {
                        this.modalPaymentMethod = method;
                        this.showPaymentModal = true;
                        return;
                    }

                    this.$refs.checkoutForm.submit();
                },

                confirmPayment() {
                    const form = this.$refs.checkoutForm;
                    let input = form.querySelector('input[name="payment_confirmed"]');
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'payment_confirmed';
                        input.value = '1';
                        form.appendChild(input);
                    } else {
                        input.value = '1';
                    }

                    let pmInput = form.querySelector('input[name="payment_method"]:checked');
                    if (!pmInput) {
                        const pm = document.createElement('input');
                        pm.type = 'hidden';
                        pm.name = 'payment_method';
                        pm.value = this.modalPaymentMethod || this.selectedPayment;
                        form.appendChild(pm);
                    }

                    // append provided transaction id if user pasted one
                    if (this.transactionId) {
                        let tx = form.querySelector('input[name="transaction_id"]');
                        if (!tx) {
                            tx = document.createElement('input');
                            tx.type = 'hidden';
                            tx.name = 'transaction_id';
                            form.appendChild(tx);
                        }
                        tx.value = this.transactionId;
                    }

                    this.showPaymentModal = false;
                    form.submit();
                }
            };
        }
    </script>

@endsection
