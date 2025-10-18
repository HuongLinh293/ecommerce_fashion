
@extends('layouts.app')

@section('title', 'Avant-Garde - VIVILLAN')

@section('content')
<div class="min-h-screen bg-white text-black pt-20">

    {{-- Hero Section --}}
    <section class="relative min-h-[90vh] flex items-center justify-center bg-black">
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('assets/products/explore3.png') }}"
                alt="Yohji Yamamoto Avant-Garde"
                class="w-full h-full object-cover opacity-80"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>
        </div>

        <div class="relative z-10 text-center text-white px-6">
            <p class="text-xs uppercase tracking-[0.3em] mb-6 opacity-80">
                VIVILLAN PRESENTS
            </p>
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-light leading-none mb-8" style="font-family: 'Playfair Display', serif">
                AVANT-GARDE
            </h1>
            <p class="text-sm md:text-base leading-relaxed opacity-90 max-w-2xl mx-auto">
                Nghệ thuật không tuân theo quy tắc. Thời trang không chỉ là quần áo.<br>
                Đây là nơi sự sáng tạo không giới hạn.
            </p>
        </div>
    </section>

    {{-- Avant-Garde Collections --}}
    <section class="py-32">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="text-center mb-24">
                <h2 class="text-5xl md:text-6xl font-light mb-6" style="font-family: 'Playfair Display', serif">
                    Deconstructed Beauty
                </h2>
                <p class="text-xs uppercase tracking-[0.25em] opacity-60">Vẻ Đẹp Phá Cách</p>
            </div>

            @php
                $collections = [
                    [
                        'season' => 'Fall/Winter 2025',
                        'title' => 'Yohji Yamamoto',
                        'subtitle' => 'AVANT-GARDE COLLECTION',
                        'description' => 'Khám phá sự giao thoa giữa nghệ thuật và thời trang, nơi ranh giới được phá vỡ...',
                        'highlights' => [
                            'Silhouette oversized đặc trưng',
                            'Màu đen tối giản chủ đạo',
                            'Chất liệu wool và cashmere cao cấp',
                            'Thiết kế deconstructed độc đáo'
                        ],
                        'image' => asset('assets/products/gallery_68f2537458163.png'),
                        'available' => true,
                    ],
                    [
                        'season' => 'Fall/Winter 2025',
                        'title' => 'Darkwave Elegance',
                        'subtitle' => 'ABSTRACT MINIMALISM',
                        'description' => 'Sự thanh lịch tối giản với những đường cắt sắc nét...',
                        'highlights' => [
                            'Layering phức tạp và tinh tế',
                            'Chất liệu leather và textile kết hợp',
                            'Chi tiết kim loại thủ công',
                            'Tông màu đen, xám, trắng'
                        ],
                        'image' => asset('assets/products/explore2.png'),
                        'available' => true,
                    ],
                    [
                        'season' => 'Fall/Winter 2025',
                        'title' => 'Structured Poetry',
                        'subtitle' => 'NEO-CONSTRUCTIVISM',
                        'description' => 'Thơ ca trong kiến trúc thời trang. Những đường nét cứng cáp kết hợp...',
                        'highlights' => [
                            'Tailoring chuẩn xác tuyệt đối',
                            'Cấu trúc geometric độc đáo',
                            'Monochrome palette',
                            'Chất liệu cao cấp Châu Âu'
                        ],
                        'image' => asset('assets/products/explore1.png'),
                        'available' => true,
                    ],
                ];
            @endphp

            <div class="space-y-40">
                @foreach($collections as $index => $collection)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center {{ $index % 2 == 1 ? 'lg:grid-flow-col-dense' : '' }}">
                        {{-- Image --}}
                        <div class="relative {{ $index % 2 == 1 ? 'lg:col-start-2' : '' }}">
                            <div class="relative overflow-hidden bg-black">
                                <img
                                    src="{{ $collection['image'] }}"
                                    alt="{{ $collection['title'] }}"
                                    class="w-full h-[600px] md:h-[800px] object-cover object-center grayscale hover:grayscale-0 transition-all duration-700"
                                />
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="space-y-8 {{ $index % 2 == 1 ? 'lg:col-start-1' : '' }}">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] opacity-50 mb-4">
                                    {{ $collection['season'] }}
                                </p>
                                <h3 class="text-5xl md:text-6xl font-light mb-4 leading-tight" style="font-family: 'Playfair Display', serif">
                                    {{ $collection['title'] }}
                                </h3>
                                <p class="text-sm uppercase tracking-[0.2em] opacity-70 mb-8">
                                    {{ $collection['subtitle'] }}
                                </p>
                            </div>

                            <p class="text-base leading-relaxed opacity-80 max-w-lg">
                                {{ $collection['description'] }}
                            </p>

                            <div class="space-y-5">
                                <h4 class="text-xs uppercase tracking-[0.25em] opacity-50">Điểm Nổi Bật</h4>
                                <ul class="space-y-4">
                                    @foreach($collection['highlights'] as $highlight)
                                        <li class="flex items-start gap-4">
                                            <span class="w-2 h-2 bg-black mt-2 shrink-0"></span>
                                            <span class="text-sm opacity-70">{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="w-24 h-[2px] bg-black"></div>

                            @if($collection['available'])
                                <a href="{{ route('products.index', ['category' => 'men']) }}"
                                   class="inline-block bg-black text-white px-10 py-4 text-xs uppercase tracking-[0.25em] hover:bg-gray-800 transition-colors">
                                    Khám Phá Bộ Sưu Tập
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Philosophy Section --}}
    <section class="py-32 bg-black text-white">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-5xl md:text-6xl font-light mb-20 text-center" style="font-family: 'Playfair Display', serif">
                    The Philosophy
                </h2>

                @php
                    $quotes = [
                        ['quote' => 'Fashion is not simply a matter of clothes. Fashion is in the air...', 'author' => 'Coco Chanel'],
                       
                        ['quote' => 'I make clothes for a woman who is not swayed by what her husband thinks.', 'author' => 'Rei Kawakubo'],
                    ];
                @endphp

                <div class="space-y-20">
                    @foreach($quotes as $item)
                        <div class="border-l-2 border-white/30 pl-8 md:pl-12">
                            <p class="text-xl md:text-2xl font-light leading-relaxed mb-6 opacity-90" style="font-family: 'Playfair Display', serif">
                                “{{ $item['quote'] }}”
                            </p>
                            <p class="text-xs uppercase tracking-[0.3em] opacity-50">— {{ $item['author'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Manifesto --}}
    <section class="py-32">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-5xl md:text-7xl font-light mb-12 leading-tight" style="font-family: 'Playfair Display', serif">
                    "Wear Art,<br>Be Different"
                </h2>
                <p class="text-base md:text-lg leading-relaxed opacity-80 mb-16">
                    VIVILLAN tin rằng thời trang là hình thức nghệ thuật cao nhất - nó là bức tranh mà bạn mặc...
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('products.index', ['category' => 'men']) }}"
                       class="bg-black text-white px-12 py-5 text-xs uppercase tracking-[0.25em] hover:bg-gray-800 transition-colors">
                        Khám Phá Nam
                    </a>
                    <a href="{{ route('products.index', ['category' => 'women']) }}"
                       class="border-2 border-black px-12 py-5 text-xs uppercase tracking-[0.25em] hover:bg-black hover:text-white transition-colors">
                        Khám Phá Nữ
                    </a>
                </div>
            </div>
        </div>
    </section>


</div>
@endsection
