<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden relative shadow-2xl shadow-black/40"
         style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1);">


        <div class="px-8 py-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white/[0.01]">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-lg shadow-emerald-500/5 transition-transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h3 class="font-black text-white text-xl leading-none uppercase tracking-tight">Payments Management</h3>
                    <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-2">Manage and Verify Student Collections</p>
                </div>
            </div>
            <button wire:click="openCreateModal" class="bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                New Payment
            </button>
        </div>

        <div class="bg-white/[0.02] px-8 py-5 border-b border-white/5">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-emerald-400">
                        <svg class="h-4 w-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="pl-11 w-full bg-white/5 border border-white/10 rounded-xl py-3 text-sm text-white focus:border-emerald-500/50 outline-none transition-all placeholder-white/10 font-bold uppercase tracking-tight shadow-inner"
                           placeholder="Search by student name or reference number...">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="w-full sm:w-48 relative">
                        <select wire:model.live="filter_course" class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-5 text-xs text-white/60 font-black uppercase tracking-widest focus:border-emerald-500/50 outline-none cursor-pointer appearance-none transition-all shadow-inner">
                            <option value="ALL" class="bg-[#0d1f3c]">All Programs</option>
                            <option value="BSIS-1" class="bg-[#0d1f3c]">BSIS 1</option>
                            <option value="BSIS-2" class="bg-[#0d1f3c]">BSIS 2</option>
                            <option value="BSIS-3" class="bg-[#0d1f3c]">BSIS 3</option>
                            <option value="BSIS-4" class="bg-[#0d1f3c]">BSIS 4</option>
                            <option value="DIT-1" class="bg-[#0d1f3c]">DIT 1</option>
                            <option value="DIT-2" class="bg-[#0d1f3c]">DIT 2</option>
                            <option value="DIT-3" class="bg-[#0d1f3c]">DIT 3</option>
                            <option value="DIT-4" class="bg-[#0d1f3c]">DIT 4</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div class="w-full sm:w-40 relative">
                        <select wire:model.live="status" class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-5 text-xs text-white/60 font-black uppercase tracking-widest focus:border-emerald-500/50 outline-none cursor-pointer appearance-none transition-all shadow-inner">
                            <option value="" class="bg-[#0d1f3c]">All Statuses</option>
                            <option value="Paid" class="bg-[#0d1f3c]">Paid</option>
                            <option value="Pending" class="bg-[#0d1f3c]">Pending</option>
                            <option value="Rejected" class="bg-[#0d1f3c]">Rejected</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <button wire:click="resetFilters" class="bg-white/5 border border-white/10 text-white/40 hover:text-white hover:bg-white/10 text-xs font-black uppercase tracking-widest px-6 rounded-xl transition-all active:scale-95 shadow-lg">Reset</button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left font-medium text-sm">
                <thead class="text-xs text-white/20 uppercase tracking-[0.2em] border-b border-white/5" style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="py-6 px-8 font-black">ID</th>
                        <th class="py-6 px-8 font-black">Student Details</th>
                        <th class="py-6 px-8 font-black">Program & Year</th>
                        <th class="py-6 px-8 font-black">Date</th>
                        <th class="py-6 px-8 text-right font-black">Amount</th>
                        <th class="py-6 px-8 text-center font-black">Status</th>
                        <th class="py-6 px-8 text-right font-black">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($payments as $payment)
                    <tr wire:key="payment-{{ $payment->id }}" class="hover:bg-emerald-500/[0.03] transition-all group">
                        <td class="py-6 px-8 font-mono text-xs text-white/20 italic">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-6 px-8">
                            <div class="font-black text-white uppercase tracking-tight group-hover:text-emerald-400 transition-colors text-sm">{{ optional($payment->user)->name ?? 'Unknown student' }}</div>
                            <div class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1 italic">{{ optional($payment->user)->email }}</div>
                        </td>
                        <td class="py-6 px-8">
                            @if(optional($payment->application)->course_code)
                                <span class="font-black text-white/60 uppercase tracking-widest text-xs block">{{ $payment->application->course_code }}</span>
                                <span class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1">{{ $payment->application->year_level }}</span>
                            @else
                                <span class="text-white/10 italic text-xs font-black">NOT DOCUMENTED</span>
                            @endif
                        </td>
                        <td class="py-6 px-8">
                            <span class="text-white/60 font-black uppercase tracking-[0.1em] text-xs block">{{ $payment->created_at->format('M d, Y') }}</span>
                            <span class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1 italic">{{ $payment->payment_method ?? 'Cash' }}</span>
                        </td>
                        <td class="py-6 px-8 text-right">
                            <span class="text-emerald-400 font-black tracking-tighter text-base">₱{{ number_format($payment->amount, 2) }}</span>
                        </td>
                        <td class="py-6 px-8 text-center whitespace-nowrap">
                            @if($payment->status === 'Paid')
                                <span class="bg-emerald-500/10 text-emerald-400 text-xs font-black px-4 py-1.5 rounded-full border border-emerald-500/20 shadow-sm uppercase tracking-widest">Paid</span>
                            @elseif($payment->status === 'Rejected')
                                <span class="bg-rose-500/10 text-rose-500 text-xs font-black px-4 py-1.5 rounded-full border border-rose-500/20 shadow-sm uppercase tracking-widest">Rejected</span>
                            @else
                                <span class="bg-amber-500/10 text-amber-400 text-xs font-black px-4 py-1.5 rounded-full border border-amber-500/20 shadow-sm uppercase tracking-widest">Pending</span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <button wire:click="editPayment({{ $payment->id }})" class="p-2.5 rounded-xl bg-white/5 border border-white/5 text-white/30 hover:text-emerald-400 hover:bg-emerald-500/10 hover:border-emerald-500/30 transition-all active:scale-95 shadow-lg group/btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                @if($payment->status !== 'Paid' && $payment->status !== 'Rejected')
                                    <button wire:click="markAsPaid({{ $payment->id }})" class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-black transition-all active:scale-95 shadow-lg shadow-emerald-500/10 group/paid" title="Mark as Paid">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button wire:click="rejectPayment({{ $payment->id }})" class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-lg shadow-rose-500/10 group/reject" title="Reject Payment">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-24 text-center">
                            <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic leading-loose">No payment records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
            {{ $payments->links('livewire.glass-pagination') }}
        </div>
    </div>

    {{-- Refined Modal System --}}
    @if($showModal)
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <div class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl transition-all" wire:click="closeModal"></div>

        <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-lg rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="p-10">
                <div class="mb-10 text-center">
                    <h3 class="text-xl font-black text-white uppercase tracking-tight leading-none">{{ $isEditMode ? 'Edit Payment' : 'New Payment' }}</h3>
                    <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-2 italic shadow-sm leading-none">Payment Information</p>
                </div>

                <form wire:submit.prevent="savePayment" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Student</label>
                        <select wire:model="payment_user_id" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 appearance-none transition-all cursor-pointer shadow-inner" required>
                            <option value="" class="bg-[#0d1f3c]">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" class="bg-[#0d1f3c] text-white">{{ $student->name }}</option>
                            @endforeach
                        </select>
                        @error('payment_user_id') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Amount</label>
                            <input type="number" wire:model="payment_amount" step="0.01"
                                class="w-full bg-white/5 text-emerald-400 border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-base font-black tracking-tighter focus:border-emerald-500/50 transition-all shadow-inner" placeholder="0.00" required>
                            @error('payment_amount') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2 hover:translate-y-[-2px] transition-transform">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Payment Method</label>
                            <select wire:model="payment_type" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 appearance-none transition-all cursor-pointer shadow-inner uppercase font-black tracking-widest" required>
                                <option value="Cash" class="bg-[#0d1f3c]">Cash</option>
                                <option value="Gcash" class="bg-[#0d1f3c]">Gcash</option>
                                <option value="PayMaya" class="bg-[#0d1f3c]">PayMaya</option>
                                <option value="Bank Transfer" class="bg-[#0d1f3c]">Bank Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Reference No.</label>
                        <input type="text" wire:model="payment_reference_no"
                            class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 transition-all uppercase shadow-inner"
                            placeholder="DOC NO / OR NO">
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-6">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-8 py-5 text-xs font-black text-white/40 uppercase tracking-widest border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white transition-all">
                            Back
                        </button>
                        <button type="submit"
                            class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-black py-5 px-8 rounded-2xl uppercase tracking-widest transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
                            <span wire:loading.remove wire:target="savePayment">{{ $isEditMode ? 'Update' : 'Save' }}</span>
                            <span wire:loading wire:target="savePayment">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
