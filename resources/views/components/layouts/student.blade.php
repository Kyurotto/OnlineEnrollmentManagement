<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Student Portal' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: localStorage.getItem('student_sidebar_open') === 'true', mobileOpen: false }"
      x-init="$watch('sidebarOpen', value => localStorage.setItem('student_sidebar_open', value))"
      class="text-slate-900 flex flex-row min-h-screen" style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); background-attachment: fixed; min-height: 100vh;">
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
        /* Adjust glass cards for light theme */
        .glass-card {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        }
        /* Fix table headers and dividers */
        main .border-white\/5, main .divide-white\/5 {
            border-color: rgba(0, 0, 0, 0.05) !important;
        }
        main .bg-white\/\[0\.01\], main .bg-white\/\[0\.02\], main .bg-white\/5 {
            background-color: rgba(37, 99, 235, 0.03) !important;
        }
        /* Dashboard Card adjustments */
        main .bg-white\/5 {
            background-color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        /* Notification Dropdown Light Theme Overrides */
        nav .absolute.right-0.top-12.w-80 {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
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

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden border-none opacity-0'" class="hidden sm:flex flex-col transition-all duration-300 ease-in-out flex-shrink-0 sticky top-0 h-screen overflow-y-auto overflow-x-hidden z-30 group/side shadow-sm"
           style="background: #ffffff; border-right: 1px solid rgba(0,0,0,0.06);">

        {{-- Sidebar Toggle (At the top of SD) --}}
        <div class="h-20 flex items-center px-6 flex-shrink-0" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
            <button @click="sidebarOpen = !sidebarOpen" class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 p-2 rounded-xl transition-all active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col flex-1">
            {{-- Branding --}}
            <div class="flex items-center gap-3 px-4 h-20 flex-shrink-0 overflow-hidden" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <div class="text-white font-black p-2.5 rounded-xl text-sm uppercase flex-shrink-0 tracking-widest"
                     style="background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 4px 12px rgba(37,99,235,0.2);">ST</div>
                <div class="whitespace-nowrap">
                    <div class="text-sm font-black leading-none text-slate-800 tracking-tight uppercase">Student Portal</div>
                    <div class="text-[10px] mt-1 font-bold uppercase tracking-widest text-blue-600">Digital Campus</div>
                </div>
            </div>

            {{-- Nav Items --}}
            <div class="py-6 flex-1 flex flex-col">


                <p class="text-[11px] font-black uppercase tracking-[0.2em] px-6 mb-3 whitespace-nowrap text-slate-300">Navigation</p>
                <nav class="space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('student.payment') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('student.payment', 'student.payment.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Payments</span>
                </a>

                <a href="{{ route('student.profile') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('student.profile', 'student.profile.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">My Profile</span>
                </a>

                <div class="my-6 mx-4 border-t border-slate-50"></div>
                <p x-show="sidebarOpen" x-cloak class="text-[11px] font-black uppercase tracking-[0.2em] px-6 mb-3 whitespace-nowrap text-slate-300">Management</p>

                @php
                    $user = Auth::user();
                    $activeSemester = \App\Models\Semester::where('is_active', true)->first();
                    $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                    $hasSubmittedSidebar = false;
                    $isOldStudentSidebar = false;
                    $isClearedSidebar = false;

                    if ($activeYear && $activeSemester) {
                        // 1. Check if they have ALREADY submitted for the CURRENT term
                        $currentEnrollmentSidebar = \App\Models\Enrollment::where('user_id', $user->id)
                            ->where(function($q) use ($activeYear, $activeSemester) {
                                $q->where(function($sub) use ($activeYear, $activeSemester) {
                                    $sub->where('semester_name', $activeSemester->name)
                                        ->where('academic_year_name', $activeYear->year_name);
                                })->orWhere(function($sub) use ($activeYear, $activeSemester) {
                                    $sub->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                                        ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
                                });
                            })->first();

                        $hasSubmittedSidebar = $currentEnrollmentSidebar !== null;

                        // 2. Check if they are an OLD student (Returning Student)
                        // A student is old if they have ANY historical record other than the one they are currently processing
                        $isOldStudentSidebar = \App\Models\Enrollment::where('user_id', $user->id)
                            ->where('id', '!=', $currentEnrollmentSidebar->id ?? 0)
                            ->exists();

                        // 3. Check if they are CLEARED for the current term (if they have a record)
                        $isClearedSidebar = ($currentEnrollmentSidebar && $currentEnrollmentSidebar->credentials_verified == 1);

                        // 4. Detect if it's a "Shell" record (Returning student starting clearance but hasn't filled form)
                        $isShellRecord = ($currentEnrollmentSidebar && (empty($currentEnrollmentSidebar->course_code) || str_contains($currentEnrollmentSidebar->year_level, 'Returning Student')));
                    }

                    // Logic:
                    // 1. If no record exists at all -> OK for New Students, but Old Students need clearance.
                    // 2. If it's a shell record AND cleared -> OK to click (to fill the form).
                    // 3. If it's a FULL record (has course_code) -> Locked (already applied).

                    $canShowApplicationsLink = false;

                    if (!$hasSubmittedSidebar) {
                        // New Students can always see it. Old students must upload docs first (which creates shell)
                        if (!$isOldStudentSidebar) {
                            $canShowApplicationsLink = true;
                        }
                    } else {
                        // If they have a record, only allow clicking if it's a SHELL record that is CLEARED
                        if ($isOldStudentSidebar && $isClearedSidebar && $isShellRecord) {
                            $canShowApplicationsLink = true;
                        }
                    }
                @endphp

                <a href="{{ route('student.enrollment.upload') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('student.enrollment.upload') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Documents</span>
                </a>

                @if($canShowApplicationsLink)
                <a href="{{ route('student.enrollment.create') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('student.enrollment.create') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Applications</span>
                </a>
                @else
                <div class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold text-slate-300 cursor-not-allowed opacity-50 relative group">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Applications</span>
                </div>
                @endif

                @php
                    $canReviewSidebar = $hasSubmittedSidebar && !$isShellRecord;
                @endphp

                @if($canReviewSidebar)
                <a href="{{ route('student.enrollment.review') }}"
                   class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold transition-all duration-200 relative {{ request()->routeIs('student.enrollment.review') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Review Application</span>
                </a>
                @else
                <div class="flex items-center gap-3 mx-3 px-3 py-3 rounded-xl text-[15px] font-bold text-slate-300 cursor-not-allowed opacity-50 relative group">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Review Application</span>
                </div>
                @endif
            </nav>
        </div>
    </aside>

    {{-- Content Area --}}
    <div class="flex flex-col flex-1 min-w-0">
        {{-- Navbar --}}
        <nav class="sticky top-0 z-20 shadow-sm border-b h-20 flex items-center bg-white/80 backdrop-blur-xl"
             style="border-color: rgba(0,0,0,0.06);">
            <div class="w-full px-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    {{-- Desktop Hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen" x-show="!sidebarOpen" class="hidden sm:flex text-slate-600 hover:bg-slate-100 p-2.5 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>


                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="sm:hidden text-slate-600 hover:bg-slate-100 p-2.5 rounded-xl transition-colors">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>


                </div>

                <div class="flex items-center gap-6 ml-auto">
                    {{-- Manage Account Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-200/60">
                            <div class="text-right hidden sm:block">
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest">{{ Auth::user()->role }} Account</div>
                                <div class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
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
                                <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all group">
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

            {{-- Mobile Menu Dropdown --}}
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="sm:hidden absolute top-20 left-0 right-0 border-b shadow-2xl z-30 bg-white max-h-[85vh] overflow-y-auto"
                 style="border-color: rgba(0,0,0,0.06);"
                 @click.away="mobileOpen = false"
                 x-cloak>
                <div class="p-4 space-y-1">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Navigation</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Dashboard</span>
                    </a>

                    <a href="{{ route('student.payment') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('student.payment') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Payments</span>
                    </a>

                    <a href="{{ route('student.profile') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('student.profile') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">My Profile</span>
                    </a>

                    <div class="my-4 border-t border-slate-50"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 text-slate-300">Management</p>

                    <a href="{{ route('student.enrollment.upload') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('student.enrollment.upload') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Documents</span>
                    </a>

                    @if($canShowApplicationsLink)
                    <a href="{{ route('student.enrollment.create') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('student.enrollment.create') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Applications</span>
                    </a>
                    @else
                    <div class="flex items-center gap-4 px-5 py-3.5 rounded-2xl text-slate-300 cursor-not-allowed opacity-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Applications</span>
                    </div>
                    @endif

                    @if($canReviewSidebar)
                    <a href="{{ route('student.enrollment.review') }}"
                       class="flex items-center gap-4 px-5 py-3.5 rounded-2xl {{ request()->routeIs('student.enrollment.review') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-indigo-50/50' }} transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span class="font-bold text-[13px] uppercase tracking-wider">Review Application</span>
                    </a>
                    @endif
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="flex-1 px-8 py-10 animate-in fade-in duration-700">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="py-12 border-t mt-auto" style="background: rgba(255,255,255,0.5); border-color: rgba(0,0,0,0.05);">
            <div class="text-center">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                    &copy; 2026 Your Institution &mdash; <span class="text-blue-600">Student Portal</span>
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
