<x-layouts.student title="Enrollment Application">
<div class="space-y-6">
    <style>
        select option {
            background-color: #ffffff !important;
            color: #0f172a !important;
        }
        /* Custom styles for light-theme glass effect */
        .enrollment-glass {
            background: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.15) !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05) !important;
        }
        .input-underline {
            border-bottom: 2px solid rgba(15, 23, 42, 0.2) !important;
            color: #0f172a !important;
            background: transparent !important;
        }
        .input-underline:focus {
            border-bottom-color: #3b82f6 !important;
        }
        .input-underline::placeholder {
            color: rgba(15, 23, 42, 0.4) !important;
        }
        .label-premium {
            color: #0f172a !important;
            font-weight: 900 !important;
            opacity: 1 !important;
            display: block !important;
        }
        .protocol-label {
            color: #64748b !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
        }
        .radio-indicator {
            border: 2px solid rgba(15, 23, 42, 0.3) !important;
            background: #ffffff !important;
        }
        .peer:checked ~ .radio-indicator {
            border-color: #3b82f6 !important;
            background-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }
    </style>
    <div class="max-w-5xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Enrollment Application</h2>
                <p class="text-[10px] mt-2 font-black uppercase tracking-[0.3em] text-blue-600/50">Academic Registration Protocol</p>
            </div>

            <a href="{{ route('student.enrollment.create') }}" 
               class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all text-xs font-black uppercase tracking-widest group shadow-sm">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Selection
            </a>
        </div>

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-6 rounded-2xl shadow-xl shadow-rose-900/10 w-full mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.enrollment.store') }}" method="POST" class="space-y-8 pb-12">
            @csrf

            {{-- Hidden Level Field --}}
            <input type="hidden" name="level" value="{{ $level }}">

            {{-- Course / Strand Selection --}}
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-8 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 rounded-full" style="background-color: {{ $level === 'shs' ? '#3b82f6' : '#3B82F6' }};"></span>
                    {{ $level === 'shs' ? 'Strand Selection' : 'Course Selection' }}
                </h3>

                @if($level === 'shs')
                    {{-- SHS Tracks --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        {{-- Academic Track --}}
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Academic Track</p>
                            @php
                                $acadStrands = $strands->filter(function($strand) {
                                    $autoTrack = match(strtoupper($strand->course_code)) {
                                        'STEM', 'HUMSS', 'HUMMS', 'GAS', 'ABM' => 'ACAD',
                                        'HE', 'ICT' => 'TVL',
                                        default => null
                                    };
                                    $finalTrack = !empty($strand->track) ? $strand->track : $autoTrack;
                                    return $finalTrack === 'ACAD';
                                });
                            @endphp
                            @forelse($acadStrands as $strand)
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-emerald-500/10 has-[:checked]:border-emerald-500 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20 has-[:checked]:shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                                    <input type="radio" name="course_code" value="{{ $strand->course_code }}" {{ old('course_code', $course_code ?? '') === $strand->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                    <div class="w-5 h-5 rounded-full border-2 transition-all duration-300 border-white/20 peer-checked:border-emerald-400 peer-checked:bg-emerald-400"></div>
                                    <div class="ml-4">
                                        <span class="block text-sm font-bold text-white peer-checked:text-emerald-400">{{ $strand->course_code }}</span>
                                        <span class="text-xs text-white/40 uppercase tracking-widest">{{ $strand->course_name }}</span>
                                    </div>
                                </label>
                            @empty
                                <p class="text-xs text-white/40">No Academic tracks available</p>
                            @endforelse
                        </div>

                        {{-- Tech-Voc Track --}}
                        <div class="space-y-4">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-widest">Technical Vocational Track</p>
                            @php
                                $tvlStrands = $strands->filter(function($strand) {
                                    $autoTrack = match(strtoupper($strand->course_code)) {
                                        'STEM', 'HUMSS', 'HUMMS', 'GAS', 'ABM' => 'ACAD',
                                        'HE', 'ICT' => 'TVL',
                                        default => null
                                    };
                                    $finalTrack = !empty($strand->track) ? $strand->track : $autoTrack;
                                    return $finalTrack === 'TVL';
                                });
                            @endphp
                            @forelse($tvlStrands as $strand)
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-purple-500/10 has-[:checked]:border-purple-500 has-[:checked]:ring-2 has-[:checked]:ring-purple-500/20 has-[:checked]:shadow-[0_0_15px_rgba(168,85,247,0.3)]">
                                    <input type="radio" name="course_code" value="{{ $strand->course_code }}" {{ old('course_code', $course_code ?? '') === $strand->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                    <div class="w-5 h-5 rounded-full border-2 transition-all duration-300 border-white/20 peer-checked:border-purple-400 peer-checked:bg-purple-400"></div>
                                    <div class="ml-4">
                                        <span class="block text-sm font-bold text-white peer-checked:text-purple-400">{{ $strand->course_code }}</span>
                                        <span class="text-xs text-white/40 uppercase tracking-widest">{{ $strand->course_name }}</span>
                                    </div>
                                </label>
                            @empty
                                <p class="text-xs text-white/40">No Tech-Voc tracks available</p>
                            @endforelse
                        </div>
                    </div>
                @else
                    {{-- College Programs --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @forelse($programs as $program)
                            <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-blue-500/10 has-[:checked]:border-blue-500 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20 has-[:checked]:shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                            <input type="radio" name="course_code" value="{{ $program->course_code }}" {{ old('course_code', $course_code ?? '') === $program->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                <div class="w-5 h-5 rounded-full radio-indicator transition-all duration-300"></div>
                                <div class="ml-4">
                                    <span class="block text-sm font-black text-slate-800 peer-checked:text-blue-600 transition-colors">{{ $program->course_code }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $program->course_name }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-xs text-white/40">No college programs available</p>
                        @endforelse
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Year Level</label>
                        <select name="year_level" class="w-full bg-slate-50 border border-slate-100 text-slate-900 font-bold rounded-xl py-3.5 px-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all cursor-pointer shadow-sm" required>
                            <option value="">Select Year</option>
                            @if($level === 'shs')
                                <option value="Grade 11" {{ old('year_level', $year_level ?? '') === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ old('year_level', $year_level ?? '') === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            @else
                                @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year'] as $year)
                                    <option value="{{ $year }}" {{ old('year_level', $year_level ?? '') === $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Semester</label>
                        <div class="w-full bg-emerald-50/50 border border-emerald-100 text-slate-900 font-bold rounded-xl py-3.5 px-4 flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="tracking-tight">{{ $activeSemester ? $activeSemester->name : 'Not Set' }}</span>
                            <span class="text-[8px] font-black text-emerald-600/40 uppercase tracking-[0.2em] ml-auto">Auto</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Academic Year</label>
                        <div class="w-full bg-blue-50/50 border border-blue-100 text-slate-900 font-bold rounded-xl py-3.5 px-4 flex items-center gap-3">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="tracking-tight">{{ $activeYear ? $activeYear->year_name : 'Not Set' }}</span>
                            <span class="text-[8px] font-black text-blue-600/40 uppercase tracking-[0.2em] ml-auto">Auto</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Information --}}
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.3)]"></span>
                    Student Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $last_name ?? '') }}" placeholder="Surname" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $first_name ?? '') }}" placeholder="Given Name" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $middle_name ?? '') }}" placeholder="Optional" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>

                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Extension</label>
                        <input type="text" name="extension" value="{{ old('extension', $extension ?? '') }}" placeholder="e.g., Jr., Sr., III" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">LRN (Learner Reference Number)</label>
                        <input type="text" name="lrn" value="{{ old('lrn', $lrn ?? '') }}" placeholder="e.g., 123456789012" maxlength="12" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Religion / Church</label>
                        <input type="text" name="religion_church" value="{{ old('religion_church', $religion_church ?? '') }}" placeholder="e.g., Catholic, Protestant" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>

                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Birth Date</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $birth_date ?? '') }}" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all cursor-pointer" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Age</label>
                        <input type="number" name="age" value="{{ old('age', $age ?? '') }}" placeholder="00" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Gender</label>
                        <div class="flex items-center gap-8 py-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="gender" value="male" {{ old('gender', $gender ?? '') === 'male' ? 'checked' : '' }} class="sr-only peer" required>
                                <div class="w-5 h-5 rounded-full radio-indicator transition-all duration-300"></div>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-500 peer-checked:text-blue-600 transition-colors">Male</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="gender" value="female" {{ old('gender', $gender ?? '') === 'female' ? 'checked' : '' }} class="sr-only peer" required>
                                <div class="w-5 h-5 rounded-full radio-indicator transition-all duration-300"></div>
                                <span class="text-xs font-black uppercase tracking-widest text-slate-500 peer-checked:text-blue-600 transition-colors">Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $email ?? '') }}" readonly class="w-full bg-transparent input-underline py-3 text-sm font-bold text-slate-400 cursor-not-allowed opacity-60">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Contact Number</label>
                        <input type="text" name="contact" value="{{ old('contact', $contact ?? '') }}" placeholder="09XXXXXXXXX" maxlength="11" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Facebook Account</label>
                        <input type="text" name="facebook_account" value="{{ old('facebook_account', $facebook_account ?? '') }}" placeholder="Facebook username or URL" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 bg-teal-500 rounded-full shadow-[0_0_10px_rgba(20,184,166,0.3)]"></span>
                    Address Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Prk. / Blk. Lot / Vill.</label>
                        <input type="text" name="prk_blk_lot_vill" value="{{ old('prk_blk_lot_vill', $prk_blk_lot_vill ?? '') }}" placeholder="e.g., Purok 1, Block A, Lot 5" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-teal-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Barangay</label>
                        <input type="text" name="barangay" value="{{ old('barangay', $barangay ?? '') }}" placeholder="Barangay name" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-teal-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">City / Municipality</label>
                        <input type="text" name="city" value="{{ old('city', $city ?? '') }}" placeholder="City or municipality" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-teal-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Province</label>
                        <input type="text" name="province" value="{{ old('province', $province ?? '') }}" placeholder="Province name" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-teal-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">ZIP / Postal Code</label>
                        <input type="text" name="zip" value="{{ old('zip', $zip ?? '') }}" placeholder="Postal code" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-teal-500 outline-none transition-all" required>
                    </div>
                </div>
            </div>

            {{-- Parent Information --}}
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.3)]"></span>
                    Parent / Guardian Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Father's Full Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $father_name ?? '') }}" placeholder="Full Name" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Mother's Maiden Name</label>
                        <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name', $mother_maiden_name ?? '') }}" placeholder="Full Name" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Guardian's Full Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $guardian_name ?? '') }}" placeholder="Full Name (If not parents)" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Guardian Contact Number</label>
                        <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $guardian_contact ?? '') }}" placeholder="09XXXXXXXXX" maxlength="11" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-purple-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            {{-- Educational Background (SHS Only) --}}
            @if($level === 'shs')
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 bg-orange-500 rounded-full shadow-[0_0_10px_rgba(249,115,22,0.3)]"></span>
                    Educational Background
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Junior High School Attended</label>
                        <input type="text" name="junior_high_school" value="{{ old('junior_high_school', $junior_high_school ?? '') }}" placeholder="Name of JHS school" class="w-full bg-transparent input-underline py-3 text-sm font-bold focus:border-orange-500 outline-none transition-all">
                    </div>
                </div>
            </div>
            @endif

            {{-- Health Information --}}
            <div class="p-8 rounded-[2rem] border shadow-2xl enrollment-glass">

                <h3 class="text-lg font-black text-slate-900 mb-10 flex items-center gap-3 uppercase tracking-tight">
                    <span class="w-1.5 h-6 bg-pink-500 rounded-full shadow-[0_0_10px_rgba(236,72,153,0.3)]"></span>
                    Health Information
                </h3>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="label-premium text-[10px] uppercase tracking-[0.2em] ml-1">Health Concerns / Medical Issues</label>
                        <p class="text-[10px] protocol-label mb-2 ml-1">Confidential Medical Protocol</p>
                        <textarea name="health_concerns" placeholder="e.g., Asthma, Allergies, Medications, Physical limitations, etc." rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-slate-900 font-bold focus:border-pink-500 outline-none transition-all resize-none shadow-sm">{{ old('health_concerns', $health_concerns ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Dynamic Year Level Filtering --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const courseRadios = document.querySelectorAll('input[name="course_code"]');
                    const yearSelect = document.querySelector('select[name="year_level"]');
                    const level = "{{ $level }}";

                    function updateYearLevels() {
                        if (level !== 'college') return;

                        const selectedCourse = document.querySelector('input[name="course_code"]:checked');
                        if (!selectedCourse) return;

                        const courseCode = selectedCourse.value.toUpperCase();
                        const currentYear = yearSelect.value;
                        
                        // Define max years for each course
                        let maxYear = 4;
                        if (courseCode === 'ACT') {
                            maxYear = 2;
                        } else if (courseCode === 'DIT' || courseCode === 'DHRT') {
                            maxYear = 3;
                        }

                        // Clear options except first
                        yearSelect.innerHTML = '<option value="">Select Year</option>';

                        for (let i = 1; i <= maxYear; i++) {
                            const yearStr = i === 1 ? '1st Year' : (i === 2 ? '2nd Year' : (i === 3 ? '3rd Year' : '4th Year'));
                            const option = document.createElement('option');
                            option.value = yearStr;
                            option.textContent = yearStr;
                            if (yearStr === currentYear) option.selected = true;
                            yearSelect.appendChild(option);
                        }
                    }

                    courseRadios.forEach(radio => {
                        radio.addEventListener('change', updateYearLevels);
                    });

                    // Run on load to handle initial selection (e.g., from old input or draft)
                    updateYearLevels();
                });
            </script>

            {{-- HCI Guidance: Required Field Scroller --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('form');
                    form.addEventListener('submit', function(e) {
                        // Check for invalid fields using the constraint validation API
                        const invalidElement = form.querySelector(':invalid');
                        if (invalidElement) {
                            e.preventDefault();
                            invalidElement.focus();
                            // Smooth scroll to the required field for better user guidance
                            invalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
 
                            // Add visual feedback to the invalid field
                            invalidElement.classList.add('ring-2', 'ring-rose-500');
                            setTimeout(() => {
                                invalidElement.classList.remove('ring-2', 'ring-rose-500');
                            }, 3000);
                        }
                    });
                });
            </script>

            {{-- Error Handling & Submission --}}
            <div class="flex flex-col items-end gap-4">
                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-6 rounded-2xl shadow-xl shadow-rose-900/10 w-full animate-in slide-in-from-bottom-2">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="font-black text-sm uppercase tracking-widest">Please fix the following errors</p>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <button type="submit"
                    class="bg-emerald-500 hover:bg-emerald-400 text-black font-black py-4 px-12 rounded-2xl shadow-2xl shadow-emerald-500/20 transition-all transform active:scale-95 uppercase tracking-[0.2em] text-xs flex items-center gap-3">
                    Submit Application
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </button>
            </div>

        </form>
    </div>
</div>
</x-layouts.student>
