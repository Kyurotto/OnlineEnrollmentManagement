<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Teacher Panel' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        html,
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
        }

        aside {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        aside::-webkit-scrollbar {
            display: none;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @livewireStyles
</head>

<body x-data="{ sidebarOpen: localStorage.getItem('teacher_sidebar_open') === 'true', mobileOpen: false }"
    x-init="$watch('sidebarOpen', value => localStorage.setItem('teacher_sidebar_open', value))"
    class="text-slate-900 flex flex-row min-h-screen"
    style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); background-attachment: fixed; min-height: 100vh;">
    {{-- Skeleton Loading Screen --}}
    <x-skeleton-loader />

    {{-- Global Styles for Content Visibility --}}
    <style>
        main .text-white:not(button, button *, .bg-blue-600 *, .bg-emerald-600 *, .bg-rose-600 *, .bg-indigo-600 *, .bg-slate-900 *, .bg-amber-500 *, .bg-blue-500 *),
        main .text-white\/90,
        main .text-white\/80,
        main .text-white\/70 {
            color: #0f172a !important;
        }

        main .text-white\/20,
        main .text-white\/30,
        main .text-white\/40,
        main .text-white\/50,
        main .text-white\/60 {
            color: #2563eb !important;
            opacity: 0.5;
        }

        main svg {
            transition: all 0.3s ease;
        }

        main .group:hover svg {
            opacity: 1 !important;
            transform: translateY(-2px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        }

        main .border-white\/5,
        main .divide-white\/5 {
            border-color: rgba(0, 0, 0, 0.05) !important;
        }

        main .bg-white\/\[0\.01\],
        main .bg-white\/\[0\.02\],
        main .bg-white\/5 {
            background-color: rgba(37, 99, 235, 0.03) !important;
        }
    </style>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 overflow-hidden border-none opacity-0'"
        class="hidden sm:flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto z-30 group/side shadow-sm"
        style="background: #ffffff; border-right: 1px solid rgba(0,0,0,0.06);">

        {{-- Sidebar Toggle --}}
        <div class="h-20 flex items-center px-6 flex-shrink-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
            <button @click="sidebarOpen = !sidebarOpen"
                class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-xl transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
            class="flex flex-col flex-1">
            {{-- Sidebar Branding --}}
            <div class="flex items-center gap-3 px-6 h-20 flex-shrink-0 overflow-hidden border-b"
                style="border-color: rgba(0,0,0,0.06);">
                <div class="text-white font-black p-2.5 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest shadow-lg shadow-blue-600/20"
                    style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">TR</div>
                <div class="whitespace-nowrap transition-all duration-300">
                    <div class="text-sm font-black leading-none text-slate-800 uppercase tracking-tighter">Teacher Panel
                    </div>
                    <div class="text-[10px] mt-1 font-bold uppercase tracking-[0.2em] text-blue-600">Academic Control
                    </div>
                </div>
            </div>

            {{-- Nav Items --}}
            <div class="py-6 flex-1 flex flex-col">

                <p class="text-[11px] font-black uppercase tracking-[0.3em] px-6 mb-6 text-slate-300">System Core</p>
                <nav class="space-y-1.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('teacher.dashboard') }}"
                        class="flex items-center gap-3 mx-4 px-4 py-3.5 rounded-2xl text-[14px] font-black uppercase tracking-widest transition-all duration-300 relative group"
                        style="{{ request()->routeIs('teacher.dashboard') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('teacher.dashboard') ? 'text-blue-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                            </path>
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Dashboard</span>
                    </a>

                    <div class="my-8 mx-6 border-t border-slate-100"></div>
                    <p x-show="sidebarOpen" x-cloak
                        class="text-[10px] font-black uppercase tracking-[0.4em] px-6 mb-4 text-slate-300">Academic
                        Records</p>

                    {{-- Students --}}
                    <a href="{{ route('teacher.students.index') }}"
                        class="flex items-center gap-3 mx-4 px-4 py-3.5 rounded-2xl text-[14px] font-black uppercase tracking-widest transition-all duration-300 relative group"
                        style="{{ request()->routeIs('teacher.students.*') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('teacher.students.*') ? 'text-blue-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Students</span>
                    </a>

                    {{-- Sections --}}
                    <a href="{{ route('teacher.sections.index') }}"
                        class="flex items-center gap-3 mx-4 px-4 py-3.5 rounded-2xl text-[14px] font-black uppercase tracking-widest transition-all duration-300 relative group"
                        style="{{ request()->routeIs('teacher.sections.*') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('teacher.sections.*') ? 'text-blue-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Sections</span>
                    </a>



                </nav>
            </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar --}}
        <nav class="sticky top-0 z-20 shadow-sm border-b h-20 flex items-center bg-white/80 backdrop-blur-xl"
            style="border-color: rgba(0,0,0,0.06);">
            <div class="w-full px-8 flex items-center justify-between">
                {{-- Desktop Hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" x-show="!sidebarOpen"
                    class="hidden sm:flex items-center justify-center w-10 h-10 rounded-xl transition-colors text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="sm:hidden flex items-center justify-center w-10 h-10 rounded-xl transition-colors text-slate-600 hover:bg-slate-100">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="flex items-center gap-6 ml-auto">
                    {{-- Manage Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-200/60">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Active
                                    Teacher</div>
                                <div class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                    {{ auth()->user()->name ?? 'System Teacher' }}
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                            <div class="px-5 py-4 bg-slate-50/50 border-b border-slate-100">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Manage
                                    Account</p>
                            </div>
                            <div class="p-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all group">
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all group">
                                        <svg class="w-4 h-4 text-red-400 group-hover:text-red-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu Dropdown --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="sm:hidden absolute top-20 left-0 right-0 border-b shadow-2xl z-30 bg-white max-h-[85vh] overflow-y-auto"
                style="border-color: rgba(0,0,0,0.06);" @click.away="mobileOpen = false" x-cloak>
                <div class="p-4 space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">System Core
                    </p>
                    <a href="{{ route('teacher.dashboard') }}"
                        class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                            </path>
                        </svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dashboard</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Academic
                        Records</p>

                    <a href="{{ route('teacher.students.index') }}"
                        class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('teacher.students.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Students</span>
                    </a>

                    <a href="{{ route('teacher.sections.index') }}"
                        class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('teacher.sections.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Sections</span>
                    </a>


                </div>
            </div>
        </nav>

        {{-- Content --}}
        <main class="flex-1 px-8 py-10 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-12 border-t mt-auto"
            style="background: rgba(255,255,255,0.5); border-color: rgba(0,0,0,0.05);">
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                    &copy; 2026 Your Institution &mdash; <span class="text-blue-600">Teacher Panel</span>
                </p>
                <p class="text-[9px] font-bold text-slate-200 uppercase tracking-widest mt-2">
                    Certified Light Interface Environment — v4.5L
                </p>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>