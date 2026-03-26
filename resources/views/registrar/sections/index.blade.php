<x-layouts.registrar title="Manage Sections">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border overflow-hidden relative"
             style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

            <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-pink-500/10 text-pink-400 border border-pink-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg leading-tight uppercase tracking-tight">Section</h3>
                        <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5">Academic Blocks</p>
                    </div>
                </div>
                <a href="{{ route('registrar.sections.index', ['showModal' => 'true']) }}" class="bg-pink-500 hover:bg-pink-400 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-pink-500/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Block
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="py-5 px-8 font-black">ID</th>
                            <th class="py-5 px-8 font-black">Academic Year</th>
                            <th class="py-5 px-8 font-black">Block</th>
                            <th class="py-5 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($sections as $section)
                        <tr class="hover:bg-pink-500/[0.03] transition-all group">
                            <td class="py-5 px-8 text-white/20 font-mono text-xs">#{{ str_pad($section->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-8 text-white/40 uppercase tracking-widest font-black text-xs">{{ $section->academic_year }}</td>
                            <td class="py-5 px-8">
                                <span class="text-white font-bold group-hover:text-pink-300 transition-colors uppercase tracking-tight">{{ $section->section_name }}</span>
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.sections.index', ['edit_id' => $section->id]) }}" 
                                       class="px-4 py-1.5 rounded-lg bg-pink-500/10 border border-pink-500/20 text-[10px] font-black uppercase tracking-widest text-pink-400 hover:bg-pink-500 hover:text-white transition-all shadow-lg shadow-pink-500/5">
                                        EDIT
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em] italic">No records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sections->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $sections->links('pagination') }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        @php
            $showModal = request('showModal') === 'true' || request('edit_id');
            $isEditMode = request('edit_id');
            $editingSection = $isEditMode ? \App\Models\Section::find(request('edit_id')) : null;
        @endphp

        @if($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <a href="{{ route('registrar.sections.index') }}" class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl"></a>

            <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-lg rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-10">
                    <div class="mb-10 text-center">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">{{ $isEditMode ? 'Edit Section' : 'New Section' }}</h3>
                        <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-1 italic">Section Information</p>
                    </div>

                    <form action="{{ $isEditMode ? route('registrar.sections.update', $editingSection->id) : route('registrar.sections.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if($isEditMode) @method('PATCH') @endif

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Academic Period</label>
                            <select name="academic_year" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-pink-500/50 appearance-none transition-all cursor-pointer" required>
                                <option value="" class="bg-[#060d1a]">Select Period</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->year_name }}" class="bg-[#060d1a]" {{ old('academic_year', $editingSection ? $editingSection->academic_year : '') === $year->year_name ? 'selected' : '' }}>{{ $year->year_name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Program Track</label>
                            <select name="course_id" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-pink-500/50 appearance-none transition-all cursor-pointer" required>
                                <option value="" class="bg-[#060d1a]">Select Program</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" class="bg-[#060d1a]" {{ old('course_id', $editingSection ? $editingSection->course_id : '') == $course->id ? 'selected' : '' }}>
                                        {{ preg_replace('/[0-9]+/', '', $course->course_code) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Block Designation</label>
                            <input type="text" name="section_name" value="{{ old('section_name', $editingSection ? $editingSection->section_name : '') }}"
                                class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-pink-500/50 transition-all shadow-inner uppercase"
                                placeholder="e.g. BSIS 1A" required>
                            @error('section_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-6">
                            <a href="{{ route('registrar.sections.index') }}"
                                class="flex-1 px-8 py-4 text-center text-xs font-bold text-white/40 uppercase tracking-widest border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition-all">
                                Back
                            </a>
                            <button type="submit"
                                class="flex-1 bg-pink-500 hover:bg-pink-400 text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-pink-500/20 active:scale-95">
                                {{ $isEditMode ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.registrar>