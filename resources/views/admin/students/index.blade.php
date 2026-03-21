<x-layouts.admin>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-white tracking-tight">Student Records</h2>
                <p class="text-purple-400/60 text-xs font-bold uppercase tracking-widest mt-1">Official Student Directory</p>
            </div>

            @if (session('success'))
                <div class="bg-purple-500/10 border border-purple-500/20 text-purple-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border overflow-hidden"
                 style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

                <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span class="w-1 h-5 rounded-full bg-purple-500 inline-block"></span>
                        Students List
                        <span class="text-white/40 text-xs font-normal ml-1">({{ count($students) }})</span>
                    </h3>
                </div>

                <div class="overflow-x-auto min-h-[400px]">
                    <table class="w-full text-sm text-left text-white/70">
                        <thead class="text-[10px] text-purple-300 uppercase tracking-widest border-b border-white/5"
                            style="background: rgba(255,255,255,0.03);">
                            <tr>
                                <th scope="col" class="px-6 py-5 font-black">Full Name</th>
                                <th scope="col" class="px-6 py-5 font-black">Email Address</th>
                                <th scope="col" class="px-6 py-5 font-black text-center">Program</th>
                                <th scope="col" class="px-6 py-5 font-black text-center">Section</th>
                                <th scope="col" class="px-6 py-5 font-black text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($students as $student)
                                <tr class="hover:bg-white/5 transition-all group">
                                    <td class="px-6 py-5">
                                        <span class="font-bold text-white uppercase tracking-tight group-hover:text-purple-200 transition-colors">
                                            {{ $student->last_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 font-bold text-white/90 uppercase tracking-tight">
                                        {{ $student->first_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-5 text-white/40 lowercase italic text-xs">
                                        {{ $student->email }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-purple-500/20 bg-purple-500/10 text-purple-400">
                                            {{ $student->program }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 font-bold text-white/60 text-center whitespace-nowrap text-xs">
                                        {{ $student->year_display }}</td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-500/20 shadow-sm uppercase tracking-widest">
                                            {{ $student->status ?? 'Active' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center gap-2 opacity-20">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p class="italic text-sm font-bold uppercase tracking-widest">No students found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->hasPages())
                    <div class="px-6 py-4 border-t border-white/5" style="background: rgba(255,255,255,0.02);">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
ml>
