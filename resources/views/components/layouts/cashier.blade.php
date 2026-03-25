<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cashier Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 50%, #1a3a6e 100%);
            background-attachment: fixed;
        }
    </style>
    @livewireStyles
</head>
<body class="text-white flex flex-col min-h-screen">

    {{-- Unified Navbar --}}
    <div class="mb-8">
        <nav x-data="{ showMobileMenu: false }" class="sticky top-0 z-20 shadow-lg border-b"
            style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="text-white font-bold p-2 rounded-lg text-sm uppercase"
                                style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6);">
                                CS</div>
                            <div>
                                <h1 class="text-lg font-bold leading-none text-white">Cashier Panel</h1>
                                <span class="text-xs" style="color: #8ab4d8;">Financial System</span>
                            </div>
                        </div>
                        <div class="flex space-x-6 text-sm font-medium h-16" style="color: #8ab4d8;">
                            <a href="{{ route('cashier.dashboard') }}"
                                class="flex items-center transition h-full"
                                style="{{ request()->routeIs('cashier.dashboard') ? 'color: #ffffff; border-bottom: 2px solid #a8d5f5;' : 'color: #8ab4d8;' }}"
                                onmouseover="this.style.color='#ffffff'"
                                onmouseout="this.style.color='{{ request()->routeIs('cashier.dashboard') ? '#ffffff' : '#8ab4d8' }}'">Dashboard</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs text-white/40 italic">Signed in as</div>
                                <div class="text-sm font-bold text-white uppercase">{{ auth()->user()->name }}</div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="text-white text-sm font-semibold py-2 px-6 rounded-full transition-all shadow-lg active:scale-95"
                                    style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);"
                                    onmouseover="this.style.background='rgba(220,38,38,1)'"
                                    onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                            </form>
                            <button @click="showMobileMenu = !showMobileMenu" class="sm:hidden p-2 rounded-lg transition" 
                                style="color: #8ab4d8; background: rgba(255,255,255,0.05);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                    <path x-show="showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="showMobileMenu" x-cloak class="sm:hidden overflow-hidden transition-all duration-300" 
                style="background: rgba(6,13,26,0.98); border-top: 1px solid rgba(26,58,110,0.4);">
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('cashier.dashboard') }}"
                        class="block py-3 px-4 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('cashier.dashboard') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5' }}">Dashboard</a>
                </div>
            </div>
        </nav>
    </div>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full space-y-6 animate-in fade-in duration-500">
        {{ $slot }}
    </main>

    <footer class="border-t py-6 mt-auto shadow-inner" style="background: rgba(6,13,26,0.6); border-color: rgba(26,58,110,0.3);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div class="text-sm font-medium" style="color: #4a6fa5;">
                © 2026 Your Institution — Cashier System
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
