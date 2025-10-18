@extends('layouts.app')

@section('title', 'VIVILLAN - Home')

@section('content')
    {{-- Hero Section --}}
    <section class="relative h-screen flex items-end justify-center overflow-hidden mt-6">
        <div class="absolute inset-0 z-10">
            <img src="{{ asset('assets/products/home2.png') }}" alt="Avant-Garde Hero"
                class="w-full h-300 object-cover object-center ">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <div class="relative z-10 pb-16">
            <a href="{{ route('explore') }}"
                class="text-white text-xs tracking-[0.3em] uppercase border-2 border-white px-10 py-4 hover:bg-white hover:text-black transition-all duration-300">
                ENTER COLLECTION
            </a>
        </div>
    </section>

    {{-- Philosophy Section --}}
    <section class="py-32 bg-black text-white text-center">
        <div class="w-2 h-2 bg-white mx-auto mb-8"></div>
        <h2 class="text-4xl md:text-5xl lg:text-6xl leading-tight mb-12 font-serif">
            "Fashion is not about clothes.<br>It's about living art."
        </h2>
        <p class="text-xs tracking-[0.3em] uppercase opacity-60">VIVILLAN Philosophy</p>
    </section>

    {{-- Ready-to-Wear Section --}}
    <x-product-carousel 
        title="READY-TO-WEAR" 
        subtitle="Collection 01" 
        :products="$readyToWearProducts" 
        category="women"
       
    />

    {{-- Featured Image --}}
    <section class="bg-black">
        <img src="{{ asset('assets/products/home1.png') }}" alt="Featured Collection"
            class="w-full h-auto object-cover opacity-90">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    </section>
    

    {{-- Women Section --}}
    <x-product-carousel 
        title="WOMEN" 
        subtitle="Collection 02" 
        :products="$womenProducts" 
        category="women"
        
    />

        <section class="py-32 bg-black text-white text-center">
        <div class="w-2 h-2 bg-white mx-auto mb-8"></div>
        <h2 class="text-4xl md:text-5xl lg:text-6xl leading-tight mb-12 font-serif">
            "Fashion is not about clothes.<br>It's about living art."
        </h2>
        <p class="text-xs tracking-[0.3em] uppercase opacity-60">VIVILLAN Philosophy</p>
    </section>

    {{-- Men Section --}}
    <x-product-carousel 
        title="MEN" 
        subtitle="Collection 03" 
        :products="$menProducts" 
        category="men"
    />

    {{-- CTA Section --}}
    <section class="py-32 bg-black text-white">
        <div class="container mx-auto px-6 lg:px-12 max-w-5xl grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <div class="mb-8">
                    <div class="w-12 h-[2px] bg-white mb-8"></div>
                    <h3 class="text-4xl md:text-5xl leading-tight mb-6 font-serif">Wear<br>Different</h3>
                </div>
                <p class="text-sm leading-relaxed opacity-80 mb-8">
                    VIVILLAN redefines fashion through avant-garde design. 
                    Each piece is a statement, a rebellion against the ordinary. 
                    We create for those who dare to be different.
                </p>
                <a href="#"
                    class="text-xs tracking-[0.3em] uppercase border-2 border-white px-8 py-4 hover:bg-white hover:text-black transition-all duration-300">
                    EXPLORE PHILOSOPHY
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="border-2 border-white/20 p-8">
                    <p class="text-4xl mb-2 font-serif">100+</p>
                    <p class="text-xs tracking-[0.2em] uppercase opacity-60">Designs</p>
                </div>
                <div class="border-2 border-white/20 p-8">
                    <p class="text-4xl mb-2 font-serif">2025</p>
                    <p class="text-xs tracking-[0.2em] uppercase opacity-60">New Era</p>
                </div>
                <div class="border-2 border-white/20 p-8">
                    <p class="text-4xl mb-2 font-serif">A+</p>
                    <p class="text-xs tracking-[0.2em] uppercase opacity-60">Premium</p>
                </div>
                <div class="border-2 border-white/20 p-8">
                    <p class="text-4xl mb-2 font-serif">∞</p>
                    <p class="text-xs tracking-[0.2em] uppercase opacity-60">Creativity</p>
                </div>
            </div>
        </div>
    </section>
@endsection
