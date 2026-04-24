<x-layouts.registrar title="Student Profile Bank">
    <div class="space-y-6 animate-in fade-in duration-500">
        
        <div class="glass-card rounded-[32px] border-white/5 shadow-2xl shadow-black/40">
            <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex-shrink-0">
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase italic text-shadow-lg shadow-black/40">
                        Student Profile Bank
                    </h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1 italic">
                        Complete Registry of All Students
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-max w-full text-left border-collapse font-bold">
                    <thead class="text-[10px] text-white/20 uppercase tracking-[0.2em] border-b border-white/5 bg-white/[0.01]">
                        <tr>
                            <th class="py-6 px-8 text-left">ID</th>
                            <th class="py-6 px-8 text-left">Full Name</th>
                            <th class="py-6 px-8 text-left">Email</th>
                            <th class="py-6 px-8 text-left">Program</th>
                            <th class="py-6 px-8 text-left">Year Level</th>
                            <th class="py-6 px-8 text-left">Status</th>
                            <th class="py-6 px-8 text-left">Date Registered</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($students as $student)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider font-bold">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </span>
                                        <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Student</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-white/40 lowercase tracking-tight">{{ $student->email }}</td>
                                <td class="py-6 px-8 text-cyan-400 font-black uppercase text-[10px] tracking-widest">
                                    {{ $student->program ?? 'N/A' }}
                                </td>
                                <td class="py-6 px-8">
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-lg">
                                        {{ $student->year_display ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-6 px-8">
                                    @php
                                        $status = $student->enrollment_status ?? 'No Enrollment';
                                        $statusColor = match($status) {
                                            'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            'Paid' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                            default => 'bg-white/5 text-white/40 border-white/10',
                                        };
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColor }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="py-6 px-8 text-white/30 font-medium italic tracking-tight">
                                    {{ $student->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <div class="flex flex-col items-center opacity-20">
                                        <svg class="w-16 h-16 mb-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-[0.4em] italic text-white">No students found</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                    {{ $students->links('pagination') }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.registrar>
