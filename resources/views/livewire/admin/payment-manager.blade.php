<div>
    @if(session('success'))
        <div class="bg-[#3b82f6]/10 border border-[#3b82f6]/20 text-[#3b82f6] px-4 py-3 rounded-lg relative mb-6 flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-blue-500/10 shadow-xl overflow-hidden relative">
        <div class="px-8 py-8 border-b border-blue-500/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-blue-50/30">
            <div>
                <h2 class="text-2xl font-black text-black tracking-tight uppercase">Payments History</h2>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Manage and verify student transactions.</p>
            </div>
            <button wire:click="prepareCreate" class="bg-white border-2 border-blue-500/30 hover:border-blue-500 text-black text-[10px] font-black px-8 py-4 rounded-2xl transition-all shadow-lg shadow-blue-500/10 uppercase tracking-[0.2em] flex items-center gap-3 group active:scale-95">
                <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                New Payment
            </button>
        </div>

        <div class="bg-blue-50/10 px-8 py-6 border-b border-blue-500/10">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                        <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="pl-11 w-full bg-white border border-slate-200 rounded-xl py-3 text-sm text-black focus:border-blue-500/40 outline-none transition-all placeholder-slate-300 font-bold uppercase tracking-tight shadow-sm" placeholder="Search student name, email, or receipt #...">
                </div>
                <div class="w-full sm:w-48 relative">
                    <select wire:model.live="filter_course" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                        <option value="ALL">All Programs</option>
                        <option value="BSIS-1">BSIS 1</option>
                        <option value="BSIS-2">BSIS 2</option>
                        <option value="BSIS-3">BSIS 3</option>
                        <option value="BSIS-4">BSIS 4</option>
                        <option value="DIT-1">DIT 1</option>
                        <option value="DIT-2">DIT 2</option>
                        <option value="DIT-3">DIT 3</option>
                        <option value="DIT-4">DIT 4</option>
                        <option value="ACT-1">ACT 1</option>
                        <option value="ACT-2">ACT 2</option>
                        <option value="ACT-3">ACT 3</option>
                        <option value="DHRT-1">DHRT 1</option>
                        <option value="DHRT-2">DHRT 2</option>
                        <option value="DHRT-3">DHRT 3</option>
                        <option value="BTVTED-1">BTVTED 1</option>
                        <option value="BTVTED-2">BTVTED 2</option>
                        <option value="BTVTED-3">BTVTED 3</option>
                        <option value="BTVTED-4">BTVTED 4</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <div class="w-full sm:w-40 relative">
                    <select wire:model.live="status" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                        <option value="All statuses">All Statuses</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="text-[10px] text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 bg-slate-50/50">
                        <th class="py-6 px-8 font-black">Receipt #</th>
                        <th class="py-6 px-8 font-black">Student Name</th>
                        <th class="py-6 px-8 font-black">Program/Year</th>
                        <th class="py-6 px-8 font-black">Date</th>
                        <th class="py-6 px-8 font-black text-right">Amount</th>
                        <th class="py-6 px-8 font-black text-center">Status</th>
                        <th class="py-6 px-8 font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 bg-white">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="py-6 px-8 font-mono text-[10px] text-slate-300">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-6 px-8">
                            <div class="font-black text-black uppercase tracking-tight group-hover:text-blue-600 transition-colors text-xs">{{ optional($payment->user)->name ?? 'Unknown' }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ optional($payment->user)->email }}</div>
                        </td>
                        <td class="py-6 px-8 text-slate-700">@if(optional($payment->application)->course_code)<span class="font-black text-slate-700 uppercase tracking-widest text-[10px] block leading-tight">{{ $payment->application->course_code }}</span><span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 block leading-tight">{{ $payment->application->year_level }}</span>@else<span class="text-slate-200 text-[10px] font-black">N/A</span>@endif</td>
                        <td class="py-6 px-8 text-slate-700 font-black uppercase tracking-[0.1em] text-[10px] block">{{ $payment->created_at->format('M d, Y') }}<span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 block">{{ $payment->payment_method ?? 'Cash' }}</span></td>
                        <td class="py-6 px-8 font-black text-blue-600 tracking-tighter text-sm text-right">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="py-6 px-8 text-center whitespace-nowrap">
                            @if($payment->status === 'Paid') <span class="bg-blue-600/10 text-blue-600 text-[10px] font-black px-3 py-1 rounded-full border border-blue-600/20 shadow-sm uppercase tracking-[0.1em]">Paid</span>
                            @elseif($payment->status === 'Rejected') <span class="bg-rose-500/10 text-rose-500 text-[10px] font-black px-3 py-1 rounded-full border border-rose-500/20 shadow-sm uppercase tracking-[0.1em]">Rejected</span>
                            @else <span class="bg-amber-500/10 text-amber-600 text-[10px] font-black px-3 py-1 rounded-full border border-amber-500/20 shadow-sm uppercase tracking-[0.1em]">Pending</span> @endif
                        </td>
                        <td class="py-6 px-8 text-right whitespace-nowrap">
                             <div class="flex justify-end gap-3 text-right">
                                <button wire:click="editPayment({{ $payment->id }})" class="p-2.5 text-blue-600 bg-white border-2 border-blue-500/30 hover:border-blue-500 hover:bg-blue-50 rounded-xl transition-all shadow-lg shadow-blue-500/5 group/btn" title="Edit Details">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                @if($payment->status !== 'Paid')
                                <button wire:click="updateStatus({{ $payment->id }}, 'Paid')" class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-500 border-2 border-transparent rounded-xl transition-all group/paid" title="Mark as Paid">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                @endif
                             </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-12 text-center text-gray-500 text-sm">No payment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">            {{ $payments->links('pagination') }}
 </div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <button wire:click="closeModal" class="absolute inset-0 bg-blue-900/40 backdrop-blur-md transition-all cursor-default"></button>

        <div class="bg-white border border-blue-500/20 w-full max-w-xl rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(30,58,138,0.2)] relative z-10 transform animate-in zoom-in-95 duration-300">
            <div class="p-12">
                <div class="mb-10 text-center">
                    <h3 class="text-2xl font-black text-black uppercase tracking-tight leading-none">{{ $isEditMode ? 'Update Payment' : 'New Payment' }}</h3>
                    <p class="text-blue-600 text-[10px] font-black uppercase tracking-[0.4em] mt-3">Payment Protocol Review</p>
                </div>

                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="space-y-8">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Select Student</label>
                        <div class="relative group">
                            <select wire:model="payment_user_id" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm" required>
                                <option value="" disabled>Select Student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount To Pay</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                                <input type="number" wire:model="payment_amount" step="0.01" class="w-full bg-slate-50 text-blue-600 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-base font-black tracking-tighter focus:border-blue-500/40 transition-all shadow-sm" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Channel</label>
                            <div class="relative group">
                                <select wire:model="payment_type" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-black tracking-widest focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm uppercase" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Gcash">Gcash</option>
                                    <option value="PayMaya">PayMaya</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference Number (OR/DOC)</label>
                        <input type="text" wire:model="payment_reference_no" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all uppercase shadow-sm" placeholder="OR NUMBER">
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-8">
                        <button type="button" wire:click="closeModal" class="flex-1 px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:text-black transition-all">Cancel Protocol</button>
                        <button type="submit" class="flex-1 bg-white border-2 border-blue-500/30 text-black text-[10px] font-black py-5 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/10 hover:bg-blue-50 hover:border-blue-500/50 active:scale-95 flex items-center justify-center gap-3">
                            <span wire:loading.remove wire:target="{{ $isEditMode ? 'update' : 'store' }}" class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                {{ $isEditMode ? 'Update Payment' : 'Process Payment' }}
                            </span>
                            <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
