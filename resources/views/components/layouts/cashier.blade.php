<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cashier Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        * { -ms-overflow-style: none; scrollbar-width: none; }
        *::-webkit-scrollbar { display: none; }
    </style>
    @livewireStyles
</head>
<body class="text-gray-600 flex flex-row min-h-screen" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 40%, #1a3a6e 100%); background-attachment: fixed; min-height: 100vh;">
    <!-- Fixed dark background with premium blurry glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index: 0;">
        <div class="absolute top-[20%] left-[10%] w-[40%] h-[40%] bg-blue-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[40%] h-[40%] bg-emerald-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 w-[60%] h-[60%] bg-purple-500/5 blur-[150px] rounded-full"></div>
    </div>

    {{-- Sidebar --}}
    <aside class="hidden sm:flex flex-col w-16 hover:w-64 transition-[width] duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side"
           style="background: rgba(6,13,26,0.97); backdrop-filter: blur(12px);">

        {{-- Sidebar Branding --}}
        <div class="flex items-center gap-3 px-3 h-16 flex-shrink-0 overflow-hidden" style="border-bottom: 1px solid rgba(26,58,110,0.4);">
            <div class="text-white font-black p-2 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                 style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6), 0 0 0 1px rgba(99,179,237,0.15);">CS</div>
            <div class="hidden group-hover/side:block whitespace-nowrap">
                <div class="text-sm font-bold leading-none text-white tracking-tight">Cashier Panel</div>
                <div class="text-[10px] mt-0.5 font-semibold uppercase tracking-widest" style="color: rgba(138,180,216,0.55);">Financial System</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <div class="py-4 flex-1">
            <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Navigation</p>
            <nav class="space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('cashier.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200 relative"
                   style="{{ request()->routeIs('cashier.dashboard') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                   onmouseout="this.style.background='{{ request()->routeIs('cashier.dashboard') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                   {{ request()->routeIs('cashier.dashboard') ? 'data-active=1' : '' }}>
                    @if(request()->routeIs('cashier.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full" style="background: #63b3ed;"></span>
                    @endif
                    <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(99,179,237,0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                    </span>
                    <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Dashboard</span>
                </a>

                {{-- Divider --}}
                <div class="my-4 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Payments Section --}}

                <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Payments</p>


                <div class="mt-1 space-y-0.5">
                     <a href="{{ route('cashier.payments.shs') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                        style="{{ request()->routeIs('cashier.payments.shs') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                        onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                        onmouseout="this.style.background='{{ request()->routeIs('cashier.payments.shs') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                        {{ request()->routeIs('cashier.payments.shs') ? 'data-active=1' : '' }}>
                         @if(request()->routeIs('cashier.payments.shs'))
                             <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                         @endif
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                         <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">SHS Payments</span>
                     </a>

                    <a href="{{ route('cashier.payments.college') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('cashier.payments.college') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('cashier.payments.college') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('cashier.payments.college') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('cashier.payments.college'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">College Payments</span>
                    </a>

                    {{-- Divider --}}
                    <div class="my-2 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                    {{-- Edit Payment Assessment Section --}}
                    <div class="space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold uppercase hidden group-hover/side:block whitespace-nowrap" style="color: rgba(139,180,216,0.6);">Assessment</div>
                        <a href="{{ route('cashier.assessment.shs') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('cashier.assessment.shs') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('cashier.assessment.shs') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('cashier.assessment.shs') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('cashier.assessment.shs'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(99,179,237,0.12);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </span>
                            <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Edit SHS Assessment</span>
                        </a>
                        <a href="{{ route('cashier.assessment.college') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('cashier.assessment.college') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('cashier.assessment.college') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('cashier.assessment.college') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('cashier.assessment.college'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(99,179,237,0.12);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </span>
                            <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Edit College Assessment</span>
                        </a>
                    </div>
                </div>

            </nav>
        </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar --}}
        <nav class="sticky top-0 z-20 shadow-lg border-b h-16 flex items-center"
             style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <div class="w-full px-6 flex justify-end items-center gap-6">

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-white/40 italic">Signed in as</div>
                        <div class="text-sm font-bold text-white uppercase">{{ auth()->user()->name ?? 'Cashier' }}</div>
                    </div>
                    @if(request()->routeIs('cashier.dashboard'))
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-white text-sm font-semibold py-2 px-4 rounded-full transition-all tracking-wide"
                            style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);"
                            onmouseover="this.style.background='rgba(220,38,38,1)'"
                            onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                    </form>
                    @endif
                </div>
            </div>
        </nav>

        {{-- Content --}}
        <main class="flex-1 px-6 py-8 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-6 border-t" style="background: rgba(6,13,26,0.85); border-color: rgba(26,58,110,0.4);">
            <div class="text-center text-sm" style="color: #4a6fa5;">
                © 2026 Your Institution — Cashier System
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
