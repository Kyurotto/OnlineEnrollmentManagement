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
                <div class="my-2 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Management Dropdown --}}
                <div x-data="{ open: {{ request()->routeIs('registrar.students.*', 'registrar.applications.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200"
                            style="color: #8ab4d8;"
                            onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#ffffff';"
                            onmouseout="this.style.background='transparent'; this.style.color='#8ab4d8';">
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg" style="background: rgba(99,179,237,0.08);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </span>
                            Management
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" style="display: none;" class="mt-1 ml-4 pl-3 border-l border-[rgba(26,58,110,0.4)] space-y-0.5">
                        
                        {{-- Students --}}
                        <a href="{{ route('registrar.students.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('registrar.students.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('registrar.students.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('registrar.students.*') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('registrar.students.*'))
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"></path></svg>
                            Students
                        </a>

                        {{-- Applications --}}
                        <a href="{{ route('registrar.applications.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('registrar.applications.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('registrar.applications.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('registrar.applications.*') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('registrar.applications.*'))
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Applications
                        </a>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="my-2 mx-2" style="border-top: 1px solid rgba(26,58,110,0.3);"></div>

                {{-- Configuration Dropdown --}}
                <div x-data="{ open: {{ request()->routeIs('registrar.programs.*', 'registrar.sections.*', 'registrar.academic_years.*', 'registrar.semesters.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-[15px] font-semibold transition-all duration-200"
                            style="color: #8ab4d8;"
                            onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#ffffff';"
                            onmouseout="this.style.background='transparent'; this.style.color='#8ab4d8';">
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-lg" style="background: rgba(99,179,237,0.08);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            </span>
                            Configuration
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" style="display: none;" class="mt-1 ml-4 pl-3 border-l border-[rgba(26,58,110,0.4)] space-y-0.5">
                        
                        {{-- Programs --}}
                        <a href="{{ route('registrar.programs.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 relative"
                           style="{{ request()->routeIs('registrar.programs.*') ? 'background: rgba(99,179,237,0.12); color: #ffffff;' : 'color: #8ab4d8;' }}"
                           onmouseover="this.style.background='rgba(255,255,255,0.05)'; if(!this.dataset.active) this.style.color='#ffffff';"
                           onmouseout="this.style.background='{{ request()->routeIs('registrar.programs.*') ? 'rgba(99,179,237,0.12)' : 'transparent' }}'; if(!this.dataset.active) this.style.color='#8ab4d8';"
                           {{ request()->routeIs('registrar.programs.*') ? 'data-active=1' : '' }}>
                            @if(request()->routeIs('registrar.programs.*'))
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
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
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
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
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
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
                                <span class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-[2px] h-4 rounded-r-full" style="background: #63b3ed;"></span>
                            @endif
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Semesters
                        </a>
                    </div>
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

                {{-- Notifications --}}
                <div class="relative">
                    <button @click="showDropdown = !showDropdown" @click.away="showDropdown = false" class="relative p-2 transition focus:outline-none" style="color: #8ab4d8;">
                        <svg class="w-6 h-6 transition" :style="showDropdown ? 'color: #ffffff' : 'color: #8ab4d8'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($newEnrolleesCount) && $newEnrolleesCount > 0)
                            <span class="absolute top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
                            </span>
                        @endif
                    </button>
                    <template x-if="showDropdown">
                        <div class="absolute right-0 top-12 w-80 shadow-2xl rounded-xl z-50 overflow-hidden"
                             style="background: rgba(6,13,26,0.97); backdrop-filter: blur(16px); border: 1px solid rgba(26,58,110,0.5);">
                            <div class="px-4 py-3 border-b flex justify-between items-center" style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                <h3 class="text-sm font-bold uppercase tracking-wide text-white">Notifications</h3>
                                @if(isset($newEnrolleesCount) && $newEnrolleesCount > 0)
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-rose-400 hover:text-rose-300 uppercase tracking-widest">Clear All</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto p-2 space-y-2" style="background: rgba(6,13,26,0.6);">
                                @if(isset($dbNotifications) && $dbNotifications->count() > 0)
                                    @foreach($dbNotifications as $notification)
                                        <div @click="fetch('{{ route('notifications.markAsRead', $notification->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => { window.location.href = '{{ route('registrar.applications.index') }}' })"
                                             class="block p-3 rounded-lg border transition cursor-pointer hover:bg-white/5"
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
                                <a href="{{ route('registrar.applications.index') }}" class="text-xs font-bold" style="color: #8ab4d8;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8ab4d8'">View All Applications →</a>
                            </div>
                        </div>
                    </template>
                </div>

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
</body>
</html>
