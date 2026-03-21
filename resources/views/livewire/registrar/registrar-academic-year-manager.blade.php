<div class="space-y-6">
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
                    <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5"></p>
                </div>
            </div>
            <button wire:click="openModal" class="bg-amber-500 hover:bg-amber-400 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-amber-500/20 active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                New Academic Year
            </button>
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
                    <tr wire:key="year-{{ $year->id }}" class="hover:bg-amber-500/[0.03] transition-all group">
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
                                <button wire:click="editModal({{ $year->id }})" class="p-2 rounded-lg bg-white/5 border border-white/5 text-white/40 hover:text-amber-400 hover:bg-amber-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $year->id }})" wire:confirm="Terminate this record?" class="p-2 rounded-lg bg-white/5 border border-white/5 text-rose-500/50 hover:text-white hover:bg-rose-500 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <p class="text-xs font-black text-white/20 uppercase tracking-[0.4em] italic">No records found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($years->hasPages())
            <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                {{ $years->links('livewire.glass-pagination') }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <div class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl" wire:click="closeModal"></div>

        <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-md rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="p-10">
                <div class="mb-10 text-center">
                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">{{ $isEditMode ? 'Edit Academic Year' : 'New Academic Year' }}</h3>
                    <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-1">Academic Year Information</p>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Period Designation</label>
                        <input type="text" wire:model.defer="year_name"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-amber-500/50 transition-all shadow-inner"
                            placeholder="e.g. 2025 - 2026" required>
                        @error('year_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative group overflow-hidden rounded-xl bg-white/5 border border-white/10 p-5 hover:bg-white/10 transition-all cursor-pointer">
                        <label class="flex items-center gap-4 cursor-pointer">
                            <input type="checkbox" wire:model.defer="is_active" id="is_active"
                                class="w-5 h-5 rounded border-white/10 bg-white/5 text-amber-500 focus:ring-amber-500 transition-all">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-white uppercase tracking-wider">Set Operational</span>
                                <span class="text-xs text-white/30 uppercase tracking-widest mt-0.5 font-bold">Current Active Phase</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-8 py-4 text-xs font-bold text-white/40 uppercase tracking-widest border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition-all">
                            Back
                        </button>
                        <button type="submit"
                            class="flex-1 bg-amber-500 hover:bg-amber-400 text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-amber-500/20 active:scale-95">
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
