@php
    $menuItems = config('menu');
@endphp

<header x-data="header()" class="fixed top-0 z-50 w-full bg-white text-black border-b border-black/20">
    <div class="relative">
        {{-- Thanh navigation chính --}}
        <div class="flex items-center h-16 px-0 lg:px-2">

            {{-- Menu trái --}}
            <nav class="hidden lg:flex items-center space-x-6 flex-1 relative">
                <template x-for="item in menuItems" :key="item.id">
                    <div 
                        class="relative group"
                        @mouseenter="item.hasDropdown && (hoveredMenu = item.id)"
                        @mouseleave="item.hasDropdown && (hoveredMenu = null)"
                    >
<a 
    :href="item.url" 
    class="text-xs tracking-[0.1em] transition-all hover:opacity-60 uppercase flex items-center gap-1"
    x-text="item.label"
></a>



                        {{-- Dropdown --}}
                        <div 
                            x-show="item.hasDropdown && hoveredMenu === item.id"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="absolute top-full left-0 pt-2 z-50"
                        >
                            <div class="bg-white border border-white/20 shadow-xl min-w-[180px] overflow-hidden">
                                <template x-for="subItem in item.subItems" :key="subItem.id">
                                    <a 
    :href="subItem.url"
    class="block w-full px-4 py-3 text-xs uppercase tracking-[0.15em] hover:bg-black hover:text-white transition-all"
    x-text="subItem.label"
></a>


                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </nav>

            {{-- Logo giữa --}}
            <a href="{{ url('/') }}" class="flex items-center cursor-pointer absolute left-1/2 -translate-x-1/2">
                <h1 class="text-3xl uppercase tracking-[0.2em] font-light">VIVILLAN</h1>
            </a>

            {{-- Icons bên phải --}}
            <div class="flex items-center space-x-7 ml-auto">
                {{-- Search --}}
                <button @click="isSearchOpen = !isSearchOpen" class="hover:opacity-60 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                </button>

                {{-- VI flag --}}
                <div class="flex items-center gap-1.5 opacity-80">
                    <div class="w-5 h-3.5 bg-red-600 flex items-center justify-center">
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider">VI</span>
                </div>

                {{-- User --}}
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="hover:opacity-60 flex items-center gap-2">
                            <div class="w-7 h-7 bg-black text-white flex items-center justify-center text-xs uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 bg-white border border-black/20 shadow-lg w-48">
                            <div class="px-4 py-3 border-b border-black/10">
                                <p class="text-sm">{{ auth()->user()->name }}</p>
                                <p class="text-xs opacity-60">{{ auth()->user()->email }}</p>
                            </div>
                            <button @click="window.location.href='/orders'" class="block w-full text-left px-4 py-2 text-sm hover:bg-black hover:text-white">Đơn hàng</button>
                            <button @click="window.location.href='/wishlist'" class="block w-full text-left px-4 py-2 text-sm hover:bg-black hover:text-white">Yêu thích</button>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-black hover:text-white">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hover:opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>
                @endauth

                {{-- Cart --}}
                <div class="relative">
                    <a href="{{ route('cart.index') }}" class="relative hover:opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 1.293a1 1 0 000 1.414L7 17h10l1.293-1.293a1 1 0 000-1.414L17 13M5 21h2a2 2 0 002-2H3a2 2 0 002 2zm12 0h2a2 2 0 002-2h-6a2 2 0 002 2z" />
                        </svg>
                        <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-2 -right-2 bg-black text-white text-xs w-4 h-4 flex items-center justify-center text-[10px]"></span>
                    </a>

                    {{-- Toast anchored to cart icon: reads Alpine.store('cartToast') or fallback --}}
                    <div
                        x-show="(typeof Alpine !== 'undefined' && Alpine.store('cartToast') && Alpine.store('cartToast').show) || (window._cartToastStub && window._cartToastStub.show)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="absolute z-50 pointer-events-none"
                        style="right: 0; top: -3rem;"
                    >
                        <div class="pointer-events-auto bg-black text-white text-sm px-3 py-2 rounded shadow-lg max-w-xs text-right">
                            <span x-text="(typeof Alpine !== 'undefined' && Alpine.store('cartToast') && Alpine.store('cartToast').message) || (window._cartToastStub && window._cartToastStub.message)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thanh search --}}
        <div x-show="isSearchOpen" x-transition class="px-4 lg:px-8 pb-6">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="SEARCH..."
                    x-model="searchQuery"
                    @input="doSearch"
                    class="w-full px-0 py-4 border-b border-black/20 bg-transparent text-black focus:outline-none placeholder:text-black/40 text-sm uppercase tracking-[0.15em]"
                    autofocus
                />
                <button 
                    x-show="searchQuery"
                    @click="clearSearch"
                    class="absolute right-0 top-1/2 -translate-y-1/2 opacity-40 hover:opacity-100"
                >
                    ✕
                </button>
            </div>

            {{-- Kết quả tìm kiếm --}}
            <div x-show="searchResults.length" class="mt-4 max-h-96 overflow-y-auto border border-black/20 bg-white shadow-xl">
                <template x-for="product in searchResults" :key="product.id">
                    <div @click="goProduct(product.id)" class="flex items-center gap-4 p-4 hover:bg-black hover:text-white cursor-pointer border-b border-black/10 last:border-0">
                        <img :src="product.image" class="w-16 h-20 object-cover" />
                        <div class="flex-1">
                            <p class="text-sm mb-1" x-text="product.name"></p>
                            <p class="text-xs opacity-60 uppercase tracking-[0.15em]" x-text="product.type"></p>
                            <p class="text-sm mt-1" x-text="product.price"></p>
                        </div>
                    </div>
                </template>
                <div x-show="searchQuery && !searchResults.length" class="mt-4 p-4 text-center text-sm opacity-60">
                    Không tìm thấy sản phẩm nào
                </div>
            </div>
        </div>
    </div>
