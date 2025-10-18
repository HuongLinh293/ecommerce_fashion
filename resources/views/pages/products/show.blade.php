{{-- Product Detail Page (Clean + Alpine + Toast) --}}
@extends('layouts.app')

@section('title', $product->name . ' - VIVILLAN')

@section('content')

@php
    $images = collect($product->gallery_urls);
    $sizes = $product->parsed_sizes;
    $colors = $product->parsed_colors;
@endphp

<div x-data="productPage()" class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-12">

        {{-- Breadcrumb --}}
        <nav class="mb-8 text-xs uppercase tracking-wider opacity-60">
            <a href="{{ route('home') }}" class="hover:opacity-100">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/products/category/' . $product->category) }}" class="hover:opacity-100">{{ ucfirst($product->category) }}</a>
            <span class="mx-2">/</span>
            <span class="opacity-100">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

            {{-- Left - Gallery --}}
            <div class="flex flex-col lg:flex-row gap-4">

                {{-- Thumbnails --}}
                <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto pb-2 lg:pb-0 lg:max-h-[600px] order-2 lg:order-1">
                    <template x-for="(img, index) in images" :key="index">
                        <button @click="currentImage = index"
                            :class="currentImage === index ? 'ring-2 ring-black shadow-lg scale-105' : 'ring-1 ring-gray-200 hover:ring-gray-300 hover:scale-105'"
                            class="min-w-[80px] lg:min-w-[100px] w-20 lg:w-24 h-24 lg:h-28 bg-gray-100 overflow-hidden rounded transition-all">
                            <img :src="img" :alt="'Thumbnail ' + (index+1) + ' of {{ $product->name }}'" class="w-full h-full object-contain" />
                        </button>
                    </template>
                </div>

                {{-- Main Image --}}
                <div class="relative bg-gray-50 group rounded-lg overflow-hidden flex-1 order-1 lg:order-2" style="max-width: 500px; height: 600px;">
                    <img :src="images[currentImage]" alt="{{ $product->name }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" style="object-position: center top;">

                    {{-- Prev/Next --}}
                    <button @click="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button @click="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    {{-- Dots --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="currentImage = index" :class="currentImage === index ? 'bg-black' : 'bg-white/60'" class="w-2 h-2 rounded-full transition-colors"></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Right - Product Info --}}
            <div class="relative space-y-8">

                {{-- Header --}}
                <div>
                    <h1 class="text-2xl mb-4 font-serif">{{ $product->name }}</h1>
                    <p class="text-2xl">{{ number_format($product->price,0,',','.') }}₫</p>
                    @if($product->discount > 0)
                        <p class="text-sm opacity-60 line-through">{{ number_format($product->original_price,0,',','.') }}₫</p>
                    @endif
                </div>

                {{-- Description --}}
                <p class="text-sm opacity-80 leading-relaxed">{{ $product->description }}</p>

                {{-- Material --}}
                @if($product->material)
                    <div class="border-t border-b py-4">
                        <p class="text-xs uppercase tracking-wide opacity-60 mb-1">Material</p>
                        <p class="text-sm">{{ $product->material }}</p>
                    </div>
                @endif

                {{-- Size --}}
                @if(!empty($sizes))
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm uppercase tracking-wide">Size: <span x-text="selectedSize"></span></h3>
                            <button @click="openGuide = true" class="text-xs underline hover:text-gray-600">Size Guide</button>
                        </div>
                        <div class="flex gap-2">
                            @foreach($sizes as $size)
                                <button @click="selectedSize='{{ $size }}'"
                                    :class="selectedSize==='{{ $size }}' ? 'bg-black text-white border-black' : 'bg-white text-black border-gray-300 hover:border-black'"
                                    class="w-12 h-12 border-2 text-sm transition-all flex items-center justify-center">{{ $size }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Color --}}
                @if(!empty($colors))
                    <div>
                        <h3 class="text-sm uppercase tracking-wide mb-4">Color: <span x-text="selectedColor"></span></h3>
                        <div class="flex gap-3">
                            @foreach($colors as $color)
                                <button @click="selectedColor='{{ $color }}'"
                                    :class="selectedColor==='{{ $color }}' ? 'border-black shadow-lg' : 'border-gray-200 hover:border-gray-300'"
                                    class="w-16 h-16 bg-gray-100 border-[3px] border-double rounded transition-all flex items-center justify-center">
                                    <span class="text-xs">{{ $color }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Quantity --}}
                <div>
                    <h3 class="text-sm uppercase tracking-wide mb-4">Quantity</h3>
                    <div class="flex items-center gap-4">
                        <button @click="decrementQuantity()" class="w-12 h-12 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span x-text="quantity" class="text-lg w-12 text-center"></span>
                        <button @click="incrementQuantity()" class="w-12 h-12 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        {{-- Wishlist inline button placed to the right of quantity --}}
                        <div class="ml-4">
                            @include('components.wishlist-button', ['product' => $product, 'inline' => true])
                        </div>
                    </div>
                </div>

                {{-- Add to Cart --}}
                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="quantity" x-model="quantity">
                    <input type="hidden" name="size" x-bind:value="selectedSize">
                    <input type="hidden" name="color" x-bind:value="selectedColor">
                    <input type="hidden" name="image" value="{{ $product->image }}">
                    <button type="submit" class="w-full bg-black text-white py-4 px-8 text-sm uppercase tracking-wide hover:bg-gray-800 transition-colors">
                        Add to bag
                    </button>
                </form>

            </div>
        </div>

        {{-- Size Guide Modal --}}
        <div x-show="openGuide" x-transition @click.away="openGuide=false"
            class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-lg relative">
                <button @click="openGuide=false" class="absolute top-2 right-3 text-gray-600 hover:text-black text-xl">&times;</button>
                <img src="{{ $product->category==='shoes' ? asset('assets/products/sizegiay.png') : asset('assets/products/sizequanao.jpg') }}" alt="Size Guide" class="w-full h-auto rounded">
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->count() > 0)
            <section class="mt-24">
                <h2 class="text-2xl mb-8 text-center font-serif">You May Also Like</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </section>
        @endif

    </div>

    {{-- Toast --}}
    <div id="cartToast" x-data="{ show:false, message:'' }" x-cloak class="fixed top-6 right-6 z-50 pointer-events-none">
        <div x-show="show" x-transition class="bg-black text-white px-5 py-3 rounded-lg shadow-lg pointer-events-auto">
            <span x-text="message"></span>
        </div>
    </div>

    {{-- DOM-only fallback toast (used when Alpine not available) --}}
    <div id="cartToastFallback" style="display:none; position:fixed; top:1.5rem; right:1.5rem; z-index:9999;">
        <div id="cartToastFallbackInner" style="background:#000;color:#fff;padding:10px 16px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.25);opacity:0;transform:translateY(-6px);transition:opacity .22s,transform .22s;max-width:320px;">
            <!-- message injected here -->
        </div>
    </div>

