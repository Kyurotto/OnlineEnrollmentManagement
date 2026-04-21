<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#A1A1AA] bg-[#121212] antialiased">
        <!-- Fixed dark background with premium blurry glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 50%, #1a3a6e 100%); z-index: -1;">
            <div class="absolute top-[20%] left-[10%] w-[40%] h-[40%] bg-blue-500/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[20%] right-[10%] w-[40%] h-[40%] bg-emerald-500/10 blur-[120px] rounded-full"></div>
        </div>
        {{ $slot }}
    </body>
</html>