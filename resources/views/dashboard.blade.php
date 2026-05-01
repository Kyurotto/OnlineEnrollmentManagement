@php
    $role = auth()->user()->role ?? '';
@endphp

@if ($role === 'student')
    <x-layouts.student title="Student Dashboard">
        <div class="w-full">
            <div class="space-y-6">
                @if (session('success'))
                    <div
                        class="bg-[#3b82f6]/10 border border-[#3b82f6]/20 text-[#3b82f6] px-4 py-3 rounded-xl relative font-medium shadow-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
                            !$isEnrollmentForCurrentTerm) && $canEnrollNow && !$hasSubmitted;
                    } else {
                        $canEnroll = !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved', 'Paid']) && $canEnrollNow && !$hasSubmitted;
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
                                    <span class="text-blue-600">{{ Auth::user()->first_name }}</span>!</h2>
                                <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Portal Access — Core Infrastructure</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Enrollment Status</p>
                                {{-- Row 1: Status + Level --}}
                                <div class="flex items-center justify-end gap-3 flex-wrap">
                                    @if ($currentStatus === 'Enrolled')
                                        <span class="bg-blue-600 text-white px-5 py-2 rounded-full font-black text-xs border border-blue-700 tracking-widest uppercase shadow-lg shadow-blue-600/20">ENROLLED</span>
                                    @elseif($currentStatus === 'Pending' || $currentStatus === 'Approved' || $currentStatus === 'Paid')
                                        <span class="bg-blue-50 text-blue-600 px-5 py-2 rounded-full font-black text-xs border border-blue-100 tracking-widest uppercase shadow-sm">PROCESSING</span>
                                    @elseif($currentStatus === 'Rejected')
                                        <span class="bg-slate-50 text-slate-400 px-5 py-2 rounded-full font-black text-xs border border-slate-200 tracking-widest uppercase shadow-sm">REJECTED</span>
                                    @else
                                        <span class="bg-white text-slate-300 px-5 py-2 rounded-full font-black text-xs border border-slate-100 tracking-widest uppercase">NOT ENROLLED</span>
                                    @endif
                                    @if(isset($currentYearEnrollment) && $currentYearEnrollment)
                                        @php
                                            $level = strtolower($currentYearEnrollment->level);
                                            $bgClass = $level === 'college' ? 'bg-blue-50' : ($level === 'shs' ? 'bg-emerald-50' : 'bg-slate-50');
                                            $textClass = $level === 'college' ? 'text-blue-600' : ($level === 'shs' ? 'text-emerald-600' : 'text-slate-600');
                                            $borderClass = $level === 'college' ? 'border-blue-100' : ($level === 'shs' ? 'border-emerald-100' : 'border-slate-200');
                                        @endphp
                                        <span class="text-xs font-black uppercase tracking-widest {{ $bgClass }} {{ $textClass }} px-3 py-2 rounded-full border {{ $borderClass }} shadow-sm">
                                            Level: {{ ucfirst($currentYearEnrollment->level) }}
                                        </span>
                                        @php
                                            $stype = $currentYearEnrollment->student_type ?? ($isOldStudent ? 'returnee' : 'new');
                                            $stypeLabel = match(strtolower($stype)) {
                                                'new' => 'New Student',
                                                'returnee' => 'Returning Student',
                                                'transferee' => 'Transferee',
                                                'shifter' => 'Shifter',
                                                default => ucfirst($stype)
                                            };
                                        @endphp
                                        <span class="text-xs font-black uppercase tracking-widest bg-blue-600 text-white px-3 py-2 rounded-full border border-blue-700 shadow-md">
                                            {{ $stypeLabel }}
                                        </span>
                                    @endif
                                </div>
                                {{-- Row 2: Academic Classification centered --}}
                                @if(isset($currentYearEnrollment) && $currentYearEnrollment)
                                <div class="flex justify-end mt-3">
                                    @if($currentYearEnrollment->is_regular === null)
                                        <span class="text-[10px] font-black uppercase tracking-widest bg-slate-50 text-slate-400 border border-slate-200 px-4 py-1.5 rounded-full">
                                            Not Audited
                                        </span>
                                    @elseif($currentYearEnrollment->is_regular)
                                        <span class="text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-200 px-4 py-1.5 rounded-full shadow-sm">
                                            ✓ Regular Student
                                        </span>
                                    @else
                                        <span class="text-[10px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-200 px-4 py-1.5 rounded-full shadow-sm"
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
                        <div class="text-blue-600 p-4 rounded-2xl bg-blue-100 flex-shrink-0 {{ $hasSubmitted ? 'opacity-40 grayscale' : 'animate-pulse' }}">
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
                                @if($hasSubmitted)
                                    You have already submitted your application for <strong class="text-slate-600">{{ $activeSemester->name }}</strong>, Academic Year <strong class="text-slate-600">{{ $activeYear->year_name }}</strong>.
                                @elseif($canEnrollNow)
                                    You can now submit your application for <strong class="text-blue-600">{{ $activeSemester->name }}</strong>, Academic Year <strong class="text-blue-600">{{ $activeYear->year_name }}</strong>.
                                @else
                                    The enrollment period is open, but you must first complete your <strong class="text-blue-600">Registrar Clearance</strong> (Steps 1-3) to proceed.
                                @endif
                            </p>
                        </div>
                        <div class="ml-auto hidden sm:block">
                            @if($canEnrollNow && !$hasSubmitted)
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
                            <p class="text-slate-400 text-sm font-medium">Please wait for the next enrollment period announcement
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
                                        <div class="text-2xl font-black text-slate-900 tracking-tight">Review Application</div>
                                    </div>
                                    <div
                                        class="p-4 rounded-2xl bg-indigo-600 text-white group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/30">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">View and review your submitted
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
                                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Start a new enrollment or track your
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
                            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">View outstanding balances and record
                                secure financial transactions.</p>

                            @if(isset($assessment) && $assessment)
                                <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Assessment</span>
                                        <span class="text-lg font-black text-blue-600">₱{{ number_format($assessment['finalTotal'], 2) }}</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between text-[9px] font-bold text-slate-500 uppercase tracking-tighter">
                                            <span>Tuition:</span>
                                            <span>₱{{ number_format($assessment['tuitionFee'], 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-[9px] font-bold text-slate-500 uppercase tracking-tighter">
                                            <span>Misc:</span>
                                            <span>₱{{ number_format($assessment['miscellaneousFees'], 2) }}</span>
                                        </div>
                                        @if($assessment['totalDiscount'] > 0)
                                            <div class="flex justify-between text-[9px] font-bold text-rose-500 uppercase tracking-tighter border-t border-slate-200 pt-1 mt-1">
                                                <span>Discount:</span>
                                                <span>- ₱{{ number_format($assessment['totalDiscount'], 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
                            <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">Maintain your contact information and
                                view academic history logs.</p>
                        </div>
                    </div>
                </div>

                {{-- Requirements Section --}}
                <div class="p-10 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden relative bg-white"
                    style="border-color: rgba(37,99,235,0.1);">

                    {{-- Background Logo --}}
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none opacity-[0.03]">
                        <img src="{{ asset('image/logo.jfif') }}" alt="Logo"
                            class="w-96 h-96 rounded-full object-cover">
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
                                        <span class="text-xs text-slate-500 font-medium">Select from Academic or Tech-Voc strands</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                        <span class="text-xs text-slate-500 font-medium">Valid JHS credentials required</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="space-y-4 pt-6 border-t border-blue-100">
                                <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Required Documents:</p>
                                <ul class="grid grid-cols-1 gap-3">
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">SF9 (JHS Report Card)</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">SF10 (Permanent Record)</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Certificate of Good Moral</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">2x2 ID Picture</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">PSA Birth Certificate</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="p-8 rounded-2xl border border-blue-100 bg-blue-50/30 space-y-6">
                            <h4
                                class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-600 shadow-lg shadow-blue-600/50"></div>
                                College
                            </h4>

                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Eligibility:</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-3">
                                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                        <span class="text-xs text-slate-500 font-medium">Senior High graduates or equivalent</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                        <span class="text-xs text-slate-500 font-medium">Select from 5 college programs</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></div>
                                        <span class="text-xs text-slate-500 font-medium">Valid tertiary entry documentation</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="space-y-4 pt-6 border-t border-blue-100">
                                <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Required Documents:</p>
                                <ul class="grid grid-cols-1 gap-3">
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Form 137 (SHS Report Card)</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">Certificate of Good Moral</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">PSA Birth Certificate</span>
                                    </li>
                                    <li class="flex items-center gap-3 p-2 rounded-lg bg-white border border-blue-50 shadow-sm">
                                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white shadow-sm shadow-indigo-600/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs text-slate-600 font-bold uppercase tracking-tight">2x2 ID Picture</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($canEnroll && !$isEnrolledInActiveYear)
                    <div class="relative z-10 flex justify-center pt-4">
                        <a href="{{ route('student.enrollment.create') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-12 rounded-2xl shadow-xl shadow-blue-600/30 transition-all active:scale-95 uppercase tracking-widest text-sm">Apply
                            Now</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </x-layouts.student>
@elseif($role === 'cashier')
    <div class="w-full">
        <div class="space-y-8 animate-in fade-in duration-700" wire:poll.3s>

            {{-- SECTION 1 — Header Node --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>

                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-8">
                        <div
                            class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Financial Overview</h2>
                            <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Daily Revenue & Collection Metrics</p>
                        </div>
                    </div>
                </div>
            </div>
                        {{-- SECTION 2 — Core Metrics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Collected Today --}}
                <div class="p-10 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">Collected Today</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                ₱{{ number_format($stats['daily_collection'], 2) }}
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-600/5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full w-[65%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-emerald-500/30">
                        </div>
                    </div>
                    <p class="text-[10px] mt-6 font-black text-slate-400 uppercase tracking-widest">Confirmed Revenue Pool</p>
                </div>

                {{-- Transaction Count --}}
                <div class="p-10 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">Receipts Issued</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                {{ $stats['transactions_today'] }}
                            </div>
                            <div class="flex items-center gap-2 mt-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20H5a2 2 0 01-2-2V6a2 2 0 012-2h3m4 0h3a2 2 0 012 2v1M9 12h6m-6 4h6"/>
                                </svg>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    {{ $stats['students_paid_today'] }} {{ Str::plural('student', $stats['students_paid_today']) }} processed
                                </span>
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-600/5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full w-[45%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-blue-600/30"></div>
                    </div>
                    <p class="text-[10px] mt-6 font-black text-slate-400 uppercase tracking-widest">Daily Transaction Volume</p>
                </div>
            </div>

            {{-- SECTION 3 — Transaction Logs --}}
            <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">
                            Official Audit Record — Core Database</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                            <tr>
                                <th class="py-5 px-6 font-black">Receipt #</th>
                                <th class="py-5 px-6 font-black">Student Name</th>
                                <th class="py-5 px-6 font-black text-center">Classification</th>
                                <th class="py-5 px-6 font-black text-center">Timestamp</th>
                                <th class="py-5 px-6 text-right font-black">Amount</th>
                                <th class="py-5 px-6 text-center font-black">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($paymentsToday as $payment)
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td
                                        class="py-6 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                        #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-6 px-6">
                                        <div class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                            {{ optional($payment->user)->name }}
                                        </div>
                                    </td>
                                    <td class="py-6 px-6 text-center">
                                        @php
                                            $latestEnrollment = $payment->user->enrollments()->latest()->first();
                                            $isReturning = $payment->user->enrollments()->where('id', '<', $latestEnrollment?->id)->exists();
                                            $classification = $latestEnrollment?->student_type ?? ($isReturning ? 'Returning' : 'New');
                                            $classColor = match(strtolower($classification)) {
                                                'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'returning' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'transferee' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'shifter' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                default => 'bg-slate-50 text-slate-400 border-slate-100'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $classColor }} rounded-full border">
                                            {{ $classification }}
                                        </span>
                                    </td>
                                    <td class="py-6 px-6 text-center text-xs font-bold text-slate-400">
                                        {{ $payment->updated_at->format('H:i A') }}</td>
                                    <td class="py-6 px-6 text-right font-black text-blue-600 text-lg">
                                        ₱{{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-6 px-6 text-center">
                                        @php
                                            $statusStyle = match ($payment->status) {
                                                'Paid'
                                                    => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                default => 'bg-slate-50 text-slate-400 border-slate-100',
                                            };
                                        @endphp
                                        <span
                                            class="{{ $statusStyle }} text-[9px] font-black px-4 py-1.5 rounded-full border shadow-sm uppercase tracking-widest">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-32 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="p-6 rounded-full bg-slate-50 text-slate-200">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <p class="text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No activity detected for today.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 bg-white overflow-hidden"
                style="border-color: rgba(37,99,235,0.1);">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Archived Records — Previous Activity</p>
                    </div>
                </div>
                @if ($paymentsYesterday->isEmpty())
                    <div class="py-16 text-center text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No
                        archival records found.</div>
                @else
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                                <tr>
                                    <th class="py-5 px-6 font-black">Receipt #</th>
                                    <th class="py-4 px-6 font-black">Student Name</th>
                                    <th class="py-4 px-6 font-black text-center">Classification</th>
                                    <th class="py-4 px-6 font-black">Time</th>
                                    <th class="py-4 px-6 text-right font-black">Amount</th>
                                    <th class="py-4 px-6 text-center font-black">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($paymentsYesterday as $payment)
                                    <tr class="hover:bg-blue-50/30 transition-all group">
                                        <td
                                            class="py-6 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                            #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-5 px-6 font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                            {{ optional($payment->user)->name }}</td>
                                        <td class="py-5 px-6 text-center">
                                            @php
                                                $latestEnrollment = $payment->user->enrollments()->latest()->first();
                                                $isReturning = $payment->user->enrollments()->where('id', '<', $latestEnrollment?->id)->exists();
                                                $classification = $latestEnrollment?->student_type ?? ($isReturning ? 'Returning' : 'New');
                                                $classColor = match(strtolower($classification)) {
                                                    'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'returning' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                    'transferee' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'shifter' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                    default => 'bg-slate-50 text-slate-400 border-slate-100'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $classColor }} rounded-full border">
                                                {{ $classification }}
                                            </span>
                                        </td>
                                        <td class="py-5 px-6 text-xs font-bold text-slate-400">
                                            {{ $payment->updated_at->format('H:i A') }}</td>
                                        <td class="py-5 px-6 text-right font-black text-blue-600">
                                            ₱{{ number_format($payment->amount, 2) }}</td>
                                        <td class="py-5 px-6 text-center">
                                            @php
                                                $statusStyle = match ($payment->status) {
                                                    'Paid'
                                                        => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                    default => 'bg-slate-50 text-slate-400 border-slate-100',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
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
    </div>
    @elseif($role === 'registrar')
    <x-layouts.registrar title="Registrar Dashboard">
        <div class="w-full">
            <div class="space-y-8 animate-in fade-in duration-700" wire:poll.3s>

                {{-- SECTION 1 — Header Node --}}
                <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                    style="border-color: rgba(37,99,235,0.1);">

                    <div
                        class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                        <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 relative z-10">
                        <div class="flex items-center gap-8">
                            <div
                                class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Registrar Oversight</h2>
                                <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Application Pipeline & Student Records</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- SECTION 2 — Calendar Node --}}
                <div class="p-10 rounded-[2rem] border shadow-xl shadow-blue-900/5 bg-white overflow-hidden relative"
                    style="border-color: rgba(37,99,235,0.1);">

                    <div class="relative z-10 flex items-center gap-4 mb-10">
                        <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Academic Intake Timeline</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Registry & Operations Summary</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        @foreach ($weekDates as $day)
                            <div class="rounded-3xl flex flex-col h-[400px] transition-all duration-500 hover:shadow-2xl hover:shadow-blue-900/10"
                                style="{{ $day['is_today'] ? 'border: 2px solid #2563eb; background: #eff6ff;' : 'border: 1px solid rgba(0,0,0,0.05); background: #f8fafc;' }}">
                                <div class="text-center py-5 rounded-t-[1.4rem]"
                                    style="{{ $day['is_today'] ? 'background: #2563eb; color: white;' : 'border-bottom: 1px solid rgba(0,0,0,0.05);' }}">
                                    <p class="text-[10px] font-black uppercase tracking-widest {{ $day['is_today'] ? 'text-blue-100' : 'text-slate-400' }}">
                                        {{ $day['day_name'] }}</p>
                                    <p class="text-3xl font-black mt-1 {{ $day['is_today'] ? 'text-white' : 'text-slate-800' }}">
                                        {{ $day['day_num'] }}</p>
                                    @if ($day['is_today'])
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-white text-blue-600 shadow-lg shadow-blue-600/20">Today</span>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                                    @php
                                        $dayApps = isset($appsByDate[$day['date_string']])
                                            ? collect($appsByDate[$day['date_string']])
                                            : collect();
                                    @endphp
                                    @if ($dayApps->isEmpty())
                                        <div class="h-full flex items-center justify-center text-[9px] uppercase tracking-[0.25em] font-black text-slate-300">
                                            No Data</div>
                                    @else
                                        @foreach ($dayApps as $app)
                                            @php
                                                $status = ucfirst($app['status'] ?? 'Pending');
                                                $style = match ($status) {
                                                    'Pending' => [
                                                        'bg' => 'bg-amber-50',
                                                        'border' => 'border-amber-100',
                                                        'text' => 'text-amber-700',
                                                        'dot' => 'bg-amber-500',
                                                    ],
                                                    'Approved', 'Enrolled', 'Paid' => [
                                                        'bg' => 'bg-emerald-50',
                                                        'border' => 'border-emerald-100',
                                                        'text' => 'text-emerald-700',
                                                        'dot' => 'bg-emerald-500',
                                                    ],
                                                    default => [
                                                        'bg' => 'bg-slate-100',
                                                        'border' => 'border-slate-200',
                                                        'text' => 'text-slate-600',
                                                        'dot' => 'bg-slate-400',
                                                    ],
                                                };
                                            @endphp
                                            <a href="{{ route('registrar.dashboard', ['app_id' => $app['id']]) }}"
                                                class="p-4 rounded-2xl border flex flex-col gap-2 transition-all hover:scale-[1.03] active:scale-95 text-left w-full shadow-sm {{ $style['bg'] }} {{ $style['border'] }}">
                                                <p class="text-[11px] font-black text-slate-800 truncate uppercase tracking-tight">
                                                    {{ $app['first_name'] ?? '' }} {{ $app['last_name'] ?? '' }}</p>
                                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $style['text'] }}">{{ $status === 'Enrolled' ? 'Paid' : $status }}</span>
                                                    </div>
                                                    @php
                                                        $isReturningApp = \App\Models\Enrollment::where('user_id', $app['user_id'])
                                                            ->where('id', '<', $app['id'])
                                                            ->exists();
                                                        $appClass = $app['student_type'] ?? ($isReturningApp ? 'Returning' : 'New');
                                                        $appClassColor = match(strtolower($appClass)) {
                                                            'new' => 'bg-blue-100/50 text-blue-600',
                                                            'returning' => 'bg-indigo-100/50 text-indigo-600',
                                                            'transferee' => 'bg-emerald-100/50 text-emerald-600',
                                                            'shifter' => 'bg-purple-100/50 text-purple-600',
                                                            default => 'bg-slate-100/50 text-slate-500'
                                                        };
                                                    @endphp
                                                    <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter {{ $appClassColor }}">
                                                        {{ $appClass }}
                                                    </span>
                                                </div>
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SECTION 3 — Reports & Metrics --}}
                <div class="space-y-8">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Enrollment Reports</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Official Student Registry</p>
                            </div>
                        </div>
                        <a href="{{ route($role === 'admin' ? 'admin.reports.students.print' : 'registrar.reports.students.print') }}" target="_blank"
                            class="flex items-center gap-3 px-8 py-4 rounded-2xl bg-rose-600 text-white hover:bg-rose-700 transition-all text-[11px] font-black uppercase tracking-[0.15em] shadow-xl shadow-rose-600/20 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print Student Registry
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white p-8 rounded-[2rem] border border-blue-100 shadow-xl shadow-blue-900/5 group transition-all hover:scale-[1.02]">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Total Students</p>
                            <p class="text-5xl font-black text-slate-900 mt-3 tracking-tighter group-hover:text-blue-600 transition-colors">{{ $registryTotalStudents ?? 0 }}</p>
                        </div>
                        <div class="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-xl shadow-emerald-900/5 group transition-all hover:scale-[1.02]">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em]">✓ Regular Students</p>
                            <p class="text-5xl font-black text-slate-900 mt-3 tracking-tighter group-hover:text-emerald-600 transition-colors">{{ $registryRegularCount ?? 0 }}</p>
                        </div>
                        <div class="bg-white p-8 rounded-[2rem] border border-rose-100 shadow-xl shadow-rose-900/5 group transition-all hover:scale-[1.02]">
                            <p class="text-[10px] font-black text-rose-600 uppercase tracking-[0.3em]">⚠ Irregular Students</p>
                            <p class="text-5xl font-black text-slate-900 mt-3 tracking-tighter group-hover:text-rose-600 transition-colors">{{ $registryIrregularCount ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-8 rounded-[2rem] border border-blue-100 shadow-xl shadow-blue-900/5 group transition-all hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em]">New Students</p>
                                    <p class="text-5xl font-black text-slate-900 mt-3 tracking-tighter group-hover:text-blue-600 transition-colors">{{ $registryNewCount ?? 0 }}</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-blue-50 text-blue-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[2rem] border border-indigo-100 shadow-xl shadow-indigo-900/5 group transition-all hover:scale-[1.02]">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em]">Returning Students</p>
                                    <p class="text-5xl font-black text-slate-900 mt-3 tracking-tighter group-hover:text-indigo-600 transition-colors">{{ $registryReturningCount ?? 0 }}</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-8 rounded-[2rem] bg-blue-50 border border-blue-100 shadow-lg shadow-blue-600/5 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-black text-blue-600 uppercase tracking-[0.2em]">Senior High</p>
                                    <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $shs_count }}</h3>
                                </div>
                                <div class="p-4 rounded-2xl bg-white text-blue-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Total SHS Applicants</p>
                        </div>
                        <div class="p-8 rounded-[2rem] bg-indigo-50 border border-indigo-100 shadow-lg shadow-indigo-600/5 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em]">College</p>
                                    <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $college_count }}</h3>
                                </div>
                                <div class="p-4 rounded-2xl bg-white text-indigo-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Total College Applicants</p>
                        </div>
                        <div class="p-8 rounded-[2rem] bg-emerald-50 border border-emerald-100 shadow-lg shadow-emerald-600/5 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em]">Consolidated</p>
                                    <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $total_count }}</h3>
                                </div>
                                <div class="p-4 rounded-2xl bg-white text-emerald-600 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Overall Database Size</p>
                        </div>
                    </div>

                    <div class="p-10 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5 border-blue-100">
                        <div class="flex items-center gap-4 mb-10 px-2">
                            <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Enrollment Analytics</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Real-time Performance Metrics</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach ($stats as $key => $val)
                                <div class="p-8 rounded-3xl border border-blue-50 bg-slate-50/50 text-center transition-all hover:bg-white hover:shadow-xl hover:shadow-blue-900/5 group">
                                    <div class="text-[9px] font-black uppercase tracking-[0.3em] mb-3 text-slate-400 group-hover:text-blue-600 transition-colors">
                                        {{ str_replace('_', ' ', $key) }}</div>
                                    <div class="text-4xl font-black text-slate-800 tracking-tighter group-hover:scale-110 transition-transform">{{ $val }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                @if ($selectedApp)
                    <a href="{{ route('registrar.dashboard') }}"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[90] cursor-default transition-opacity duration-500"></a>
                    <div class="fixed inset-0 z-[100] p-4 flex items-center justify-center transition-all duration-500">
                        <div
                            class="rounded-[3rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] w-full max-w-6xl relative z-10 border overflow-hidden transform transition-all bg-white border-blue-100 animate-in zoom-in-95 duration-500">

                            {{-- Modal Header --}}
                            <div class="px-12 py-10 border-b border-slate-50 flex justify-between items-center bg-blue-50/30">
                                <div class="flex items-center gap-8">
                                    <span
                                        class="bg-blue-600 text-white font-black text-xs px-6 py-3 rounded-2xl shadow-xl shadow-blue-600/20">#{{ str_pad($selectedApp->id, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Application Details</h3>
                                </div>
                                <a href="{{ route('registrar.dashboard') }}"
                                    class="text-slate-300 hover:text-slate-600 transition-all p-4 rounded-3xl hover:bg-slate-100 group">
                                    <svg class="w-8 h-8 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            </div>

                            <div class="overflow-y-auto max-h-[85vh] custom-scrollbar">
                                <div class="p-12 grid grid-cols-1 lg:grid-cols-12 gap-12">

                                    {{-- Left Column: Program Choice --}}
                                    <div class="lg:col-span-4 space-y-8">
                                        <div class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 space-y-10 shadow-inner">
                                            <div class="space-y-4 text-center">
                                                <span class="text-[11px] font-black text-blue-600 uppercase tracking-[0.4em] block">Program Choice</span>
                                                <h2 class="text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
                                                    {{ $selectedApp->course_code }}</h2>
                                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-4">
                                                    {{ $selectedApp->year_level }}</p>
                                            </div>

                                            <div class="pt-10 border-t border-slate-200 space-y-5 text-center">
                                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] block">Current Status</span>
                                                @php
                                                    $statusBase = ucfirst($selectedApp->status);
                                                    $statusLabel = in_array($statusBase, ['Enrolled', 'Paid'])
                                                        ? 'PAID'
                                                        : strtoupper($statusBase);
                                                    $statusClass = match ($statusBase) {
                                                        'Approved',
                                                        'Enrolled',
                                                        'Paid'
                                                            => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                        'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                        'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                        default => 'bg-slate-50 text-slate-400 border-slate-100',
                                                    };
                                                @endphp
                                                <div
                                                    class="inline-block px-10 py-3 rounded-full border text-xs font-black tracking-[0.25em] {{ $statusClass }} shadow-sm">
                                                    {{ $statusLabel }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column: Main Content --}}
                                    <div class="lg:col-span-8 space-y-16">

                                        {{-- Biographical Data Section --}}
                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-1.5 h-6 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Biographical Data</h4>
                                            </div>
                                            <div class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 grid grid-cols-2 gap-10">
                                                <div class="space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Given Name</span>
                                                    <span class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $selectedApp->first_name }}</span>
                                                </div>
                                                <div class="space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Surname</span>
                                                    <span class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $selectedApp->last_name }}</span>
                                                </div>
                                                <div class="col-span-2 space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Residential Address</span>
                                                    <span class="text-sm font-bold text-slate-600 uppercase tracking-tight leading-relaxed">{{ $selectedApp->address_full ?? '— Not Provided —' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Supporting Documentation Section --}}
                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-1.5 h-6 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Supporting Documentation</h4>
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                                @php
                                                    $docs =
                                                        $selectedApp->level === 'shs'
                                                            ? [
                                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                                'SF10' => $selectedApp->sf10_path ?? null,
                                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                                            ]
                                                            : [
                                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                                            ];
                                                @endphp
                                                @foreach ($docs as $label => $file)
                                                    <div class="space-y-4">
                                                        <div class="flex items-center gap-2 mb-1 pl-2">
                                                            <span class="w-2 h-2 rounded-full {{ $file ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                                        </div>
                                                        <div class="aspect-[3/4] rounded-3xl border-2 bg-slate-50 {{ $file ? 'border-blue-100' : 'border-dashed border-slate-200' }} flex flex-col items-center justify-center gap-4 group relative overflow-hidden transition-all hover:border-blue-300">
                                                            @if ($file)
                                                                <img src="/storage/{{ $file }}"
                                                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                                <div class="absolute inset-0 bg-blue-600/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">View Document</span>
                                                                </div>
                                                            @else
                                                                <svg class="w-10 h-10 text-slate-200" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                    </path>
                                                                </svg>
                                                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Missing</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Promissory Note Asset --}}
                                        @if ($selectedApp->promissory_note_path || $selectedApp->promissory_reason)
                                            <div class="space-y-8 pt-12 border-t border-slate-100 lg:col-span-12">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-1.5 h-6 bg-amber-400 rounded-full shadow-lg shadow-amber-400/30"></div>
                                                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Promissory Note & Reason</h4>
                                                </div>
                                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 bg-amber-50 border border-amber-100 rounded-[2.5rem] p-10">
                                                    <div class="lg:col-span-1 space-y-5">
                                                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block">Note Attachment</span>
                                                        @if ($selectedApp->promissory_note_path)
                                                            <a href="{{ Storage::url($selectedApp->promissory_note_path) }}"
                                                                target="_blank"
                                                                class="flex items-center gap-5 p-6 rounded-3xl border border-amber-200 bg-white hover:bg-amber-100 transition-all shadow-sm">
                                                                <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                                                                    <svg class="w-7 h-7" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2.5"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-[11px] font-black text-slate-900 uppercase tracking-wider leading-none">View Note</p>
                                                                    <p class="text-[9px] font-bold text-amber-600 uppercase mt-1">Official Document</p>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="p-6 rounded-3xl border border-dashed border-amber-200 bg-white/50 flex items-center justify-center opacity-60">
                                                                <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest leading-none">No File Provided</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="lg:col-span-2 space-y-4">
                                                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block">Explanation</span>
                                                        <div class="p-10 rounded-[2rem] bg-white border border-amber-100 min-h-[120px] shadow-sm">
                                                            <p class="text-sm text-slate-700 font-medium leading-relaxed">
                                                                "{{ $selectedApp->promissory_reason ?? 'No explanation provided.' }}"
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="bg-blue-50 px-12 py-10 border-t border-blue-100 flex justify-between items-center">
                                    <div class="flex flex-col gap-2 pl-4 border-l-4 border-blue-600">
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em]">Status Pipeline</span>
                                        <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ strtoupper($selectedApp->status) }} — CONFIRMED SUBMISSION</span>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        @if (in_array(strtolower($selectedApp->status), ['pending', 'enrolled', 'paid']))
                                            <form action="{{ route('registrar.dashboard.reject', $selectedApp->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="bg-white hover:bg-rose-50 text-rose-600 px-10 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest border border-rose-100 shadow-sm active:scale-95">Deny Application</button>
                                            </form>
                                            <form action="{{ route('registrar.dashboard.approve', $selectedApp->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest shadow-xl shadow-blue-600/30 active:scale-95 flex items-center gap-3">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Grant Approval
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('registrar.dashboard') }}"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-500 px-12 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest border border-slate-200">Dismiss</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </x-layouts.registrar>
    @elseif($role === 'admin')
        <div class="w-full space-y-6">

            {{-- SECTION 1 — Header Node --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6 relative z-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Administrator Oversight</h2>
                            <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Registry & Operations Summary</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- SECTION 2 — Calendar Node --}}
            <div class="p-10 rounded-[2rem] border shadow-xl shadow-blue-900/5 bg-white overflow-hidden relative"
                style="border-color: rgba(37,99,235,0.1);">

                <div class="relative z-10 flex items-center gap-4 mb-10">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Academic Intake Timeline</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Registry & Operations Summary</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    @foreach ($weekDates as $day)
                        <div class="rounded-3xl flex flex-col h-[400px] transition-all duration-500 hover:shadow-2xl hover:shadow-blue-900/10"
                            style="{{ $day['is_today'] ? 'border: 2px solid #2563eb; background: #eff6ff;' : 'border: 1px solid rgba(0,0,0,0.05); background: #f8fafc;' }}">
                            <div class="text-center py-5 rounded-t-[1.4rem]"
                                    style="{{ $day['is_today'] ? 'background: #2563eb; color: white;' : 'border-bottom: 1px solid rgba(0,0,0,0.05);' }}">
                                    <p class="text-[10px] font-black uppercase tracking-widest {{ $day['is_today'] ? 'text-blue-100' : 'text-slate-400' }}">
                                        {{ $day['day_name'] }}</p>
                                    <p class="text-3xl font-black mt-1 {{ $day['is_today'] ? 'text-white' : 'text-slate-800' }}">
                                        {{ $day['day_num'] }}</p>
                                    @if ($day['is_today'])
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-white text-blue-600 shadow-lg shadow-blue-600/20">Today</span>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                                    @php
                                        $dayApps = isset($appsByDate[$day['date_string']])
                                            ? collect($appsByDate[$day['date_string']])
                                            : collect();
                                    @endphp
                                    @if ($dayApps->isEmpty())
                                        <div class="h-full flex items-center justify-center text-[9px] uppercase tracking-[0.25em] font-black text-slate-300">
                                            No Data</div>
                                    @else
                                        @foreach ($dayApps as $app)
                                            @php
                                                $status = ucfirst($app['status'] ?? 'Pending');
                                                $style = match ($status) {
                                                    'Pending' => [
                                                        'bg' => 'bg-amber-50',
                                                        'border' => 'border-amber-100',
                                                        'text' => 'text-amber-700',
                                                        'dot' => 'bg-amber-500',
                                                    ],
                                                    'Approved', 'Enrolled', 'Paid' => [
                                                        'bg' => 'bg-emerald-50',
                                                        'border' => 'border-emerald-100',
                                                        'text' => 'text-emerald-700',
                                                        'dot' => 'bg-emerald-500',
                                                    ],
                                                    default => [
                                                        'bg' => 'bg-slate-100',
                                                        'border' => 'border-slate-200',
                                                        'text' => 'text-slate-600',
                                                        'dot' => 'bg-slate-400',
                                                    ],
                                                };
                                            @endphp
                                            <button type="button" wire:click="viewApplication({{ $app['id'] }})"
                                                class="p-4 rounded-2xl border flex flex-col gap-2 transition-all hover:scale-[1.03] active:scale-95 text-left w-full shadow-sm {{ $style['bg'] }} {{ $style['border'] }}">
                                                <p class="text-[11px] font-black text-slate-800 truncate uppercase tracking-tight">
                                                    {{ $app['first_name'] ?? '' }} {{ $app['last_name'] ?? '' }}</p>
                                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $style['dot'] }}"></span>
                                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $style['text'] }}">{{ $status === 'Enrolled' ? 'Paid' : $status }}</span>
                                                    </div>
                                                    @php
                                                        $isReturningApp = \App\Models\Enrollment::where('user_id', $app['user_id'])
                                                            ->where('id', '<', $app['id'])
                                                            ->exists();
                                                        $appClass = $app['student_type'] ?? ($isReturningApp ? 'Returning' : 'New');
                                                        $appClassColor = match(strtolower($appClass)) {
                                                            'new' => 'bg-blue-100/50 text-blue-600',
                                                            'returning' => 'bg-indigo-100/50 text-indigo-600',
                                                            'transferee' => 'bg-emerald-100/50 text-emerald-600',
                                                            'shifter' => 'bg-purple-100/50 text-purple-600',
                                                            default => 'bg-slate-100/50 text-slate-500'
                                                        };
                                                    @endphp
                                                    <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter {{ $appClassColor }}">
                                                        {{ $appClass }}
                                                    </span>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 3 — System Oversight --}}
            <div class="space-y-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">System Oversight</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Official Enrollment Metrics</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-[2rem] bg-blue-50 border border-blue-100 shadow-lg shadow-blue-600/5 group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-black text-blue-600 uppercase tracking-[0.2em]">Senior High</p>
                                <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $shs_count }}</h3>
                            </div>
                            <div class="p-4 rounded-2xl bg-white text-blue-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Total SHS Records</p>
                    </div>
                    <div class="p-8 rounded-[2rem] bg-indigo-50 border border-indigo-100 shadow-lg shadow-indigo-600/5 group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em]">College</p>
                                <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $college_count }}</h3>
                            </div>
                            <div class="p-4 rounded-2xl bg-white text-indigo-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Total College Records</p>
                    </div>
                    <div class="p-8 rounded-[2rem] bg-emerald-50 border border-emerald-100 shadow-lg shadow-emerald-600/5 group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em]">Consolidated</p>
                                <h3 class="text-4xl font-black text-slate-900 mt-2 tracking-tighter">{{ $total_count }}</h3>
                            </div>
                            <div class="p-4 rounded-2xl bg-white text-emerald-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6">Overall Database Size</p>
                    </div>
                </div>
            </div>

            {{-- SECTION 4 — Analytics Cluster --}}
            <div class="p-10 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5 border-blue-100">
                <div class="flex items-center gap-4 mb-10 px-2">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Enrollment Analytics</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Real-time Performance Metrics</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($stats as $key => $val)
                        <div class="p-8 rounded-3xl border border-blue-50 bg-slate-50/50 text-center transition-all hover:bg-white hover:shadow-xl hover:shadow-blue-900/5 group">
                            <div class="text-[9px] font-black uppercase tracking-[0.3em] mb-3 text-slate-400 group-hover:text-blue-600 transition-colors">
                                {{ str_replace('_', ' ', $key) }}</div>
                            <div class="text-4xl font-black text-slate-800 tracking-tighter group-hover:scale-110 transition-transform">{{ $val }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

                {{-- Modal Container (Admin — uses Livewire state) --}}
                <div x-data="{ open: @entangle('showModal') }" x-show="open"
                    class="fixed inset-0 z-[100] p-4 flex items-center justify-center"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">

                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md cursor-pointer" wire:click="closeModal">
                    </div>

                    {{-- Modal Box --}}
                    <div class="rounded-[3rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] w-full max-w-6xl relative z-10 border overflow-hidden transform transition-all bg-white border-blue-100"
                        :class="open ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
                        @if ($selectedApp)
                            <div class="px-12 py-10 border-b border-slate-50 flex justify-between items-center bg-blue-50/30">
                                <div class="flex items-center gap-8">
                                    <span
                                        class="bg-blue-600 text-white font-black text-xs px-6 py-3 rounded-2xl shadow-xl shadow-blue-600/20">#{{ str_pad($selectedApp->id, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Application Details</h3>
                                </div>
                                <button wire:click="closeModal"
                                    class="text-slate-300 hover:text-slate-600 transition-all p-4 rounded-3xl hover:bg-slate-100 group">
                                    <svg class="w-8 h-8 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="overflow-y-auto max-h-[85vh] custom-scrollbar">
                                <div class="p-12 grid grid-cols-1 lg:grid-cols-12 gap-12">

                                    {{-- Left Column: Program Choice --}}
                                    <div class="lg:col-span-4 space-y-8">
                                        <div class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 space-y-10 shadow-inner">
                                            <div class="space-y-4 text-center">
                                                <span class="text-[11px] font-black text-blue-600 uppercase tracking-[0.4em] block">Program Choice</span>
                                                <h2 class="text-7xl font-black text-slate-900 tracking-tighter uppercase leading-none">
                                                    {{ $selectedApp->course_code }}</h2>
                                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-4">
                                                    {{ $selectedApp->year_level }}</p>
                                            </div>

                                            <div class="pt-10 border-t border-slate-200 space-y-5 text-center">
                                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] block">Current Status</span>
                                                @php
                                                    $statusBase = ucfirst($selectedApp->status);
                                                    $statusLabel = in_array($statusBase, ['Enrolled', 'Paid'])
                                                        ? 'PAID'
                                                        : strtoupper($statusBase);
                                                    $statusClass = match ($statusBase) {
                                                        'Approved',
                                                        'Enrolled',
                                                        'Paid'
                                                            => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                        'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                        'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                        default => 'bg-slate-50 text-slate-400 border-slate-100',
                                                    };
                                                @endphp
                                                <div
                                                    class="inline-block px-10 py-3 rounded-full border text-xs font-black tracking-[0.25em] {{ $statusClass }} shadow-sm">
                                                    {{ $statusLabel }}</div>

                                                <div class="pt-5 space-y-4">
                                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] block">Classification</span>
                                                    @php
                                                        $isReturning = \App\Models\Enrollment::where('user_id', $selectedApp->user_id)
                                                            ->where('id', '<', $selectedApp->id)
                                                            ->exists();
                                                        $classification = $selectedApp->student_type ?? ($isReturning ? 'Returning' : 'New');
                                                        $classColor = match(strtolower($classification)) {
                                                            'new' => 'bg-blue-600 text-white border-blue-700',
                                                            'returning' => 'bg-indigo-600 text-white border-indigo-700',
                                                            'transferee' => 'bg-emerald-600 text-white border-emerald-700',
                                                            'shifter' => 'bg-purple-600 text-white border-purple-700',
                                                            default => 'bg-slate-900 text-white border-slate-950'
                                                        };
                                                    @endphp
                                                    <div class="inline-block px-10 py-3 rounded-full border text-xs font-black tracking-[0.25em] {{ $classColor }} shadow-lg">
                                                        {{ strtoupper($classification) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column: Main Content --}}
                                    <div class="lg:col-span-8 space-y-16">

                                        {{-- Biographical Data Section --}}
                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-1.5 h-6 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Biographical Data</h4>
                                            </div>
                                            <div class="p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 grid grid-cols-2 gap-10">
                                                <div class="space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Given Name</span>
                                                    <span class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $selectedApp->first_name }}</span>
                                                </div>
                                                <div class="space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Surname</span>
                                                    <span class="text-3xl font-black text-slate-900 uppercase tracking-tight">{{ $selectedApp->last_name }}</span>
                                                </div>
                                                <div class="col-span-2 space-y-3">
                                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Residential Address</span>
                                                    <span class="text-sm font-bold text-slate-600 uppercase tracking-tight leading-relaxed">{{ $selectedApp->address_full ?? '— Not Provided —' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Supporting Documentation Section --}}
                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-1.5 h-6 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Supporting Documentation</h4>
                                            </div>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                                @php
                                                    $docs =
                                                        $selectedApp->level === 'shs'
                                                            ? [
                                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                                'SF10' => $selectedApp->sf10_path ?? null,
                                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                                            ]
                                                            : [
                                                                'Form 137' => $selectedApp->form_137_path ?? null,
                                                                'Good Moral' => $selectedApp->good_moral_path ?? null,
                                                                'PSA Birth' => $selectedApp->psa_path ?? null,
                                                                'ID Photo' => $selectedApp->id_picture_path ?? null,
                                                            ];
                                                @endphp
                                                @foreach ($docs as $label => $file)
                                                    <div class="space-y-4">
                                                        <div class="flex items-center gap-2 mb-1 pl-2">
                                                            <span class="w-2 h-2 rounded-full {{ $file ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                                        </div>
                                                        <div class="aspect-[3/4] rounded-3xl border-2 bg-slate-50 {{ $file ? 'border-blue-100' : 'border-dashed border-slate-200' }} flex flex-col items-center justify-center gap-4 group relative overflow-hidden transition-all hover:border-blue-300">
                                                            @if ($file)
                                                                <img src="/storage/{{ $file }}"
                                                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                                <div class="absolute inset-0 bg-blue-600/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">View Document</span>
                                                                </div>
                                                            @else
                                                                <svg class="w-10 h-10 text-slate-200" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                    </path>
                                                                </svg>
                                                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Missing</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Promissory Note Asset --}}
                                        @if ($selectedApp->promissory_note_path || $selectedApp->promissory_reason)
                                            <div class="space-y-8 pt-12 border-t border-slate-100 lg:col-span-12">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-1.5 h-6 bg-amber-400 rounded-full shadow-lg shadow-amber-400/30"></div>
                                                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-[0.3em]">Promissory Note & Reason</h4>
                                                </div>
                                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 bg-amber-50 border border-amber-100 rounded-[2.5rem] p-10">
                                                    <div class="lg:col-span-1 space-y-5">
                                                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block">Note Attachment</span>
                                                        @if ($selectedApp->promissory_note_path)
                                                            @php
                                                                $isPdf = Str::endsWith(
                                                                    $selectedApp->promissory_note_path,
                                                                    '.pdf',
                                                                );
                                                            @endphp
                                                            <a href="{{ Storage::url($selectedApp->promissory_note_path) }}"
                                                                target="_blank"
                                                                class="flex items-center gap-5 p-6 rounded-3xl border border-amber-200 bg-white hover:bg-amber-100 transition-all shadow-sm">
                                                                <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                                                                    <svg class="w-7 h-7" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2.5"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-[11px] font-black text-slate-900 uppercase tracking-wider leading-none">View Note</p>
                                                                    <p class="text-[9px] font-bold text-amber-600 uppercase mt-1">
                                                                        {{ $isPdf ? 'PDF Format' : 'Word Doc' }}</p>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="p-6 rounded-3xl border border-dashed border-amber-200 bg-white/50 flex items-center justify-center opacity-60">
                                                                <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest leading-none">No File Provided</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="lg:col-span-2 space-y-4">
                                                        <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest block">Explanation</span>
                                                        <div class="p-10 rounded-[2rem] bg-white border border-amber-100 min-h-[120px] shadow-sm">
                                                            <p class="text-sm text-slate-700 font-medium leading-relaxed">
                                                                "{{ $selectedApp->promissory_reason ?? 'No explanation provided.' }}"
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="bg-blue-50 px-12 py-10 border-t border-blue-100 flex justify-between items-center">
                                    <div class="flex flex-col gap-2 pl-4 border-l-4 border-blue-600">
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em]">Status Pipeline</span>
                                        <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ strtoupper($selectedApp->status) }} — CONFIRMED SUBMISSION</span>
                                    </div>

                                    <div class="flex items-center gap-6">
                                        @if (in_array(strtolower($selectedApp->status), ['pending', 'enrolled', 'paid']))
                                            <button wire:click="rejectApplication({{ $selectedApp->id }})"
                                                class="bg-white hover:bg-rose-50 text-rose-600 px-10 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest border border-rose-100 shadow-sm active:scale-95">Deny Application</button>
                                            <button wire:click="approveApplication({{ $selectedApp->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-12 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest shadow-xl shadow-blue-600/30 active:scale-95 flex items-center gap-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Grant Approval
                                            </button>
                                        @endif
                                        <button wire:click="closeModal"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-500 px-12 py-5 rounded-2xl text-[11px] font-black transition-all uppercase tracking-widest border border-slate-200">Dismiss</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
@endif
