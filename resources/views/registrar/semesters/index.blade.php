<x-layouts.registrar title="Manage Semesters">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="card-white relative overflow-hidden">
            <div class="px-8 py-6 border-b border-blue-50 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-xl leading-tight uppercase tracking-tight">Semesters</h3>
                        <p class="text-[10px] text-blue-600 font-black uppercase tracking-[0.2em] mt-1">Academic Cycle Management</p>
                    </div>
                </div>
                <a href="{{ route('registrar.semesters.index', ['showModal' => 'true']) }}" class="btn-primary flex items-center gap-2 py-3.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Semester
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-[11px] text-slate-400 uppercase tracking-[0.2em] border-b border-blue-50 bg-slate-50/30">
                        <tr>
                            <th class="py-6 px-8 font-black">ID</th>
                            <th class="py-6 px-8 font-black">Academic Year</th>
                            <th class="py-6 px-8 font-black">Semester Name</th>
                            <th class="py-6 px-8 font-black">Duration</th>
                            <th class="py-6 px-8 text-center font-black">Status</th>
                            <th class="py-6 px-8 text-right font-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50 bg-white">
                        @forelse($semesters as $semester)
                        <tr class="hover:bg-blue-50/40 transition-all group">
                            <td class="py-6 px-8 text-slate-400 font-mono text-xs">#{{ str_pad($semester->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8 text-blue-600 uppercase tracking-widest font-black text-[10px]">{{ $semester->academic_year }}</td>
                            <td class="py-6 px-8">
                                <span class="text-slate-900 font-black uppercase tracking-tight">{{ $semester->name }}</span>
                            </td>
                            <td class="py-6 px-8 text-slate-500 text-xs font-bold">
                                <span class="block tracking-tight text-slate-700">{{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }} —</span>
                                <span class="block tracking-tight text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                @if($semester->is_active)
                                    <span class="inline-flex items-center gap-2 bg-blue-600 text-white text-[10px] font-black px-4 py-2 rounded-full shadow-lg shadow-blue-600/30 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <form action="{{ route('registrar.semesters.activate', $semester->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-slate-100 text-slate-400 text-[10px] font-black px-5 py-2 rounded-full border border-slate-200 uppercase tracking-widest hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">
                                            Activate
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-6 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.semesters.index', ['edit_id' => $semester->id]) }}"
                                       class="px-6 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-32 text-center">
                                <p class="text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($semesters->hasPages())
                <div class="p-6 border-t border-blue-50 bg-slate-50/30">
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
            <a href="{{ route('registrar.semesters.index') }}" class="absolute inset-0 bg-slate-900/40 backdrop-blur-md"></a>            <div class="bg-white border border-blue-100 w-full max-w-3xl rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(37,99,235,0.15)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-12">
                    <div class="mb-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-blue-600 text-white shadow-2xl shadow-blue-600/30 mb-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight">{{ $isEditMode ? 'Edit Semester' : 'New Semester' }}</h3>
                        <div class="flex items-center justify-center gap-3 mt-2">
                            <span class="h-px w-8 bg-blue-100"></span>
                            <p class="text-blue-600 text-[10px] font-black uppercase tracking-[0.4em]">Academic Cycle Management</p>
                            <span class="h-px w-8 bg-blue-100"></span>
                        </div>
                    </div>

                    <form action="{{ $isEditMode ? route('registrar.semesters.update', $editingSemester->id) : route('registrar.semesters.store') }}" method="POST" class="space-y-8">
                        @csrf
                        @if($isEditMode) @method('PATCH') @endif

                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Academic Period</label>
                                <select name="academic_year" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-5 px-8 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-600 focus:bg-white transition-all cursor-pointer" required>
                                    <option value="">Select Period</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->year_name }}" {{ old('academic_year', $editingSemester ? $editingSemester->academic_year : '') === $year->year_name ? 'selected' : '' }}>{{ $year->year_name }}</option>
                                    @endforeach
                                </select>
                                @error('academic_year') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Term Label</label>
                                <select name="name" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-5 px-8 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-600 focus:bg-white transition-all cursor-pointer" required>
                                    <option value="1ST SEMESTER" {{ old('name', $editingSemester ? $editingSemester->name : '') === '1ST SEMESTER' ? 'selected' : '' }}>1ST SEMESTER</option>
                                    <option value="2ND SEMESTER" {{ old('name', $editingSemester ? $editingSemester->name : '') === '2ND SEMESTER' ? 'selected' : '' }}>2ND SEMESTER</option>
                                    <option value="Summer" {{ old('name', $editingSemester ? $editingSemester->name : '') === 'Summer' ? 'selected' : '' }}>Summer Session</option>
                                </select>
                                @error('name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $editingSemester ? \Carbon\Carbon::parse($editingSemester->start_date)->format('Y-m-d') : '') }}" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-5 px-8 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-600 focus:bg-white transition-all" required>
                                @error('start_date') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $editingSemester ? \Carbon\Carbon::parse($editingSemester->end_date)->format('Y-m-d') : '') }}" class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-5 px-8 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-600 focus:bg-white transition-all" required>
                                @error('end_date') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="relative group rounded-[24px] bg-blue-50/40 border border-blue-100 p-8 hover:bg-blue-50/60 transition-all cursor-pointer">
                            <label class="flex items-center gap-6 cursor-pointer">
                                <div class="relative w-8 h-8 flex-shrink-0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $editingSemester && $editingSemester->is_active) ? 'checked' : '' }}
                                        class="peer absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="absolute inset-0 rounded-xl border-2 border-blue-200 bg-white transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center peer-checked:[&_svg]:opacity-100">
                                        <svg class="w-5 h-5 text-white opacity-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 uppercase tracking-wider">Set Operational</span>
                                    <span class="text-[10px] text-blue-600 uppercase tracking-widest mt-1 font-bold">Activate this term now</span>
                                </div>
                            </label>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('registrar.semesters.index') }}"
                                class="flex-1 px-8 py-5 text-center text-xs font-black text-slate-400 uppercase tracking-widest border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:text-slate-900 hover:border-slate-200 transition-all">
                                Back
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-5 px-8 rounded-2xl uppercase tracking-widest transition-all shadow-xl shadow-blue-600/30 active:scale-95">
                                {{ $isEditMode ? 'Update Semester' : 'Create Semester' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.registrar>
