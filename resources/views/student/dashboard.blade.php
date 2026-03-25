<x-layouts.student title="Student Dashboard">
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-xl relative font-medium shadow-sm flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl relative font-medium shadow-sm flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    @php
        // Use enrollment for CURRENT academic year only
        $currentStatus = $currentYearEnrollment ? $currentYearEnrollment->status : 'Not Enrolled';

        // Check if student can enroll in the CURRENT ACTIVE semester
        $currentAcademicYear = $activeYear;
        if ($currentAcademicYear && $currentYearEnrollment) {
            $isEnrollmentForCurrentYear = strpos($currentYearEnrollment->year_level, $currentAcademicYear->year_name) !== false;
            $canEnroll = !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved']) || !$isEnrollmentForCurrentYear;
        } else {
            $canEnroll = !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved']);
        }
    @endphp

    {{-- Welcome Header --}}
    <div class="p-8 rounded-2xl border relative overflow-hidden group shadow-2xl shadow-black/40"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

        <div class="absolute top-0 right-0 p-12 opacity-5 mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
            <svg class="w-64 h-64 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-[#10B981]/10 border border-[#10B981]/20 flex items-center justify-center text-[#10B981] shadow-lg shadow-[#10B981]/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white leading-none tracking-tight">Welcome, {{ Auth::user()->first_name }}!</h2>
                    <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Portal Access — Core Infrastructure</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-white/30 uppercase tracking-widest mb-1">Enrollment Status</p>
                    @if($currentStatus === 'Enrolled' || $currentStatus === 'Approved')
                        <span class="bg-[#10B981]/20 text-[#10B981] px-4 py-1.5 rounded-full font-black text-xs border border-[#10B981]/30 tracking-widest uppercase">ENROLLED</span>
                    @elseif($currentStatus === 'Pending')
                        <span class="bg-amber-500/20 text-amber-400 px-4 py-1.5 rounded-full font-black text-xs border border-amber-500/30 tracking-widest uppercase">PENDING</span>
                    @elseif($currentStatus === 'Rejected')
                        <span class="bg-rose-500/20 text-rose-400 px-4 py-1.5 rounded-full font-black text-xs border border-rose-500/30 tracking-widest uppercase">REJECTED</span>
                    @else
                        <span class="bg-white/5 text-white/30 px-4 py-1.5 rounded-full font-black text-xs border border-white/10 tracking-widest uppercase">NOT ENROLLED</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Enrollment Open Alert --}}
    @if($activeSemester && $activeYear && $canEnroll)
    <div class="p-6 rounded-2xl border flex items-center gap-4"
         style="background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); box-shadow: 0 4px 20px rgba(16,185,129,0.1);">
        <div class="text-[#10B981] p-3 rounded-xl bg-[#10B981]/10 flex-shrink-0 animate-pulse">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <div>
            <h3 class="text-[#34d399] font-bold text-lg mb-0.5">Enrollment is Now Open!</h3>
            <p class="text-white/60 text-xs">
                You can now submit your application for <strong class="text-white">{{ $activeSemester->name }}</strong>, Academic Year <strong class="text-white">{{ $activeYear->year_name }}</strong>.
            </p>
        </div>
        <div class="ml-auto hidden sm:block">
            <a href="{{ route('student.enrollment.create') }}" class="bg-[#10B981] hover:bg-[#34d399] text-black text-xs font-black px-6 py-2.5 rounded-xl uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-emerald-500/20">Enroll Now</a>
        </div>
    </div>
    @elseif(!$activeSemester || !$activeYear)
    <div class="p-6 rounded-2xl border flex items-center gap-4"
         style="background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1);">
        <div class="text-white/20 p-3 rounded-xl bg-white/5 flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-white/60 font-bold text-lg mb-0.5">Enrollment Currently Closed</h3>
            <p class="text-white/40 text-xs italic">Please wait for the next enrollment period announcement from the registrar.</p>
        </div>
    </div>
    @endif

    {{-- Action Matrix --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Enrollment Card --}}
        @if($canEnroll && !$isEnrolledInActiveYear)
        <a href="{{ route('student.enrollment.create') }}" class="group block h-full">
            <div class="p-8 rounded-2xl border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden"
                 style="background: rgba(99,179,237,0.06); border-color: rgba(99,179,237,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-xs font-bold text-blue-400/80 uppercase tracking-[0.2em] mb-1">Enrollment</h4>
                        <div class="text-2xl font-black text-white tracking-tight">Enrollment</div>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-white/40 leading-relaxed mb-6">Start a new enrollment or track your pending applications in real-time.</p>
                <div class="text-xs font-bold text-blue-400 uppercase tracking-widest flex items-center gap-2">
                    Initialize Operation <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </a>
        @else
        <div class="p-8 rounded-2xl border h-full opacity-60 relative overflow-hidden"
             style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.05);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-white/20 uppercase tracking-[0.2em] mb-1">Enrollment</h4>
                    <div class="text-2xl font-black text-white/20 tracking-tight">Enrollment</div>
                </div>
                <div class="p-3 rounded-xl bg-white/5 text-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-white/10 leading-relaxed mb-6 italic">Active session detected. Application commit locked.</p>
        </div>
        @endif

        {{-- Payments Card --}}
        <a href="{{ route('student.payment') }}" class="group block h-full">
            <div class="p-8 rounded-2xl border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden"
                 style="background: rgba(167,139,250,0.06); border-color: rgba(167,139,250,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-xs font-bold text-purple-400/80 uppercase tracking-[0.2em] mb-1">Payments</h4>
                        <div class="text-2xl font-black text-white tracking-tight">Payments</div>
                    </div>
                    <div class="p-3 rounded-xl bg-purple-500/10 text-purple-400 group-hover:rotate-12 transition-transform shadow-lg shadow-purple-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-white/40 leading-relaxed mb-6">View outstanding balances and record secure financial transactions.</p>
                <div class="text-xs font-bold text-purple-400 uppercase tracking-widest flex items-center gap-2">
                    Initialize Operation <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </a>

        {{-- Profile Card --}}
        <a href="{{ route('student.profile') }}" class="group block h-full">
            <div class="p-8 rounded-2xl border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden"
                 style="background: rgba(251,191,36,0.06); border-color: rgba(251,191,36,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h4 class="text-xs font-bold text-amber-400/80 uppercase tracking-[0.2em] mb-1">My Account</h4>
                        <div class="text-2xl font-black text-white tracking-tight">Student Profile</div>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400 group-hover:rotate-12 transition-transform shadow-lg shadow-amber-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-white/40 leading-relaxed mb-6">Maintain your contact information and view academic history logs.</p>
                <div class="text-xs font-bold text-amber-400 uppercase tracking-widest flex items-center gap-2">
                    Initialize Operation <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Requirements Section --}}
    <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40 overflow-hidden"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-6 bg-[#10B981] rounded-full"></div>
            <div>
                <h3 class="text-xl font-bold text-white tracking-tight">Enrollment Requirements</h3>
                <p class="text-xs font-bold text-white/20 uppercase tracking-widest italic mt-1">Application Checklist — Academic Year</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-8">
            <div class="space-y-4">
                <h4 class="text-xs font-black text-[#10B981] uppercase tracking-[0.2em] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Eligibility Matrix
                </h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">Registry Status: Must be a verified portal user.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">Academic Merit: Meet program-specific entry criteria.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">Payments: Settled fees or approved payments.</span>
                    </li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="text-xs font-black text-blue-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Required Documentation
                </h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">Form 137 / SHS Report Card (Original Copy)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">PSA Birth Certification (Clear Copy)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-sm text-white/50 leading-relaxed">Identity Validation Assets (2x2 Portraits)</span>
                    </li>
                </ul>
            </div>
        </div>

        @if($canEnroll && !$isEnrolledInActiveYear)
        <a href="{{ route('student.enrollment.create') }}" class="inline-block bg-[#10B981] hover:bg-[#34d399] text-black font-black py-4 px-10 rounded-xl shadow-xl shadow-emerald-500/20 transition-all active:scale-95 uppercase tracking-widest text-xs">Apply Now</a>
        @else
        <button disabled class="inline-block bg-white/5 text-white/20 border border-white/10 font-bold py-4 px-10 rounded-xl cursor-not-allowed uppercase tracking-widest text-xs italic">Application Already Submitted</button>
        @endif
    </div>
</div>
</x-layouts.student>
