{{-- resources/views/components/ui/banner-slider.blade.php --}}
@props([
    'banners' => [
        [
            'id' => 1,
            'title' => 'WINTER 2025',
            'subtitle' => 'NEW COLLECTION',
            'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1600&h=900&fit=crop',
        ],
        [
            'id' => 2,
            'title' => 'EXCLUSIVE SALE',
            'subtitle' => 'UP TO 50% OFF',
            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&h=900&fit=crop',
        ],
    ],
])

<div 
    x-data="bannerSlider({{ json_encode($banners) }})" 
    x-init="init()" 
    class="relative w-full h-[70vh] md:h-[80vh] overflow-hidden bg-gray-100"
>
    {{-- Slides --}}
    <template x-for="(banner, index) in banners" :key="banner.id">
        <div 
            class="absolute inset-0 transition-opacity duration-1000" 
            :class="index === currentSlide ? 'opacity-100' : 'opacity-0'"
        >
            <div class="relative w-full h-full">
                <img 
                    :src="banner.image" 
                    :alt="banner.title" 
                    class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <p class="text-sm md:text-base tracking-[0.3em] mb-3 uppercase opacity-80" x-text="banner.subtitle"></p>
                        <h2 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight uppercase mb-8" x-text="banner.title"></h2>
                        <button class="border-2 border-white px-10 py-4 uppercase tracking-widest text-xs hover:bg-white hover:text-black transition-all duration-300">
                            Khám phá ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Navigation Buttons --}}
    <button 
        @click="prevSlide"
        class="absolute left-6 top-1/2 -translate-y-1/2 opacity-60 hover:opacity-100 transition-opacity"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <button 
        @click="nextSlide"
        class="absolute right-6 top-1/2 -translate-y-1/2 opacity-60 hover:opacity-100 transition-opacity"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
        <template x-for="(banner, index) in banners" :key="index">
            <button 
                @click="goToSlide(index)"
                class="h-0.5 transition-all"
                :class="index === currentSlide ? 'bg-white w-12' : 'bg-white/40 w-8'"
            ></button>
        </template>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function bannerSlider(banners) {
                return {
                    banners,
                    currentSlide: 0,
                    timer: null,

                    init() {
                        this.startAutoSlide();
                    },

                    startAutoSlide() {
                        this.stopAutoSlide();
                        this.timer = setInterval(() => {
                            this.nextSlide();
                        }, 5000);
                    },

                    stopAutoSlide() {
                        if (this.timer) clearInterval(this.timer);
                    },

                    nextSlide() {
                        this.currentSlide = (this.currentSlide + 1) % this.banners.length;
                    },

                    prevSlide() {
                        this.currentSlide = (this.currentSlide - 1 + this.banners.length) % this.banners.length;
                    },

                    goToSlide(index) {
                        this.currentSlide = index;
                    }
                }
            }
        </script>
    @endpush
@endonce
