<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside 
        x-data="{ open: true }"
        :class="open ? 'w-64' : 'w-20'"
        class="fixed top-0 left-0 h-full bg-white border-r border-gray-200 transition-all duration-300 z-50 flex flex-col"
    >
        {{-- Logo + Toggle --}}
        <div class="h-16 border-b flex items-center justify-between px-4">
            <h1 
                x-show="open"
                class="text-xl font-bold uppercase tracking-[0.3em]" 
                style="font-family: 'Playfair Display', serif;"
            >
                VIVILLAN
            </h1>
            <button 
                @click="open = !open"
                class="p-2 hover:bg-gray-100 rounded"
            >
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <svg x-show="!open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 py-5 px-2 space-y-1 text-sm overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">📊</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.products.*') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">📦</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Sản phẩm</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.orders.*') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">🧾</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Đơn hàng</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.customers.*') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">👤</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Khách hàng</span>
            </a>

            <a href="{{ route('admin.payments.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.payments.*') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">💳</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Thanh toán</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors 
                {{ request()->routeIs('admin.settings.*') ? 'bg-black text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <span class="w-6 text-center">⚙️</span>
                <span x-show="open" class="text-sm uppercase tracking-wide">Cài đặt</span>
            </a>
        </nav>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t">
            @csrf
            <button 
                type="submit"
                class="w-full flex items-center gap-3 px-3 py-3 hover:bg-gray-100 rounded text-gray-700 transition-colors"
            >
                <span class="w-6 text-center">🚪</span>
                <span x-show="open" class="text-sm">Đăng xuất</span>
            </button>
        </form>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 ml-64 transition-all duration-300" :class="open ? 'ml-64' : 'ml-20'">

        {{-- ADMIN TOPBAR --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center gap-4 flex-1">
                {{-- Search --}}
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        placeholder="Tìm kiếm..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                    />
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Notifications --}}
                <button class="relative p-2 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 
                                 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 
                                 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 
                                 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                {{-- User Menu --}}
                <div x-data="{ openMenu: false }" @click.away="openMenu = false" class="relative">
                    <button 
                        @click="openMenu = !openMenu"
                        class="flex items-center gap-3 hover:bg-gray-100 rounded-lg p-2"
                    >
                        <div class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center text-xs">
                            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : '?' }}
                        </div>
                        <span class="text-sm hidden md:block">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
                    </button>

                    <div 
                        x-show="openMenu"
                        x-transition
                        class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50"
                        style="display: none;"
                    >
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            Tài khoản
                        </a>
                        <hr class="my-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <section class="p-6">
            @yield('content')
        </section>
    </main>

    {{-- Render pushed scripts from child views (charts, page scripts) --}}
    @stack('scripts')
    </body>
</html>
