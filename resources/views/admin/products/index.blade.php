{{-- Admin Products List --}}
@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm - Admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Quản lý sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">
            + Thêm sản phẩm
        </a>
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <form method="GET" class="flex flex-wrap gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Tìm kiếm..." 
                value="{{ request('search') }}"
                class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg"
            >
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Tất cả danh mục</option>
                <option value="men" {{ request('category') == 'men' ? 'selected' : '' }}>Nam</option>
                <option value="women" {{ request('category') == 'women' ? 'selected' : '' }}>Nữ</option>
                <option value="accessories" {{ request('category') == 'accessories' ? 'selected' : '' }}>Phụ kiện</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                Lọc
            </button>
        </form>
    </div>

    {{-- Bảng sản phẩm --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600">Sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600">Tồn kho</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-600">Trạng thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-600">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @php
                                    $mainImg = $product->image_url;
                                    $galleryUrls = $product->gallery_urls ?? [];
                                    $hoverImg = (is_array($galleryUrls) && count($galleryUrls) > 0) ? $galleryUrls[0] : $mainImg;
                                @endphp
                                <style>
                                    .relative-img-hover { position: relative; width: 48px; height: 48px; }
                                    .relative-img-hover img { position: absolute; top: 0; left: 0; transition: opacity 0.2s; }
                                    .relative-img-hover img:first-child { z-index: 1; opacity: 1; }
                                    .relative-img-hover:hover img:first-child { opacity: 0; }
                                    .relative-img-hover img:last-child { z-index: 2; opacity: 0; }
                                    .relative-img-hover:hover img:last-child { opacity: 1; }
                                </style>
                                <div class="relative-img-hover">
                                    <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                    <img src="{{ $hoverImg }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->type }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($product->category) }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ number_format($product->price, 0, ',', '.') }}₫</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $product->stock_quantity }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Hoạt động' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Xác nhận xóa sản phẩm này?')" class="text-red-600 hover:text-red-800 text-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>
@endsection
