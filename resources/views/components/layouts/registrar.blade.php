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
        * { -ms-overflow-style: none; scrollbar-width: none; }
        *::-webkit-scrollbar { display: none; }
    </style>
    @livewireStyles
</head>
<body class="text-gray-600 flex flex-row min-h-screen" style="background: linear-gradient(135deg, #060d1a 0%, #0d1f3c 40%, #1a3a6e 100%); background-attachment: fixed; min-height: 100vh;">

    {{-- Sidebar --}}
    <aside class="hidden sm:flex flex-col w-64 flex-shrink-0 sticky top-0 h-screen overflow-y-auto z-30"
           style="background: rgba(6,13,26,0.97); backdrop-filter: blur(12px);">

        {{-- Sidebar Branding --}}
        <div class="flex items-center gap-3 px-5 h-16 flex-shrink-0" style="border-bottom: 1px solid rgba(26,58,110,0.4);">
            <div class="text-white font-black p-2 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                 style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6), 0 0 0 1px rgba(99,179,237,0.15);">RD</div>
            <div>
                <div class="text-sm font-bold leading-none text-white tracking-tight">Registrar Panel</div>
                <div class="text-[10px] mt-0.5 font-semibold uppercase tracking-widest" style="color: rgba(138,180,216,0.55);">Management System</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <div class="px-3 py-4 flex-1">
            <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2" style="color: rgba(138,180,216,0.35);">Navigation</p>
            <nav class="space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('registrar.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200 relative"
                   style="{{ request()->routeIs('registrar.dashboard') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                   onmouseout="this.style.background='{{ request()->routeIs('registrar.dashboard') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                   {{ request()->routeIs('registrar.dashboard') ? 'data-active=1' : '' }}>
                    @if(request()->routeIs('registrar.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full" style="background: #63b3ed;"></span>
                    @endif
                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg" style="background: rgba(99,179,237,0.12);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                    </span>
                    Dashboard
                </a>

                {{-- Divider --}}
                <div class="my-4 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Management Section --}}
                <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2" style="color: rgba(138,180,216,0.35);">Management</p>

                <div class="mt-1 space-y-0.5">

                    {{-- Students --}}
                    <a href="{{ route('registrar.students.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.students.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.students.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.students.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.students.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"></path></svg>
                        Students
                    </a>

                    {{-- College Applications --}}
                    <a href="{{ route('registrar.applications.college') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.applications.college') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.applications.college') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.applications.college') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.applications.college'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Applications for College
                    </a>

                    {{-- SHS Applications --}}
                    <a href="{{ route('registrar.applications.shs') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.applications.shs') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.applications.shs') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.applications.shs') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.applications.shs'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        Applications for SHS
                    </a>
                </div>

                {{-- Divider --}}
                <div class="my-4 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Configuration Section --}}
                <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2" style="color: rgba(138,180,216,0.35);">Configuration</p>

                <div class="mt-1 space-y-0.5">

                    {{-- Programs --}}
                    <a href="{{ route('registrar.programs.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.programs.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.programs.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.programs.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.programs.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        Programs
                    </a>

                    {{-- Sections --}}
                    <a href="{{ route('registrar.sections.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.sections.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.sections.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.sections.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.sections.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Sections
                    </a>

                    {{-- Academic Years --}}
                    <a href="{{ route('registrar.academic_years.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.academic_years.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.academic_years.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.academic_years.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.academic_years.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Academic Years
                    </a>

                    {{-- Semesters --}}
                    <a href="{{ route('registrar.semesters.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.semesters.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('registrar.semesters.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('registrar.semesters.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('registrar.semesters.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Semesters
                    </a>
                </div>

            </nav>
        </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar --}}
        <nav x-data="{ showDropdown: false }" class="sticky top-0 z-20 shadow-lg border-b h-16 flex items-center"
             style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <div class="w-full px-6 flex justify-end items-center gap-6">

                {{-- Notifications Dropdown --}}
                @livewire('registrar-notification-bell')

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-white/40 italic">Signed in as</div>
                        <div class="text-sm font-bold text-white uppercase">{{ auth()->user()->name ?? 'Registrar' }}</div>
                    </div>
                    @if(request()->routeIs('registrar.dashboard'))
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
                © 2026 Your Institution — Registrar Panel
            </div>
        </footer>
    </div>

    @livewireScripts
    <script>
        // Auto-refresh Livewire components every 5 seconds
        setInterval(function() {
            Livewire.dispatch('refresh-stats');
            Livewire.dispatch('refreshNotifications');
        }, 5000);
    </script>
</body>
</html>
