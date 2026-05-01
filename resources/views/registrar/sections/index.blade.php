<x-layouts.registrar title="Manage Sections">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm relative">

            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg leading-tight uppercase tracking-tight">Section</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Academic Blocks</p>
                    </div>
                </div>
                <a href="{{ route('registrar.sections.index', ['showModal' => 'true']) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Block
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="py-5 px-8 font-black">ID</th>
                            <th class="py-5 px-8 font-black">Academic Year</th>
                            <th class="py-5 px-8 font-black">Block</th>
                            <th class="py-5 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sections as $section)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="py-5 px-8 text-slate-400 font-mono text-xs">#{{ str_pad($section->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-8 text-slate-500 uppercase tracking-widest font-black text-xs">{{ $section->academic_year }}</td>
                            <td class="py-5 px-8">
                                <span class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $section->section_name }}</span>
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.sections.index', ['edit_id' => $section->id]) }}" 
                                       class="px-4 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        EDIT
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <p class="text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sections->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
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
            <a href="{{ route('registrar.sections.index') }}" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></a>

            <div class="bg-white border border-slate-200 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-10">
                    <div class="mb-10 text-center">
                        <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">{{ $isEditMode ? 'Edit Section' : 'New Section' }}</h3>
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.3em] mt-1">Section Information</p>
                    </div>

                    <form action="{{ $isEditMode ? route('registrar.sections.update', $editingSection->id) : route('registrar.sections.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if($isEditMode) @method('PATCH') @endif

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Academic Period</label>
                            <select name="academic_year" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-xl outline-none placeholder-slate-400 text-sm font-bold tracking-wider focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 appearance-none transition-all cursor-pointer" required>
                                <option value="">Select Period</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->year_name }}" {{ old('academic_year', $editingSection ? $editingSection->academic_year : '') === $year->year_name ? 'selected' : '' }}>{{ $year->year_name }}</option>
                                @endforeach
                            </select>
                            @error('academic_year') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Program Track</label>
                            <select name="course_id" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-xl outline-none placeholder-slate-400 text-sm font-bold tracking-wider focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 appearance-none transition-all cursor-pointer" required>
                                <option value="">Select Program</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $editingSection ? $editingSection->course_id : '') == $course->id ? 'selected' : '' }}>
                                        {{ preg_replace('/[0-9]+/', '', $course->course_code) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Block Designation</label>
                            <input type="text" name="section_name" value="{{ old('section_name', $editingSection ? $editingSection->section_name : '') }}"
                                class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-xl outline-none placeholder-slate-400 text-sm font-bold tracking-wider focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner uppercase"
                                placeholder="e.g. BSIS 1A" required>
                            @error('section_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4 pt-6">
                            <a href="{{ route('registrar.sections.index') }}"
                                class="flex-1 px-8 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-widest border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all">
                                Back
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-95">
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
