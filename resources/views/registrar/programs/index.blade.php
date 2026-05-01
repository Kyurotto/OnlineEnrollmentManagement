<x-layouts.registrar title="Manage Programs & Strands">
    <div class="space-y-12 animate-in fade-in duration-500">
        {{-- Success Notification Toast --}}
        @if(session('success'))
            <div id="success-toast" class="fixed top-6 right-6 z-[60] animate-in slide-in-from-top-2 duration-300">
                <div class="bg-emerald-500 text-black px-8 py-4 rounded-xl font-black uppercase text-xs tracking-widest shadow-2xl shadow-emerald-500/40 flex items-center gap-3 backdrop-blur-lg border border-emerald-400/20">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-close the modal by clicking the backdrop link
                    const backdropLink = document.querySelector('a[href="{{ route('registrar.programs.index') }}"]');
                    if(backdropLink && window.location.search.includes('edit_id')) {
                        setTimeout(() => {
                            backdropLink.click();
                        }, 400);
                    }

                    // Auto-dismiss toast after 3 seconds
                    const toast = document.getElementById('success-toast');
                    if(toast) {
                        setTimeout(() => {
                            toast.remove();
                        }, 3000);
                    }
                });
            </script>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- SECTION 1: COLLEGE PROGRAMS --}}
        <section class="p-8 rounded-2xl bg-white/5 border border-white/10 shadow-2xl">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-blue-500 rounded-full"></span>
                        College Programs
                    </h3>
                    <p class="text-xs text-white/40 uppercase tracking-widest mt-2 font-bold">Undergraduate Degree Courses</p>
                </div>
                <a href="{{ route('registrar.programs.index', ['showModal' => 'true', 'type' => 'program']) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Add Program
                </a>
            </div>

            {{-- College Programs Table --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="py-5 px-8 font-black">Course Code</th>
                            <th class="py-5 px-8 font-black">Course Name</th>
                            <th class="py-5 px-8 font-black">Description</th>
                            <th class="py-5 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($programs as $program)
                        <tr class="hover:bg-blue-500/[0.03] transition-all group">
                            <td class="py-5 px-8">
                                <span class="text-blue-400 font-bold font-mono text-xs">{{ $program->course_code }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-white font-bold group-hover:text-blue-300 transition-colors uppercase tracking-tight">{{ $program->course_name }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-white/40 text-sm line-clamp-1 font-medium">{{ $program->description ?: '—' }}</span>
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.programs.index', ['edit_id' => $program->id]) }}"
                                       class="px-4 py-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 text-[10px] font-black uppercase tracking-widest text-blue-400 hover:bg-blue-500 hover:text-white transition-all">
                                        Edit
                                    </a>
                                    <form action="{{ route('registrar.programs.destroy', $program->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-[10px] font-black uppercase tracking-widest text-rose-400 hover:bg-rose-500 hover:text-white transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em]">No college programs found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- SECTION 2: SENIOR HIGH STRANDS --}}
        <section class="p-8 rounded-2xl bg-white/5 border border-white/10 shadow-2xl">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                        Senior High Strands
                    </h3>
                    <p class="text-xs text-white/40 uppercase tracking-widest mt-2 font-bold">Academic & Tech-Voc Tracks</p>
                </div>
                <a href="{{ route('registrar.programs.index', ['showModal' => 'true', 'type' => 'shs']) }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Add Strand
                </a>
            </div>

            {{-- SHS Strands Table --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="py-5 px-8 font-black">Track</th>
                            <th class="py-5 px-8 font-black">Strand Code</th>
                            <th class="py-5 px-8 font-black">Strand Title</th>
                            <th class="py-5 px-8 font-black">Description</th>
                            <th class="py-5 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($strands as $strand)
                        <tr class="hover:bg-emerald-500/[0.03] transition-all group">
                            <td class="py-5 px-8">
                                @php
                                    // HCI Rule: Prioritize actual database data over inferred/automatic data
                                    $finalTrack = 'N/A';

                                    if (!empty($strand->track)) {
                                        // Use the value you manually selected in the Edit modal
                                        $finalTrack = $strand->track;
                                    } else {
                                        // Fallback to automatic mapping ONLY if the database field is empty
                                        $finalTrack = match(strtoupper($strand->course_code)) {
                                            'STEM', 'HUMSS', 'HUMMS', 'GAS', 'ABM' => 'ACAD',
                                            'HE', 'ICT' => 'TVL',
                                            default => 'N/A'
                                        };
                                    }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                                    {{ $finalTrack === 'ACAD' ? 'bg-purple-500/20 text-purple-400 border-purple-500/20' :
                                       ($finalTrack === 'TVL' ? 'bg-orange-500/20 text-orange-400 border-orange-500/20' :
                                        'bg-white/10 text-white/40 border-white/20') }}">
                                    {{ $finalTrack }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-emerald-400 font-bold font-mono text-xs">{{ $strand->course_code }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-white font-bold group-hover:text-emerald-300 transition-colors uppercase tracking-tight">{{ $strand->course_name }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-white/40 text-sm line-clamp-1 font-medium">{{ $strand->description ?: '—' }}</span>
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.programs.index', ['edit_id' => $strand->id]) }}"
                                       class="px-4 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all">
                                        Edit
                                    </a>
                                    <form action="{{ route('registrar.programs.destroy', $strand->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-[10px] font-black uppercase tracking-widest text-rose-400 hover:bg-rose-500 hover:text-white transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em]">No SHS strands found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

    {{-- Modal for Adding/Editing --}}
    @php
        $showModal = request('showModal') === 'true' || request('edit_id');
        $modalType = request('type', 'program'); // Default to 'program'
        $isEditMode = request('edit_id');
        $editingProgram = $isEditMode ? \App\Models\Course::find(request('edit_id')) : null;

        if ($editingProgram) {
            $modalType = $editingProgram->type;
        }

        $isTypeProgram = $modalType === 'program';
    @endphp

    @if($showModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <a href="{{ route('registrar.programs.index') }}" class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl"></a>

        <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-lg rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="p-10">
                <div class="mb-10 text-center">
                    @if($isEditMode)
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">Edit {{ $isTypeProgram ? 'Program' : 'Strand' }}</h3>
                    @else
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">New {{ $isTypeProgram ? 'Program' : 'Strand' }}</h3>
                    @endif
                    <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-1">{{ $isTypeProgram ? 'College Program' : 'Senior High' }} Information</p>
                </div>

                <form action="{{ $isEditMode ? route('registrar.programs.update', $editingProgram->id) : route('registrar.programs.store') }}" method="POST" class="space-y-8">
                    @csrf
                    @if($isEditMode) @method('PATCH') @endif

                    {{-- Hidden type field (for both new and edit) --}}
                    <input type="hidden" name="type" value="{{ $modalType }}">

                    {{-- Track Selection (SHS Strands Only) --}}
                    @if(!$isTypeProgram)
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-emerald-400 uppercase tracking-[0.2em] ml-1">Enrollment Track</label>
                        <select name="track"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none appearance-none text-sm font-bold tracking-wider focus:border-emerald-500/50 transition-all shadow-inner uppercase"
                            required>
                            <option value="" disabled class="bg-neutral-900 text-white/40">-- Select Track --</option>
                            <option value="ACAD" class="bg-neutral-900 text-white" {{ old('track', $editingProgram ? $editingProgram->track : '') === 'ACAD' ? 'selected' : '' }}>Academic Track (ACAD)</option>
                            <option value="TVL" class="bg-neutral-900 text-white" {{ old('track', $editingProgram ? $editingProgram->track : '') === 'TVL' ? 'selected' : '' }}>Technical Vocational Livelihood (TVL)</option>
                        </select>
                        @error('track') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="space-y-2">
                        <label class="block text-xs font-black {{ $isTypeProgram ? 'text-blue-400' : 'text-emerald-400' }} uppercase tracking-[0.2em] ml-1">
                            {{ $isTypeProgram ? 'Program Code' : 'Strand Code' }}
                        </label>
                        <input type="text" name="course_code" value="{{ old('course_code', $editingProgram ? $editingProgram->course_code : '') }}"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-{{ $isTypeProgram ? 'blue' : 'emerald' }}-500/50 transition-all shadow-inner uppercase"
                            placeholder="{{ $isTypeProgram ? 'e.g., BSIS' : 'e.g., STEM' }}" required>
                        @error('course_code') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black {{ $isTypeProgram ? 'text-blue-400' : 'text-emerald-400' }} uppercase tracking-[0.2em] ml-1">
                            {{ $isTypeProgram ? 'Program Title' : 'Strand Title' }}
                        </label>
                        <input type="text" name="course_name" value="{{ old('course_name', $editingProgram ? $editingProgram->course_name : '') }}"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-{{ $isTypeProgram ? 'blue' : 'emerald' }}-500/50 transition-all shadow-inner uppercase"
                            placeholder="{{ $isTypeProgram ? 'e.g., BS Information Systems' : 'e.g., Science, Technology, Engineering and Mathematics' }}" required>
                        @error('course_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black {{ $isTypeProgram ? 'text-blue-400' : 'text-emerald-400' }} uppercase tracking-[0.2em] ml-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-{{ $isTypeProgram ? 'blue' : 'emerald' }}-500/50 transition-all shadow-inner resize-none"
                            placeholder="{{ $isTypeProgram ? 'Define program scope and objectives...' : 'Define strand track characteristics...' }}">{{ old('description', $editingProgram ? $editingProgram->description : '') }}</textarea>
                        @error('description') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4 pt-6">
                        <a href="{{ route('registrar.programs.index') }}"
                            class="flex-1 px-8 py-4 text-center text-xs font-bold text-white/40 uppercase tracking-widest border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition-all">
                            Back
                        </a>
                        <button type="submit"
                            class="flex-1 {{ $isTypeProgram ? 'bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-500/20' : 'bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-500/20' }} text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all active:scale-95">
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Auto-fill Track based on Course Code & Auto-close Modal on Success --}}
    @if($showModal && !$isTypeProgram)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseCodeInput = document.querySelector('input[name="course_code"]');
            const trackSelect = document.querySelector('select[name="track"]');

            if (courseCodeInput && trackSelect) {
                courseCodeInput.addEventListener('input', function() {
                    const code = this.value.toUpperCase();
                    const acadStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM'];
                    const tvlStrands = ['HE', 'ICT'];

                    if (acadStrands.includes(code)) {
                        trackSelect.value = 'ACAD';
                    } else if (tvlStrands.includes(code)) {
                        trackSelect.value = 'TVL';
                    }
                });
            }
        });
    </script>
    @endif

    {{-- Auto-close modal on successful submission --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const backdropLink = document.querySelector('a[href="{{ route('registrar.programs.index') }}"]');
                if (backdropLink) {
                    backdropLink.click();
                }
            }, 800);
        });
    </script>
    @endif
</x-layouts.registrar>