</div>

<script>
function productPage(){
    return {
        images: @json($images),
        currentImage: 0,
        selectedSize: '{{ $sizes[0] ?? '' }}',
        selectedColor: '{{ $colors[0] ?? '' }}',
        quantity: 1,
        openGuide: false,
        incrementQuantity(){ this.quantity++; },
        decrementQuantity(){ if(this.quantity>1) this.quantity--; },
        nextImage(){ this.currentImage = (this.currentImage+1)%this.images.length; },
        prevImage(){ this.currentImage = (this.currentImage-1+this.images.length)%this.images.length; }
    }
}

document.addEventListener('DOMContentLoaded', ()=>{
    const form = document.getElementById('addToCartForm');
    if(!form) return;

    const toastEl = document.getElementById('cartToast');
    let toast = null;
    if (toastEl && toastEl.__x && toastEl.__x.$data) {
        toast = toastEl.__x.$data;
    } else {
        console.debug('[cart-toast] toast element not found or Alpine not initialized');
    }

    // Fallback DOM toast helpers
    const fallbackContainer = document.getElementById('cartToastFallback');
    const fallbackInner = document.getElementById('cartToastFallbackInner');
    function showFallbackToast(message, ms = 2500){
        if (!fallbackContainer || !fallbackInner) return;
        fallbackInner.textContent = message;
        fallbackContainer.style.display = 'block';
        // force reflow to enable transition
        void fallbackInner.offsetWidth;
        fallbackInner.style.opacity = '1';
        fallbackInner.style.transform = 'translateY(0)';
        setTimeout(()=>{
            // hide
            fallbackInner.style.opacity = '0';
            fallbackInner.style.transform = 'translateY(-6px)';
            setTimeout(()=>{ fallbackContainer.style.display = 'none'; }, 250);
        }, ms);
    }

    form.addEventListener('submit', async e=>{
        e.preventDefault();
        const name = form.querySelector('[name="name"]').value;
        const qty  = form.querySelector('[name="quantity"]').value || '1';

        if (toast) {
            toast.message = `Đã thêm ${name} × ${qty} vào giỏ hàng`;
            toast.show = true;
        } else {
            // show fallback immediately
            showFallbackToast(`Đã thêm ${name} × ${qty} vào giỏ hàng`);
        }

        try{
            const res = await fetch(form.action, { method: 'POST', body: new FormData(form), credentials: 'same-origin' });
            const data = await res.json();
            if (data.status === 'success') {
                // show server-provided message when available
                if (toast && data.message) {
                    toast.message = data.message;
                } else if (data.message) {
                    showFallbackToast(data.message);
                }
                if (Alpine.store?.('cart')?.update) Alpine.store('cart').update(data.totalItems);
            } else {
                const errMsg = data.message || 'Lỗi khi thêm vào giỏ hàng';
                if (toast) {
                    toast.message = errMsg;
                } else {
                    showFallbackToast(errMsg);
                }
            }
        }catch(err){
            console.error(err);
            toast.message = 'Không thể kết nối tới máy chủ';
        }

    if (toast) setTimeout(()=>toast.show=false, 2500);
    });

    // Wishlist toast listener
    window.addEventListener('wishlist-toggled', function(e){
        const action = e.detail?.action;
        const name = e.detail?.name || 'Sản phẩm';
        const msg = (action === 'added') ? `Đã thêm "${name}" vào wishlist` : `Đã xoá "${name}" khỏi wishlist`;
        if (toast) {
            toast.message = msg;
            toast.show = true;
            setTimeout(()=>toast.show=false, 2200);
        } else {
            showFallbackToast(msg, 2200);
        }
    });
});
</script>

@endsection
