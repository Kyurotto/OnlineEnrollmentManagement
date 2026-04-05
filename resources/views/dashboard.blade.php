@php $role = auth()->user()->role; @endphp

@if($role === 'student')
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

        {{-- Progress Bar --}}
        <div class="mt-10 pt-8 border-t border-white/5">
            <div class="relative">
                {{-- Connection Line --}}
                <div class="absolute top-5 left-0 w-full h-0.5 bg-white/5 -z-0"></div>
                
                <div class="relative z-10 flex justify-between items-start">
                    @php
                        $progressBar = [
                            ['key' => 'application', 'label' => 'Fill Up Application'],
                            ['key' => 'online_docs', 'label' => 'Upload Online Documents'],
                            ['key' => 'physical_docs', 'label' => 'Pass Physical Documents'],
                            ['key' => 'payment', 'label' => 'Pay Physical in Cashier'],
                            ['key' => 'enroll', 'label' => 'Enroll'],
                        ];
                    @endphp

                    @foreach($progressBar as $step)
                        <div class="flex flex-col items-center group w-1/5">
                            @php
                                $color = $steps[$step['key']];
                                $circleClass = match($color) {
                                    'green' => 'bg-emerald-500 shadow-emerald-500/40 text-black',
                                    'yellow' => 'bg-amber-500 shadow-amber-500/40 text-black animate-pulse',
                                    default => 'bg-[#1a1a1a] border-2 border-white/10 text-white/20'
                                };
                            @endphp
                            
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 {{ $circleClass }} shadow-lg">
                                @if($color === 'green')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($color === 'yellow')
                                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                @else
                                    <span class="text-[10px] font-black">{{ $loop->iteration }}</span>
                                @endif
                            </div>
                            
                            <span class="mt-4 text-[9px] font-black uppercase tracking-widest text-center px-2 {{ $color !== 'grey' ? 'text-white' : 'text-white/20' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
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
    <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40 overflow-hidden relative"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

        {{-- Background Logo --}}
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none"
             style="opacity: 0.08;">
            <img src="{{ asset('image/logo.jfif') }}" alt="Logo" class="w-80 h-80 rounded-full object-cover">
        </div>

        <div class="relative z-10 flex items-center gap-3 mb-8">
            <div class="w-1.5 h-6 bg-[#10B981] rounded-full"></div>
            <div>
                <h3 class="text-xl font-bold text-white tracking-tight">Enrollment Requirements</h3>
                <p class="text-xs font-bold text-white/20 uppercase tracking-widest italic mt-1">Application Checklist — Academic Year</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 relative z-10">
            <!-- Senior High School Section -->
            <div class="p-6 rounded-xl border border-emerald-500/20 bg-emerald-500/5 space-y-4">
                <h4 class="text-xs font-black text-emerald-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    Senior High School (SHS)
                </h4>

                <div class="space-y-3">
                    <p class="text-xs font-semibold text-white/70">Eligibility:</p>
                    <ul class="space-y-2 ml-2">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-emerald-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Grade 11-12 students</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-emerald-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Select from Academic or Tech-Voc strands</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-emerald-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Valid JHS credentials required</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-3 pt-2 border-t border-emerald-500/10">
                    <p class="text-xs font-semibold text-white/70">Required Documents:</p>
                    <ul class="space-y-2 ml-2">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-400 text-xs">•</span>
                            <span class="text-xs text-white/50">SF9 (JHS Report Card)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-400 text-xs">•</span>
                            <span class="text-xs text-white/50">SF10 (Permanent Record)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-400 text-xs">•</span>
                            <span class="text-xs text-white/50">Certificate of Good Moral (optional)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-400 text-xs">•</span>
                            <span class="text-xs text-white/50">PSA Birth Certificate</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-400 text-xs">•</span>
                            <span class="text-xs text-white/50">2x2 ID Portrait (2 pieces)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- College Section -->
            <div class="p-6 rounded-xl border border-blue-500/20 bg-blue-500/5 space-y-4">
                <h4 class="text-xs font-black text-blue-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                    College
                </h4>

                <div class="space-y-3">
                    <p class="text-xs font-semibold text-white/70">Eligibility:</p>
                    <ul class="space-y-2 ml-2">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-blue-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Senior High graduates or equivalent</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-blue-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Select from 5 college programs</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-0.5 h-0.5 rounded-full bg-blue-400/50 flex-shrink-0"></span>
                            <span class="text-xs text-white/50">Valid tertiary entry documentation</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-3 pt-2 border-t border-blue-500/10">
                    <p class="text-xs font-semibold text-white/70">Required Documents:</p>
                    <ul class="space-y-2 ml-2">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 text-xs">•</span>
                            <span class="text-xs text-white/50">Form 137 (SHS Report Card)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 text-xs">•</span>
                            <span class="text-xs text-white/50">Certificate of Good Moral</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 text-xs">•</span>
                            <span class="text-xs text-white/50">PSA Birth Certificate</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 text-xs">•</span>
                            <span class="text-xs text-white/50">2x2 ID Portrait (2 pieces)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 text-xs">•</span>
                            <span class="text-xs text-white/50">Valid ID or government-issued document</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="relative z-10">
        @if($canEnroll && !$isEnrolledInActiveYear)
        <a href="{{ route('student.enrollment.create') }}" class="inline-block bg-[#10B981] hover:bg-[#34d399] text-black font-black py-4 px-10 rounded-xl shadow-xl shadow-emerald-500/20 transition-all active:scale-95 uppercase tracking-widest text-xs">Apply Now</a>
        @else
        <button disabled class="inline-block bg-white/5 text-white/20 border border-white/10 font-bold py-4 px-10 rounded-xl cursor-not-allowed uppercase tracking-widest text-xs italic">Application Already Submitted</button>
        @endif
        </div>
    </div>
