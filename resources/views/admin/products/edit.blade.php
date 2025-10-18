{{-- Admin Edit Product --}}
@extends('layouts.admin')

@section('title', 'Sửa Sản Phẩm - Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Sửa sản phẩm: {{ $product->name }}</h1>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Quay lại</a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm mb-2">Tên sản phẩm *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('name', $product->name) }}">
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm mb-2">Loại sản phẩm *</label>
                <input type="text" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('type', $product->type) }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Danh mục *</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="men" {{ $product->category == 'men' ? 'selected' : '' }}>Nam</option>
                    <option value="women" {{ $product->category == 'women' ? 'selected' : '' }}>Nữ</option>
                    <option value="accessories" {{ $product->category == 'accessories' ? 'selected' : '' }}>Phụ kiện</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-2">Giá bán *</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('price', $product->price) }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Giá gốc</label>
                <input type="number" name="original_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('original_price', $product->original_price) }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Tồn kho *</label>
                <input type="number" name="stock_quantity" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('stock_quantity', $product->stock_quantity) }}">
            </div>
        </div>

        <div>
            <label class="block text-sm mb-2">Mô tả *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-2">Chất liệu</label>
            <input type="text" name="material" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('material', $product->material) }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Màu sắc (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="colors" required placeholder="Black, White, Grey" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('colors', is_array($product->colors) ? implode(', ', $product->colors) : $product->colors) }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Kích thước (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="sizes" required placeholder="S, M, L, XL" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : $product->sizes) }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Ảnh chính hiện tại</label>
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded mb-2">
            <label class="block text-sm mb-2">Thay đổi ảnh chính</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-sm mb-2">Ảnh phụ hiện tại</label>
            @php
                // use model accessors: parsed_gallery and gallery_urls
                $gallery = $product->parsed_gallery;
            @endphp
                @if(!empty($product->parsed_gallery))
                <div class="flex gap-2 mb-2">
                        @foreach($product->gallery_urls as $img)
                        <img src="{{ $img }}" alt="" class="w-20 h-20 object-cover rounded">
                    @endforeach
                </div>
            @endif
            <label class="block text-sm mb-2">Thay đổi ảnh phụ</label>
            <input type="file" name="gallery[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

              <input type="text" name="colors" required placeholder="Black, White, Grey" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('colors', is_array($product->parsed_colors) ? implode(', ', $product->parsed_colors) : $product->colors) }}">
            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-4 h-4">
            <label class="text-sm">Hiển thị sản phẩm</label>
        </div>

              <input type="text" name="sizes" required placeholder="S, M, L, XL" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sizes', is_array($product->parsed_sizes) ? implode(', ', $product->parsed_sizes) : $product->sizes) }}">
            <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">Cập nhật sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">Hủy</a>
        </div>
    </form>
</div>
@endsection
