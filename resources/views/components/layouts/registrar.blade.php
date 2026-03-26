<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Registrar Panel' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="text-gray-600 flex flex-col min-h-screen" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 40%, #1a3a6e 100%); background-attachment: fixed; min-height: 100vh;">

    {{-- Unified Navbar --}}

    <div class="mb-8">
        <nav x-data="{ showDropdown: false, showMobileMenu: false }" class="sticky top-0 z-20 shadow-lg border-b"
            style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="text-white font-bold p-2 rounded-lg text-sm uppercase"
                                style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6);">
                                RD</div>
                            <div>
                                <h1 class="text-lg font-bold leading-none text-white">Registrar Panel</h1>
                                <span class="text-xs" style="color: #8ab4d8;">Dashboard</span>
                            </div>
                        </div>
                        <div class="flex space-x-6 text-sm font-medium h-16" style="color: #8ab4d8;">
                            <a href="{{ route('registrar.dashboard') }}"
                                class="flex items-center transition h-full"
                                style="{{ request()->routeIs('registrar.dashboard') ? 'color: #ffffff; border-bottom: 2px solid #a8d5f5;' : 'color: #8ab4d8;' }}"
                                onmouseover="this.style.color='#ffffff'"
                                onmouseout="this.style.color='{{ request()->routeIs('registrar.dashboard') ? '#ffffff' : '#8ab4d8' }}'">Dashboard</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <!-- Notifications Dropdown -->
                        <div class="relative mr-2">
                            <button @click="showDropdown = !showDropdown" @click.away="showDropdown = false" class="relative p-2 transition focus:outline-none"
                                style="color: #8ab4d8;">
                                <svg class="w-6 h-6 transition" :style="showDropdown ? 'color: #ffffff' : 'color: #8ab4d8'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @if(isset($newEnrolleesCount) && $newEnrolleesCount > 0)
                                    <span class="absolute top-1 -right-1 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
                                    </span>
                                @endif
                            </button>

                            <template x-if="showDropdown">
                                <div class="absolute right-0 top-12 w-80 shadow-2xl rounded-xl z-50 overflow-hidden transform transition-all"
                                    style="background: rgba(6,13,26,0.97); backdrop-filter: blur(16px); border: 1px solid rgba(26,58,110,0.5);">
                                    <div class="px-4 py-3 border-b flex justify-between items-center"
                                        style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                        <h3 class="text-sm font-bold uppercase tracking-wide text-white">Notifications</h3>
                                        @if(isset($newEnrolleesCount) && $newEnrolleesCount > 0)
                                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[10px] font-bold text-rose-400 hover:text-rose-300 uppercase tracking-widest transition-colors">Clear All</button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-2" style="background: rgba(6,13,26,0.6);">
                                        @if(isset($dbNotifications) && $dbNotifications->count() > 0)
                                            @foreach($dbNotifications as $notification)
                                                <div @click="fetch('{{ route('notifications.markAsRead', $notification->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => { window.location.href = '{{ route('registrar.applications.index') }}' })"
                                                     class="block p-3 rounded-lg border transition group cursor-pointer hover:bg-white/5 active:scale-[0.98]"
                                                     style="background: rgba(16,185,129,0.05); border-color: rgba(16,185,129,0.2);">
                                                    <p class="text-xs text-white/90">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                                    <p class="text-[9px] mt-2 text-right text-white/20">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-6 text-sm text-white/20">No new notifications</div>
                                        @endif
                                    </div>
                                    <div class="p-2 border-t text-center" style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                        <a href="{{ route('registrar.applications.index') }}"
                                            class="text-xs font-bold" style="color: #8ab4d8;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8ab4d8'">View All Applications →</a>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs text-white/40 italic">Signed in as</div>
                                <div class="text-sm font-bold text-white">Registrar</div>
                            </div>
                            @if(request()->routeIs('registrar.dashboard'))
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="text-white text-sm font-semibold py-2 px-4 rounded-full transition-all"
                                    style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);"
                                    onmouseover="this.style.background='rgba(220,38,38,1)'"
                                    onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                            </form>
                            @endif
                            <button @click="showMobileMenu = !showMobileMenu" class="sm:hidden p-2 rounded-lg transition" style="color: #8ab4d8; background: rgba(255,255,255,0.05);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!showMobileMenu" d="M4 6h16M4 12h16m-7 6h7"></path><path x-show="showMobileMenu" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="showMobileMenu" x-cloak class="sm:hidden overflow-hidden transition-all duration-300" style="background: rgba(6,13,26,0.98); border-top: 1px solid rgba(26,58,110,0.4);">
                <div class="px-4 py-6 space-y-4">
                    <a href="{{ route('registrar.dashboard') }}"
                        class="block py-3 px-4 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('registrar.dashboard') ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/5' }}">Dashboard</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Page Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full animate-in fade-in duration-700">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="py-6 mt-auto border-t" style="background: rgba(6,13,26,0.85); border-color: rgba(26,58,110,0.4);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm" style="color: #4a6fa5;">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>

    @livewireScripts
</body>
</html>
