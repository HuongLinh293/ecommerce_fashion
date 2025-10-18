@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
    <div class="container mx-auto px-6 lg:px-8 py-12">

        {{-- Header --}}
        <div class="mb-12 flex items-center justify-between">
            <div>
                <h1 class="text-sm uppercase tracking-[0.2em] mb-2">
                    {{ $category === 'men' ? 'Men' : 'Women' }}
                    @if(isset($type) && $type !== 'view-all')
                        <span class="opacity-60"> / {{ ucfirst($type) }}</span>
                    @endif
                </h1>
                <p class="text-xs opacity-60">
                    {{ $products->total() }} pieces available
                </p>
            </div>

            {{-- Mobile filter --}}
            <div class="lg:hidden">
                <button 
                    @click="mobileFilterOpen = true" 
                    class="border border-gray-300 px-3 py-2 flex items-center gap-2 text-xs uppercase tracking-[0.2em]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M4 8h16M5 12h14M6 16h12M7 20h10"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>

        <div class="flex gap-16">
            {{-- Sidebar (Desktop) --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-32">
                    @include('products.partials.filter')
                </div>
            </aside>

            {{-- Products Grid --}}
            <div class="flex-1">
                @if($products->isEmpty())
                    <div class="text-center py-24">
                        <p class="text-sm opacity-60 mb-8">
                            No pieces match your current selection
                        </p>
                        <a href="{{ route('products.index', ['category' => $category]) }}" 
                           class="border border-gray-300 hover:border-black px-4 py-2 text-xs uppercase tracking-[0.2em]">
                            Clear All Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            @include('components.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