</div>
</x-layouts.student>

@elseif($role === 'cashier')
<div class="space-y-6 animate-in fade-in duration-700" wire:poll.3s>

    {{-- SECTION 1 — Header Node --}}
    <div class="p-8 rounded-2xl border relative overflow-hidden group shadow-2xl shadow-black/40"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

        <div class="absolute top-0 right-0 p-12 opacity-5 mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
            <svg class="w-64 h-64 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <div class="flex justify-between items-center relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-lg shadow-emerald-500/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white leading-none tracking-tight">Financial Overview</h2>
                    <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Daily Revenue & Collection Metrics</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2 — Core Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Collected Today --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(16,185,129,0.04); border-color: rgba(16,185,129,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-emerald-400/80 uppercase tracking-[0.2em] mb-1">Collected Today</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        ₱{{ number_format($stats['daily_collection'], 2) }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full w-[65%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Confirmed Revenue</p>
        </div>

        {{-- Transaction Count --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(99,179,237,0.04); border-color: rgba(99,179,237,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-blue-400/80 uppercase tracking-[0.2em] mb-1">Receipts Today</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        {{ $stats['transactions_today'] }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full w-[45%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Daily Transaction Volume</p>
        </div>

        {{-- Pending Verifications --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(251,191,36,0.04); border-color: rgba(251,191,36,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-amber-400/80 uppercase tracking-[0.2em] mb-1">Verification Queue</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        {{ $stats['pending_verifications'] }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400 group-hover:rotate-12 transition-transform shadow-lg shadow-amber-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full w-[30%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Pending Financial Audit</p>
        </div>
    </div>

    {{-- SECTION 3 — Transaction Logs --}}
    <div class="p-6 rounded-2xl border shadow-2xl shadow-black/40 overflow-hidden"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

        <div class="flex items-center gap-3 mb-6 px-4">
            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
            <div>
                <h3 class="font-bold text-white text-base">Today's Collections</h3>
                <p class="text-xs font-bold text-white/20 uppercase tracking-widest">{{ now()->format('M d, Y') }} — Transaction Record</p>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="text-xs text-white/20 uppercase tracking-[0.2em] border-b border-white/5">
                    <tr>
                        <th class="py-4 px-6 font-bold">Receipt #</th>
                        <th class="py-4 px-6 font-bold">Student Name</th>
                        <th class="py-4 px-6 font-bold">Time</th>
                        <th class="py-4 px-6 text-right font-bold">Amount</th>
                        <th class="py-4 px-6 text-center font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($paymentsToday as $payment)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="py-5 px-6 font-mono text-xs text-white/30 italic group-hover:text-emerald-400 transition-colors">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-6 font-bold text-white uppercase tracking-tight">{{ optional($payment->user)->name }}</td>
                        <td class="py-5 px-6 text-xs text-white/40">{{ $payment->updated_at->format('H:i A') }}</td>
                        <td class="py-5 px-6 text-right font-black text-emerald-400">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="py-5 px-6 text-center">
                            @php
                                $statusStyle = match($payment->status) {
                                    'Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'Rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    default => 'bg-white/5 text-white/40 border-white/10'
                                };
                            @endphp
                            <span class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
                                {{ $payment->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic leading-loose">No activity detected for today.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-6 rounded-2xl border shadow-2xl shadow-black/40 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700"
         style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.05);">
        <div class="flex items-center gap-3 mb-6 px-4">
            <div class="w-1.5 h-6 bg-white/20 rounded-full"></div>
            <h3 class="font-bold text-white/40 text-xs uppercase tracking-widest">Archived Records: {{ now()->subDay()->format('M d, Y') }}</h3>
        </div>
        @if($paymentsYesterday->isEmpty())
            <div class="py-8 text-center text-xs font-bold text-white/5 uppercase tracking-widest italic">No archival records found.</div>
        @else
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="text-xs text-white/20 uppercase tracking-[0.2em] border-b border-white/5">
                        <tr>
                            <th class="py-4 px-6 font-bold">Receipt #</th>
                            <th class="py-4 px-6 font-bold">Student Name</th>
                            <th class="py-4 px-6 font-bold">Time</th>
                            <th class="py-4 px-6 text-right font-bold">Amount</th>
                            <th class="py-4 px-6 text-center font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($paymentsYesterday as $payment)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-5 px-6 font-mono text-xs text-white/30 italic group-hover:text-emerald-400 transition-colors">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-6 font-bold text-white uppercase tracking-tight">{{ optional($payment->user)->name }}</td>
                            <td class="py-5 px-6 text-xs text-white/40">{{ $payment->updated_at->format('H:i A') }}</td>
                            <td class="py-5 px-6 text-right font-black text-emerald-400">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-5 px-6 text-center">
                                @php
                                    $statusStyle = match($payment->status) {
                                        'Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'Rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                        default => 'bg-white/5 text-white/40 border-white/10'
                                    };
                                @endphp
                                <span class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>


@elseif($role === 'registrar')
<x-layouts.registrar title="Registrar Dashboard">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats & Calendar Section --}}
        <div class="p-6 rounded-2xl border"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl" style="background: rgba(99,179,237,0.15); color: #63b3ed;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white leading-none">Welcome, Registrar</h2>
                        <p class="text-xs mt-1 text-white/50 italic uppercase tracking-widest font-bold">Registry & Operations Summary</p>
                    </div>
                </div>
                <div class="px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest" style="background: rgba(99,179,237,0.15); border: 1px solid rgba(99,179,237,0.3); color: #63b3ed;">{{ $weekRange }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                @foreach ($weekDates as $day)
                    <div class="rounded-xl flex flex-col h-[380px]"
                         style="{{ $day['is_today'] ? 'border: 1px solid rgba(99,179,237,0.5); background: rgba(99,179,237,0.08); shadow: 0 0 20px rgba(99,179,237,0.15);' : 'border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.03);' }}">
                        <div class="text-center py-3 rounded-t-xl" style="{{ $day['is_today'] ? 'border-bottom: 1px solid rgba(99,179,237,0.3); background: rgba(99,179,237,0.12);' : 'border-bottom: 1px solid rgba(255,255,255,0.07);' }}">
                            <p class="text-[10px] font-black uppercase tracking-widest" style="color: {{ $day['is_today'] ? '#63b3ed' : 'rgba(255,255,255,0.35)' }};">{{ $day['day_name'] }}</p>
                            <p class="text-2xl font-black mt-0.5" style="color: {{ $day['is_today'] ? '#90cdf4' : 'rgba(255,255,255,0.8)' }};">{{ $day['day_num'] }}</p>
                            @if($day['is_today']) <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter bg-blue-400 text-black">Today</span> @endif
                        </div>
                        <div class="p-2.5 flex-1 overflow-y-auto space-y-2 custom-scrollbar">
                            @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp
                            @if ($dayApps->isEmpty())
                                <div class="h-full flex items-center justify-center text-[8px] uppercase tracking-[0.2em] font-black text-white/10 italic">No Data</div>
                            @else
                                @foreach ($dayApps as $app)
                                    @php
                                        $style = match(ucfirst($app->status)) {
                                            'Pending' => [
                                                'bg' => 'rgba(251,191,36,0.08)',
                                                'border' => '1px solid rgba(251,191,36,0.2)',
                                                'text' => '#fcd34d',
                                                'dot' => '#fbbf24'
                                            ],
                                            'Approved','Enrolled','Paid' => [
                                                'bg' => 'rgba(34,211,238,0.08)',
                                                'border' => '1px solid rgba(34,211,238,0.2)',
                                                'text' => '#67e8f9',
                                                'dot' => '#22d3ee'
                                            ],
                                            default => [
                                                'bg' => 'rgba(255,255,255,0.04)',
                                                'border' => '1px solid rgba(255,255,255,0.1)',
                                                'text' => 'rgba(255,255,255,0.4)',
                                                'dot' => 'rgba(255,255,255,0.2)'
                                            ],
                                        };
                                    @endphp
                                    <a href="{{ route('registrar.dashboard', ['app_id' => $app->id]) }}" class="p-2.5 rounded-lg border flex flex-col gap-1.5 transition-all hover:scale-105 active:scale-95" style="background: {{ $style['bg'] }}; border-color: {{ $style['border'] }};">
                                        <p class="text-[10px] font-bold text-white truncate uppercase tracking-tight">{{ $app->user->name ?? 'Student' }}</p>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1 h-1 rounded-full" style="background: {{ $style['dot'] }};"></span>
                                            <span class="text-[8px] font-black uppercase tracking-widest italic" style="color: {{ $style['text'] }};">{{ in_array($app->status, ['Enrolled', 'Paid']) ? 'Paid' : $app->status }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Enrollment Overview Cards (SHS vs College) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- SHS Card -->
            <div class="p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">
                <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Senior High</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $shs_count }}</h3>
                <p class="text-[10px] text-emerald-400/50 mt-4">Total Applicants</p>
            </div>
            <!-- College Card -->
            <div class="p-6 rounded-2xl bg-blue-500/10 border border-blue-500/20">
                <p class="text-xs font-bold text-blue-400 uppercase tracking-widest">College</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $college_count }}</h3>
                <p class="text-[10px] text-blue-400/50 mt-4">Total Applicants</p>
            </div>
            <!-- Consolidated Card -->
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Consolidated</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $total_count }}</h3>
                <p class="text-[10px] text-white/20 mt-4">Overall Enrollment</p>
            </div>
        </div>

        {{-- Overview Stats --}}
        <div class="p-6 rounded-2xl border bg-white/5 backdrop-blur-md border-white/10 shadow-2xl">
            <h3 class="font-black text-white mb-5 flex items-center gap-2 italic uppercase tracking-[0.2em] text-xs">
                <span class="w-1 h-4 rounded-full bg-cyan-500"></span> Overview
            </h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-{{ count($stats) }} gap-4">
                @foreach($stats as $key => $val)
                    <div class="p-4 rounded-xl border border-white/5 bg-white/[0.02] text-center shadow-inner group">
                        <div class="text-[8px] font-black uppercase tracking-[0.3em] mb-2 text-white/30 group-hover:text-cyan-400 transition-colors">{{ str_replace('_', ' ', $key) }}</div>
                        <div class="text-3xl font-black text-white tracking-tighter">{{ $val }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Modal --}}
        @if ($selectedApp)
        <a href="{{ route('registrar.dashboard') }}" class="fixed inset-0 bg-[#060d1a]/85 backdrop-blur-sm z-[90] cursor-default transition-opacity duration-300"></a>
        <div class="fixed inset-0 z-[100] p-4 flex items-center justify-center transition-all duration-500">
            <div class="rounded-[40px] shadow-2xl w-full max-w-5xl relative z-10 border overflow-hidden transform transition-all bg-[#0d1f3c] border-white/10 animate-in zoom-in-95 duration-500">

                {{-- Modal Header --}}
                <div class="px-10 py-8 border-b border-white/5 flex justify-between items-center bg-white/5">
                    <div class="flex items-center gap-6">
                        <span class="bg-cyan-500/20 text-cyan-400 font-mono text-xs font-black px-4 py-2 rounded-xl border border-cyan-500/30 italic">#{{ str_pad($selectedApp->id, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="text-2xl font-black text-white uppercase tracking-[0.15em]">Application Details</h3>
                    </div>
                    <a href="{{ route('registrar.dashboard') }}" class="text-white/20 hover:text-white transition p-3 rounded-2xl hover:bg-white/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                </div>

                <div class="overflow-y-auto max-h-[80vh] custom-scrollbar">
                    <div class="p-10 grid grid-cols-1 lg:grid-cols-12 gap-10">

                        {{-- Left Column: Program Choice --}}
                        <div class="lg:col-span-4 space-y-6">
                            <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 space-y-8 shadow-inner">
                                <div class="space-y-4">
                                    <span class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.4em] block">Program Choice</span>
                                    <h2 class="text-6xl font-black text-white tracking-tighter uppercase leading-none">{{ $selectedApp->course_code }}</h2>
                                    <p class="text-xs font-bold text-white/40 uppercase tracking-[0.2em] leading-relaxed">{{ $selectedApp->year_level }}</p>
                                </div>

                                <div class="pt-8 border-t border-white/5 space-y-4">
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.4em] block">Current Status</span>
                                    @php
                                        $statusBase = ucfirst($selectedApp->status);
                                        $statusLabel = in_array($statusBase, ['Enrolled', 'Paid']) ? 'PAID' : strtoupper($statusBase);
                                        $statusClass = match ($statusBase) {
                                            'Approved','Enrolled','Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            default => 'bg-white/5 text-white/40 border-white/10',
                                        };
                                    @endphp
                                    <div class="inline-block px-6 py-2 rounded-full border text-[10px] font-black tracking-[0.2em] {{ $statusClass }} shadow-lg">{{ $statusLabel }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Main Content --}}
                        <div class="lg:col-span-8 space-y-12">

                            {{-- Biographical Data Section --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <h4 class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em]">Biographical Data</h4>
                                </div>
                                <div class="p-8 rounded-[32px] bg-white/[0.01] border border-white/5 grid grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Given Name</span>
                                        <span class="text-2xl font-black text-white uppercase tracking-tight">{{ $selectedApp->first_name }}</span>
                                    </div>
                                    <div class="space-y-2">
                                        <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Surname</span>
                                        <span class="text-2xl font-black text-white uppercase tracking-tight">{{ $selectedApp->last_name }}</span>
                                    </div>
                                    <div class="col-span-2 space-y-2">
                                        <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Residential Address</span>
                                        <span class="text-xs font-bold text-white/60 uppercase tracking-tight leading-relaxed">{{ $selectedApp->address_full ?? '— Not Provided —' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Supporting Documentation Section --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <h4 class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em]">Supporting Documentation</h4>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @php
                                        $docs = ($selectedApp->level === 'shs') ? [
                                            'Form 137' => $selectedApp->form_137_path ?? null,
                                            'SF10' => $selectedApp->sf10_path ?? null,
                                            'Good Moral' => $selectedApp->good_moral_path ?? null,
                                            'PSA Birth' => $selectedApp->psa_path ?? null,
                                            'ID Photo' => $selectedApp->id_picture_path ?? null,
                                        ] : [
                                            'Form 137' => $selectedApp->form_137_path ?? null,
                                            'Good Moral' => $selectedApp->good_moral_path ?? null,
                                            'PSA Birth' => $selectedApp->psa_path ?? null,
                                            'ID Photo' => $selectedApp->id_picture_path ?? null,
                                        ];
                                    @endphp
                                    @foreach($docs as $label => $file)
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2 mb-1 pl-1">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $file ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">{{ $label }}</span>
                                            </div>
                                            <div class="aspect-[3/4] rounded-2xl border bg-white/[0.02] border-white/5 flex flex-col items-center justify-center gap-3 group border-dashed relative overflow-hidden">
                                                @if($file)
                                                    <img src="/storage/{{ $file }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <span class="text-[8px] font-black text-white uppercase tracking-widest">View File</span>
                                                    </div>
                                                @else
                                                    <svg class="w-8 h-8 text-white/5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    <span class="text-[8px] font-black text-white/10 uppercase tracking-[0.2em]">Missing</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-white/5 px-10 py-10 border-t border-white/5 flex justify-between items-center bg-[#070f1d]">
                        <div class="flex flex-col gap-1 pl-2">
                            <span class="text-[8px] font-black text-white/20 uppercase tracking-[0.3em]">Status: {{ strtoupper($selectedApp->status) }}</span>
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest italic decoration-emerald-500/30 underline decoration-2 underline-offset-8">Confirmed Submission</span>
                        </div>

                        <div class="flex items-center gap-4">
                            @if(in_array(strtolower($selectedApp->status), ['pending', 'enrolled', 'paid']))
                                <form action="{{ route('registrar.dashboard.reject', $selectedApp->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-white/5 hover:bg-rose-500/10 text-white/30 hover:text-rose-500 px-8 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] border border-white/5 hover:border-rose-500/20 active:scale-95">Deny Entry</button>
                                </form>
                                <form action="{{ route('registrar.dashboard.approve', $selectedApp->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-400 text-black px-12 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] shadow-2xl shadow-cyan-500/40 active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Grant Approval
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('registrar.dashboard') }}" class="bg-white/5 hover:bg-white/10 text-white/40 hover:text-white px-10 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] border border-white/5 italic">Close Panel</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.registrar>

