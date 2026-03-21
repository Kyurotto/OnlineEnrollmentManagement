<div>
    @if (session('success'))
        <div class="bg-purple-500/10 border border-purple-500/20 text-purple-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3 animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden mt-6 relative"
         style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">


        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center sm:flex-row flex-col gap-4"
             style="background: rgba(255,255,255,0.02);">
            <h3 class="font-bold text-white flex items-center gap-2 text-lg">
                <span class="w-1 h-5 rounded-full bg-purple-500 inline-block shadow-[0_0_10px_rgba(167,139,250,0.5)]"></span>
                Students List
                <span class="text-white/40 text-sm font-normal ml-1"></span>
            </h3>
        </div>

        <div class="overflow-x-auto min-h-[400px] custom-scrollbar">
            <table class="w-full text-sm text-left text-white/70 min-w-[900px]">
                <thead class="text-sm text-purple-300 font-black uppercase tracking-widest border-b border-white/5"
                       style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th scope="col" class="px-6 py-5">Last Name</th>
                        <th scope="col" class="px-6 py-5">First Name</th>
                        <th scope="col" class="px-6 py-5 text-center">Email</th>
                        <th scope="col" class="px-6 py-5 text-center">Course</th>
                        <th scope="col" class="px-6 py-5 text-center">Year</th>
                        <th scope="col" class="px-6 py-5 text-center">Status</th>
                        <th scope="col" class="px-6 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($students as $student)
                        <tr wire:key="student-{{ $student->id }}" class="hover:bg-purple-500/[0.03] transition-all group">
                            <td class="px-6 py-5">
                                <span class="font-bold text-white uppercase tracking-tight group-hover:text-purple-300 transition-colors">
                                    {{ $student->last_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 font-bold text-white/90 uppercase tracking-tight">
                                {{ $student->first_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-white/40 lowercase text-sm font-medium">{{ $student->email }}</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest border border-purple-500/20 bg-purple-500/10 text-purple-400">
                                    {{ $student->program }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-white/40 text-xs font-bold uppercase tracking-widest">{{ $student->year_display }}</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-purple-500/10 text-purple-400 text-xs font-black px-3 py-1 rounded-full border border-purple-500/20 shadow-sm uppercase tracking-widest">
                                    {{ $student->status ?? 'Enrolled' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <button wire:click="edit({{ $student->id }})" class="text-purple-400 hover:text-white font-black text-xs uppercase tracking-[0.2em] transition-all py-2 px-4 rounded-lg bg-white/5 hover:bg-purple-500 border border-white/5 hover:border-purple-400 shadow-lg active:scale-95">
                                    Modify
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-20">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-xs font-black uppercase tracking-[0.4em] italic">No records found</p>
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


    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <div class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl" wire:click="closeModal"></div>

        <div class="bg-[#0d1f3c] w-full max-w-2xl rounded-[32px] shadow-[0_32px_120px_rgba(0,0,0,0.7)] border border-white/10 overflow-hidden flex flex-col relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Edit Student</h2>
                    <p class="text-xs text-white/30 uppercase tracking-[0.2em] mt-1 font-bold">Update Student Information</p>
                </div>
                <button wire:click="closeModal" class="text-white/20 hover:text-white transition-colors text-2xl font-light">&times;</button>
            </div>

            <form wire:submit.prevent="update" class="p-8 space-y-6">
                <div class="grid grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-white/40 uppercase tracking-widest ml-1">Given Name</label>
                        <input type="text" wire:model="first_name" class="w-full bg-white/5 text-white border border-white/10 rounded-xl p-3 text-sm focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none uppercase transition-all">
                        @error('first_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-white/40 uppercase tracking-widest ml-1">Middle</label>
                        <input type="text" wire:model="middle_name" class="w-full bg-white/5 text-white border border-white/10 rounded-xl p-3 text-sm focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none uppercase transition-all">
                        @error('middle_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-white/40 uppercase tracking-widest ml-1">Family Name</label>
                        <input type="text" wire:model="last_name" class="w-full bg-white/5 text-white border border-white/10 rounded-xl p-3 text-sm focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none uppercase transition-all">
                        @error('last_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" wire:model="email" class="w-full bg-white/5 text-white border border-white/10 rounded-xl p-3 text-sm focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                        @error('email') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-white/40 uppercase tracking-widest ml-1">Status</label>
                        <select wire:model="status" class="w-full bg-[#1a3a6e] text-white border border-white/10 rounded-xl p-3 text-sm focus:ring-1 focus:ring-purple-500 outline-none cursor-pointer appearance-none transition-all">
                            <option value="Not Enrolled">Not Enrolled</option>
                            <option value="Enrolled">Enrolled / Paid</option>
                            <option value="Active">Operational / Active</option>
                        </select>
                        @error('status') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8 border-t border-white/5">
                    <button type="button" wire:click="closeModal" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white/40 hover:text-white rounded-xl font-bold transition-all text-sm uppercase tracking-widest">Back</button>
                    <button type="submit" class="px-8 py-2.5 bg-purple-500 hover:bg-purple-400 text-white font-black rounded-xl shadow-lg shadow-purple-500/20 transition-all text-sm uppercase tracking-widest active:scale-95">
                        <span wire:loading.remove wire:target="update">Update</span>
                        <span wire:loading wire:target="update">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
