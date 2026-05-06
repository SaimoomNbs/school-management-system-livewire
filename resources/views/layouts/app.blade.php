<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin panel for {{ config('app.name', 'Academy') }}">

    <title>@stack('title', config('app.name', 'Academy') . ' — Admin')</title>
    @if(\App\Models\Setting::get('favicon_path'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . \App\Models\Setting::get('favicon_path')) }}">
    @endif

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap"
        rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @hasSection('style')
        @yield('style')
    @endif
</head>

<body x-data="{ sidebarOpen: false }" :class="sidebarOpen ? 'sidebar-is-open' : ''">
    <div class="admin-shell">
        <div class="sidebar-overlay" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" x-cloak></div>

        {{-- SIDEBAR --}}
        <livewire:layout.sidebar />

        {{-- MAIN AREA --}}
        <div class="main-area">

            {{-- TOPBAR --}}
            <livewire:layout.navigation />

            {{-- PAGE CONTENT --}}
            <main class="content-area" id="mainContent">
                <livewire:layout.flash />
                {{ $slot }}
            </main>

        </div>

    </div>

    @hasSection('script')
        @yield('script')
    @endif
</body>

</html>