@elseif($role === 'admin')
<div class="space-y-6">
    {{-- Stats & Calendar Section --}}
    <div class="p-6 rounded-2xl border"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl" style="background: rgba(99,179,237,0.15); color: #63b3ed;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white leading-none">Welcome, {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Registrar' }}</h2>
                    <p class="text-xs mt-1 text-white/50 italic uppercase tracking-widest font-bold">Registry & Operations Summary</p>
                </div>
            </div>
            <div class="px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest" style="background: rgba(99,179,237,0.15); border: 1px solid rgba(99,179,237,0.3); color: #63b3ed;">{{ $weekRange }}</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @foreach ($weekDates as $day)
                <div class="rounded-xl flex flex-col h-[380px]"
                     style="{{ $day['is_today'] ? 'border: 1px solid rgba(99,179,237,0.5); background: rgba(99,179,237,0.08); shadow: 0 0 20px rgba(99,179,237,0.15);' : 'border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.03);' }}">
                    <div class="text-center py-3 rounded-t-xl" style="{{ $day['is_today'] ? 'border-bottom: 1px solid rgba(99,179,237,0.3); background: rgba(99,179,237,0.12);' : 'border-bottom: 1px solid rgba(255,255,255,0.07);' }}">
                        <p class="text-[10px] font-black uppercase tracking-widest" style="color: {{ $day['is_today'] ? '#63b3ed' : 'rgba(255,255,255,0.35)' }};">{{ $day['day_name'] }}</p>
                        <p class="text-2xl font-black mt-0.5" style="color: {{ $day['is_today'] ? '#90cdf4' : 'rgba(255,255,255,0.8)' }};">{{ $day['day_num'] }}</p>
                        @if($day['is_today']) <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter bg-blue-400 text-black">Today</span> @endif
                    </div>
                    <div class="p-2.5 flex-1 overflow-y-auto space-y-2 custom-scrollbar">
                        @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp
                        @if ($dayApps->isEmpty())
                            <div class="h-full flex items-center justify-center text-[8px] uppercase tracking-[0.2em] font-black text-white/10 italic">No Data</div>
                        @else
                            @foreach ($dayApps as $app)
                                @php
                                    $style = match(ucfirst($app->status)) {
                                        'Pending' => [
                                            'bg' => 'rgba(251,191,36,0.08)',
                                            'border' => '1px solid rgba(251,191,36,0.2)',
                                            'text' => '#fcd34d',
                                            'dot' => '#fbbf24'
                                        ],
                                        'Approved','Enrolled' => [
                                            'bg' => 'rgba(34,211,238,0.08)',
                                            'border' => '1px solid rgba(34,211,238,0.2)',
                                            'text' => '#67e8f9',
                                            'dot' => '#22d3ee'
                                        ],
                                        default => [
                                            'bg' => 'rgba(255,255,255,0.04)',
                                            'border' => '1px solid rgba(255,255,255,0.1)',
                                            'text' => 'rgba(255,255,255,0.4)',
                                            'dot' => 'rgba(255,255,255,0.2)'
                                        ],
                                    };
                                @endphp
                                <button type="button" wire:click="viewApplication({{ $app->id }})" class="p-2.5 rounded-lg border flex flex-col gap-1.5 transition-all hover:scale-105 active:scale-95 text-left w-full" style="background: {{ $style['bg'] }}; border-color: {{ $style['border'] }};">
                                    <p class="text-[10px] font-bold text-white truncate uppercase tracking-tight">{{ $app->user->name ?? 'Student' }}</p>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1 h-1 rounded-full" style="background: {{ $style['dot'] }};"></span>
                                        <span class="text-[8px] font-black uppercase tracking-widest italic" style="color: {{ $style['text'] }};">{{ $app->status === 'Enrolled' ? 'Paid' : $app->status }}</span>
                                    </div>
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Enrollment Overview Cards (SHS vs College) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- SHS Card -->
        <div class="p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">
            <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Senior High</p>
            <h3 class="text-3xl font-black text-white mt-1">{{ $shs_count }}</h3>
            <p class="text-[10px] text-emerald-400/50 mt-4">Total Applicants</p>
        </div>
        <!-- College Card -->
        <div class="p-6 rounded-2xl bg-blue-500/10 border border-blue-500/20">
            <p class="text-xs font-bold text-blue-400 uppercase tracking-widest">College</p>
            <h3 class="text-3xl font-black text-white mt-1">{{ $college_count }}</h3>
            <p class="text-[10px] text-blue-400/50 mt-4">Total Applicants</p>
        </div>
        <!-- Consolidated Card -->
        <div class="p-6 rounded-2xl bg-white/5 border border-white/10">
            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Consolidated</p>
            <h3 class="text-3xl font-black text-white mt-1">{{ $total_count }}</h3>
            <p class="text-[10px] text-white/20 mt-4">Overall Enrollment</p>
        </div>
    </div>

    {{-- Overview Stats --}}
    <div class="p-6 rounded-2xl border bg-white/5 backdrop-blur-md border-white/10 shadow-2xl">
        <h3 class="font-black text-white mb-5 flex items-center gap-2 italic uppercase tracking-[0.2em] text-xs">
            <span class="w-1 h-4 rounded-full bg-cyan-500"></span> Overview
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-{{ count($stats) }} gap-4">
            @foreach($stats as $key => $val)
                <div class="p-4 rounded-xl border border-white/5 bg-white/[0.02] text-center shadow-inner group">
                    <div class="text-[8px] font-black uppercase tracking-[0.3em] mb-2 text-white/30 group-hover:text-cyan-400 transition-colors">{{ str_replace('_', ' ', $key) }}</div>
                    <div class="text-3xl font-black text-white tracking-tighter">{{ $val }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Modal Container (Admin — uses Livewire state) --}}
    <div x-data="{ open: @entangle('showModal') }" 
         x-show="open" 
         class="fixed inset-0 z-[100] p-4 flex items-center justify-center"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#060d1a]/85 backdrop-blur-sm cursor-pointer" wire:click="closeModal"></div>

        {{-- Modal Box --}}
        <div class="rounded-3xl shadow-2xl w-full max-w-4xl relative z-10 border overflow-hidden transform transition-all bg-[#0d1f3c] border-white/10"
             :class="open ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
            @if ($selectedApp)
                <div class="px-10 py-8 border-b border-white/5 flex justify-between items-center bg-white/5">
                    <div class="flex items-center gap-6">
                        <span class="bg-cyan-500/20 text-cyan-400 font-mono text-xs font-black px-4 py-2 rounded-xl border border-cyan-500/30 italic">#{{ str_pad($selectedApp->id, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="text-2xl font-black text-white uppercase tracking-[0.15em]">Application Details</h3>
                    </div>
                    <button wire:click="closeModal" class="text-white/20 hover:text-white transition p-3 rounded-2xl hover:bg-white/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                    <div class="overflow-y-auto max-h-[80vh] custom-scrollbar">
                        <div class="p-10 grid grid-cols-1 lg:grid-cols-12 gap-10">

                            {{-- Left Column: Program Choice --}}
                            <div class="lg:col-span-4 space-y-6">
                                <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 space-y-8 shadow-inner">
                                    <div class="space-y-4">
                                        <span class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.4em] block">Program Choice</span>
                                        <h2 class="text-6xl font-black text-white tracking-tighter uppercase leading-none">{{ $selectedApp->course_code }}</h2>
                                        <p class="text-xs font-bold text-white/40 uppercase tracking-[0.2em] leading-relaxed">{{ $selectedApp->year_level }}</p>
                                    </div>

                                    <div class="pt-8 border-t border-white/5 space-y-4">
                                        <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.4em] block">Current Status</span>
                                        @php
                                            $statusBase = ucfirst($selectedApp->status);
                                            $statusLabel = in_array($statusBase, ['Enrolled', 'Paid']) ? 'PAID' : strtoupper($statusBase);
                                            $statusClass = match ($statusBase) {
                                                'Approved','Enrolled','Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                default => 'bg-white/5 text-white/40 border-white/10',
                                            };
                                        @endphp
                                        <div class="inline-block px-6 py-2 rounded-full border text-[10px] font-black tracking-[0.2em] {{ $statusClass }} shadow-lg">{{ $statusLabel }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Main Content --}}
                            <div class="lg:col-span-8 space-y-12">

                                {{-- Biographical Data Section --}}
                                <div class="space-y-6">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <h4 class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em]">Biographical Data</h4>
                                    </div>
                                    <div class="p-8 rounded-[32px] bg-white/[0.01] border border-white/5 grid grid-cols-2 gap-8">
                                        <div class="space-y-2">
                                            <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Given Name</span>
                                            <span class="text-2xl font-black text-white uppercase tracking-tight">{{ $selectedApp->first_name }}</span>
                                        </div>
                                        <div class="space-y-2">
                                            <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Surname</span>
                                            <span class="text-2xl font-black text-white uppercase tracking-tight">{{ $selectedApp->last_name }}</span>
                                        </div>
                                        <div class="col-span-2 space-y-2">
                                            <span class="text-[8px] font-black text-white/20 uppercase tracking-widest block">Residential Address</span>
                                            <span class="text-xs font-bold text-white/60 uppercase tracking-tight leading-relaxed">{{ $selectedApp->address_full ?? '— Not Provided —' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Supporting Documentation Section --}}
                                <div class="space-y-6">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <h4 class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em]">Supporting Documentation</h4>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @php
                                            $docs = ($selectedApp->level === 'shs') ? [
                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                'SF10' => $selectedApp->sf10_path ?? null,
                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                            ] : [
                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                            ];
                                        @endphp
                                        @foreach($docs as $label => $file)
                                            <div class="space-y-3">
                                                <div class="flex items-center gap-2 mb-1 pl-1">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $file ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                    <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">{{ $label }}</span>
                                                </div>
                                                <div class="aspect-[3/4] rounded-2xl border bg-white/[0.02] border-white/5 flex flex-col items-center justify-center gap-3 group border-dashed relative overflow-hidden">
                                                    @if($file)
                                                        <img src="/storage/{{ $file }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <span class="text-[8px] font-black text-white uppercase tracking-widest">View File</span>
                                                        </div>
                                                    @else
                                                        <svg class="w-8 h-8 text-white/5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        <span class="text-[8px] font-black text-white/10 uppercase tracking-[0.2em]">Missing</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Promissory Note Asset --}}
                                @if($selectedApp->promissory_note_path || $selectedApp->promissory_reason)
                                <div class="space-y-6 pt-10 border-t border-white/5">
                                    <div class="flex items-center gap-3">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <h4 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Promissory Note & Reason</h4>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-amber-500/5 border border-amber-500/10 rounded-[32px] p-8">
                                        <div class="lg:col-span-1 space-y-4">
                                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Note Attachment</span>
                                            @if($selectedApp->promissory_note_path)
                                                @php
                                                    $isPdf = Str::endsWith($selectedApp->promissory_note_path, '.pdf');
                                                @endphp
                                                <a href="{{ \Storage::disk('public')->url($selectedApp->promissory_note_path) }}" target="_blank" class="flex items-center gap-4 p-5 rounded-2xl border border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10 transition-all">
                                                    <div class="w-11 h-11 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-black text-white uppercase tracking-wider">Download Note</p>
                                                        <p class="text-[8px] text-amber-500/60 uppercase font-bold mt-0.5">{{ $isPdf ? 'PDF Format' : 'Word Doc' }}</p>
                                                    </div>
                                                </a>
                                            @else
                                                <div class="p-5 rounded-2xl border border-dashed border-white/5 bg-white/[0.01] flex items-center justify-center opacity-30">
                                                    <span class="text-[8px] font-black text-white uppercase tracking-widest italic">No File Provided</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="lg:col-span-2 space-y-3">
                                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Student's Explanation</span>
                                            <div class="p-8 rounded-3xl bg-white/[0.02] border border-white/5 min-h-[100px]">
                                                <p class="text-xs text-white/60 leading-relaxed italic">
                                                    {{ $selectedApp->promissory_reason ?? 'No explanation provided.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="bg-white/5 px-10 py-10 border-t border-white/5 flex justify-between items-center bg-[#070f1d]">
                            <div class="flex flex-col gap-1 pl-2">
                                <span class="text-[8px] font-black text-white/20 uppercase tracking-[0.3em]">Status: {{ strtoupper($selectedApp->status) }}</span>
                                <span class="text-[10px] font-black text-white/40 uppercase tracking-widest italic decoration-emerald-500/30 underline decoration-2 underline-offset-8">Confirmed Submission</span>
                            </div>

                            <div class="flex items-center gap-4">
                                @if(in_array(strtolower($selectedApp->status), ['pending', 'enrolled', 'paid']))
                                    <button wire:click="rejectApplication({{ $selectedApp->id }})" class="bg-white/5 hover:bg-rose-500/10 text-white/30 hover:text-rose-500 px-8 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] border border-white/5 hover:border-rose-500/20 active:scale-95">Deny Entry</button>
                                    <button wire:click="approveApplication({{ $selectedApp->id }})" class="bg-cyan-500 hover:bg-cyan-400 text-black px-12 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] shadow-2xl shadow-cyan-500/40 active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Grant Approval
                                    </button>
                                @endif
                                <button wire:click="closeModal" class="bg-white/5 hover:bg-white/10 text-white/40 hover:text-white px-10 py-4 rounded-[20px] text-[10px] font-black transition-all uppercase tracking-[0.2em] border border-white/5 italic">Close Panel</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

@endif
