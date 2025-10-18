@php
/**
 * products grid partial
 * expects $products (LengthAwarePaginator)
 */
@endphp

@if($products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="product-grid">
        @foreach($products as $product)
            <div class="bg-white shadow rounded overflow-hidden">
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                    <img src="{{ $product->image_url ?? '/assets/placeholder.png' }}" class="w-full h-48 object-cover" alt="{{ $product->name }}">
                </a>
                <div class="p-4">
                    <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                    <div class="text-gray-600">{{ number_format($product->price) }}₫</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
@else
    <div class="p-8 text-center text-gray-500">Không tìm thấy sản phẩm phù hợp.</div>
@endif
