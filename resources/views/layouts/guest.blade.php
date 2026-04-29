<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 bg-white antialiased">
        <!-- Premium light background with subtle glows -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none" style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); z-index: -1;">
            <div class="absolute top-[20%] left-[10%] w-[40%] h-[40%] bg-blue-500/5 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[20%] right-[10%] w-[40%] h-[40%] bg-blue-400/5 blur-[120px] rounded-full"></div>
        </div>
        {{ $slot }}
    </body>
</html>
