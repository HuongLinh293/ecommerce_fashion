@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
  <div class="container mx-auto px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
      
      {{-- LEFT - Images --}}
      <div class="flex flex-col lg:flex-row gap-4">
        {{-- Thumbnails --}}
        <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto pb-2 lg:pb-0 lg:max-h-[600px] order-2 lg:order-1">
          @php
            $gallery = $product->parsed_gallery;
            $galleryUrls = $product->gallery_urls;
          @endphp

          @foreach($galleryUrls as $index => $img)
            <button 
              x-on:click="currentImage = {{ $index }}"
              :class="currentImage === {{ $index }} ? 'ring-2 ring-black shadow-lg' : 'ring-1 ring-gray-200 hover:ring-gray-300'"
              class="min-w-[80px] lg:min-w-[100px] w-20 lg:w-24 h-24 lg:h-28 bg-gray-100 overflow-hidden rounded transition-all"
            >
              <img src="{{ asset($img) }}" class="w-full h-full object-contain" alt="thumb">
            </button>
          @endforeach
        </div>

        {{-- Main Image --}}
        <div 
          x-data="{ currentImage: 0, total: {{ count($gallery) }} }"
          class="relative bg-gray-50 group rounded-lg overflow-hidden flex-1 order-1 lg:order-2" 
          style="width: 500px; height: 600px;"
        >
          <template x-for="(img, index) in {{ json_encode($galleryUrls) }}">
            <img 
              x-show="currentImage === index"
              :src="img"
              class="w-full h-full object-cover absolute inset-0 transition-opacity duration-300"
              style="object-position: center top;"
            >
          </template>

          {{-- Arrows --}}
          <button 
            @click="currentImage = (currentImage > 0) ? currentImage - 1 : total - 1"
            class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
          >
            <x-lucide-chevron-left class="w-8 h-8 text-white drop-shadow-lg" stroke-width="3" />
          </button>

          <button 
            @click="currentImage = (currentImage < total - 1) ? currentImage + 1 : 0"
            class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
          >
            <x-lucide-chevron-right class="w-8 h-8 text-white drop-shadow-lg" stroke-width="3" />
          </button>

          {{-- Dots --}}
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            <template x-for="(img, index) in {{ json_encode($gallery) }}">
              <button 
                @click="currentImage = index"
                :class="currentImage === index ? 'bg-black' : 'bg-white/60'"
                class="w-2 h-2 rounded-full"
              ></button>
            </template>
          </div>
        </div>
      </div>

      {{-- RIGHT - Info --}}
      <div class="space-y-8">
        <div>
          <h1 class="text-2xl font-light mb-4" style="font-family: 'Playfair Display', serif;">
            {{ $product->name }}
          </h1>
          <p class="text-2xl">{{ number_format($product->price, 0, ',', '.') }}₫</p>
        </div>

        {{-- Size --}}
  @php $sizes = $product->parsed_sizes; @endphp
  @if(!empty($sizes))
        <div x-data="{ selectedSize: '{{ $sizes[0] ?? '' }}' }">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm uppercase tracking-wide">Size: <span x-text="selectedSize"></span></h3>
            <button 
              @click="document.getElementById('sizeGuideModal').showModal()"
              class="text-xs uppercase tracking-wide underline opacity-60 hover:opacity-100"
            >
              Size guide
            </button>
          </div>
          <div class="flex gap-2">
            @foreach($sizes as $size)
            <button 
              @click="selectedSize='{{ $size }}'"
              :class="selectedSize==='{{ $size }}' ? 'bg-black text-white border-black' : 'bg-white text-black border-gray-300 hover:border-black'"
              class="w-12 h-12 border-2 text-sm transition-all flex items-center justify-center"
            >{{ $size }}</button>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Color --}}
  @php $colors = $product->parsed_colors; @endphp
  @if(!empty($colors))
        <div x-data="{ selectedColor: '{{ $colors[0] ?? '' }}' }">
          <h3 class="text-sm uppercase tracking-wide mb-4">Color: <span x-text="selectedColor"></span></h3>
          <div class="flex gap-3">
            @foreach($colors as $color)
            <button 
              @click="selectedColor='{{ $color }}'"
              :class="selectedColor==='{{ $color }}' ? 'border-black shadow-lg' : 'border-gray-200 hover:border-gray-300'"
              class="w-16 h-16 bg-gray-100 border-[3px] border-double rounded transition-all"
            >
              <img src="{{ asset($product->image) }}" class="w-full h-full object-cover rounded" alt="">
            </button>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Quantity + Add to Cart --}}
        <div x-data="{ quantity: 1 }" class="space-y-4">
          <div class="flex items-center gap-4">
            <div class="flex items-center border-[3px] border-double border-gray-300">
              <button @click="quantity=Math.max(1, quantity-1)" class="p-3 hover:bg-gray-100">
                <x-lucide-minus class="w-4 h-4"/>
              </button>
              <span class="px-4 text-sm" x-text="quantity"></span>
              <button @click="quantity++" class="p-3 hover:bg-gray-100">
                <x-lucide-plus class="w-4 h-4"/>
              </button>
            </div>
            <button class="p-3 border-[3px] border-double border-gray-300 hover:bg-gray-100">
              <x-lucide-heart class="w-4 h-4"/>
            </button>
          </div>

          <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <input type="hidden" name="quantity" x-model="quantity">
            <button 
              type="submit"
              class="w-full bg-black text-white py-4 px-8 text-sm uppercase tracking-wide hover:bg-gray-800 transition-colors"
            >
              Add to bag
            </button>
          </form>
        </div>

        {{-- Accordion --}}
        <div class="space-y-4 pt-8">
          <details class="border-t border-gray-200 pt-4">
            <summary class="text-sm uppercase tracking-wide cursor-pointer flex items-center justify-between">
              Description
              <span class="text-lg">+</span>
            </summary>
            <div class="mt-4 text-sm opacity-80 leading-relaxed">
              <p>{{ $product->description ?? 'Premium quality fabric with modern design.' }}</p>
            </div>
          </details>

          <details class="border-t border-gray-200 pt-4">
            <summary class="text-sm uppercase tracking-wide cursor-pointer flex items-center justify-between">
              Composition
              <span class="text-lg">+</span>
            </summary>
            <div class="mt-4 text-sm opacity-80">
              <p>100% Premium Cotton</p>
            </div>
          </details>
        </div>
      </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <section class="mt-24 pt-16 border-t border-gray-200">
      <h2 class="text-2xl font-light mb-12 text-center" style="font-family: 'Playfair Display', serif;">You may also like</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
        @foreach($relatedProducts as $related)
        <a href="{{ route('product.show', $related->id) }}" class="group">
          <div class="bg-white mb-4 overflow-hidden relative border-[15px] border-transparent" style="width: 375px; height: 470px;">
            <img src="{{ asset($related->image) }}" class="w-full h-full object-cover transition-opacity duration-300" style="object-position: center top;">
            <button class="absolute top-4 right-4 w-8 h-8 bg-white/80 rounded-full flex items-center justify-center hover:bg-white transition-colors">
              <x-lucide-heart class="w-4 h-4" />
            </button>
          </div>
          <div class="space-y-1">
            <h3 class="text-base font-semibold">{{ $related->name }}</h3>
            <p class="text-sm opacity-80">{{ number_format($related->price, 0, ',', '.') }}₫</p>
          </div>
        </a>
        @endforeach
      </div>
    </section>
    @endif
  </div>
</div>

{{-- Size Guide Modal --}}
<dialog id="sizeGuideModal" class="modal p-0 rounded-lg">
  <div class="p-6 bg-white max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-lg font-semibold">Size Guide</h2>
      <button onclick="document.getElementById('sizeGuideModal').close()" class="p-2 hover:bg-gray-100 rounded-full">
        <x-lucide-x class="w-4 h-4"/>
      </button>
    </div>
    <img src="https://images.unsplash.com/photo-1586170737392-383ba61aca98?w=600&h=400&fit=crop" class="w-full rounded-lg mb-4">
    {{-- size table... --}}
  </div>
</dialog>
@endsection
