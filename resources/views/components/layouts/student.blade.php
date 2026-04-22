<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Portal' }}</title>
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
                 style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6), 0 0 0 1px rgba(99,179,237,0.15);">ST</div>
            <div class="hidden group-hover/side:block whitespace-nowrap">
                <div class="text-sm font-bold leading-none text-white tracking-tight">Student Portal</div>
                <div class="text-[10px] mt-0.5 font-semibold uppercase tracking-widest" style="color: rgba(138,180,216,0.55);">Information System</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <div class="py-4 flex-1">
            <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Navigation</p>
            <nav class="space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('student.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200 relative"
                   style="{{ request()->routeIs('student.dashboard') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                   onmouseout="this.style.background='{{ request()->routeIs('student.dashboard') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                   {{ request()->routeIs('student.dashboard') ? 'data-active=1' : '' }}>
                    @if(request()->routeIs('student.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full" style="background: #63b3ed;"></span>
                    @endif
                    <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(99,179,237,0.12);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                    </span>
                    <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Dashboard</span>
                </a>

                {{-- Divider --}}
                <div class="my-4 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Services Section (Simplified) --}}
                <p class="text-[11px] font-black uppercase tracking-[0.25em] px-3 mb-2 hidden group-hover/side:block whitespace-nowrap" style="color: rgba(138,180,216,0.35);">Services</p>

                <div class="mt-1 space-y-0.5">

                    @php
                        $hasEnrollment = \App\Models\Enrollment::where('user_id', auth()->id())->exists();
                    @endphp

                    {{-- Enrollment --}}
                    @if(!$hasEnrollment)
                    <a href="{{ route('student.enrollment.create') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('student.enrollment.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('student.enrollment.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('student.enrollment.*') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('student.enrollment.*'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Enrollment</span>
                    </a>
                    @else
                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium opacity-40 cursor-not-allowed group relative"
                         style="color: #8ab4d8;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Enrollment</span>
                        <span class="absolute left-full ml-2 px-2 py-1 bg-black text-[10px] font-bold text-white rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">Already submitted</span>
                    </div>
                    @endif


                    @if($hasEnrollment)
                        <a href="{{ route('student.enrollment.upload') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('student.enrollment.upload') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('student.enrollment.upload') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('student.enrollment.upload') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('student.enrollment.upload'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Documents</span>
                        </a>

                        <a href="{{ route('student.enrollment.review') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('student.enrollment.review') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('student.enrollment.review') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('student.enrollment.review') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('student.enrollment.review'))
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Review App</span>
                        </a>
                    @else
                        <div class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium opacity-40 cursor-not-allowed group relative"
                             style="color: #8ab4d8;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Review App</span>
                            <span class="absolute left-full ml-2 px-2 py-1 bg-black text-[10px] font-bold text-white rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">Apply first to unlock</span>
                        </div>
                    @endif

                    {{-- Payments --}}
                    <a href="{{ route('student.payment') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('student.payment') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('student.payment') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('student.payment') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('student.payment'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Payments</span>
                    </a>

                    {{-- Profile --}}
                    <a href="{{ route('student.profile') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[15px] font-medium transition-all duration-200 relative"
                       style="{{ request()->routeIs('student.profile') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                       onmouseout="this.style.background='{{ request()->routeIs('student.profile') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                       {{ request()->routeIs('student.profile') ? 'data-active=1' : '' }}>
                        @if(request()->routeIs('student.profile'))
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="hidden group-hover/side:inline-block whitespace-nowrap align-middle">Profile</span>
                    </a>
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
                        <div class="text-xs text-white/40 italic">Signed in as Student</div>
                        <div class="text-sm font-bold text-white capitalize">{{ auth()->user()->first_name ?? 'Student' }}</div>
                    </div>
                    @if(request()->routeIs('student.dashboard'))
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
                © 2026 Your Institution — Student Portal
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
