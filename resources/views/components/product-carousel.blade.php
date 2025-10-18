@props(['title', 'subtitle' => '', 'products', 'category' => null])


<section class="py-12 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        {{-- Title --}}
   <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-1">
    <div>
        <p class="text-xs tracking-[0.3em] uppercase opacity-60 mb-2">{{ $subtitle }}</p>
        <h2 class="text-3xl md:text-3xl font-serif">{{ $title }}</h2>
    </div>

@if ($category)
    <a 
        href="{{ route('products.category', ['type' => $category]) }}" 
        class="text-sm text-gray-600 uppercase tracking-wider hover:opacity-80"
    >
        Show all →
    </a>
@endif



</div>


        {{-- Carousel --}}
        <x-carousel orientation="horizontal">
            @foreach ($products as $product)
                <div class="px-4 min-w-[300px] md:min-w-[375px]">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </x-carousel>
    </div>
</section>
