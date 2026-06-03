<x-layouts.registrar title="Student Registry">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        <div class="glass-card rounded-[32px] overflow-hidden border-white/5 shadow-2xl shadow-black/40">
            <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase">{{ $level === 'shs' ? 'SHS' : ($level === 'college' ? 'College' : 'Student') }} Enrolled List</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Management of Verified Academic Personas & Operational Status</p>
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
                    <!-- Search Form -->
                    <form action="{{ route('registrar.students.index') }}" method="GET" class="relative group w-full md:w-80">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        <input type="hidden" name="level" value="{{ $level }}">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search identifier, name, or track..."
                            class="w-full bg-white/[0.03] border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-xs text-white placeholder:text-white/20 focus:border-purple-500/50 outline-none transition-all font-bold uppercase tracking-widest">
                    </form>

                    <!-- Filter Form -->
                    <div class="flex bg-white/5 p-1 rounded-2xl border border-white/10 shadow-inner">
                        <a href="{{ route('registrar.students.index', ['filter' => 'all', 'search' => $search, 'level' => $level]) }}"
                           class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filter === 'all' ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/20' : 'text-white/40 hover:text-white' }}">ALL</a>
                        <a href="{{ route('registrar.students.index', ['filter' => 'regular', 'search' => $search, 'level' => $level]) }}"
                           class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filter === 'regular' ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/20' : 'text-white/40 hover:text-white' }}">REGULAR</a>
                        <a href="{{ route('registrar.students.index', ['filter' => 'irregular', 'search' => $search, 'level' => $level]) }}"
                           class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $filter === 'irregular' ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/20' : 'text-white/40 hover:text-white' }}">IRREGULAR</a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse font-bold">
                    <thead>
                        <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] bg-white/[0.02]">
                            <th class="py-6 px-8">ID</th>
                            <th class="py-6 px-8">Full Name</th>
                            <th class="py-6 px-8">Email</th>
                            <th class="py-6 px-8 text-center">{{ $level === 'college' ? 'Program' : 'Academic Track' }}</th>
                            <th class="py-6 px-8 text-center">Section</th>
                            <th class="py-6 px-8 text-center">Classification</th>
                            <th class="py-6 px-8">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($students as $student)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-6 px-8 text-white/20 font-mono tracking-tighter whitespace-nowrap">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-8 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-white group-hover:text-purple-400 transition-colors uppercase tracking-wider block">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                        <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Verified Profile</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8 whitespace-nowrap">
                                    <span class="text-white/40 lowercase tracking-tight">{{ $student->email }}</span>
                                </td>
                                <td class="py-6 px-8 text-center whitespace-nowrap">
                                    <span class="text-purple-400 uppercase tracking-widest font-black text-[10px]">{{ $student->program }}</span>
                                </td>
                                <td class="py-6 px-8 text-center whitespace-nowrap">
                                    <span class="text-white/40 uppercase tracking-widest font-black text-[10px]">{{ $student->year_display }}</span>
                                </td>
                                <td class="py-6 px-8 text-center whitespace-nowrap">
                                    @if($student->is_regular === 1 || $student->is_regular === true)
                                        <span class="bg-emerald-500/10 text-emerald-400 border-emerald-500/20 text-[10px] font-black px-4 py-1.5 rounded-full border uppercase tracking-widest">
                                            Regular
                                        </span>
                                    @elseif($student->is_regular === 0 || $student->is_regular === false && $student->is_regular !== null)
                                        <span class="bg-amber-500/10 text-amber-500 border-amber-500/20 text-[10px] font-black px-4 py-1.5 rounded-full border uppercase tracking-widest" title="{{ $student->classification_reason }}">
                                            Irregular
                                        </span>
                                    @else
                                        <span class="bg-white/5 text-white/40 border-white/10 text-[10px] font-black px-4 py-1.5 rounded-full border uppercase tracking-widest">
                                            Not Set
                                        </span>
                                    @endif
                                </td>
                                <td class="py-6 px-8 whitespace-nowrap">
                                    @php
                                        $status = $student->status ?? 'Enrolled';
                                        $statusColor = match(ucfirst($status)) {
                                            'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                            default => 'bg-purple-500/10 text-purple-400 border-purple-500/20'
                                        };
                                    @endphp
                                    <span class="{{ $statusColor }} text-[10px] font-black px-4 py-1.5 rounded-full border uppercase tracking-widest">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-20 text-center">
                                    <div class="flex flex-col items-center opacity-20">
                                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">No Students Documented</span>
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
</x-layouts.registrar>
