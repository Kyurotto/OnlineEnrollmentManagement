<x-layouts.registrar title="Update Student Record">
    @php
        $enrollment = \App\Models\Enrollment::where('user_id', $student->id)
            ->whereIn('status', ['Enrolled', 'Approved', 'Paid', 'Pending'])
            ->latest()
            ->first();
    @endphp

    <div class="max-w-2xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">

        {{-- Header --}}
        <div class="mb-8 flex items-center gap-5">
            <a href="{{ route('registrar.students.index') }}" class="group/back flex items-center justify-center w-11 h-11 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-purple-400 hover:border-purple-500/30 transition-all active:scale-95 shadow-xl">
                <svg class="w-4 h-4 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <p class="text-white/30 text-[9px] font-black uppercase tracking-[0.3em]">Registrar Control</p>
                <h2 class="text-2xl font-black text-white tracking-tight uppercase italic">Update Student Record</h2>
            </div>
        </div>

        <div class="glass-card rounded-[32px] border border-white/5 shadow-2xl overflow-hidden relative">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-purple-500/10 blur-[80px] rounded-full pointer-events-none"></div>

            <form action="{{ route('registrar.students.update', $student->id) }}" method="POST" class="p-10 relative z-10 space-y-8">
                @csrf
                @method('PATCH')

                {{-- Personal Info --}}
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Given Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}"
                                class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all uppercase">
                            @error('first_name') <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}"
                                class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all uppercase">
                            @error('middle_name') <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}"
                                class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all uppercase">
                            @error('last_name') <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-purple-500/50 focus:bg-white/[0.05] transition-all">
                        @error('email') <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Academic Classification --}}
                @if($enrollment)
                <div class="border-t border-white/5 pt-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-purple-400"></div>
                        <p class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em]">Academic Classification</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Student Type --}}
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Student Type</label>
                            <select name="student_type"
                                class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wide focus:border-purple-500/50 focus:bg-white/[0.05] transition-all cursor-pointer">
                                @foreach(['new' => 'New Student', 'transferee' => 'Transferee', 'shifter' => 'Shifter', 'returnee' => 'Returnee'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('student_type', $enrollment->student_type) === $value ? 'selected' : '' }} style="background-color:#0d1b2e;color:#ffffff;">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Classification --}}
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">Classification</label>
                            <select name="is_regular" id="isRegularSelect"
                                class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wide focus:border-purple-500/50 focus:bg-white/[0.05] transition-all cursor-pointer">
                                <option value="1" {{ old('is_regular', $enrollment->is_regular) == '1' ? 'selected' : '' }} style="background-color:#0d1b2e;color:#ffffff;">Regular</option>
                                <option value="0" {{ old('is_regular', $enrollment->is_regular) === false || old('is_regular', $enrollment->is_regular) == '0' ? 'selected' : '' }} style="background-color:#0d1b2e;color:#ffffff;">Irregular</option>
                            </select>
                        </div>
                    </div>

                    {{-- Classification Reason --}}
                    <div class="space-y-2" id="classificationReasonWrapper">
                        <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.2em] ml-1">
                            Classification Reason
                            <span class="text-rose-400 ml-1 font-black italic">(Required if Irregular)</span>
                        </label>
                        <select name="classification_reason" id="classificationReasonSelect"
                            class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wide focus:border-purple-500/50 focus:bg-white/[0.05] transition-all cursor-pointer">
                            <option value="" style="background-color:#0d1b2e;color:#ffffff;">— None / Not Applicable —</option>
                            @php
                                $reasons = $enrollment->isSHS()
                                    ? \App\Models\Enrollment::SHS_CLASSIFICATION_REASONS
                                    : \App\Models\Enrollment::CLASSIFICATION_REASONS;
                            @endphp
                            @foreach($reasons as $key => $label)
                                <option value="{{ $key }}" {{ old('classification_reason', $enrollment->classification_reason) === $key ? 'selected' : '' }} style="background-color:#0d1b2e;color:#ffffff;">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('classification_reason') <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <a href="{{ route('registrar.students.index') }}"
                        class="flex-1 px-8 py-4 text-[9px] font-black text-white/40 uppercase tracking-[0.3em] border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white text-center transition-all bg-white/[0.01]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-[2] bg-purple-500 hover:bg-purple-400 text-white text-[9px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.3em] transition-all shadow-[0_20px_50px_rgba(167,139,250,0.3)] active:scale-[0.98] italic">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-white/20 text-[9px] font-bold uppercase tracking-widest mt-8">© 2026 Your Institution — Registrar Panel</p>
    </div>

    <script>
        (function () {
            const statusSelect = document.getElementById('isRegularSelect');
            const reasonWrapper = document.getElementById('classificationReasonWrapper');
            const reasonSelect = document.getElementById('classificationReasonSelect');

            if (!statusSelect || !reasonWrapper) return;

            function toggle() {
                const isIrregular = statusSelect.value === '0';
                reasonWrapper.style.opacity = isIrregular ? '1' : '0.4';
                if (reasonSelect) reasonSelect.disabled = !isIrregular;
            }

            statusSelect.addEventListener('change', toggle);
            toggle();
        })();
    </script>
</x-layouts.registrar>
