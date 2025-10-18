{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm - Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Thêm sản phẩm mới</h1>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Quay lại</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tên sản phẩm --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Tên sản phẩm *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                    value="{{ old('name') }}">
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Loại sản phẩm --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Loại sản phẩm *</label>
                <input type="text" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                    value="{{ old('type') }}">
                @error('type')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Danh mục --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Danh mục *</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black">
                    <option value="">-- Chọn danh mục --</option>
                    <option value="men" {{ old('category') == 'men' ? 'selected' : '' }}>Nam</option>
                    <option value="women" {{ old('category') == 'women' ? 'selected' : '' }}>Nữ</option>
                    <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>Phụ kiện</option>
                </select>
                @error('category')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Giá bán --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Giá bán *</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                    value="{{ old('price') }}">
                @error('price')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Giá gốc --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Giá gốc</label>
                <input type="number" name="original_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                    value="{{ old('original_price') }}">
                @error('original_price')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tồn kho --}}
            <div>
                <label class="block text-sm mb-2 font-medium">Tồn kho *</label>
                <input type="number" name="stock_quantity" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                    value="{{ old('stock_quantity', 0) }}">
                @error('stock_quantity')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Mô tả --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Mô tả *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Chất liệu --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Chất liệu</label>
            <input type="text" name="material" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                value="{{ old('material') }}">
            @error('material')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Màu sắc --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Màu sắc (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="colors" required placeholder="Black, White, Grey"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                value="{{ old('colors') }}">
            @error('colors')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Kích thước --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Kích thước (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="sizes" required placeholder="S, M, L, XL"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black"
                value="{{ old('sizes') }}">
            @error('sizes')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Ảnh chính --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Ảnh chính *</label>
            <input type="file" name="image" required accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black">
            @error('image')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Ảnh phụ --}}
        <div>
            <label class="block text-sm mb-2 font-medium">Ảnh phụ</label>
            <input type="file" name="gallery[]" multiple accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black">
            @error('gallery')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Hiển thị sản phẩm --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4">
            <label class="text-sm">Hiển thị sản phẩm</label>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">
                Tạo sản phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
