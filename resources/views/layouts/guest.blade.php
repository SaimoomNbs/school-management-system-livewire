<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Academy') }} &mdash; Login</title>
        @if(\App\Models\Setting::get('favicon_path'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . \App\Models\Setting::get('favicon_path')) }}">
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
        @hasSection('style')
            @yield('style')
        @endif
    </head>
    <body>
        {{ $slot }}

        @hasSection('script')
            @yield('script')
        @endif
    </body>
</html>
