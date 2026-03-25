<x-layouts.registrar title="Student Registry">
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
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase italic">Student Population Registry</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Management of Verified Academic Personas & Operational Status</p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse font-bold">
                    <thead>
                        <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] bg-white/[0.02]">
                            <th class="py-6 px-8">Identifier</th>
                            <th class="py-6 px-8">Full Legal Name</th>
                            <th class="py-6 px-8">Email</th>
                            <th class="py-6 px-8 text-center">Academic Track</th>
                            <th class="py-6 px-8 text-center">Section</th>
                            <th class="py-6 px-8">Status</th>
                            <th class="py-6 px-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($students as $student)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-white group-hover:text-purple-400 transition-colors uppercase tracking-wider block">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                        <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Verified Profile</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="text-white/40 lowercase tracking-tight">{{ $student->email }}</span>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <span class="text-purple-400 uppercase tracking-widest font-black text-[10px]">{{ $student->program }}</span>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <span class="text-white/40 uppercase tracking-widest font-black text-[10px]">{{ $student->year_display }}</span>
                                </td>
                                <td class="py-6 px-8">
                                    <span class="bg-purple-500/10 text-purple-400 text-[10px] font-black px-4 py-1.5 rounded-full border border-purple-500/20 uppercase tracking-widest">
                                        {{ $student->status ?? 'Enrolled' }}
                                    </span>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <a href="{{ route('registrar.students.edit', $student->id) }}"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-purple-400 hover:border-purple-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn">
                                        Modify
                                        <svg class="w-3 h-3 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
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
                    {{ $students->links('pagination.glass') }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.registrar>