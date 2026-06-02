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
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Hide global scrollbars but allow them where needed */
        html,
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
        }

        /* Premium Sidebar Scrollbar - Hidden but scrollable */
        aside {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        aside::-webkit-scrollbar {
            display: none;
        }

        /* Force black text for elements that were white in the views */
        main .text-white:not(button *, .bg-blue-600 *, .bg-purple-600 *, .bg-rose-600 *, .bg-emerald-600 *, .bg-indigo-600 *, .bg-slate-900 *) {
            color: #0f172a !important;
        }

        main .text-white\/20,
        main .text-white\/30,
        main .text-white\/40,
        main .text-white\/50,
        main .text-white\/60,
        main .text-white\/70,
        main .text-white\/80,
        main .text-white\/90 {
            color: #64748b !important;
            /* Slate 400ish */
        }

        main .text-blue-300,
        main .text-blue-400\/60,
        main .text-indigo-300 {
            color: #2563eb !important;
        }

        /* Adjust glass cards for light theme */
        .glass-card,
        main div[style*="background: rgba(255,255,255,0.05)"],
        main div[style*="background: rgba(255,255,255,0.06)"] {
            background: #ffffff !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(37, 99, 235, 0.1) !important;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.05) !important;
        }

        /* Form elements adjustments */
        main input,
        main textarea,
        main select {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }

        main input::placeholder {
            color: #94a3b8 !important;
        }

        /* Fix table headers and dividers */
        main .border-white\/5,
        main .border-white\/10,
        main .divide-white\/5,
        main .divide-white\/10 {
            border-color: rgba(37, 99, 235, 0.08) !important;
        }

        main .bg-white\/\[0\.01\],
        main .bg-white\/\[0\.02\],
        main .bg-white\/\[0\.03\],
        main .bg-white\/5,
        main .bg-white\/10 {
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

<body x-data="{ sidebarOpen: localStorage.getItem('registrar_sidebar_open') === 'true', mobileOpen: false }" x-init="$watch('sidebarOpen', value => localStorage.setItem('registrar_sidebar_open', value))" class="text-slate-900 flex flex-row min-h-screen"
    style="background: #ffffff; min-height: 100vh;">

    {{-- Skeleton Loading Screen --}}
    <x-skeleton-loader />

    {{-- Global Styles for Content Visibility --}}
    <style>
        /* Force black text for elements that were white, but spare buttons and colored badges */
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
            /* Force Brand Blue for icons that were white */
            opacity: 0.5;
        }

        main .text-blue-300,
        main .text-blue-400\/60,
        main .text-indigo-300 {
            color: #1d4ed8 !important;
        }

        /* Specific enhancement for Folder Icons and Navigation SVGs */
        main svg {
            transition: all 0.3s ease;
        }

        main .group:hover svg {
            opacity: 1 !important;
            transform: translateY(-2px);
        }

        nav .absolute.right-0.top-12.w-80 div[style*="background: rgba(13,31,60"] {
            background: #f8fafc !important;
            border-color: rgba(0, 0, 0, 0.05) !important;
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
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden border-none opacity-0'"
        class="hidden md:flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side shadow-sm"
        style="background: #ffffff; border-right: 1px solid rgba(0,0,0,0.06);">

        {{-- Sidebar Toggle (At the top of RD) --}}
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
            {{-- Branding --}}
            <div class="flex items-center gap-3 px-4 h-20 flex-shrink-0 overflow-hidden"
                style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <div class="text-white font-black p-2.5 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                    style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
                    RD</div>
                <div class="whitespace-nowrap">
                    <div class="text-sm font-black leading-none text-slate-800 tracking-tight">Registrar Panel</div>
                    <div class="text-[10px] mt-1 font-bold uppercase tracking-widest text-blue-600">Student Services
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <div class="py-6 flex-1 flex flex-col">


                <p class="text-[11px] font-black uppercase tracking-[0.2em] px-6 mb-3 whitespace-nowrap text-slate-300">
                    Navigation</p>
                <nav class="space-y-0.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('registrar.dashboard') }}"
                        class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative group"
                        style="{{ request()->routeIs('registrar.dashboard') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                        <span
                            class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                            style="{{ request()->routeIs('registrar.dashboard') ? 'background: #ffffff; color: #2563eb;' : 'background: #f8fafc;' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                                </path>
                            </svg>
                        </span>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Dashboard</span>
                    </a>

                    <div class="my-6 mx-4 border-t border-slate-100"></div>
                    <p x-show="sidebarOpen" x-cloak
                        class="text-[11px] font-black uppercase tracking-[0.2em] px-4 mb-3 whitespace-nowrap text-slate-300">
                        Management</p>

                    <div class="mt-1 space-y-1">

                        {{-- Enrollment Dropdown --}}
                        @php
                            $isEnrollmentActive =
                                request()->routeIs('registrar.students.*') || request()->routeIs('registrar.dropped.*');
                        @endphp
                        <div x-data="{ open: {{ $isEnrollmentActive ? 'true' : 'false' }} }" class="mx-3">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isEnrollmentActive ? 'true' : 'false' }}) ? 'bg-blue-50 text-blue-600' :
                                'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                :style="(open || {{ $isEnrollmentActive ? 'true' : 'false' }}) ?
                                'color: #2563eb; background: #eff6ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isEnrollmentActive ? 'true' : 'false' }}) ? 'text-blue-600' :
                                        'text-slate-400 group-hover:text-blue-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Enrollments</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[
                                        (open || {{ $isEnrollmentActive ? 'true' : 'false' }}) ? 'text-blue-600' :
                                        'text-slate-400 group-hover:text-blue-600',
                                        open ? 'rotate-180' : ''
                                    ]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                class="pl-8 pr-3 py-1.5 space-y-1 bg-slate-50 rounded-xl mt-1 border border-slate-100">
                                <a href="{{ route('registrar.students.index', ['level' => 'college']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.students.*') && request('level') === 'college' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">College
                                    Enrolled</a>
                                <a href="{{ route('registrar.students.index', ['level' => 'shs']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.students.*') && request('level') === 'shs' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">SHS
                                    Enrolled</a>
                                <a href="{{ route('registrar.dropped.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.dropped.*') ? 'text-rose-600' : 'text-slate-700 hover:text-slate-900' }}">Dropped
                                    Students</a>
                            </div>
                        </div>

                        {{-- Applications Dropdown --}}
                        @php
                            $isApplicationsActive = request()->routeIs('registrar.applications.*');
                        @endphp
                        <div x-data="{ open: {{ $isApplicationsActive ? 'true' : 'false' }} }" class="mx-3">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isApplicationsActive ? 'true' : 'false' }}) ?
                                'bg-purple-50 text-purple-600' :
                                'text-slate-600 hover:bg-purple-50/50 hover:text-purple-600'"
                                :style="(open || {{ $isApplicationsActive ? 'true' : 'false' }}) ?
                                'color: #9333ea; background: #faf5ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isApplicationsActive ? 'true' : 'false' }}) ? 'text-purple-600' :
                                        'text-slate-400 group-hover:text-purple-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Applications</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[
                                        (open || {{ $isApplicationsActive ? 'true' : 'false' }}) ? 'text-purple-600' :
                                        'text-slate-400 group-hover:text-purple-600',
                                        open ? 'rotate-180' : ''
                                    ]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                class="pl-8 pr-3 py-1.5 space-y-1 bg-slate-50 rounded-xl mt-1 border border-slate-100">
                                <a href="{{ route('registrar.applications.college') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.applications.college') ? 'text-purple-600' : 'text-slate-700 hover:text-slate-900' }}">College
                                    Applications</a>
                                <a href="{{ route('registrar.applications.shs') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.applications.shs') ? 'text-purple-600' : 'text-slate-700 hover:text-slate-900' }}">SHS
                                    Applications</a>
                            </div>
                        </div>

                        {{-- Historical Academic Records --}}
                        <a href="{{ route('registrar.archives.index') }}"
                            class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative"
                            style="{{ request()->routeIs('registrar.archives.*') ? 'background: #eff6ff; color: #2563eb;' : 'color: #64748b;' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('registrar.archives.*') ? 'text-blue-600' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                            <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Archives</span>
                        </a>
                    </div>

                    <div class="my-6 mx-4 border-t border-slate-100"></div>
                    <p x-show="sidebarOpen" x-cloak
                        class="text-[11px] font-black uppercase tracking-[0.2em] px-4 mb-3 whitespace-nowrap text-slate-300">
                        Configuration</p>

                    <div class="space-y-1">
                        {{-- Configuration Dropdown --}}
                        @php
                            $isConfigActive =
                                request()->routeIs('registrar.programs.*') ||
                                request()->routeIs('registrar.sections.*') ||
                                request()->routeIs('registrar.academic_years.*') ||
                                request()->routeIs('registrar.semesters.*');
                        @endphp
                        <div x-data="{ open: {{ $isConfigActive ? 'true' : 'false' }} }" class="mx-3">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isConfigActive ? 'true' : 'false' }}) ? 'bg-blue-50 text-blue-600' :
                                'text-slate-600 hover:bg-blue-50/50 hover:text-blue-600'"
                                :style="(open || {{ $isConfigActive ? 'true' : 'false' }}) ?
                                'color: #2563eb; background: #eff6ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isConfigActive ? 'true' : 'false' }}) ? 'text-blue-600' :
                                        'text-slate-400 group-hover:text-blue-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Portal Config</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[
                                        (open || {{ $isConfigActive ? 'true' : 'false' }}) ? 'text-blue-600' :
                                        'text-slate-400 group-hover:text-blue-600',
                                        open ? 'rotate-180' : ''
                                    ]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                class="pl-8 pr-3 py-1.5 space-y-1 bg-slate-50 rounded-xl mt-1 border border-slate-100">
                                <a href="{{ route('registrar.programs.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.programs.*') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">Programs</a>
                                <a href="{{ route('registrar.sections.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.sections.*') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">Sections</a>
                                <a href="{{ route('registrar.academic_years.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.academic_years.*') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">Academic
                                    Years</a>
                                <a href="{{ route('registrar.semesters.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('registrar.semesters.*') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">Semesters</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar --}}
        <nav x-data="{
            navSection: '{{ request()->routeIs('registrar.dashboard') ? 'navigation' : (request()->routeIs('registrar.students.*', 'registrar.applications.*') ? 'management' : (request()->routeIs('registrar.programs.*', 'registrar.sections.*', 'registrar.academic_years.*', 'registrar.semesters.*') ? 'configuration' : 'navigation')) }}'
        }" class="sticky top-0 z-20 border-b h-20 flex items-center bg-white"
            style="border-color: rgba(0,0,0,0.06);">

            {{-- Navbar row --}}
            <div class="w-full px-8 flex items-center justify-between">

                {{-- Desktop Hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" x-show="!sidebarOpen"
                    class="hidden md:flex items-center justify-center w-10 h-10 rounded-xl transition-colors flex-shrink-0 text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                {{-- Mobile hamburger (hidden on md+) --}}
                <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl transition-colors flex-shrink-0 text-slate-600 hover:bg-slate-100"
                    aria-label="Toggle menu">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>



                {{-- Right side: notifications + user + logout --}}
                <div class="flex items-center gap-6 ml-auto">
                    @livewire('registrar-notification-bell')

                    {{-- Manage Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-200/60">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Active
                                    Registrar</div>
                                <div class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                    {{ auth()->user()->name ?? 'System User' }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
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

            {{-- Mobile dropdown menu --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="md:hidden absolute top-20 left-0 w-full border-b overflow-y-auto max-h-[85vh] shadow-2xl bg-white z-40"
                style="border-color: rgba(0,0,0,0.06);" @click.away="mobileOpen = false" x-cloak>

                <div class="p-4 space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Navigation
                    </p>
                    <a href="{{ route('registrar.dashboard') }}"
                        class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('registrar.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                            </path>
                        </svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dashboard</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Management
                    </p>

                    {{-- Mobile Enrollment Dropdown --}}
                    @php
                        $isEnrollmentActive =
                            request()->routeIs('registrar.students.*') || request()->routeIs('registrar.dropped.*');
                    @endphp
                    <div x-data="{ open: {{ $isEnrollmentActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isEnrollmentActive ? 'bg-blue-50/80 text-blue-700' : 'text-slate-600 hover:bg-blue-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isEnrollmentActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Enrollment</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isEnrollmentActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('registrar.students.index', ['level' => 'college']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.students.*') && request('level') === 'college' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Enrolled
                                </a>
                                <a href="{{ route('registrar.students.index', ['level' => 'shs']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.students.*') && request('level') === 'shs' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Enrolled
                                </a>
                                <a href="{{ route('registrar.dropped.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.dropped.*') ? 'text-rose-700 bg-rose-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Dropped Students
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Applications Dropdown --}}
                    @php
                        $isApplicationsActive = request()->routeIs('registrar.applications.*');
                    @endphp
                    <div x-data="{ open: {{ $isApplicationsActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isApplicationsActive ? 'bg-purple-50/80 text-purple-700' : 'text-slate-600 hover:bg-purple-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isApplicationsActive ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Applications</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isApplicationsActive ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('registrar.applications.college') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.applications.college') ? 'text-purple-700 bg-purple-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Applications
                                </a>
                                <a href="{{ route('registrar.applications.shs') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.applications.shs') ? 'text-purple-700 bg-purple-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Applications
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Configuration
                    </p>

                    {{-- Mobile Configuration Dropdown --}}
                    @php
                        $isConfigActive =
                            request()->routeIs('registrar.programs.*') ||
                            request()->routeIs('registrar.sections.*') ||
                            request()->routeIs('registrar.academic_years.*') ||
                            request()->routeIs('registrar.semesters.*');
                    @endphp
                    <div x-data="{ open: {{ $isConfigActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isConfigActive ? 'bg-blue-50/80 text-blue-700' : 'text-slate-600 hover:bg-blue-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isConfigActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Portal Config</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isConfigActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('registrar.programs.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.programs.*') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Programs
                                </a>
                                <a href="{{ route('registrar.sections.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.sections.*') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Sections
                                </a>
                                <a href="{{ route('registrar.academic_years.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.academic_years.*') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Academic Years
                                </a>
                                <a href="{{ route('registrar.semesters.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('registrar.semesters.*') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Semesters
                                </a>
                            </div>
                        </div>
                    </div>
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
                    &copy; 2026 Your Institution &mdash; <span class="text-blue-600">Registrar Panel</span>
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
