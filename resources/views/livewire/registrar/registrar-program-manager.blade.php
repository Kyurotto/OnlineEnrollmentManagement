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
                <div class="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-lg leading-tight uppercase tracking-tight">Programs</h3>
                    <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5"></p>
                </div>
            </div>
            <button wire:click="openModal" class="bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                New Program
            </button>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left font-medium text-sm">
                <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th class="py-5 px-8 font-black">ID</th>
                        <th class="py-5 px-8 font-black">Academic Program</th>
                        <th class="py-5 px-8 font-black">Description</th>
                        <th class="py-5 px-8 text-right font-black">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($programs as $program)
                    <tr wire:key="program-{{ $program->id }}" class="hover:bg-indigo-500/[0.03] transition-all group">
                        <td class="py-5 px-8 text-white/20 font-mono text-xs">#{{ str_pad($program->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-8">
                            <span class="text-white font-bold group-hover:text-indigo-300 transition-colors uppercase tracking-tight">{{ $program->course_name }}</span>
                        </td>
                        <td class="py-5 px-8">
                            <span class="text-white/40 text-sm line-clamp-1 font-medium">{{ $program->description ?: 'No operational description provided.' }}</span>
                        </td>
                        <td class="py-5 px-8 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <button wire:click="editModal({{ $program->id }})" class="p-2 rounded-lg bg-white/5 border border-white/5 text-white/40 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $program->id }})" wire:confirm="Delete this program?" class="p-2 rounded-lg bg-white/5 border border-white/5 text-rose-500/50 hover:text-white hover:bg-rose-500 transition-all">
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

        @if(method_exists($programs, 'links'))
            <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                {{ $programs->links('livewire.glass-pagination') }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <div class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl" wire:click="closeModal"></div>

        <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-lg rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="p-10">
                <div class="mb-10 text-center">
                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">{{ $isEditMode ? 'Edit Program' : 'New Program' }}</h3>
                    <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-1 italic">Program Information</p>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Program Title</label>
                        <input type="text" wire:model.defer="course_name"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-indigo-500/50 transition-all shadow-inner uppercase"
                            placeholder="e.g. BS Information Systems" required>
                        @error('course_name') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Description</label>
                        <textarea wire:model.defer="description" rows="4"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-indigo-500/50 transition-all shadow-inner resize-none"
                            placeholder="Define program scope..."></textarea>
                        @error('description') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-8 py-4 text-xs font-bold text-white/40 uppercase tracking-widest border border-white/10 rounded-xl hover:bg-white/5 hover:text-white transition-all">
                            Back
                        </button>
                        <button type="submit"
                            class="flex-1 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-black py-4 px-8 rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
