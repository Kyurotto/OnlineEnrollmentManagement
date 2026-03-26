<x-layouts.registrar title="Manage Semesters">
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
                    <div class="p-2.5 rounded-xl bg-teal-500/10 text-teal-400 border border-teal-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg leading-tight uppercase tracking-tight">Semester</h3>
                        <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5">Management Portal</p>
                    </div>
                </div>
                <a href="{{ route('registrar.semesters.index', ['showModal' => 'true']) }}" class="bg-teal-500 hover:bg-teal-400 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-teal-500/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Semester
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="py-5 px-8 font-black">ID</th>
                            <th class="py-5 px-8 font-black">Academic Year</th>
                            <th class="py-5 px-8 font-black">Semesters</th>
                            <th class="py-5 px-8 font-black">Date</th>
                            <th class="py-5 px-8 text-center font-black">Status</th>
                            <th class="py-5 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($semesters as $semester)
                        <tr class="hover:bg-teal-500/[0.03] transition-all group">
                            <td class="py-5 px-8 text-white/20 font-mono text-xs">#{{ str_pad($semester->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-8 text-white/40 uppercase tracking-widest font-black text-xs">{{ $semester->academic_year }}</td>
                            <td class="py-5 px-8">
                                <span class="text-white font-bold group-hover:text-teal-300 transition-colors uppercase tracking-tight">{{ $semester->name }}</span>
                            </td>
                            <td class="py-5 px-8 text-white/30 text-xs font-medium">
                                <span class="block tracking-tight">{{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }} —</span>
                                <span class="block tracking-tight text-white/20">{{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}</span>
                            </td>
                            <td class="py-5 px-8 text-center">
                                @if($semester->is_active)
                                    <span class="bg-teal-500/10 text-teal-400 text-xs font-black px-4 py-1.5 rounded-full border border-teal-500/20 shadow-sm uppercase tracking-widest animate-pulse">Actived</span>
                                @else
                                    <form action="{{ route('registrar.semesters.activate', $semester->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-white/5 text-white/20 text-xs font-black px-4 py-1.5 rounded-full border border-white/5 uppercase tracking-widest hover:text-teal-400 hover:border-teal-500/20 transition-all">Activate</button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.semesters.index', ['edit_id' => $semester->id]) }}" 
                                       class="px-4 py-1.5 rounded-lg bg-teal-500/10 border border-teal-500/20 text-[10px] font-black uppercase tracking-widest text-teal-400 hover:bg-teal-500 hover:text-white transition-all shadow-lg shadow-teal-500/5">
                                        EDIT
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em] italic">No records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($semesters->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $semesters->links('pagination') }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        @php
            $showModal = request('showModal') === 'true' || request('edit_id');
            $isEditMode = request('edit_id');
            $editingSemester = $isEditMode ? \App\Models\Semester::find(request('edit_id')) : null;
        @endphp

        @if($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <a href="{{ route('registrar.semesters.index') }}" class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl"></a>
            
            <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-2xl rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-10">
                    <div class="mb-10 text-center">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">{{ $isEditMode ? 'Edit Semester' : 'New Semester' }}</h3>
                        <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-1 italic">Semester Information</p>
                    </div>

                    <form action="{{ $isEditMode ? route('registrar.semesters.update', $editingSemester->id) : route('registrar.semesters.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if($isEditMode) @method('PATCH') @endif

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Academic Period</label>
                                <select name="academic_year" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-teal-500/50 appearance-none transition-all cursor-pointer" required>
                                    <option value="" class="bg-[#060d1a]">Select Period</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->year_name }}" class="bg-[#060d1a]" {{ old('academic_year', $editingSemester ? $editingSemester->academic_year : '') === $year->year_name ? 'selected' : '' }}>{{ $year->year_name }}</option>
                                    @endforeach
                                </select>
                                @error('academic_year') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Term Label</label>
                                <select name="name" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-teal-500/50 appearance-none transition-all cursor-pointer" required>
                                    <option value="First Semester" class="bg-[#060d1a]" {{ old('name', $editingSemester ? $editingSemester->name : '') === 'First Semester' ? 'selected' : '' }}>First Semester</option>
                                    <option value="Second Semester" class="bg-[#060d1a]" {{ old('name', $editingSemester ? $editingSemester->name : '') === 'Second Semester' ? 'selected' : '' }}>Second Semester</option>
                                    <option value="Summer" class="bg-[#060d1a]" {{ old('name', $editingSemester ? $editingSemester->name : '') === 'Summer' ? 'selected' : '' }}>Summer Session</option>
                                </select>
                                @error('name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $editingSemester ? \Carbon\Carbon::parse($editingSemester->start_date)->format('Y-m-d') : '') }}" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none text-sm font-bold tracking-wider focus:border-teal-500/50 transition-all" required>
                                @error('start_date') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $editingSemester ? \Carbon\Carbon::parse($editingSemester->end_date)->format('Y-m-d') : '') }}" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none text-sm font-bold tracking-wider focus:border-teal-500/50 transition-all" required>
                                @error('end_date') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="relative group overflow-hidden rounded-xl bg-white/5 border border-white/10 p-5 hover:bg-white/10 transition-all cursor-pointer">
                            <label class="flex items-center gap-4 cursor-pointer">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $editingSemester && $editingSemester->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-white/10 bg-white/5 text-teal-500 focus:ring-teal-500 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-white uppercase tracking-wider">Set Operational</span>
                                    <span class="text-xs text-white/30 uppercase tracking-widest mt-0.5 font-bold italic">Current Active Term</span>
                                </div>
                            </label>
                        </div>

                        <div class="flex gap-4 pt-6">
                            <a href="{{ route('registrar.semesters.index') }}" 
                                class="flex-1 px-8 py-4 text-center text-xs font-bold text-white/40 uppercase tracking-widest border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition-all">
                                Back
                            </a>
                            <button type="submit" 
                                class="flex-1 bg-teal-500 hover:bg-teal-400 text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-teal-500/20 active:scale-95">
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