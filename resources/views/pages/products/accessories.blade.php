@extends('layouts.app')

@section('title', 'Phụ kiện - VIVILLAN')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">
  <div class="container mx-auto px-6 lg:px-8 py-12">
    
    {{-- Header --}}
    <div class="mb-16">
      <h1 class="text-sm uppercase tracking-[0.2em] mb-2">
        @if($subcategory === 'bag')
          Túi Xách
        @elseif($subcategory === 'shoes')
          Giày
        @else
          Phụ Kiện
        @endif
      </h1>
      <p class="text-xs opacity-60">{{ count($accessories) }} sản phẩm</p>
    </div>

    {{-- Hero --}}
    <section class="mb-20">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
          <h2 class="text-5xl font-light mb-8" style="font-family: 'Playfair Display', serif">
            TÚI & GIÀY<br>CAO CẤP
          </h2>
          <p class="text-sm leading-relaxed opacity-80 mb-8 max-w-md">
            Khám phá bộ sưu tập phụ kiện cao cấp của chúng tôi. Mỗi sản phẩm đại diện cho sự 
            kết hợp hoàn hảo giữa nghệ thuật thủ công và thiết kế đương đại.
          </p>
          <div class="w-24 h-px bg-black mb-8"></div>
          <p class="text-xs uppercase tracking-[0.2em] opacity-60">
            VIVILLAN Excellence Since 2020
          </p>
        </div>
        <div>
          <img src="{{ asset('assets/products/pk2.png') }}" alt="Phụ kiện" class="w-full h-[500px] object-contain bg-white">
        </div>
      </div>
    </section>

    {{-- Products Grid --}}
    <section class="mb-20">
     <h3 class="text-3xl font-light mb-12 text-center underline"
    style="font-family: 'Playfair Display', serif">
        BỘ SƯU TẬP
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
        @foreach($accessories as $item)
          <div>
            <div class="bg-white mb-6 overflow-hidden flex items-center justify-center group cursor-pointer border-[15px] border-transparent" style="width:375px;height:450px">
              @if($item['name'] === 'VIVILLAN City Bag - Black')
                <img src="{{ asset('assets/products/pk1.png') }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
              @elseif($item['name'] === 'VIVILLAN City Bag - Grey')
                <img src="{{ asset('assets/products/pk2.png') }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
              @else
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
              @endif
            </div>

            <div class="space-y-4">
              <div class="flex items-center justify-between text-xs tracking-[0.15em] opacity-60">
                <span>{{ $item['category'] }}</span>
                <span>{{ $item['type'] }}</span>
              </div>
              <h3 class="text-lg">{{ $item['name'] }}</h3>
              <p class="text-base">{{ number_format($item['price'], 0, ',', '.') }}₫</p>

              <div class="border-t border-gray-200 pt-4"></div>

              

              <a href="{{ route('products.show', ['id' => $item['id']]) }}" class="block w-full bg-black text-white py-3 text-xs uppercase tracking-[0.2em] hover:bg-gray-800 text-center mt-6">
                Xem Chi Tiết
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </section>

    {{-- Features --}}
    <section class="py-16 border-t border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
        <div>
          <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Chất liệu cao cấp</h4>
          <p class="text-xs opacity-60">Da Ý cao cấp, thủ công tỉ mỉ</p>
        </div>
        <div>
          <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Bảo hành trọn đời</h4>
          <p class="text-xs opacity-60">Bảo hành toàn diện</p>
        </div>
        <div>
          <h4 class="text-xs uppercase tracking-[0.2em] mb-3">Giao hàng toàn cầu</h4>
          <p class="text-xs opacity-60">Miễn phí vận chuyển quốc tế</p>
        </div>
      </div>
    </section>

    {{-- Newsletter --}}
    <section class="pt-16 border-t border-gray-200">
      <div class="max-w-md mx-auto text-center">
        <h3 class="text-xs uppercase tracking-[0.2em] mb-6">Cập nhật tin tức</h3>
        <p class="text-xs opacity-60 mb-6">Đăng ký để không bỏ lỡ sản phẩm mới</p>
        <div class="flex">
          <input type="email" placeholder="Email của bạn" class="flex-1 border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:border-black">
          <button class="bg-black text-white px-6 py-3 text-xs uppercase tracking-[0.2em] hover:bg-gray-800">Đăng ký</button>
        </div>
      </div>
    </section>

  </div>
</div>
@endsection
