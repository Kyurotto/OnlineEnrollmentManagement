<div class="space-y-6 animate-in fade-in duration-500">
    @if (session('success'))
        <div
            class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
            <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-[32px] overflow-hidden border-white/5 shadow-2xl shadow-black/40">
        <div
            class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight uppercase italic text-shadow-lg shadow-black/40">Student Population Registry</h2>
                <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1 italic">Management of Verified Academic Personas & Operational Status</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto flex-wrap">
                <div class="relative group w-full md:w-72">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Search Students or Credentials..."
                        class="w-full bg-black/20 border border-white/5 rounded-2xl pl-12 pr-6 py-4 text-[11px] font-bold text-white placeholder:text-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/30 transition-all shadow-inner tracking-wider">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-20 group-focus-within:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center bg-white/5 border border-white/10 rounded-2xl p-1 gap-1">
                    <button type="button" wire:click="setFilter('all')"
                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $filter === 'all' ? 'bg-white/10 text-white' : 'text-white/30 hover:text-white' }}">
                        All
                    </button>
                    <button type="button" wire:click="setFilter('regular')"
                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $filter === 'regular' ? 'bg-emerald-500/20 text-emerald-400' : 'text-white/30 hover:text-white' }}">
                        Regular
                    </button>
                    <button type="button" wire:click="setFilter('irregular')"
                        class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $filter === 'irregular' ? 'bg-rose-500/20 text-rose-400' : 'text-white/30 hover:text-white' }}">
                        Irregular
                    </button>
                </div>
                <a href="{{ route('admin.students.export', [
                    'search' => $search,
                    'filter' => $filter,
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                    'course_filter' => $course_filter,
                    'level' => $level,
                    'year_level' => $year_level,
                    'section_filter' => $section_filter
                ]) }}"
                    class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-500/20 transition-all text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-500/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Printable CSV
                </a>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse font-bold">
                <thead>
                    <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] bg-white/[0.02]">
                        <th class="py-6 px-8 cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('users.id')">
                            <div class="flex items-center gap-2">
                                ID
                                <span class="transition-opacity {{ $sortField === 'users.id' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if ($sortField === 'users.id' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('last_name')">
                            <div class="flex items-center gap-2">
                                Full Name
                                <span class="transition-opacity {{ $sortField === 'last_name' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if ($sortField === 'last_name' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8">Account Details</th>
                        <th class="py-6 px-8 cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('enrollments.course_code')">
                            <div class="flex items-center justify-center gap-2">
                                ACADEMIC TRACK
                                <span class="transition-opacity {{ $sortField === 'enrollments.course_code' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @foreach(['asc', 'desc'] as $dir)
                                        @if ($sortField === 'enrollments.course_code' && $sortDirection === $dir)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $dir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                            </svg>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('year_level')">
                            <div class="flex items-center justify-center gap-2">
                                SECTION
                                <span class="transition-opacity {{ $sortField === 'year_level' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @foreach(['asc', 'desc'] as $dir)
                                        @if ($sortField === 'year_level' && $sortDirection === $dir)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $dir === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                            </svg>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('status')">
                            <div class="flex items-center justify-center gap-2">
                                STATUS
                                <span class="transition-opacity {{ $sortField === 'status' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if ($sortField === 'status' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                     <th class="py-6 px-8 text-center">TYPE</th>
                     <th class="py-6 px-8 text-center">CLASSIFICATION</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-white/5">
                    @forelse($students as $student)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">
                                #{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span
                                        class="text-white group-hover:text-blue-400 transition-colors uppercase tracking-wider block">{{ $student->last_name }},
                                        {{ $student->first_name }}</span>
                                    <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">UID:
                                        {{ $student->username }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span
                                        class="text-white/40 lowercase italic tracking-tight mb-0.5">{{ $student->email }}</span>
                                    <span class="text-[9px] text-white/20 uppercase tracking-widest">Personal
                                        Account</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-blue-400 uppercase tracking-widest font-black text-[10px]">{{ $student->program }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-white/40 uppercase tracking-widest font-black text-[10px]">{{ $student->year_display }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                @php
                                    $statusColor = match (strtolower($student->status ?? 'enrolled')) {
                                        'active', 'approved', 'enrolled' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                        'pending' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                        'rejected', 'inactive' => 'text-rose-400 bg-rose-400/10 border-rose-400/20',
                                        default => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                    };
                                @endphp
                                <span class="{{ $statusColor }} text-[10px] font-black px-4 py-1.5 rounded-full border uppercase tracking-widest">
                                    {{ $student->status ?: 'Enrolled' }}
                                </span>
                            </td>
{{-- Type --}}
<td class="py-6 px-8 text-center whitespace-nowrap">
    <span class="text-[10px] font-black px-3 py-1.5 rounded-full border uppercase tracking-widest
        {{ $student->student_type_display === 'Transferee' ? 'text-amber-400 bg-amber-400/10 border-amber-400/20' :
           ($student->student_type_display === 'Shifter' ? 'text-sky-400 bg-sky-400/10 border-sky-400/20' :
           'text-white/40 bg-white/5 border-white/10') }}">
        {{ $student->student_type_display }}
    </span>
</td>
{{-- Classification --}}
<td class="py-6 px-8 text-center whitespace-nowrap">
    @if($student->is_regular === null)
        <span class="text-white/20 text-[10px] font-black px-3 py-1.5 rounded-full border border-white/10 uppercase tracking-widest">
            Not Audited
        </span>
    @elseif($student->is_regular)
        <span class="text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest">
            Regular
        </span>
    @else
        <div class="flex flex-col items-center gap-1">
            <span class="text-rose-400 bg-rose-400/10 border border-rose-400/20 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest">
                Irregular
            </span>
            @if($student->classification_reason)
                <span class="text-[8px] text-rose-300/50 uppercase tracking-wider">{{ $student->classification_reason }}</span>
            @endif
        </div>
    @endif
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] italic">No Student
                                        matches found</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-8 border-t border-white/5 bg-white/[0.01]">
            {{ $students->links('pagination') }}
        </div>
    </div>

    {{-- Classification Modal (Set Classification) --}}
    @if($showClassificationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeClassificationModal"></div>

            <div class="relative z-10 w-full max-w-sm bg-[#0f0f1a] border border-white/10 rounded-[28px] shadow-2xl p-8 space-y-6 animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Admin Control</p>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight italic mt-0.5">Set Classification</h3>
                    </div>
                    <button type="button" wire:click="closeClassificationModal" class="text-white/20 hover:text-white transition-colors mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Classification</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button"
                            wire:click="$set('classificationIsRegular', true)"
                            class="py-3 px-4 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all
                                {{ $classificationIsRegular
                                    ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400'
                                    : 'bg-white/5 border-white/10 text-white/40 hover:bg-emerald-500/10 hover:border-emerald-500/20 hover:text-emerald-400' }}">
                            ✓ Regular
                        </button>
                        <button type="button"
                            wire:click="$set('classificationIsRegular', false)"
                            class="py-3 px-4 rounded-2xl border text-[10px] font-black uppercase tracking-widest transition-all
                                {{ !$classificationIsRegular
                                    ? 'bg-rose-500/20 border-rose-500/40 text-rose-400'
                                    : 'bg-white/5 border-white/10 text-white/40 hover:bg-rose-500/10 hover:border-rose-500/20 hover:text-rose-400' }}">
                            ✗ Irregular
                        </button>
                    </div>
                </div>

                @if(!$classificationIsRegular)
                <div class="space-y-2">
                    <label class="block text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">
                        Classification Reason <span class="text-rose-400">*</span>
                    </label>
                    <select wire:model="classificationReason"
                        class="w-full bg-white/[0.03] text-white border border-white/10 py-4 px-5 rounded-2xl outline-none text-sm font-bold tracking-wide focus:border-purple-500/50 focus:bg-white/[0.05] transition-all cursor-pointer">
                        <option value="" style="background-color:#0d1b2e;color:#ffffff;">— Select a reason —</option>
                        @foreach($classificationReasons as $key => $label)
                            <option value="{{ $key }}" style="background-color:#0d1b2e;color:#ffffff;">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('classificationReason')
                        <span class="text-rose-500 text-[9px] font-black uppercase tracking-tighter ml-1">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <div class="flex gap-3 pt-1">
                    <button type="button" wire:click="closeClassificationModal"
                        class="flex-1 px-5 py-3.5 text-[9px] font-black text-white/40 uppercase tracking-[0.3em] border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white transition-all">
                        Cancel
                    </button>
                    <button type="button" wire:click="saveClassification"
                        wire:loading.attr="disabled"
                        class="flex-[2] bg-purple-500 hover:bg-purple-400 text-white text-[9px] font-black py-3.5 px-5 rounded-2xl uppercase tracking-[0.3em] transition-all shadow-[0_10px_30px_rgba(167,139,250,0.3)] active:scale-[0.98] italic disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveClassification">Save Classification</span>
                        <span wire:loading wire:target="saveClassification">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