</header>

<script defer>
document.addEventListener('alpine:init', () => {
    // Ensure a cartToast store exists so header toast can react immediately
    try {
        if (!Alpine.store('cartToast')) {
            Alpine.store('cartToast', { show: false, message: '' });
            console.debug('[alpine:init] created store cartToast', Alpine.store('cartToast'));
        }
    } catch (e) {
        console.debug('[alpine:init] could not create store cartToast yet', e);
    }

    Alpine.data('header', () => ({
        // Dữ liệu mặc định
        currentPage: 'home',
        hoveredMenu: null,
        isSearchOpen: false,
        searchQuery: '',
        searchResults: [],
        cartCount: 0,
        products: [],
        menuItems: @json($menuItems),

        // === Hàm điều hướng ===
        navigate(page, sub = null) {
            const menu = this.menuItems.find(i => i.id === page);
            if (!menu) return;

            if (sub) {
                const subItem = menu.subItems?.find(s => s.id === sub);
                if (subItem?.url) window.location.href = subItem.url;
                return;
            }

            if (menu.url) {
                if (menu.hasDropdown) {
                    const viewAll = menu.subItems?.find(s => s.id === 'view-all');
                    if (viewAll?.url) {
                        window.location.href = viewAll.url;
                        return;
                    }
                }
                window.location.href = menu.url;
            }
        },

        // === Hàm tìm kiếm ===
        doSearch() {
            const q = this.searchQuery.trim();
            if (!q) { this.searchResults = []; return; }

            // debounce pattern: clear existing timer and set a new one
            if (this._searchTimer) clearTimeout(this._searchTimer);
            this._searchTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`/api/products/search?q=${encodeURIComponent(q)}`);
                    if (res.ok) {
                        this.searchResults = await res.json();
                    } else {
                        this.searchResults = [];
                    }
                } catch (e) {
                    this.searchResults = [];
                }
            }, 250);
        },

        // === Xoá ô tìm kiếm ===
        clearSearch() {
            this.searchQuery = '';
            this.searchResults = [];
        },

        // === Chuyển đến sản phẩm cụ thể ===
        goProduct(id) {
            window.location.href = `/products/view/${id}`;
        }
    }));
});
</script>

