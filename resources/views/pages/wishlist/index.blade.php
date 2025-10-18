@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-8">
    {{-- Thông báo success --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 space-y-2 md:space-y-0">
        <div>
            <h1 class="text-xl font-medium text-gray-700d">SẢN PHẨM YÊU THÍCH</h1>
           
        </div>
        <div class="text-sm text-gray-600">
            <span class="inline-flex items-center bg-gray-100 px-3 py-1 rounded-full text-xs font-medium">
                {{ $items->total() }} sản phẩm
            </span>
        </div>
    </div>

    {{-- Nội dung wishlist --}}
    @if($items->isEmpty())
        <div class="text-gray-500 text-center py-16">
            Bạn chưa có sản phẩm nào trong wishlist.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($items as $item)
                <div class="relative group">
                    {{-- Product card --}}
                    @include('components.product-card', ['product' => $item->product])

                    {{-- Nút xóa wishlist --}}
                    <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST"
                          class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-200">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                            Xóa
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Phân trang --}}
       
    @endif
</div>
@endsection
