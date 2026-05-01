<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{ $title ?? 'Registrar Panel' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Hide global scrollbars but allow them where needed */
        html, body { -ms-overflow-style: none; scrollbar-width: none; }
        html::-webkit-scrollbar, body::-webkit-scrollbar { display: none; }

        /* Premium Sidebar Scrollbar - Hidden but scrollable */
        aside { -ms-overflow-style: none; scrollbar-width: none; }
        aside::-webkit-scrollbar { display: none; }

        /* Force black text for elements that were white in the views */
        main .text-white:not(button *, .bg-blue-600 *, .bg-purple-600 *, .bg-rose-600 *, .bg-emerald-600 *, .bg-indigo-600 *, .bg-slate-900 *) {
            color: #0f172a !important;
        }
        main .text-white\/20, main .text-white\/30, main .text-white\/40, main .text-white\/50, main .text-white\/60, main .text-white\/70, main .text-white\/80, main .text-white\/90 {
            color: #64748b !important; /* Slate 400ish */
        }
        main .text-blue-300, main .text-blue-400\/60, main .text-indigo-300 {
            color: #2563eb !important;
        }

        /* Adjust glass cards for light theme */
        .glass-card, main div[style*="background: rgba(255,255,255,0.05)"], main div[style*="background: rgba(255,255,255,0.06)"] {
            background: #ffffff !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(37, 99, 235, 0.1) !important;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.05) !important;
        }

        /* Form elements adjustments */
        main input, main textarea, main select {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }
        main input::placeholder {
            color: #94a3b8 !important;
        }

        /* Fix table headers and dividers */
        main .border-white\/5, main .border-white\/10, main .divide-white\/5, main .divide-white\/10 {
            border-color: rgba(37, 99, 235, 0.08) !important;
        }
        main .bg-white\/\[0\.01\], main .bg-white\/\[0\.02\], main .bg-white\/\[0\.03\], main .bg-white\/5, main .bg-white\/10 {
            background-color: rgba(37, 99, 235, 0.02) !important;
        }
        main .bg-slate-50\/50 {
            background-color: #f8fafc !important;
        }

        /* Notification Dropdown Light Theme Overrides */
        nav .absolute.right-0.top-12.w-80 {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: localStorage.getItem('registrar_sidebar_open') === 'true', mobileOpen: false }"
      x-init="$watch('sidebarOpen', value => localStorage.setItem('registrar_sidebar_open', value))"
      class="text-slate-900 flex flex-row min-h-screen" style="background: #ffffff; min-height: 100vh;">
    {{-- Global Styles for Content Visibility --}}
    <style>
        /* Force black text for elements that were white, but spare buttons and colored badges */
        main .text-white:not(button, button *, .bg-blue-600 *, .bg-emerald-600 *, .bg-rose-600 *, .bg-indigo-600 *, .bg-slate-900 *, .bg-amber-500 *, .bg-blue-500 *),
        main .text-white\/90, main .text-white\/80, main .text-white\/70 {
            color: #0f172a !important;
        }
        main .text-white\/20, main .text-white\/30, main .text-white\/40, main .text-white\/50, main .text-white\/60 {
            color: #64748b !important;
        }
        main .text-blue-300, main .text-blue-400\/60, main .text-indigo-300 {
            color: #2563eb !important;
        }
        nav .absolute.right-0.top-12.w-80 div[style*="background: rgba(13,31,60"] {
            background: #f8fafc !important;
            border-color: rgba(0,0,0,0.05) !important;
        }
        nav .absolute.right-0.top-12.w-80 div[style*="background: rgba(6,13,26"] {
            background: #ffffff !important;
        }
        nav .absolute.right-0.top-12.w-80 h3 {
            color: #0f172a !important;
        }
        nav .absolute.right-0.top-12.w-80 .bg-emerald-500\/10 {
            background-color: #f0fdf4 !important;
            border-color: #bbf7d0 !important;
        }
        nav .absolute.right-0.top-12.w-80 .text-white\/90 {
            color: #166534 !important;
        }
        nav .absolute.right-0.top-12.w-80 .text-white\/20 {
            color: #64748b !important;
        }
        nav .absolute.right-0.top-12.w-80 a[style*="color: #3b82f6"] {
            color: #2563eb !important;
        }
    </style>

    {{-- DESKTOP Sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden border-none opacity-0'" class="hidden md:flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side shadow-sm"
           style="background: #ffffff; border-right: 1px solid rgba(0,0,0,0.06);">

        {{-- Sidebar Toggle (At the top of RD) --}}
        <div class="h-20 flex items-center px-6 flex-shrink-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
            <button @click="sidebarOpen = !sidebarOpen" class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-xl transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col flex-1">
            {{-- Branding --}}
            <div class="flex items-center gap-3 px-4 h-20 flex-shrink-0 overflow-hidden" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <div class="text-white font-black p-2.5 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                     style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 4px 12px rgba(37,99,235,0.2);">RD</div>
                <div class="whitespace-nowrap">
                    <div class="text-sm font-black leading-none text-slate-800 tracking-tight uppercase">Registrar Portal</div>
                    <div class="text-[10px] mt-1 font-bold uppercase tracking-widest text-blue-600">Student Services</div>
                </div>
            </div>

            {{-- Nav --}}
            <div class="py-6 flex-1 flex flex-col">


                <p class="text-[11px] font-black uppercase tracking-[0.2em] px-6 mb-3 whitespace-nowrap text-slate-300">Navigation</p>
                <nav class="space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('registrar.dashboard') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative group"
                   style="{{ request()->routeIs('registrar.dashboard') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                    <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                          style="{{ request()->routeIs('registrar.dashboard') ? 'background: #ffffff; color: #2563eb;' : 'background: #f8fafc;' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                    </span>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Dashboard</span>
                </a>

                <div class="my-6 mx-4 border-t border-slate-100"></div>
                <p x-show="sidebarOpen" x-cloak class="text-[11px] font-black uppercase tracking-[0.2em] px-4 mb-3 whitespace-nowrap text-slate-300">Management</p>

                <div class="mt-1 space-y-1">

                    {{-- Manage College Students --}}
                    <a href="{{ route('registrar.students.index', ['level' => 'college']) }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.students.*') && request('level') === 'college' ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.students.*') && request('level') === 'college' ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">College Enrolled</span>
                    </a>

                    {{-- Manage SHS Students --}}
                    <a href="{{ route('registrar.students.index', ['level' => 'shs']) }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.students.*') && request('level') === 'shs' ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.students.*') && request('level') === 'shs' ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">SHS Enrolled</span>
                    </a>

                    {{-- Dropped Students --}}
                    <a href="{{ route('registrar.dropped.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.dropped.*') ? 'background: #fff1f2; color: #e11d48;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.dropped.*') ? 'text-rose-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dropped Students</span>
                    </a>

                    {{-- College Applications --}}
                    <a href="{{ route('registrar.applications.college') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.applications.college') ? 'background: #f5f3ff; color: #7c3aed;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.applications.college') ? 'text-purple-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">College Applications</span>
                    </a>

                    {{-- SHS Applications --}}
                    <a href="{{ route('registrar.applications.shs') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.applications.shs') ? 'background: #f5f3ff; color: #7c3aed;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.applications.shs') ? 'text-purple-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">SHS Applications</span>
                    </a>

                    {{-- Historical Academic Records --}}
                    <a href="{{ route('registrar.archives.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.archives.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.archives.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Archives</span>
                    </a>
                </div>

                <div class="my-6 mx-4 border-t border-slate-100"></div>
                <p x-show="sidebarOpen" x-cloak class="text-[11px] font-black uppercase tracking-[0.2em] px-4 mb-3 whitespace-nowrap text-slate-300">Configuration</p>

                <div class="space-y-1">
                    {{-- Programs --}}
                    <a href="{{ route('registrar.programs.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.programs.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.programs.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Programs</span>
                    </a>

                    {{-- Sections --}}
                    <a href="{{ route('registrar.sections.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.sections.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.sections.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Sections</span>
                    </a>

                    {{-- Academic Years --}}
                    <a href="{{ route('registrar.academic_years.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.academic_years.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.academic_years.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Academic Years</span>
                    </a>

                    {{-- Semesters --}}
                    <a href="{{ route('registrar.semesters.index') }}"
                       class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                       style="{{ request()->routeIs('registrar.semesters.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.semesters.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Semesters</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar --}}
        <nav x-data="{
                navSection: '{{ request()->routeIs('registrar.dashboard') ? 'navigation' : (request()->routeIs('registrar.students.*', 'registrar.applications.*') ? 'management' : (request()->routeIs('registrar.programs.*', 'registrar.sections.*', 'registrar.academic_years.*', 'registrar.semesters.*') ? 'configuration' : 'navigation')) }}'
             }"
             class="sticky top-0 z-20 border-b h-20 flex items-center bg-white"
             style="border-color: rgba(0,0,0,0.06);">

            {{-- Navbar row --}}
            <div class="w-full px-8 flex items-center justify-between">

                {{-- Desktop Hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" x-show="!sidebarOpen" class="hidden md:flex items-center justify-center w-10 h-10 rounded-xl transition-colors flex-shrink-0 text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                {{-- Mobile hamburger (hidden on md+) --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl transition-colors flex-shrink-0 text-slate-600 hover:bg-slate-100"
                        aria-label="Toggle menu">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>



                {{-- Right side: notifications + user + logout --}}
                <div class="flex items-center gap-6 ml-auto">
                    @livewire('registrar-notification-bell')

                    {{-- Manage Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-200/60">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Active Registrar</div>
                                <div class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ auth()->user()->name ?? 'System User' }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                            <div class="px-5 py-4 bg-slate-50/50 border-b border-slate-100">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Manage Account</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all group">
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all group">
                                        <svg class="w-4 h-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile dropdown menu --}}
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden absolute top-20 left-0 w-full border-b overflow-y-auto max-h-[85vh] shadow-2xl bg-white z-40"
                 style="border-color: rgba(0,0,0,0.06);"
                 @click.away="mobileOpen = false"
                 x-cloak>

                <div class="p-4 space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Navigation</p>
                    <a href="{{ route('registrar.dashboard') }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('registrar.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dashboard</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Management</p>
                    
                    <a href="{{ route('registrar.students.index', ['level' => 'college']) }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request('level') === 'college' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">College Enrolled</span>
                    </a>

                    <a href="{{ route('registrar.students.index', ['level' => 'shs']) }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request('level') === 'shs' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">SHS Enrolled</span>
                    </a>

                    <a href="{{ route('registrar.dropped.index') }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('registrar.dropped.*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dropped Students</span>
                    </a>

                    <a href="{{ route('registrar.applications.college') }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('registrar.applications.college') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">College Applications</span>
                    </a>

                    <a href="{{ route('registrar.applications.shs') }}" 
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('registrar.applications.shs') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">SHS Applications</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Configuration</p>

                    <a href="{{ route('registrar.programs.index') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl text-slate-600 hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Programs</span>
                    </a>
                    
                    <a href="{{ route('registrar.academic_years.index') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-2xl text-slate-600 hover:bg-slate-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Academic Years</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- Content --}}
        <main class="flex-1 px-8 py-10 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-12 border-t mt-auto" style="background: rgba(255,255,255,0.5); border-color: rgba(0,0,0,0.05);">
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                    &copy; 2026 Your Institution &mdash; <span class="text-blue-600">Registrar Portal</span>
                </p>
                <p class="text-[9px] font-bold text-slate-200 uppercase tracking-widest mt-2">
                    Certified Light Interface Environment — v4.5L
                </p>
            </div>
        </footer>
    </div>

    @livewireScripts
    <script>
        setInterval(function() {
            Livewire.dispatch('refresh-stats');
            Livewire.dispatch('refreshNotifications');
        }, 5000);
    </script>
</body>
</html>
