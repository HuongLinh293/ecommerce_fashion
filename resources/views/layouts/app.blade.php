<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Vivillan'))</title>

    {{-- 🧠 Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- 🧰 Styles & Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 🧠 AlpineJS --}}
    <script src="//unpkg.com/alpinejs" defer></script>
  

</head>
<body class="min-h-screen flex flex-col font-sans antialiased bg-gray-100">

    {{-- ✅ Header --}}
    @include('components.layout.header')
    


    {{-- ✅ Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ✅ Footer --}}
    @include('components.layout.footer')

    @stack('scripts')


</body>
</html>
