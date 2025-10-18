@extends('layouts.app')

@section('title', ucfirst($category) . ' Products')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-12">
        {{-- Header --}}
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-sm uppercase tracking-[0.2em] mb-2">
                        {{ ucfirst($category) }}
                    </h1>
                    <p class="text-xs opacity-60">
                        {{ $products->total() }} pieces available
                    </p>
                </div>

                {{-- Mobile Filter Button --}}
                <div class="lg:hidden">
                    <button type="button" data-drawer-target="filter-drawer" data-drawer-show="filter-drawer"
                        class="flex items-center border border-gray-300 px-3 py-2 text-xs uppercase tracking-[0.2em]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 012 0v16a1 1 0 01-2 0V4zm9 0a1 1 0 012 0v10a1 1 0 01-2 0V4zm9 0a1 1 0 012 0v7a1 1 0 01-2 0V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="flex gap-16">
            {{-- Sidebar Filters - Desktop --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-32 space-y-8">
                    {{-- Price Range --}}
                    <div>
                        <h3 class="text-xs uppercase tracking-[0.2em] mb-6 opacity-60">Khoảng giá</h3>
                        <form method="GET" class="flex flex-col space-y-3">
                            <div class="flex gap-2">
                                <input type="number" name="price_min" placeholder="Từ"
                                    value="{{ request('price_min') }}" class="w-1/2 border p-2 text-xs">
                                <input type="number" name="price_max" placeholder="Đến"
                                    value="{{ request('price_max') }}" class="w-1/2 border p-2 text-xs">
                            </div>
                            <button type="submit"
                                class="text-xs uppercase tracking-[0.2em] border border-gray-300 hover:border-black py-1">
                                Lọc giá
                            </button>
                        </form>
                    </div>

                    {{-- Product Types --}}
                    @if(isset($types) && is_countable($types) && count($types) > 0)
                        <div>
                            <h3 class="text-xs uppercase tracking-[0.2em] mb-6 opacity-60">Loại sản phẩm</h3>
                            <form method="GET" id="filter-form">
                                @foreach($types as $type)
                                    <div class="flex items-center space-x-3 mb-2">
                                        <input type="checkbox" name="types[]" value="{{ $type }}"
                                            {{ in_array($type, (array)request('types', [])) ? 'checked' : '' }}
                                            class="cursor-pointer">
                                        <label class="text-xs cursor-pointer">{{ $type }}</label>
                                    </div>
                                @endforeach
                                <button type="submit"
                                    class="mt-3 text-xs uppercase tracking-[0.2em] border border-gray-300 hover:border-black py-1 w-full">
                                    Lọc loại
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Colors --}}
                    @if(isset($colors) && is_countable($colors) && count($colors) > 0)
                        <div>
                            <h3 class="text-xs uppercase tracking-[0.2em] mb-6 opacity-60">Màu sắc</h3>
                            <form method="GET">
                                @foreach($colors as $color)
                                    <div class="flex items-center space-x-3 mb-2">
                                        <input type="checkbox" name="colors[]" value="{{ $color }}"
                                            {{ in_array($color, (array)request('colors', [])) ? 'checked' : '' }}>
                                        <label class="text-xs cursor-pointer">{{ $color }}</label>
                                    </div>
                                @endforeach
                                <button type="submit"
                                    class="mt-3 text-xs uppercase tracking-[0.2em] border border-gray-300 hover:border-black py-1 w-full">
                                    Lọc màu
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Sizes --}}
                    @if(isset($sizes) && is_countable($sizes) && count($sizes) > 0)
                        <div>
                            <h3 class="text-xs uppercase tracking-[0.2em] mb-6 opacity-60">Kích thước</h3>
                            <form method="GET" class="flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                    <label>
                                        <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                            {{ in_array($size, (array)request('sizes', [])) ? 'checked' : '' }}
                                            class="hidden peer">
                                        <span
                                            class="peer-checked:bg-black peer-checked:text-white border text-xs px-3 py-1 cursor-pointer inline-block">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                                <button type="submit"
                                    class="mt-3 text-xs uppercase tracking-[0.2em] border border-gray-300 hover:border-black py-1 w-full">
                                    Lọc size
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- Product Grid --}}
            <div class="flex-1">
                @if($products->isEmpty())
                    <div class="text-center py-24">
                        <p class="text-sm opacity-60 mb-8">No pieces match your current selection</p>
                        <a href="{{ route('products.all') }}"
                            class="border border-gray-300 hover:border-black px-4 py-2 text-xs uppercase tracking-[0.2em] inline-block">
                            Clear All Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            <div>
                                <a href="{{ route('products.show', $product->id) }}" class="block group">
                                    <div class="aspect-[3/4] bg-gray-100 mb-3 overflow-hidden">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <div>
                                        <h3 class="text-xs uppercase tracking-[0.1em]">{{ $product->name }}</h3>
                                        <p class="text-xs opacity-60 mt-1">
                                            {{ number_format($product->price, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
