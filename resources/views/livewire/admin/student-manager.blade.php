<div>
    @if (session('success'))
        <div class="bg-purple-500/10 border border-purple-500/20 text-purple-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden mt-6 relative"
         style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">


        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center sm:flex-row flex-col gap-4"
             style="background: rgba(255,255,255,0.02);">
            <h3 class="font-bold text-white flex items-center gap-2">
                <span class="w-1 h-5 rounded-full bg-purple-500 inline-block"></span>
                Students List
                <span class="text-white/40 text-xs font-normal ml-1">({{ $students->total() }})</span>
            </h3>

            <div class="relative w-full sm:w-72 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-purple-400 group-focus-within:text-purple-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="pl-10 w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder-white/20 shadow-inner"
                    placeholder="Search by name, email or ID...">
            </div>
        </div>

        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-sm text-left text-white/70 min-w-[900px]">
                <thead class="text-xs text-purple-300 uppercase tracking-widest border-b border-white/5"
                       style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th scope="col" class="px-6 py-5">Last Name</th>
                        <th scope="col" class="px-6 py-5">First Name</th>
                        <th scope="col" class="px-6 py-5 text-center">Email</th>
                        <th scope="col" class="px-6 py-5 text-center">Course</th>
                        <th scope="col" class="px-6 py-5 text-center">Year</th>
                        <th scope="col" class="px-6 py-5 text-center">Status</th>
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

                            <td class="px-6 py-5 text-white/40 lowercase italic text-sm">
                                {{ $student->email }}
                            </td>

                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest border border-purple-500/20 bg-purple-500/10 text-purple-400">
                                    {{ $student->program }}
                                </span>
                            </td>
                            <td class="px-6 py-5 font-bold text-white/60 text-center whitespace-nowrap text-sm">
                                {{ $student->year_display }}</td>

                            <td class="px-6 py-5 text-center">
                                <span class="bg-emerald-500/10 text-emerald-400 text-xs font-black px-3 py-1 rounded-full border border-emerald-500/20 shadow-sm uppercase tracking-widest">
                                    {{ $student->status ?? 'Active' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-2 opacity-20">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="italic text-sm font-bold uppercase tracking-widest">No matching students found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="px-6 py-4 border-t border-white/5" style="background: rgba(255,255,255,0.02);">
                {{ $students->links('livewire.glass-pagination') }}
            </div>
        @endif
    </div>
</div>
