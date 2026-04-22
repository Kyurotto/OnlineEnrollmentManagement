<div class="space-y-6 animate-in fade-in duration-500">
    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
            <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-[32px] overflow-hidden border-white/5 shadow-2xl shadow-black/40">
        <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight uppercase italic text-shadow-lg shadow-black/40">Student Population Registry</h2>
                <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1 italic">Management of Verified Academic Personas & Operational Status</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative group w-full md:w-72">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Search Name or Program..."
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-3.5 text-[11px] font-bold text-white placeholder:text-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 transition-all shadow-inner tracking-wider">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-20 group-focus-within:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
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
                                    @if($sortField === 'users.id' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('last_name')">
                            <div class="flex items-center gap-2">
                                FULL NAME
                                <span class="transition-opacity {{ $sortField === 'last_name' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'last_name' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8">ACCOUNT DETAILS</th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('latest_enrollments.course_code')">
                            <div class="flex items-center justify-center gap-2">
                                ACADEMIC TRACK
                                <span class="transition-opacity {{ $sortField === 'latest_enrollments.course_code' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'latest_enrollments.course_code' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('latest_enrollments.year_level')">
                            <div class="flex items-center justify-center gap-2">
                                LEVEL
                                <span class="transition-opacity {{ $sortField === 'latest_enrollments.year_level' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'latest_enrollments.year_level' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('year_level')">
                            <div class="flex items-center justify-center gap-2">
                                SECTION
                                <span class="transition-opacity {{ $sortField === 'year_level' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'year_level' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8">STATUS</th>
                        <th class="py-6 px-8 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-white/5">
                    @forelse($students as $student)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span class="text-white group-hover:text-blue-400 transition-colors uppercase tracking-wider block font-bold">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                    <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Verified Profile</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <span class="text-white/40 lowercase tracking-tight">{{ $student->email }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-blue-400 uppercase tracking-widest font-black text-[10px]">{{ $student->program }}</span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-tighter border {{ $student->level === 'SHS' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20' }}">
                                    {{ $student->level ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-center">
                                <span class="text-white/40 uppercase tracking-widest font-black text-[10px]">{{ $student->year_display }}</span>
                            </td>
                            <td class="py-6 px-8">
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
                            <td class="py-6 px-8 text-right">
                                    <a href="{{ route('registrar.students.edit', $student->id) }}"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-blue-400 hover:border-blue-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn">
                                        UPDATE
                                        <svg class="w-3 h-3 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] italic">No Students Documented</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                {{ $students->links('pagination') }}
            </div>
        @endif
    </div>
</div>
