<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 text-blue-600 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="h-[calc(100vh-200px)] flex gap-6">
        <!-- LEFT SIDEBAR: STUDENT LIST -->
        <div class="w-80 flex flex-col bg-white border border-blue-500/10 rounded-[32px] shadow-xl shadow-blue-900/5 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-8 border-b border-blue-500/10 bg-blue-50/30">
                <div class="flex items-center gap-3 mb-5">
                    <div class="p-2 bg-white rounded-lg border border-blue-100 text-blue-600 shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <h3 class="font-black text-black uppercase text-sm tracking-tight">Student Directory</h3>
                </div>
                <div class="relative group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs text-black focus:border-blue-500/40 outline-none transition-all placeholder-slate-300 font-bold uppercase tracking-tight shadow-sm"
                        placeholder="SEARCH PROTOCOL...">
                </div>
            </div>

            <!-- Student List -->
            <div class="flex-grow overflow-y-auto custom-scrollbar divide-y divide-slate-100 bg-white">
                @forelse($payments as $enrollment)
                    @php
                        $isSelected = $selectedStudentId === $enrollment->user_id;
                        $student = $enrollment->user;
                    @endphp
                    <button wire:click="selectStudent({{ $enrollment->user_id }}, {{ $enrollment->id }})"
                        class="w-full text-left px-6 py-5 hover:bg-blue-50/50 transition-all {{ $isSelected ? 'bg-blue-50 border-l-4 border-blue-600' : 'border-l-4 border-transparent' }}">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="text-xs font-black {{ in_array($enrollment->status, ['Dropped','Withdrawn']) ? 'text-rose-500' : 'text-blue-600' }} uppercase tracking-wider">
                                {{ $student->last_name }}, {{ $student->first_name }}
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @if(in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-rose-50 border border-rose-100 text-rose-500 uppercase tracking-widest">
                                        {{ $enrollment->status }}
                                    </span>
                                @endif
                                @if($enrollment->voucher_type)
                                    <svg class="w-4 h-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                @endif
                            </div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $enrollment->course_code ?? 'N/A' }} • {{ $enrollment->year_level ?? 'N/A' }}</div>
                    </button>
                @empty
                    <div class="px-6 py-20 text-center bg-white">
                        <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.4em]">No active records</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: PAYMENT DETAILS -->
        <div class="flex-grow flex flex-col bg-white border border-blue-500/10 rounded-[32px] shadow-xl shadow-blue-900/5 overflow-y-auto overflow-x-hidden">
            @if($selectedStudentId)
                <!-- Header with Student Info & Action Buttons -->
                <div class="px-8 py-8 border-b border-blue-500/10 bg-blue-50/30">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3 mb-3 flex-wrap">
                                <h3 class="font-black text-black text-xl uppercase tracking-tight">{{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }}</h3>
                                @if($selectedVoucherType)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"/></svg>
                                        {{ $selectedVoucherType === 'free_tuition' ? 'Free Tuition' : 'Discounted' }}
                                    </span>
                                @endif
                                @if($enrollment && in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-rose-600 text-white shadow-lg">
                                        {{ $enrollment->status }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                ID: #{{ str_pad($selectedStudent->id, 3, '0', STR_PAD_LEFT) }} • {{ $enrollment->year_level ?? 'N/A' }} • {{ $enrollment->course_code ?? 'N/A' }}
                            </p>
                            <div class="flex items-center gap-2 mt-3 flex-wrap">
                                @php $stype = ucfirst(strtolower($enrollment->student_type ?? 'New')); @endphp
                                <span class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest bg-white border-blue-100 text-blue-600">
                                    {{ $stype }}
                                </span>
                                <span class="text-[9px] font-black px-3 py-1 rounded-full border border-slate-100 bg-white text-slate-400 uppercase tracking-widest">
                                    {{ $enrollment->is_regular ? 'REGULAR STATUS' : 'IRREGULAR PROTOCOL' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="setPaymentMode"
                                class="text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 border-2
                                    {{ !$isDropPayMode ? 'bg-white border-blue-600 text-black shadow-blue-500/10' : 'bg-white border-slate-100 text-slate-400 hover:text-blue-600 hover:bg-blue-50/30' }}">
                                Standard Payment
                            </button>
                            <button wire:click="setDropPayMode"
                                class="text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 border-2
                                    {{ $isDropPayMode ? 'bg-white border-amber-500 text-black shadow-amber-500/10' : 'bg-white border-slate-100 text-slate-400 hover:text-amber-500 hover:bg-amber-50/30' }}">
                                Drop Protocol
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-8 py-2 border-b border-blue-500/10 bg-white flex gap-10">
                    <button wire:click="$set('activeTab', 'assessment')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 border-b-2 transition-all {{ $activeTab === 'assessment' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-400' }}">
                        Assessment
                    </button>
                    <button wire:click="$set('activeTab', 'balance')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 border-b-2 transition-all {{ $activeTab === 'balance' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-400' }}">
                        Ledger
                    </button>
                    <button wire:click="$set('activeTab', 'history')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-4 border-b-2 transition-all {{ $activeTab === 'history' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-400' }}">
                        Transactions
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="px-10 py-8 flex-grow">
                    @if($activeTab === 'assessment')
                        <div class="space-y-8 animate-in slide-in-from-bottom-2 duration-300">
                            @if($enrollment && in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                                <div class="flex items-center gap-4 px-6 py-4 rounded-2xl bg-rose-50 border border-rose-100">
                                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    <div>
                                        <p class="text-rose-600 text-[10px] font-black uppercase tracking-widest">{{ $enrollment->status }} STATUS ACTIVE</p>
                                        <p class="text-rose-400/60 text-[9px] font-bold mt-1">Protocol deviation recorded by registrar.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-4">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Statement of Account</h4>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                        <span class="font-bold text-slate-600 text-xs uppercase">Tuition Fees</span>
                                        <span class="font-black text-black text-xs">₱ {{ number_format($tuitionFees, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                        <span class="font-bold text-slate-600 text-xs uppercase">Miscellaneous</span>
                                        <span class="font-black text-black text-xs">₱ {{ number_format($miscellaneousFees, 2) }}</span>
                                    </div>
                                    @if($appliedDiscount > 0)
                                    <div class="flex justify-between items-center py-2">
                                        <span class="font-bold text-rose-500 text-xs uppercase">Scholarship Credit</span>
                                        <span class="font-black text-rose-600 text-xs">(₱ {{ number_format($appliedDiscount, 2) }})</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between items-center pt-6 border-t-2 border-blue-500/10">
                                        <span class="font-black text-black text-xs uppercase tracking-widest">Total Assessment</span>
                                        <span class="font-black text-blue-600 text-lg tracking-tighter">₱ {{ number_format((float)$totalAssessment - (float)$appliedDiscount, 2) }}</span>
                                    </div>
                                </div>

                                <div class="bg-blue-50/30 p-8 rounded-[32px] border border-blue-500/10">
                                    <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-6">Discount Protocol</h4>
                                    @if($appliedDiscount > 0)
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="font-black text-emerald-600 text-[10px] uppercase tracking-widest">✓ Credit Verified</span>
                                                <span class="font-black text-emerald-500 text-xs">₱{{ number_format($appliedDiscount, 2) }}</span>
                                            </div>
                                            <button wire:click="removeDiscount" class="w-full bg-rose-50 border border-rose-100 text-rose-600 text-[10px] font-black py-4 px-6 rounded-2xl uppercase tracking-[0.2em] transition-all hover:bg-rose-100">
                                                Revoke Credit
                                            </button>
                                        </div>
                                    @else
                                        <div class="space-y-4">
                                            <label class="font-black text-slate-400 text-[10px] uppercase tracking-widest block">Input Discount Amount:</label>
                                            <div class="relative">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                                                <input type="number" wire:model="discountAmount" step="0.01" min="0"
                                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-black placeholder-slate-300 font-black text-sm focus:border-blue-500/40 outline-none transition-all shadow-sm"
                                                    placeholder="0.00">
                                            </div>
                                            <button wire:click="applyDiscount" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/20">
                                                Apply To Assessment
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @elseif($activeTab === 'balance')
                        <div class="flex items-center justify-center h-full animate-in zoom-in-95 duration-300">
                            <div class="text-center p-12 bg-blue-50/50 rounded-[40px] border border-blue-500/10 w-full max-w-sm">
                                <p class="text-slate-400 text-[10px] font-black uppercase mb-3 tracking-[0.4em]">Outstanding Ledger</p>
                                <p class="text-5xl font-black text-blue-600 tracking-tighter">₱ {{ number_format($currentBalance, 2) }}</p>
                                <div class="mt-6 flex justify-center">
                                    <span class="px-4 py-1 bg-white rounded-full text-[8px] font-black text-blue-600 border border-blue-100 uppercase tracking-widest shadow-sm">Status: Active Account</span>
                                </div>
                            </div>
                        </div>

                    @elseif($activeTab === 'history')
                        <div class="space-y-4 animate-in fade-in duration-300">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Historical Ledger</h4>
                            @forelse($paymentHistory as $transaction)
                                <div class="p-6 bg-white border border-slate-100 rounded-2xl shadow-sm space-y-4 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <span class="font-black text-black text-xs uppercase">TXN #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                <p class="text-[10px] text-slate-400 font-bold">{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($transaction->is_drop_payment)
                                                <span class="text-[8px] font-black px-3 py-1 rounded-full bg-amber-50 text-amber-500 border border-amber-100 uppercase tracking-widest">PROTOCOL: DROP</span>
                                            @endif
                                            <span class="text-[9px] font-black {{ $transaction->status === 'Paid' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-amber-500 bg-amber-50 border-amber-100' }} px-4 py-1.5 rounded-full border uppercase tracking-widest">
                                                {{ $transaction->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                                        <div>
                                            <p class="text-[9px] text-slate-400 font-black uppercase">Amount Settled</p>
                                            <p class="text-sm font-black text-blue-600">₱ {{ number_format($transaction->amount, 2) }}</p>
                                        </div>
                                        @if($transaction->reference_no)
                                        <div class="text-right">
                                            <p class="text-[9px] text-slate-400 font-black uppercase">Reference #</p>
                                            <p class="text-[10px] font-mono text-slate-700 font-bold uppercase">{{ $transaction->reference_no }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center bg-slate-50 border border-dashed border-slate-200 rounded-[32px]">
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No transaction records found</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- TRANSACTION ENTRY SECTION -->
                <div class="px-10 py-10 border-t {{ $isDropPayMode ? 'border-amber-500/20 bg-amber-50/20' : 'border-blue-500/10 bg-blue-50/20' }} rounded-b-[32px]">
                    <div class="flex items-center gap-3 mb-8">
                        <h4 class="font-black text-black uppercase tracking-tight text-base">Transaction Terminal</h4>
                        @if($isDropPayMode)
                            <span class="text-[9px] font-black px-3 py-1 rounded-full bg-amber-500 text-white uppercase tracking-widest shadow-lg shadow-amber-500/20">DROP PROTOCOL ACTIVE</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <div class="bg-white p-6 rounded-2xl border border-blue-500/10 shadow-sm flex flex-col justify-center text-center">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Balance Due</p>
                            <p class="font-black text-blue-600 text-2xl tracking-tighter">₱ {{ number_format($currentBalance, 2) }}</p>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] text-slate-400 font-black uppercase tracking-widest block ml-1">Payment Amount</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                                <input type="number" wire:model.live="amount" step="0.01" min="0"
                                    class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-black placeholder-slate-300 font-black text-base focus:border-blue-500/40 outline-none transition-all shadow-sm"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] text-slate-400 font-black uppercase tracking-widest block ml-1">Reference Code</label>
                            <input type="text" wire:model="reference_no"
                                class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl text-black placeholder-slate-300 font-black text-sm focus:border-blue-500/40 outline-none transition-all uppercase shadow-sm"
                                placeholder="OR/TXN #">
                        </div>
                    </div>

                    <button wire:click="submitPayment"
                        class="w-full bg-white border-2 {{ $isDropPayMode ? 'border-amber-500/40 hover:border-amber-500' : 'border-blue-500/30 hover:border-blue-500/60' }} text-black text-[10px] font-black py-6 px-8 rounded-2xl uppercase tracking-[0.3em] transition-all shadow-xl {{ $isDropPayMode ? 'shadow-amber-500/5' : 'shadow-blue-500/10' }} hover:bg-slate-50 active:scale-95 flex items-center justify-center gap-3">
                        <svg class="w-4 h-4 {{ $isDropPayMode ? 'text-amber-500' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        {{ $isDropPayMode ? 'Finalize Drop Transaction' : 'Authorize Payment Transfer' }}
                    </button>
                </div>
            @else
                <div class="flex-grow flex flex-col items-center justify-center p-20 text-center space-y-6">
                    <div class="w-24 h-24 bg-blue-50 rounded-[40px] flex items-center justify-center text-blue-200">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-black text-slate-200 uppercase tracking-[0.4em]">Terminal Standby</p>
                        <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest">Select student from directory to begin session</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <button wire:click="closeModal" class="absolute inset-0 bg-blue-900/40 backdrop-blur-md transition-all cursor-default"></button>
            <div class="bg-white border border-blue-500/20 w-full max-w-lg rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(30,58,138,0.2)] relative z-10 transform animate-in zoom-in-95 duration-300 p-12 text-center">
                 <!-- Add generic modal content here if needed, but the original was empty -->
                 <p class="text-black font-black uppercase tracking-widest">Protocol Verification in Progress</p>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</div>
