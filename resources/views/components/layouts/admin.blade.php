<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>

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

    {{-- Sidebar (full height, flush with top) --}}
    <aside x-data="{ showMobileMenu: false }" class="hidden sm:flex flex-col w-16 hover:w-64 transition-[width] duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side"
           style="background: rgba(6,13,26,0.97); backdrop-filter: blur(12px);">

        {{-- Sidebar Branding --}}
        <div class="flex items-center gap-3 px-3 h-16 flex-shrink-0 overflow-hidden" style="border-bottom: 1px solid rgba(26,58,110,0.4);">
            <div class="text-white font-black p-2 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                 style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6), 0 0 0 1px rgba(99,179,237,0.15);">AD</div>
            <div class="hidden group-hover/side:block whitespace-nowrap">
                <div class="text-sm font-bold leading-none text-white tracking-tight">Admin Panel</div>
                <div class="text-[10px] mt-0.5 font-semibold uppercase tracking-widest" style="color: rgba(138,180,216,0.55);">Management Console</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <div class="py-4 flex-1">
            <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Navigation</p>
            <nav class="space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200 relative"
                   style="{{ request()->routeIs('admin.dashboard') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                   onmouseout="this.style.background='{{ request()->routeIs('admin.dashboard') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                   {{ request()->routeIs('admin.dashboard') ? 'data-active=1' : '' }}>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full" style="background: #63b3ed;"></span>
                    @endif
                    <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(99,179,237,0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                    </span>
                    <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Dashboard</span>
                </a>

                {{-- Divider --}}
                <div class="my-4 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Management Section (Simplified) --}}
                <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Management</p>

                <div class="mt-1 space-y-0.5">

                    {{-- Manage Courses --}}
                    <a href="{{ route('admin.courses.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.courses.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.courses.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.courses.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.courses.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Courses</span>
                    </a>

                    {{-- Manage Students --}}
                    <a href="{{ route('admin.students.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.students.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.students.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.students.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.students.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Students</span>
                    </a>

                    {{-- College Payments --}}
                    <a href="{{ route('admin.payments.college') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.payments.college') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.payments.college') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.payments.college') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.payments.college'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">College Payments</span>
                    </a>

                    {{-- SHS Payments --}}
                    <a href="{{ route('admin.payments.shs') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.payments.shs') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.payments.shs') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.payments.shs') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.payments.shs'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">SHS Payments</span>
                    </a>

                    {{-- Applications for College --}}
                    <a href="{{ route('admin.applications.index', ['level' => 'college']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.applications.*') && request('level') === 'college' ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.applications.*') && request('level') === 'college' ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.applications.*') && request('level') === 'college' ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.applications.*') && request('level') === 'college')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Applications for College</span>
                    </a>

                    {{-- Applications for SHS --}}
                    <a href="{{ route('admin.applications.index', ['level' => 'shs']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('admin.applications.*') && request('level') === 'shs' ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('admin.applications.*') && request('level') === 'shs' ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('admin.applications.*') && request('level') === 'shs' ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('admin.applications.*') && request('level') === 'shs')
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Applications for SHS</span>
                    </a>

                </div>

            </nav>
        </div>
    </aside>

    {{-- Right side: navbar + content --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- Navbar (right side only) --}}
        <nav x-data="{ showDropdown: false }" class="sticky top-0 z-20 shadow-lg border-b h-16 flex items-center"
             style="background: rgba(6,13,26,0.95); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <div class="w-full px-6 flex justify-end items-center gap-6">

                {{-- Notifications Dropdown --}}
                @livewire('admin.admin-notification-bell')

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-white/40 italic">Signed in as</div>
                        <div class="text-sm font-bold text-white">Administrator</div>
                    </div>
                    @if(request()->routeIs('admin.dashboard'))
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-white text-sm font-semibold py-2 px-4 rounded-full transition-all"
                            style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);"
                            onmouseover="this.style.background='rgba(220,38,38,1)'"
                            onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                    </form>
                    @endif
                </div>
            </div>
        </nav>

        {{-- Page Content --}}
        <main class="flex-1 px-6 py-8 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-6 border-t" style="background: rgba(6,13,26,0.85); border-color: rgba(26,58,110,0.4);">
            <div class="text-center text-sm" style="color: #4a6fa5;">
                © 2026 Your Institution — Admin Panel
            </div>
        </footer>
    </div>

    @livewireScripts

</body>
</html>
