<x-layouts.student title="Enrollment Application">

    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Enrollment Application</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Enrollment Application</p>
            </div>
            <a href="{{ route('student.dashboard') }}" wire:navigate class="text-xs font-bold text-[#10B981] hover:text-[#34d399] transition-colors flex items-center gap-2 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        <form wire:submit.prevent="submitEnrollment" enctype="multipart/form-data" class="space-y-8 pb-12">

            {{-- Course Selection --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-blue-400 rounded-full"></span>
                    Course Selection
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach(['ACT' => 'ASSOCIATE IN COMPUTER TECH', 'BSIS' => 'BS INFORMATION SYSTEMS', 'BTVTED' => 'BTV Teacher Education', 'DHRT' => 'HOTEL & RESTAURANT TECH', 'DIT' => 'DIPLOMA INFO TECH'] as $code => $name)
                    <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all duration-300 group {{ $course_code === $code ? 'bg-blue-400/10 border-blue-400/50 shadow-lg shadow-blue-500/10' : 'bg-white/5 border-white/10 hover:bg-white/[0.08] hover:border-white/20' }}">
                        <input type="radio" wire:model="course_code" value="{{ $code }}" class="sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $course_code === $code ? 'border-blue-400' : 'border-white/20' }}">
                            @if($course_code === $code) <div class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-in zoom-in"></div> @endif
                        </div>
                        <div class="ml-4">
                            <span class="block text-sm font-bold {{ $course_code === $code ? 'text-blue-400' : 'text-white' }}">{{ $code }}</span>
                            <span class="text-[10px] text-white/40 uppercase tracking-widest">{{ $name }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Year Level</label>
                        <select wire:model="year_level" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer">
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Semester</label>
                        <select wire:model="semester" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            @foreach($semesters as $semesterItem)
                                <option value="{{ $semesterItem->name }}">{{ $semesterItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Academic Year</label>
                        <select wire:model="academic_year" class="w-full bg-white/5 border border-white/10 text-white rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-400 outline-none transition-all cursor-pointer">
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
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
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Last Name</label>
                        <input type="text" wire:model.blur="last_name" placeholder="Surname" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">First Name</label>
                        <input type="text" wire:model.blur="first_name" placeholder="Given Name" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Middle Name</label>
                        <input type="text" wire:model.blur="middle_name" placeholder="Optional" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Birth Date</label>
                        <input type="date" wire:model.blur="birth_date" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Age</label>
                        <input type="number" wire:model.blur="age" placeholder="00" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Gender</label>
                        <div class="flex items-center gap-6 py-2.5">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" wire:model="gender" value="male" class="sr-only">
                                <div class="w-4 h-4 rounded-full border transition-colors {{ $gender === 'male' ? 'border-purple-400 bg-purple-400' : 'border-white/20' }}"></div>
                                <span class="text-sm font-medium {{ $gender === 'male' ? 'text-purple-400' : 'text-white/40' }}">Male</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" wire:model="gender" value="female" class="sr-only">
                                <div class="w-4 h-4 rounded-full border transition-colors {{ $gender === 'female' ? 'border-purple-400 bg-purple-400' : 'border-white/20' }}"></div>
                                <span class="text-sm font-medium {{ $gender === 'female' ? 'text-purple-400' : 'text-white/40' }}">Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" wire:model="email" readonly class="w-full bg-transparent border-b border-white/5 py-2.5 text-white/30 cursor-not-allowed outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" wire:model.blur="contact" placeholder="09XXXXXXXXX" maxlength="11" class="w-full bg-transparent border-b border-white/10 py-2.5 text-white focus:border-purple-400 outline-none placeholder-white/10 transition-colors">
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
                        <p class="text-[9px] font-bold text-white/20 uppercase tracking-widest italic mt-1">Upload high-resolution assets for verification</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach([
                        ['model' => 'form_137', 'label' => 'Form 137 (Report Card)', 'desc' => "Original copy with principal's signature"],
                        ['model' => 'good_moral', 'label' => 'Certificate of Good Moral', 'desc' => 'Issued by your previous institution'],
                        ['model' => 'psa', 'label' => 'PSA Birth Certification', 'desc' => 'Clear copy of the original PSA document'],
                        ['model' => 'id_picture', 'label' => '2x2 ID Portrait', 'desc' => 'White background, formal attire']
                    ] as $doc)
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 group transition-all duration-300 hover:border-emerald-400/30">
                        <div class="mb-4">
                            <h4 class="text-sm font-bold text-white tracking-tight">{{ $doc['label'] }}</h4>
                            <p class="text-[10px] text-white/30 mt-0.5">{{ $doc['desc'] }}</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-white/10 border-dashed rounded-xl cursor-pointer bg-white/5 hover:bg-emerald-500/5 transition-all group overflow-hidden relative">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 relative z-10 transition-transform group-hover:scale-105">
                                <svg class="w-6 h-6 mb-3 text-emerald-400/60 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-1 text-[11px] text-white/40 leading-tight">
                                    @if($$doc['model']) <span class="text-emerald-400 font-black uppercase tracking-widest">Asset Ready</span>
                                    @else <span class="font-bold text-white/60">Initialize Upload</span>
                                    @endif
                                </p>
                                <p class="text-[9px] truncate max-w-[180px] {{ $$doc['model'] ? 'text-white font-medium' : 'text-white/20' }}">
                                    {{ $$doc['model'] ? $$doc['model']->getClientOriginalName() : 'PNG, JPG or PDF' }}
                                </p>
                            </div>
                            <input type="file" wire:model="{{ $doc['model'] }}" class="hidden" accept="image/*,application/pdf" />
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Error Handling & Submission --}}
            <div class="flex flex-col items-end gap-4">
                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-6 rounded-2xl shadow-xl shadow-rose-900/10 w-full animate-in slide-in-from-bottom-2">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="font-black text-xs uppercase tracking-widest">Please fix the following errors</p>
                    </div>
                    <ul class="list-disc list-inside text-[11px] space-y-1 opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <button type="submit" wire:loading.attr="disabled"
                    class="bg-emerald-500 hover:bg-emerald-400 text-black font-black py-4 px-12 rounded-2xl shadow-2xl shadow-emerald-500/20 transition-all transform active:scale-95 uppercase tracking-[0.2em] text-xs flex items-center gap-3">
                    <span wire:loading.remove>Submit Application</span>
                    <span wire:loading>Submitting...</span>
                    <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </button>
            </div>

        </form>
    </div>

</x-layouts.student>
