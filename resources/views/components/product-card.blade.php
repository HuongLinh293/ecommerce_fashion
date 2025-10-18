{{-- 
  Product Card Component
  Converted from: /components/ProductCard.tsx
  
  Usage:
  <x-product-card :product="$product" />
--}}

@props(['product'])

<div 
    x-data="{ 
        currentImage: '{{ $product->image_url }}',
        defaultImage: '{{ $product->image_url }}',
        hoverImage: '{{ $product->gallery_urls[1] ?? $product->image_url }}'
    }"
    class="group cursor-pointer relative"
    @mouseenter="currentImage = hoverImage"
    @mouseleave="currentImage = defaultImage"
    onclick="window.location.href='{{ route('products.show', $product->id) }}'"
>
    {{-- Wishlist removed from card (wishlist available on product detail page) --}}
    <div class="relative overflow-hidden bg-white mb-4 border-[15px] border-transparent w-full h-[470px]">
            <img
            :src="currentImage"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover transition-opacity duration-300"
            style="object-position: center top;"
            loading="lazy"
        />
        
        {{-- Discount Badge --}}
        @if($product->discount > 0)
            <div class="absolute top-4 left-4 bg-accent text-white px-3 py-1 text-xs tracking-wider uppercase">
                -{{ $product->discount }}%
            </div>
        @endif
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <p class="text-xs tracking-[0.15em] opacity-60 uppercase">
                {{ ucfirst($product->category) }}
            </p>
            <p class="text-xs tracking-[0.15em] opacity-60 uppercase">
                {{ $product->type }}
            </p>
        </div>
        
        <h3 class="text-base leading-tight group-hover:opacity-60 transition-opacity font-semibold">
            {{ $product->name }}
        </h3>

        <div class="flex items-center gap-3 pt-1">
            <span class="text-sm">
                {{ number_format($product->price, 0, ',', '.') }}₫
            </span>
            @if($product->original_price && $product->original_price > $product->price)
                <span class="text-xs opacity-40 line-through">
                    {{ number_format($product->original_price, 0, ',', '.') }}₫
                </span>
            @endif
        </div>
    </div>
</div>
