<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    </style>
    @livewireStyles
</head>

<body x-data="{ sidebarOpen: localStorage.getItem('admin_sidebar_open') === 'true', mobileOpen: false }" x-init="$watch('sidebarOpen', value => localStorage.setItem('admin_sidebar_open', value))" class="text-slate-900 flex flex-row min-h-screen"
    style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); background-attachment: fixed; min-height: 100vh;">

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

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden border-none opacity-0'"
        class="hidden sm:flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side shadow-sm"
        style="background: #ffffff; border-right: 1px solid rgba(0,0,0,0.06);">
        {{-- Sidebar Toggle (At the top of AD) --}}
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
            <div class="flex items-center gap-3 px-4 h-20 flex-shrink-0 overflow-hidden"
                style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <div class="text-white font-black p-2.5 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                    style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
                    AD</div>
                <div class="whitespace-nowrap">
                    <div class="text-sm font-black leading-none text-slate-800 tracking-tight uppercase">Admin Portal
                    </div>
                    <div class="text-[10px] mt-1 font-bold uppercase tracking-widest text-blue-600">Enterprise Control
                    </div>
                </div>
            </div>

            {{-- Nav Items --}}
            <div class="py-6 flex-1 flex flex-col">


                <p class="text-[11px] font-black uppercase tracking-[0.2em] px-6 mb-3 whitespace-nowrap text-slate-400">
                    Main Menu</p>
                <nav class="space-y-0.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative group"
                        style="{{ request()->routeIs('admin.dashboard') ? 'background: #eff6ff; color: #2563eb; border-left: 4px solid #2563eb;' : 'color: #64748b;' }}">
                        <span
                            class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                            style="{{ request()->routeIs('admin.dashboard') ? 'background: #ffffff; color: #2563eb;' : 'background: #f8fafc;' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                                </path>
                            </svg>
                        </span>
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap transition-colors">Dashboard</span>
                    </a>

                    {{-- Divider --}}
                    <div class="my-6 mx-4 border-t border-slate-100"></div>

                    {{-- Management Section --}}
                    <p x-show="sidebarOpen" x-cloak
                        class="text-[11px] font-black uppercase tracking-[0.2em] px-4 mb-3 whitespace-nowrap text-slate-300">
                        Operational Nodes</p>

                    <div class="space-y-1">

                        {{-- Enrollment Dropdown --}}
                        @php
                            $isEnrollmentActive =
                                request()->routeIs('admin.students.*') || request()->routeIs('admin.dropped.*');
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
                                <a href="{{ route('admin.students.index', ['level' => 'college']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.students.*') && request('level') === 'college' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">College
                                    Enrolled</a>
                                <a href="{{ route('admin.students.index', ['level' => 'shs']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.students.*') && request('level') === 'shs' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">SHS
                                    Enrolled</a>
                                <a href="{{ route('admin.dropped.index') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.dropped.*') ? 'text-rose-600' : 'text-slate-700 hover:text-slate-900' }}">Dropped
                                    Students</a>
                            </div>
                        </div>

                        {{-- Payments Dropdown --}}
                        @php
                            $isPaymentsActive = request()->routeIs('admin.payments.*');
                        @endphp
                        <div x-data="{ open: {{ $isPaymentsActive ? 'true' : 'false' }} }" class="mx-3">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isPaymentsActive ? 'true' : 'false' }}) ? 'bg-blue-50 text-blue-600' :
                                'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                :style="(open || {{ $isPaymentsActive ? 'true' : 'false' }}) ?
                                'color: #2563eb; background: #eff6ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isPaymentsActive ? 'true' : 'false' }}) ? 'text-blue-600' :
                                        'text-slate-400 group-hover:text-blue-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Payments</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[
                                        (open || {{ $isPaymentsActive ? 'true' : 'false' }}) ? 'text-blue-600' :
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
                                <a href="{{ route('admin.payments.college') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.payments.college') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">College
                                    Payments</a>
                                <a href="{{ route('admin.payments.shs') }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.payments.shs') ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">SHS
                                    Payments</a>
                            </div>
                        </div>

                        {{-- Applications Dropdown --}}
                        @php
                            $isApplicationsActive = request()->routeIs('admin.applications.*');
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
                                <a href="{{ route('admin.applications.index', ['level' => 'college']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.applications.*') && request('level') === 'college' ? 'text-purple-600' : 'text-slate-700 hover:text-slate-900' }}">College
                                    Applications</a>
                                <a href="{{ route('admin.applications.index', ['level' => 'shs']) }}"
                                    class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.applications.*') && request('level') === 'shs' ? 'text-purple-600' : 'text-slate-700 hover:text-slate-900' }}">SHS
                                    Applications</a>
                            </div>
                        </div>

                        {{-- Historical Academic Records Dropdown --}}
                        @php
                            $isArchivesActive = request()->routeIs('admin.archives.*');
                        @endphp
                        <div x-data="{ open: {{ $isArchivesActive ? 'true' : 'false' }} }" class="mx-3">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isArchivesActive ? 'true' : 'false' }}) ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600'"
                                :style="(open || {{ $isArchivesActive ? 'true' : 'false' }}) ? 'color: #2563eb; background: #eff6ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isArchivesActive ? 'true' : 'false' }}) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Archives</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[ (open || {{ $isArchivesActive ? 'true' : 'false' }}) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600', open ? 'rotate-180' : '' ]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="pl-8 pr-3 py-1.5 space-y-1 bg-slate-50 rounded-xl mt-1 border border-slate-100">
                                <a href="{{ route('admin.archives.index', ['level' => 'college']) }}" class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.archives.*') && request('level') === 'college' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">College Archives</a>
                                <a href="{{ route('admin.archives.index', ['level' => 'shs']) }}" class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.archives.*') && request('level') === 'shs' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">SHS Archives</a>
                            </div>
                        </div>

                        {{-- Activity Logs Dropdown --}}
                        @php
                            $isActivityLogsActive = request()->routeIs('admin.activity-logs.*');
                        @endphp
                        <div x-data="{ open: {{ $isActivityLogsActive ? 'true' : 'false' }} }" class="mx-3 mt-1">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 group"
                                :class="(open || {{ $isActivityLogsActive ? 'true' : 'false' }}) ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600'"
                                :style="(open || {{ $isActivityLogsActive ? 'true' : 'false' }}) ? 'color: #2563eb; background: #eff6ff;' : 'color: #64748b;'">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200"
                                        :class="(open || {{ $isActivityLogsActive ? 'true' : 'false' }}) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Activity Logs</span>
                                </div>
                                <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-all duration-200"
                                    :class="[ (open || {{ $isActivityLogsActive ? 'true' : 'false' }}) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600', open ? 'rotate-180' : '' ]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="pl-8 pr-3 py-1.5 space-y-1 bg-slate-50 rounded-xl mt-1 border border-slate-100">
                                <a href="{{ route('admin.activity-logs.index', ['level' => 'college']) }}" class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.activity-logs.*') && request('level') === 'college' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">College Logs</a>
                                <a href="{{ route('admin.activity-logs.index', ['level' => 'shs']) }}" class="block py-2 text-[13px] font-bold transition-all {{ request()->routeIs('admin.activity-logs.*') && request('level') === 'shs' ? 'text-blue-600' : 'text-slate-700 hover:text-slate-900' }}">SHS Logs</a>
                            </div>
                        </div>
                    </div>


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
                    class="hidden sm:flex text-slate-600 hover:bg-slate-100 p-2.5 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button> <button @click="mobileOpen = !mobileOpen"
                    class="sm:hidden text-slate-600 hover:bg-slate-100 p-2.5 rounded-xl transition-colors">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>



                <div class="flex items-center gap-6 ml-auto">
                    {{-- Notifications --}}
                    @livewire('admin.admin-notification-bell')

                    {{-- Manage Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-200/60">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Active
                                    Admin</div>
                                <div class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                    {{ auth()->user()->name ?? 'System Admin' }}</div>
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

            {{-- Mobile Menu Dropdown --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="sm:hidden absolute top-20 left-0 right-0 border-b shadow-2xl z-30 bg-white max-h-[85vh] overflow-y-auto"
                style="border-color: rgba(0,0,0,0.06);" @click.away="mobileOpen = false" x-cloak>
                <div class="p-4 space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-400">Main Menu</p>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dashboard</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Operational
                        Nodes</p>



                    {{-- Mobile Enrollment Dropdown --}}
                    @php
                        $isEnrollmentActive =
                            request()->routeIs('admin.students.*') || request()->routeIs('admin.dropped.*');
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
                                <a href="{{ route('admin.students.index', ['level' => 'college']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.students.*') && request('level') === 'college' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Enrolled
                                </a>
                                <a href="{{ route('admin.students.index', ['level' => 'shs']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.students.*') && request('level') === 'shs' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Enrolled
                                </a>
                                <a href="{{ route('admin.dropped.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.dropped.*') ? 'text-rose-700 bg-rose-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    Dropped Students
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Payments Dropdown --}}
                    @php
                        $isPaymentsActive = request()->routeIs('admin.payments.*');
                    @endphp
                    <div x-data="{ open: {{ $isPaymentsActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isPaymentsActive ? 'bg-blue-50/80 text-blue-700' : 'text-slate-600 hover:bg-blue-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isPaymentsActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Payments</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isPaymentsActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('admin.payments.college') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.payments.college') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Payments
                                </a>
                                <a href="{{ route('admin.payments.shs') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.payments.shs') ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Payments
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Applications Dropdown --}}
                    @php
                        $isApplicationsActive = request()->routeIs('admin.applications.*');
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
                                <a href="{{ route('admin.applications.index', ['level' => 'college']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.applications.*') && request('level') === 'college' ? 'text-purple-700 bg-purple-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Applications
                                </a>
                                <a href="{{ route('admin.applications.index', ['level' => 'shs']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.applications.*') && request('level') === 'shs' ? 'text-purple-700 bg-purple-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Applications
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Archives Dropdown --}}
                    @php
                        $isArchivesActive = request()->routeIs('admin.archives.*');
                    @endphp
                    <div x-data="{ open: {{ $isArchivesActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isArchivesActive ? 'bg-blue-50/80 text-blue-700' : 'text-slate-600 hover:bg-blue-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isArchivesActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Archives</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isArchivesActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('admin.archives.index', ['level' => 'college']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.archives.*') && request('level') === 'college' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Archives
                                </a>
                                <a href="{{ route('admin.archives.index', ['level' => 'shs']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.archives.*') && request('level') === 'shs' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Archives
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Activity Logs Dropdown --}}
                    @php
                        $isActivityLogsActive = request()->routeIs('admin.activity-logs.*');
                    @endphp
                    <div x-data="{ open: {{ $isActivityLogsActive ? 'true' : 'false' }} }" class="pb-4">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 rounded-2xl transition-colors duration-200 group
                                {{ $isActivityLogsActive ? 'bg-blue-50/80 text-blue-700' : 'text-slate-600 hover:bg-blue-50/80 hover:text-slate-900' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-200 {{ $isActivityLogsActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-bold text-[13px] uppercase tracking-wider">Activity Logs</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200 {{ $isActivityLogsActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-5 py-2 mt-1 space-y-1 bg-slate-50 rounded-2xl border border-slate-100">
                                <a href="{{ route('admin.activity-logs.index', ['level' => 'college']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.activity-logs.*') && request('level') === 'college' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    College Logs
                                </a>
                                <a href="{{ route('admin.activity-logs.index', ['level' => 'shs']) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-[13px] font-bold rounded-lg transition-colors {{ request()->routeIs('admin.activity-logs.*') && request('level') === 'shs' ? 'text-blue-700 bg-blue-50/50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                    SHS Logs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Page Content --}}
        <main class="flex-1 px-8 py-10 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-12 border-t mt-auto"
            style="background: rgba(255,255,255,0.5); border-color: rgba(0,0,0,0.05);">
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                    &copy; 2026 Your Institution &mdash; <span class="text-blue-600">Admin Panel</span>
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
