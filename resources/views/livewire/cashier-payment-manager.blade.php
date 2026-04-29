<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-blue-500/10 shadow-xl overflow-hidden">
        <div class="px-8 py-8 border-b border-blue-500/10 bg-blue-50/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-blue-600/10 text-blue-600 border border-blue-600/20 shadow-lg shadow-blue-500/5 transition-transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-black text-xl leading-none uppercase tracking-tight">
                            {{ $level ? strtoupper($level) : '' }} Payments Management
                        </h3>
                        @if($level)
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-600/10 text-blue-600 border-blue-600/20 border">
                                {{ $level === 'shs' ? 'Senior High' : 'College' }}
                            </span>
                        @endif
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">
                        Manage and Verify {{ $level ? ($level === 'shs' ? 'SHS' : 'College') : '' }} Student Collections
                    </p>
                </div>
            </div>
            <button wire:click="openCreateModal" class="bg-white border-2 border-blue-500/30 hover:border-blue-500 text-black text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-blue-500/10 active:scale-95 flex items-center gap-3 group">
                <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                New Payment
            </button>
        </div>

        <div class="bg-blue-50/10 px-8 py-5 border-b border-blue-500/10">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                        <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="pl-11 w-full bg-white border border-slate-200 rounded-xl py-3 text-sm text-black focus:border-blue-500/40 outline-none transition-all placeholder-slate-300 font-bold uppercase tracking-tight shadow-sm"
                        placeholder="Search by student name or reference number...">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="w-full sm:w-48 relative">
                        <select wire:model.live="filterCourse" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                            <option value="ALL">All Programs</option>
                            @foreach(['BSIS-1', 'BSIS-2', 'BSIS-3', 'BSIS-4', 'DIT-1', 'DIT-2', 'DIT-3', 'DIT-4'] as $course)
                                <option value="{{ $course }}">{{ str_replace('-', ' ', $course) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div class="w-full sm:w-40 relative">
                        <select wire:model.live="statusFilter" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                            <option value="All statuses">All Statuses</option>
                            @foreach(['Paid', 'Pending', 'Rejected'] as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <button wire:click="resetFilters" class="bg-slate-100 border border-slate-200 text-slate-400 hover:text-black hover:bg-slate-200 text-xs font-black uppercase tracking-widest px-6 flex items-center justify-center rounded-xl transition-all active:scale-95 shadow-sm">Reset</button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar bg-white">
            <table class="w-full text-left font-medium text-sm">
                <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 bg-slate-50/50">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="py-6 px-8 font-mono text-[10px] text-slate-300">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-6 px-8">
                            <div class="font-black text-black uppercase tracking-tight group-hover:text-blue-600 transition-colors text-xs">{{ optional($payment->user)->name ?? 'Unknown student' }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ optional($payment->user)->email }}</div>
                        </td>
                        <td class="py-6 px-8">
                            @if(optional($payment->application)->course_code)
                                <span class="font-black text-slate-700 uppercase tracking-widest text-[10px] block leading-tight">{{ $payment->application->course_code }}</span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 block leading-tight">{{ $payment->application->year_level }}</span>
                            @else
                                <span class="text-slate-200 text-[10px] font-black">NOT DOCUMENTED</span>
                            @endif
                        </td>
                        <td class="py-6 px-8">
                            <span class="text-slate-700 font-black uppercase tracking-[0.1em] text-[10px] block">{{ $payment->created_at->format('M d, Y') }}</span>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $payment->payment_method ?? 'Cash' }}</span>
                        </td>
                        <td class="py-6 px-8 text-right">
                            <span class="text-blue-600 font-black tracking-tighter text-sm">₱{{ number_format($payment->amount, 2) }}</span>
                        </td>
                        <td class="py-5 px-5 text-center whitespace-nowrap">
                            @if($payment->status === 'Paid')
                                <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-500/20 shadow-sm uppercase tracking-[0.1em]">Paid</span>
                            @elseif($payment->status === 'Rejected')
                                <span class="bg-rose-500/10 text-rose-500 text-[10px] font-black px-3 py-1 rounded-full border border-rose-500/20 shadow-sm uppercase tracking-[0.1em]">Rejected</span>
                            @else
                                <span class="bg-amber-500/10 text-amber-400 text-[10px] font-black px-3 py-1 rounded-full border border-amber-500/20 shadow-sm uppercase tracking-[0.1em]">Pending</span>
                            @endif
                        </td>
                        <td class="py-6 px-8 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-3 text-right">
                                <button wire:click="openEditModal({{ $payment->id }})" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border-2 border-blue-500/30 text-black hover:bg-blue-50 hover:border-blue-500 transition-all text-[10px] font-black uppercase tracking-widest group/btn shadow-lg shadow-blue-500/10 whitespace-nowrap">
                                    <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                @if($payment->status !== 'Paid' && $payment->status !== 'Rejected')
                                    <button wire:click="updateStatus({{ $payment->id }}, 'Paid')" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-400 transition-all active:scale-95 shadow-lg shadow-emerald-500/20 text-[10px] font-black uppercase tracking-widest">
                                        APPROVE
                                    </button>
                                    <button wire:click="updateStatus({{ $payment->id }}, 'Rejected')" class="px-5 py-2.5 rounded-xl bg-rose-500 text-white hover:bg-rose-400 transition-all active:scale-95 shadow-lg shadow-rose-500/20 text-[10px] font-black uppercase tracking-widest">
                                        REJECT
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-24 text-center">
                            <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] leading-loose">No payment records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
                {{ $payments->links('pagination') }}
            </div>
        @endif
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
                            <select wire:model="user_id" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm" required>
                                <option value="">CHOOSE APPLICANT</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
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
                                <input type="number" wire:model="amount" step="0.01"
                                    class="w-full bg-slate-50 text-blue-600 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-base font-black tracking-tighter focus:border-blue-500/40 transition-all shadow-sm" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Channel</label>
                            <div class="relative group">
                                <select wire:model="payment_type" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-black tracking-widest focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm uppercase" required>
                                    @foreach(['Cash', 'Gcash', 'PayMaya', 'Bank Transfer'] as $method)
                                        <option value="{{ $method }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference Number (OR/DOC)</label>
                        <input type="text" wire:model="reference_no"
                            class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all uppercase shadow-sm"
                            placeholder="INPUT OR NUMBER...">
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-8">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-black transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black py-5 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            {{ $isEditMode ? 'Update Payment' : 'Confirm Payment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
