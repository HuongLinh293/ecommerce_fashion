{{-- Image Zoom Modal Component --}}
@props(['src', 'alt'])

<div 
    x-data="{
        isOpen: false,
        scale: 1,
        position: { x: 0, y: 0 },
        isDragging: false,
        dragStart: { x: 0, y: 0 },
        
        open() {
            this.isOpen = true;
            this.scale = 1;
            this.position = { x: 0, y: 0 };
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = 'unset';
        },
        
        zoomIn() {
            this.scale = Math.min(this.scale + 0.5, 4);
        },
        
        zoomOut() {
            this.scale = Math.max(this.scale - 0.5, 1);
            if (this.scale <= 1.5) {
                this.position = { x: 0, y: 0 };
            }
        },
        
        reset() {
            this.scale = 1;
            this.position = { x: 0, y: 0 };
        },
        
        handleMouseDown(e) {
            if (this.scale > 1) {
                this.isDragging = true;
                this.dragStart = {
                    x: e.clientX - this.position.x,
                    y: e.clientY - this.position.y
                };
            }
        },
        
        handleMouseMove(e) {
            if (this.isDragging && this.scale > 1) {
                this.position = {
                    x: e.clientX - this.dragStart.x,
                    y: e.clientY - this.dragStart.y
                };
            }
        },
        
        handleMouseUp() {
            this.isDragging = false;
        },
        
        handleKeyDown(e) {
            if (!this.isOpen) return;
            
            if (e.key === 'Escape') {
                this.close();
            } else if (e.key === '+' || e.key === '=') {
                this.zoomIn();
            } else if (e.key === '-') {
                this.zoomOut();
            }
        }
    }"
    @keydown.window="handleKeyDown($event)"
    x-init="$watch('isOpen', value => {
        if (!value) {
            document.body.style.overflow = 'unset';
        }
    })"
>
    {{-- Trigger Button --}}
    <button 
        @click="open()"
        type="button"
        class="cursor-zoom-in focus:outline-none"
        {{ $attributes }}
    >
        {{ $slot }}
    </button>

    {{-- Modal --}}
    <div 
        x-show="isOpen"
        x-cloak
        @click="close()"
        class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
        style="display: none;"
    >
        {{-- Toolbar --}}
        <div class="absolute top-6 right-6 flex items-center gap-2 z-10">
            {{-- Zoom Out --}}
            <button
                @click.stop="zoomOut()"
                :disabled="scale <= 1"
                class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                title="Zoom out (-)"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                </svg>
            </button>
            
            {{-- Scale Display --}}
            <div class="px-4 py-2 bg-white/10 rounded-full text-white text-sm">
                <span x-text="Math.round(scale * 100) + '%'"></span>
            </div>
            
            {{-- Zoom In --}}
            <button
                @click.stop="zoomIn()"
                :disabled="scale >= 4"
                class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                title="Zoom in (+)"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                </svg>
            </button>
            
            {{-- Reset --}}
            <button
                @click.stop="reset()"
                class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors"
                title="Reset"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
            </button>
            
            {{-- Close --}}
            <button
                @click.stop="close()"
                class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors"
                title="Close (Esc)"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Image Container --}}
        <div
            @click.stop
            @mousedown="handleMouseDown($event)"
            @mousemove="handleMouseMove($event)"
            @mouseup="handleMouseUp()"
            @mouseleave="handleMouseUp()"
            @wheel.prevent="$event.deltaY < 0 ? zoomIn() : zoomOut()"
            :style="'cursor: ' + (scale > 1 ? (isDragging ? 'grabbing' : 'grab') : 'default')"
            class="relative w-full h-full flex items-center justify-center overflow-hidden"
        >
            <img
                src="{{ $src }}"
                alt="{{ $alt }}"
                draggable="false"
                :style="`transform: scale(${scale}) translate(${position.x / scale}px, ${position.y / scale}px); transform-origin: center center;`"
                class="max-w-[90vw] max-h-[90vh] object-contain select-none transition-transform"
            />
        </div>

        {{-- Instructions --}}
        <div 
            x-show="scale === 1"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/60 text-sm text-center space-y-1"
        >
            <p>Click để zoom • Scroll hoặc ± để zoom in/out • Kéo để di chuyển</p>
            <p class="text-xs">ESC để đóng</p>
        </div>
        
        <div 
            x-show="scale > 1"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/60 text-sm"
        >
            Kéo để xem chi tiết • Scroll để zoom
        </div>
    </div>
</div>

{{-- Add x-cloak style if not already in your CSS --}}
<style>
    [x-cloak] { display: none !important; }
</style>

{{-- Usage Example:
    @include('components.image-zoom', [
        'src' => $product->image,
        'alt' => $product->name
    ])
        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    @endinclude
    
    Or as a component:
    
    <x-image-zoom src="{{ $product->image }}" alt="{{ $product->name }}">
        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    </x-image-zoom>
--}}
