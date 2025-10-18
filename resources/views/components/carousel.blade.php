@props(['orientation' => 'horizontal'])

@php
    $axis = $orientation === 'horizontal' ? 'x' : 'y';
@endphp

<div 
    x-data="carouselComponent('{{ $axis }}')" 
    x-on:keydown.window.prevent.arrow-left="scrollPrev"
    x-on:keydown.window.prevent.arrow-right="scrollNext"
    class="relative w-full"
    role="region"
    aria-roledescription="carousel"
>
    <div class="overflow-hidden" x-ref="container">
        <div 
            class="flex transition-transform duration-300"
            :class="orientation === 'x' ? '-ml-4' : '-mt-4 flex-col'"
            x-ref="track"
        >
            {{ $slot }}
        </div>
    </div>

    {{-- Nút điều hướng --}}
    <button 
        type="button"
        class="absolute size-8 rounded-full top-1/2 -left-12 -translate-y-1/2 bg-white border flex items-center justify-center disabled:opacity-40"
        x-on:click="scrollPrev"
        :disabled="!canScrollPrev"
    >
        ←
        <span class="sr-only">Previous slide</span>
    </button>

    <button 
        type="button"
        class="absolute size-8 rounded-full top-1/2 -right-12 -translate-y-1/2 bg-white border flex items-center justify-center disabled:opacity-40"
        x-on:click="scrollNext"
        :disabled="!canScrollNext"
    >
        →
        <span class="sr-only">Next slide</span>
    </button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('carouselComponent', (axis) => ({
            orientation: axis,
            index: 0,
            track: null,
            container: null,
            slideWidth: 0,
            canScrollPrev: false,
            canScrollNext: true,

            init() {
                this.container = this.$refs.container;
                this.track = this.$refs.track;
                this.updateScrollState();
                window.addEventListener('resize', () => this.updateScrollState());
            },

            scrollPrev() {
                this.container.scrollBy({ 
                    left: this.orientation === 'x' ? -this.container.clientWidth : 0, 
                    top: this.orientation === 'y' ? -this.container.clientHeight : 0, 
                    behavior: 'smooth' 
                });
                this.updateScrollStateAfter();
            },

            scrollNext() {
                this.container.scrollBy({ 
                    left: this.orientation === 'x' ? this.container.clientWidth : 0, 
                    top: this.orientation === 'y' ? this.container.clientHeight : 0, 
                    behavior: 'smooth' 
                });
                this.updateScrollStateAfter();
            },

            updateScrollStateAfter() {
                setTimeout(() => this.updateScrollState(), 300);
            },

            updateScrollState() {
                this.canScrollPrev = this.container.scrollLeft > 0;
                this.canScrollNext = this.container.scrollLeft + this.container.clientWidth < this.track.scrollWidth;
            }
        }))
    })
</script>
