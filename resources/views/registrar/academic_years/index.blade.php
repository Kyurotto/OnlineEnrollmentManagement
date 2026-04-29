<x-layouts.registrar title="Manage Academic Years">
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
                    <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Academic Year</h3>
                        <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5">Record Repository</p>
                    </div>
                </div>
                <a href="{{ route('registrar.academic_years.index', ['showModal' => 'true']) }}" class="bg-amber-500 hover:bg-amber-400 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-amber-500/20 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Academic Year
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                            <th class="py-5 px-8 font-black">ID</th>
                            <th class="py-5 px-8 font-black">Academic Year</th>
                            <th class="py-5 px-8 font-black text-center">Status</th>
                            <th class="py-5 px-8 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($years as $year)
                        <tr class="hover:bg-amber-500/[0.03] transition-all group">
                            <td class="py-5 px-8 text-white/20 font-mono text-xs">#{{ str_pad($year->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-8">
                                <span class="text-white font-bold group-hover:text-amber-400 transition-colors uppercase tracking-tight">{{ $year->year_name }}</span>
                            </td>
                            <td class="py-5 px-8 text-center">
                                @if($year->is_active)
                                    <span class="bg-amber-500/10 text-amber-400 text-xs font-black px-4 py-1.5 rounded-full border border-amber-500/20 shadow-sm uppercase tracking-widest animate-pulse">Actived</span>
                                @else
                                    <span class="bg-white/5 text-white/20 text-xs font-black px-4 py-1.5 rounded-full border border-white/5 uppercase tracking-widest">Pending</span>
                                @endif
                            </td>
                            <td class="py-5 px-8 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registrar.academic_years.index', ['edit_id' => $year->id]) }}" 
                                       class="px-4 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-[10px] font-black uppercase tracking-widest text-amber-400 hover:bg-amber-500 hover:text-white transition-all shadow-lg shadow-amber-500/5">
                                        EDIT
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em]">No records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($years->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $years->links('pagination') }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        @php
            $showModal = request('showModal') === 'true' || request('edit_id');
            $isEditMode = request('edit_id');
            $editingYear = $isEditMode ? \App\Models\AcademicYear::find(request('edit_id')) : null;
        @endphp

        @if($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <a href="{{ route('registrar.academic_years.index') }}" class="absolute inset-0 bg-slate-900/20"></a>

            <div class="bg-white border border-blue-100 w-full max-w-3xl rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(37,99,235,0.15)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-12">
                    <div class="mb-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-blue-600 text-white shadow-2xl shadow-blue-600/30 mb-8 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight">{{ $isEditMode ? 'Edit Academic Year' : 'New Academic Year' }}</h3>
                        <div class="flex items-center justify-center gap-3 mt-2">
                            <span class="h-px w-8 bg-blue-100"></span>
                            <p class="text-blue-600 text-[10px] font-black uppercase tracking-[0.4em]">Academic Repository</p>
                            <span class="h-px w-8 bg-blue-100"></span>
                        </div>
                    </div>

                    <form action="{{ $isEditMode ? route('registrar.academic_years.update', $editingYear->id) : route('registrar.academic_years.store') }}" method="POST" class="space-y-10">
                        @csrf
                        @if($isEditMode) @method('PATCH') @endif

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Period Designation</label>
                            <input type="text" name="year_name" value="{{ old('year_name', $editingYear ? $editingYear->year_name : '') }}"
                                class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-5 px-8 rounded-2xl outline-none placeholder-slate-300 text-sm font-bold tracking-wider focus:border-blue-600 focus:bg-white transition-all"
                                placeholder="e.g. 2025 - 2026" required>
                            @error('year_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="relative group rounded-[24px] bg-blue-50/40 border border-blue-100 p-8 hover:bg-blue-50/60 transition-all cursor-pointer">
                            <label class="flex items-center gap-6 cursor-pointer">
                                <div class="relative w-8 h-8 flex-shrink-0">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $editingYear && $editingYear->is_active) ? 'checked' : '' }}
                                        class="peer absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div class="absolute inset-0 rounded-xl border-2 border-blue-200 bg-white transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center peer-checked:[&_svg]:opacity-100">
                                        <svg class="w-5 h-5 text-white opacity-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 uppercase tracking-wider">Set Operational</span>
                                    <span class="text-[10px] text-blue-600 uppercase tracking-widest mt-1 font-bold">Activate this cycle now</span>
                                </div>
                            </label>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('registrar.academic_years.index') }}"
                                class="flex-1 px-8 py-5 text-center text-xs font-black text-slate-400 uppercase tracking-widest border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:text-slate-900 hover:border-slate-200 transition-all">
                                Back
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-5 px-8 rounded-2xl uppercase tracking-widest transition-all shadow-xl shadow-blue-600/30 active:scale-95">
                                {{ $isEditMode ? 'Update Year' : 'Create Year' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.registrar>
