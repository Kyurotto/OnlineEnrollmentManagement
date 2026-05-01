<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Fixed light background with premium blurry glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none bg-slate-50" style="z-index: -1;">
            <div class="absolute top-[10%] left-[5%] w-[40%] h-[40%] bg-blue-200/40 blur-[120px] rounded-full animate-pulse"></div>
            <div class="absolute bottom-[10%] right-[5%] w-[40%] h-[40%] bg-blue-100/40 blur-[120px] rounded-full animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        <div class="min-h-screen" style="position: relative; z-index: 1;">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
