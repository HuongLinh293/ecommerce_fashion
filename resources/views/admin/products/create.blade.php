{{-- Admin Create Product --}}
@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm - Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Thêm sản phẩm mới</h1>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-800">← Quay lại</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm mb-2">Tên sản phẩm *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('name') }}">
                @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm mb-2">Loại sản phẩm *</label>
                <input type="text" name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('type') }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Danh mục *</label>
                <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="men" {{ old('category') == 'men' ? 'selected' : '' }}>Nam</option>
                    <option value="women" {{ old('category') == 'women' ? 'selected' : '' }}>Nữ</option>
                    <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>Phụ kiện</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-2">Giá bán *</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('price') }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Giá gốc</label>
                <input type="number" name="original_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('original_price') }}">
            </div>

            <div>
                <label class="block text-sm mb-2">Tồn kho *</label>
                <input type="number" name="stock_quantity" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('stock_quantity', 0) }}">
            </div>
        </div>

        <div>
            <label class="block text-sm mb-2">Mô tả *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-2">Chất liệu</label>
            <input type="text" name="material" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('material') }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Màu sắc (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="colors" required placeholder="Black, White, Grey" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('colors') }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Kích thước (phân cách bằng dấu phẩy) *</label>
            <input type="text" name="sizes" required placeholder="S, M, L, XL" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ old('sizes') }}">
        </div>

        <div>
            <label class="block text-sm mb-2">Ảnh chính *</label>
            <input type="file" name="image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label class="block text-sm mb-2">Ảnh phụ (gallery)</label>
            <input type="file" name="gallery[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

        <div class="flex items-center gap-4">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="w-4 h-4">
            <label class="text-sm">Hiển thị sản phẩm</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800">Tạo sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">Hủy</a>
        </div>
    </form>
</div>
@endsection
