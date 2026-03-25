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

                <div class="px-6 py-5 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white/5">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span class="w-1 h-5 rounded-full bg-purple-500 inline-block"></span>
                        Students List
                        <span class="text-white/40 text-xs font-normal ml-1">({{ $students->total() }})</span>
                    </h3>

                    <form action="{{ route('admin.students.index') }}" method="GET" class="relative group w-full md:w-80">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-purple-400">
                            <svg class="h-4 w-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="pl-11 pr-4 w-full bg-white/5 border border-white/10 rounded-xl py-3 text-sm text-white focus:border-purple-500/50 outline-none transition-all placeholder-white/10 font-bold uppercase tracking-tight shadow-inner"
                               placeholder="Search students...">
                    </form>
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
                    <div class="px-6 py-4 border-t border-white/5 bg-white/5">
                        {{ $students->appends(request()->query())->links('pagination.glass') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
