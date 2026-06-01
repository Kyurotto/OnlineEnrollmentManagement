<x-layouts.student title="Student Dashboard">
    <div class="w-full">
        <div class="space-y-6">
            @if (session('success'))
                <div
                    class="bg-[#3b82f6]/10 border border-[#3b82f6]/20 text-[#3b82f6] px-4 py-3 rounded-xl relative font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl relative font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @php
                // Use enrollment for CURRENT academic year only
                $currentStatus = $currentYearEnrollment ? $currentYearEnrollment->status : 'Not Enrolled';

                // Check if student can enroll in the CURRENT ACTIVE semester
                $currentAcademicYear = $activeYear;
                $currentSemester = $activeSemester;
                if ($currentAcademicYear && $currentSemester && $currentYearEnrollment) {
                    $isEnrollmentForCurrentTerm =
                        stripos($currentYearEnrollment->year_level, $currentAcademicYear->year_name) !== false &&
                        stripos($currentYearEnrollment->year_level, $currentSemester->name) !== false;
                    $canEnroll =
                        (!in_array($currentStatus, ['Pending', 'Enrolled', 'Approved', 'Paid']) ||
                            !$isEnrollmentForCurrentTerm) &&
                        $canEnrollNow &&
                        !$hasSubmitted;
                } else {
                    $canEnroll =
                        !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved', 'Paid']) &&
                        $canEnrollNow &&
                        !$hasSubmitted;
                }
            @endphp

            {{-- Welcome Header --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    </svg>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-8 relative z-10">
                    <div class="flex items-center gap-8">
                        <div
                            class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Welcome,
                                <span class="text-blue-600">{{ Auth::user()->first_name }}</span>!
                            </h2>
                            <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Portal
                                Access — Core Infrastructure</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Enrollment Status</p>
                            {{-- Row 1: Status + Level --}}
                            <div class="flex items-center justify-end gap-3 flex-wrap">
                                @if ($currentStatus === 'Enrolled')
                                    <span
                                        class="bg-blue-600 text-white px-5 py-2 rounded-full font-black text-xs border border-blue-700 tracking-widest uppercase shadow-lg shadow-blue-600/20">ENROLLED</span>
                                @elseif($currentStatus === 'Pending' || $currentStatus === 'Approved' || $currentStatus === 'Paid')
                                    <span
                                        class="bg-blue-50 text-blue-600 px-5 py-2 rounded-full font-black text-xs border border-blue-100 tracking-widest uppercase shadow-sm">PROCESSING</span>
                                @elseif($currentStatus === 'Rejected')
                                    <span
                                        class="bg-slate-50 text-slate-400 px-5 py-2 rounded-full font-black text-xs border border-slate-200 tracking-widest uppercase shadow-sm">REJECTED</span>
                                @else
                                    <span
                                        class="bg-white text-slate-300 px-5 py-2 rounded-full font-black text-xs border border-slate-100 tracking-widest uppercase">NOT
                                        ENROLLED</span>
                                @endif
                                @if (isset($currentYearEnrollment) && $currentYearEnrollment)
                                    @php
                                        $level = strtolower($currentYearEnrollment->level);
                                        $bgClass =
                                            $level === 'college'
                                            ? 'bg-blue-50'
                                            : ($level === 'shs'
                                                ? 'bg-emerald-50'
                                                : 'bg-slate-50');
                                        $textClass =
                                            $level === 'college'
                                            ? 'text-blue-600'
                                            : ($level === 'shs'
                                                ? 'text-emerald-600'
                                                : 'text-slate-600');
                                        $borderClass =
                                            $level === 'college'
                                            ? 'border-blue-100'
                                            : ($level === 'shs'
                                                ? 'border-emerald-100'
                                                : 'border-slate-200');
                                    @endphp
                                    <span
                                        class="text-xs font-black uppercase tracking-widest {{ $bgClass }} {{ $textClass }} px-3 py-2 rounded-full border {{ $borderClass }} shadow-sm">
                                        Level: {{ ucfirst($currentYearEnrollment->level) }}
                                    </span>
                                    @php
                                        $stype =
                                            $currentYearEnrollment->student_type ??
                                            ($isOldStudent ? 'returnee' : 'new');
                                        $stypeLabel = match (strtolower($stype)) {
                                            'new' => 'New Student',
                                            'returnee' => 'Returning Student',
                                            'transferee' => 'Transferee',
                                            'shifter' => 'Shifter',
                                            default => ucfirst($stype),
                                        };
                                    @endphp
                                    <span
                                        class="text-xs font-black uppercase tracking-widest bg-blue-600 text-white px-3 py-2 rounded-full border border-blue-700 shadow-md">
                                        {{ $stypeLabel }}
                                    </span>
                                @endif
                            </div>
                            {{-- Row 2: Academic Classification centered --}}
                            @if (isset($currentYearEnrollment) && $currentYearEnrollment)
                                <div class="flex justify-end mt-3">
                                    @if ($currentYearEnrollment->is_regular === null)
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-400 border border-slate-200 px-4 py-1.5 rounded-full">
                                            Not Audited
                                        </span>
                                    @elseif($currentYearEnrollment->is_regular)
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-200 px-4 py-1.5 rounded-full shadow-sm">
                                            ✓ Regular Student
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-200 px-4 py-1.5 rounded-full shadow-sm"
                                            title="{{ $currentYearEnrollment->classification_reason ?? '' }}">
                                            Irregular Student
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <livewire:enrollment-progress-bar />
            </div>

            {{-- Enrollment Open Alert --}}
            @if ($activeSemester && $activeYear && !$hasSubmitted)
                <div class="p-8 rounded-[1.5rem] border flex items-center gap-6 {{ $hasSubmitted ? 'bg-slate-50' : 'bg-blue-50/50' }}"
                    style="border-color: rgba(37,99,235,0.1); box-shadow: 0 4px 20px rgba(37,99,235,0.05);">
                    <div
                        class="text-blue-600 p-4 rounded-2xl bg-blue-100 flex-shrink-0 {{ $hasSubmitted ? 'opacity-40 grayscale' : 'animate-pulse' }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-blue-900 font-black text-xl mb-1 {{ $hasSubmitted ? 'text-slate-400' : '' }}">
                            {{ $hasSubmitted ? 'Application Already Submitted' : 'Enrollment is Now Open!' }}
                        </h3>
                        <p class="text-slate-500 text-sm font-medium">
                            @if ($hasSubmitted)
                                You have already submitted your application for <strong
                                    class="text-slate-600">{{ $activeSemester->name }}</strong>, Academic Year
                                <strong class="text-slate-600">{{ $activeYear->year_name }}</strong>.
                            @elseif($canEnrollNow)
                                You can now submit your application for <strong
                                    class="text-blue-600">{{ $activeSemester->name }}</strong>, Academic Year
                                <strong class="text-blue-600">{{ $activeYear->year_name }}</strong>.
                            @else
                                The enrollment period is open, but you must first complete your <strong
                                    class="text-blue-600">Registrar Clearance</strong> (Steps 1-3) to proceed.
                            @endif
                        </p>
                    </div>
                    <div class="ml-auto hidden sm:block">
                        @if ($canEnrollNow && !$hasSubmitted)
                            <a href="{{ route('student.enrollment.create') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black px-8 py-4 rounded-xl uppercase tracking-widest transition-all active:scale-95 shadow-xl shadow-blue-600/20">Enroll
                                Now</a>
                        @else
                            {{-- Show disabled for old/new students when they cannot enroll or have already submitted --}}
                            <button disabled
                                class="bg-slate-100 text-slate-400 text-xs font-black px-8 py-4 rounded-xl uppercase tracking-widest cursor-not-allowed opacity-60 border border-slate-200">
                                Enroll Now
                            </button>
                        @endif
                    </div>
                </div>
            @elseif(!$activeSemester || !$activeYear)
                <div class="p-8 rounded-[1.5rem] border flex items-center gap-6 bg-slate-100/50"
                    style="border-color: rgba(0,0,0,0.05);">
                    <div class="text-slate-400 p-4 rounded-2xl bg-slate-200 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-slate-700 font-black text-xl mb-1">Enrollment Currently Closed</h3>
                        <p class="text-slate-400 text-sm font-medium">Please wait for the next enrollment period
                            announcement
                            from the registrar.</p>
                    </div>
                </div>
            @endif

            {{-- Action Matrix --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @if ($hasSubmitted)
                    {{-- Review Application Card --}}
                    @php
                        $isShellDashboard = $existingEnrollment && empty($existingEnrollment->course_code);
                    @endphp

                    @if ($isShellDashboard)
                        <div class="group block h-full opacity-50 cursor-not-allowed">
                    @else
                            <a href="{{ route('student.enrollment.review') }}" class="group block h-full">
                        @endif
                            <div class="p-8 rounded-[2rem] border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden bg-white shadow-xl shadow-blue-900/5"
                                style="border-color: rgba(37,99,235,0.1);">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">
                                            Application</h4>
                                        <div class="text-2xl font-black text-slate-900 tracking-tight">Review Application
                                        </div>
                                    </div>
                                    <div
                                        class="p-4 rounded-2xl bg-indigo-600 text-white group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/30">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">View and review your
                                    submitted
                                    enrollment application details.</p>
                            </div>
                            @if ($isShellDashboard)
                                </div>
                            @else
                        </a>
                    @endif
                @endif

                {{-- Enrollment Card --}}
                @if ($canEnroll && !$isEnrolledInActiveYear && !$hasSubmitted)
                    <div class="group block h-full">
                        <div class="p-8 rounded-[2rem] border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden bg-white shadow-xl shadow-blue-900/5"
                            style="border-color: rgba(37,99,235,0.1);">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">
                                        Enrollment</h4>
                                    <div class="text-2xl font-black text-slate-900 tracking-tight">Enrollment</div>
                                </div>
                                <div
                                    class="p-4 rounded-2xl bg-indigo-600 text-white group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/30">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Start a new enrollment or
                                track your
                                pending applications in real-time.</p>
                        </div>
                    </div>
                @else
                    {{-- Hide completely for all students after submission or during clearance --}}
                @endif

                {{-- Payments Card --}}
                <div class="group block h-full">
                    <div class="p-8 rounded-[2rem] border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden bg-white shadow-xl shadow-blue-900/5"
                        style="border-color: rgba(37,99,235,0.1);">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">
                                    Payments</h4>
                                <div class="text-2xl font-black text-slate-900 tracking-tight">Payments</div>
                            </div>
                            <div
                                class="p-4 rounded-2xl bg-indigo-600 text-white group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/30">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">View outstanding balances and
                            record
                            secure financial transactions.</p>
                    </div>
                </div>

                {{-- Profile Card --}}
                <div class="group block h-full">
                    <div class="p-8 rounded-[2rem] border h-full transition-all duration-500 hover:scale-[1.02] relative overflow-hidden bg-white shadow-xl shadow-blue-900/5"
                        style="border-color: rgba(37,99,235,0.1);">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">My
                                    Account</h4>
                                <div class="text-2xl font-black text-slate-900 tracking-tight">Student Profile</div>
                            </div>
                            <div
                                class="p-4 rounded-2xl bg-indigo-600 text-white group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/30">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Maintain your contact
                            information and
                            view academic history logs.</p>
                    </div>
                </div>
            </div>

            {{-- Requirements Section --}}
            <div class="p-10 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden relative bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                {{-- Background Logo --}}
                <div
                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none opacity-[0.03]">
                    <img src="{{ asset('image/logo.jfif') }}" alt="Logo" class="w-96 h-96 rounded-full object-cover">
                </div>

                <div class="relative z-10 flex items-center gap-4 mb-10">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-600/30"></div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Enrollment Requirements</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Application
                            Checklist — Academic Year</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10 relative z-10">
                    <div class="p-8 rounded-2xl border border-blue-100 bg-blue-50/30 space-y-6">
                        <h4
                            class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-indigo-600 shadow-lg shadow-indigo-600/50"></div>
                            Senior High School (SHS)
                        </h4>

                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Eligibility:</p>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Grade 11-12 students</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Select from Academic or Tech-Voc
                                        strands</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Valid JHS credentials
                                        required</span>
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-blue-100">
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Required
                                Documents:</p>
                            <ul class="grid grid-cols-1 gap-3">
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">SF9 (JHS
                                        Report
                                        Card)</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">SF10
                                        (Permanent
                                        Record)</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Certificate
                                        of Good
                                        Moral</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">2x2 ID
                                        Picture</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">PSA Birth
                                        Certificate</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="p-8 rounded-2xl border border-blue-100 bg-blue-50/30 space-y-6">
                        <h4 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-600 shadow-lg shadow-blue-600/50"></div>
                            College
                        </h4>

                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Eligibility:</p>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Senior High graduates or
                                        equivalent</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Select from 5 college
                                        programs</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <span class="text-xs text-slate-500 font-medium">Valid tertiary entry
                                        documentation</span>
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-blue-100">
                            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Required
                                Documents:</p>
                            <ul class="grid grid-cols-1 gap-3">
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Form 137
                                        (SHS Report
                                        Card)</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Certificate
                                        of Good
                                        Moral</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">PSA Birth
                                        Certificate</span>
                                </li>
                                <li
                                    class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                    <div
                                        class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">2x2 ID
                                        Picture</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
</x-layouts.student>