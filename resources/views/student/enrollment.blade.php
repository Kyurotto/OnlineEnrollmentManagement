<x-layouts.student title="Enrollment Application">
<div class="space-y-6">
    <style>
        select option {
            background-color: #0d1f3c !important;
            color: white !important;
        }
    </style>
    <div class="max-w-5xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Enrollment Application</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Enrollment Application</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-6 rounded-2xl shadow-xl shadow-rose-900/10 w-full mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('student.enrollment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-12">
            @csrf

            {{-- Hidden Level Field --}}
            <input type="hidden" name="level" value="{{ $level }}">

            {{-- Course / Strand Selection --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 rounded-full" style="background-color: {{ $level === 'shs' ? '#10B981' : '#3B82F6' }};"></span>
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
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-emerald-400/10 has-[:checked]:border-emerald-400/50 has-[:checked]:shadow-lg has-[:checked]:shadow-emerald-500/10">
                                    <input type="radio" name="course_code" value="{{ $strand->course_code }}" {{ old('course_code', $course_code ?? '') === $strand->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors border-white/20 peer-checked:border-emerald-400">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-in zoom-in hidden peer-checked:block"></div>
                                    </div>
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
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-purple-400/10 has-[:checked]:border-purple-400/50 has-[:checked]:shadow-lg has-[:checked]:shadow-purple-500/10">
                                    <input type="radio" name="course_code" value="{{ $strand->course_code }}" {{ old('course_code', $course_code ?? '') === $strand->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors border-white/20 peer-checked:border-purple-400">
                                        <div class="w-2.5 h-2.5 rounded-full bg-purple-400 animate-in zoom-in hidden peer-checked:block"></div>
                                    </div>
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
                            <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20 has-[:checked]:bg-blue-400/10 has-[:checked]:border-blue-400/50 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-500/10">
                                <input type="radio" name="course_code" value="{{ $program->course_code }}" {{ old('course_code', $course_code ?? '') === $program->course_code ? 'checked' : '' }} class="sr-only peer" required>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors border-white/20 peer-checked:border-blue-400">
                                    <div class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-in zoom-in hidden peer-checked:block"></div>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-white peer-checked:text-blue-400">{{ $program->course_code }}</span>
                                    <span class="text-xs text-white/40 uppercase tracking-widest">{{ $program->course_name }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-xs text-white/40">No college programs available</p>
                        @endforelse
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Year Level</label>
                        <select name="year_level" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer" required>
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
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Semester</label>
                        <select name="semester" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer" required>
                            <option value="">Select Semester</option>
                            <option value="1st Semester" {{ old('semester', $semester ?? '') === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd Semester" {{ old('semester', $semester ?? '') === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Academic Year</label>
                        <select name="academic_year" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer" required>
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}" {{ old('academic_year', $academic_year ?? '') === $year->year_name ? 'selected' : '' }}>{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Student Information --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-purple-400 rounded-full"></span>
                    Student Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $last_name ?? '') }}" placeholder="Surname" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $first_name ?? '') }}" placeholder="Given Name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $middle_name ?? '') }}" placeholder="Optional" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Birth Date</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $birth_date ?? '') }}" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Age</label>
                        <input type="number" name="age" value="{{ old('age', $age ?? '') }}" placeholder="00" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Gender</label>
                        <div class="flex items-center gap-6 py-2.5">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="gender" value="male" {{ old('gender', $gender ?? '') === 'male' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-4 h-4 rounded-full border transition-all duration-300 border-white/20 peer-checked:border-purple-400 peer-checked:bg-purple-400"></div>
                                <span class="text-sm font-medium text-white/40 peer-checked:text-purple-400 transition-colors">Male</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="gender" value="female" {{ old('gender', $gender ?? '') === 'female' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-4 h-4 rounded-full border transition-all duration-300 border-white/20 peer-checked:border-purple-400 peer-checked:bg-purple-400"></div>
                                <span class="text-sm font-medium text-white/40 peer-checked:text-purple-400 transition-colors">Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $email ?? '') }}" readonly class="w-full bg-transparent border-b border-white/5 py-2.5 text-white/30 cursor-not-allowed outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" name="contact" value="{{ old('contact', $contact ?? '') }}" placeholder="09XXXXXXXXX" maxlength="11" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-teal-400 rounded-full"></span>
                    Address Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">House No. / Unit</label>
                        <input type="text" name="house_no" value="{{ old('house_no', $house_no ?? '') }}" placeholder="e.g. 123, Unit 4A" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Street / Road</label>
                        <input type="text" name="street" value="{{ old('street', $street ?? '') }}" placeholder="Street name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Barangay</label>
                        <input type="text" name="barangay" value="{{ old('barangay', $barangay ?? '') }}" placeholder="Barangay name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">City / Municipality</label>
                        <input type="text" name="city" value="{{ old('city', $city ?? '') }}" placeholder="City or municipality" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Province</label>
                        <input type="text" name="province" value="{{ old('province', $province ?? '') }}" placeholder="Province name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">ZIP / Postal Code</label>
                        <input type="text" name="zip" value="{{ old('zip', $zip ?? '') }}" placeholder="Postal code" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-teal-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Parent Information --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-purple-400 rounded-full"></span>
                    Parent / Guardian Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Father's Full Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $father_name ?? '') }}" placeholder="Full Name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Mother's Maiden Name</label>
                        <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name', $mother_maiden_name ?? '') }}" placeholder="Full Name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Guardian's Full Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $guardian_name ?? '') }}" placeholder="Full Name (If not parents)" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Guardian Contact Number</label>
                        <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $guardian_contact ?? '') }}" placeholder="09XXXXXXXXX" maxlength="11" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Document Requirements --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-400 rounded-full"></span>
                            Document Verification
                        </h3>
                        <p class="text-xs font-bold text-white/20 uppercase tracking-widest italic mt-1">Upload high-resolution assets for verification</p>
                    </div>
                </div>

                @php
                    $docs = ($level === 'shs') ? [
                        ['model' => 'form_137', 'label' => 'JHS Report Card (SF9)', 'desc' => 'Original SF9 with school seal and signature'],
                        ['model' => 'sf10', 'label' => 'SF10 (Permanent Record)', 'desc' => 'Certified copy of SF10'],
                        ['model' => 'good_moral', 'label' => 'Certificate of Good Moral', 'desc' => 'Optional - from previous school'],
                        ['model' => 'id_picture', 'label' => '2pcs 2x2 ID Portrait', 'desc' => 'White background, formal attire'],
                        ['model' => 'psa', 'label' => 'PSA Birth Certificate', 'desc' => 'Authenticated copy of birth certificate']
                    ] : [
                        ['model' => 'form_137', 'label' => 'Form 137 (Report Card)', 'desc' => "Original copy with principal's signature"],
                        ['model' => 'good_moral', 'label' => 'Certificate of Good Moral', 'desc' => 'Issued by your previous institution'],
                        ['model' => 'psa', 'label' => 'PSA Birth Certification', 'desc' => 'Clear copy of the original PSA document'],
                        ['model' => 'id_picture', 'label' => '2x2 ID Portrait', 'desc' => 'White background, formal attire']
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($docs as $doc)
                        <div class="bg-white/5 p-6 rounded-2xl border border-white/10 group transition-all duration-300 hover:border-emerald-400/30">
                            <div class="mb-4">
                                <h4 class="text-sm font-bold text-white tracking-tight">{{ $doc['label'] }}</h4>
                                <p class="text-xs text-white/30 mt-0.5">{{ $doc['desc'] }}</p>
                            </div>
                            <label for="file-{{ $doc['model'] }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-white/10 border-dashed rounded-xl cursor-pointer bg-white/5 hover:bg-emerald-500/5 transition-all group overflow-hidden relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 relative z-10 transition-transform group-hover:scale-105">
                                    <svg class="w-6 h-6 mb-3 text-emerald-400/60 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                    <p class="mb-1 text-xs text-white/40 leading-tight">
                                        <span class="font-bold text-white/60">Initialize Upload</span>
                                    </p>
                                    <p class="text-xs text-white/20">PNG, JPG or PDF</p>
                                    <p class="text-[10px] text-emerald-400 mt-2 hidden" id="feedback-{{ $doc['model'] }}">File Selected</p>
                                </div>
                                <input type="file" id="file-{{ $doc['model'] }}" name="{{ $doc['model'] }}" class="sr-only" accept="image/*,application/pdf" onchange="document.getElementById('feedback-{{ $doc['model'] }}').classList.remove('hidden')" />
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

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
