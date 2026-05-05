<div> <!-- SINGLE ROOT WRAPPER FOR LIVEWIRE -->

    <!-- TOP SECTION: SIDEBAR LAYOUT -->
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-blue-50 border border-blue-200 text-blue-600 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
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
                                    {{ optional($student)->last_name }}, {{ optional($student)->first_name }}
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
            <div class="flex-grow flex flex-col bg-white border border-blue-500/10 rounded-[32px] shadow-xl shadow-blue-900/5 overflow-y-auto overflow-x-hidden custom-scrollbar">
                @if($selectedStudentId)

                    <!-- BLADE DYNAMIC CALCULATION (DOES NOT TOUCH PHP LOGIC) -->
                    @php
                        $isNewTermActive = (float)($totalAssessment ?? 0) > 0;

                        $uiTuition = (float)($tuitionFees ?? 0);
                        $uiMisc = (float)($miscellaneousFees ?? 0);
                        $uiDiscount = (float)($appliedDiscount ?? 0);
                        $uiCurrentTermAssessment = max(0, ($uiTuition + $uiMisc) - $uiDiscount);

                        $paidSoFar = isset($paymentHistory) ? collect($paymentHistory)->where('status', 'Paid')->sum('amount') : 0;
                        $prevDebt = (float)($previousBalance ?? 0);

                        $actualRemainingPrevious = max(0, $prevDebt - $paidSoFar);
                        $actualRemainingCurrent = max(0, $uiCurrentTermAssessment - max(0, $paidSoFar - $prevDebt));

                        $totalOutstanding = $actualRemainingPrevious + $actualRemainingCurrent;
                    @endphp

                    <!-- Header with Student Info -->
                    <div class="px-8 py-8 border-b border-blue-500/10 bg-blue-50/30">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3 mb-3 flex-wrap">
                                    <h3 class="font-black text-black text-xl uppercase tracking-tight">{{ optional($selectedStudent)->last_name }}, {{ optional($selectedStudent)->first_name }}</h3>
                                    @if($selectedVoucherType)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
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
                                    ID: #{{ str_pad(optional($selectedStudent)->id, 3, '0', STR_PAD_LEFT) }} • {{ optional($enrollment)->year_level ?? 'N/A' }} • {{ optional($enrollment)->course_code ?? 'N/A' }}
                                </p>
                                <div class="flex items-center gap-2 mt-3 flex-wrap">
                                    @php $stype = ucfirst(strtolower(optional($enrollment)->student_type ?? 'New')); @endphp
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest bg-white border-blue-100 text-blue-600">
                                        {{ $stype }}
                                    </span>
                                    <span class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest {{ optional($enrollment)->is_regular ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                        {{ optional($enrollment)->is_regular ? 'REGULAR' : 'IRREGULAR' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button wire:click="setPaymentMode" class="text-[10px] font-black py-2.5 px-6 rounded-full uppercase tracking-widest transition-all {{ !$isDropPayMode ? 'bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/30' : 'bg-white border border-slate-200 text-blue-600 hover:bg-slate-50' }}">
                                    Payment
                                </button>
                                <button wire:click="setDropPayMode" class="text-[10px] font-black py-2.5 px-6 rounded-full uppercase tracking-widest transition-all {{ $isDropPayMode ? 'bg-amber-500 hover:bg-amber-400 text-white shadow-lg shadow-amber-500/30' : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-50' }}">
                                    Drop Pay
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
                            Pay Fees
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
                                            <span class="font-black text-black text-xs">₱ {{ number_format($uiTuition, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                            <span class="font-bold text-slate-600 text-xs uppercase">Miscellaneous</span>
                                            <span class="font-black text-black text-xs">₱ {{ number_format($uiMisc, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                            <span class="font-bold text-slate-600 text-xs uppercase">Discounted</span>
                                            <span class="font-black {{ $uiDiscount > 0 ? 'text-rose-600' : 'text-slate-300' }} text-xs">{{ $uiDiscount > 0 ? '-' : '' }} ₱ {{ number_format($uiDiscount, 2) }}</span>
                                        </div>

                                        <div class="flex justify-between items-center pt-6 border-t-2 border-blue-500/10">
                                            <div class="flex flex-col">
                                                <span class="font-black text-black text-xs uppercase tracking-widest">Total Assessment</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">Current Term Only</span>
                                            </div>
                                            <!-- Total Assessment strictly shows the current semester ONLY (no previous balance added here) -->
                                            <span class="font-black text-blue-600 text-lg tracking-tighter">
                                                ₱ {{ number_format($uiCurrentTermAssessment, 2) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="bg-blue-50/30 p-8 rounded-[32px] border border-blue-500/10">
                                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-6">Discount Protocol</h4>
                                        @if($uiDiscount > 0)
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-black text-emerald-600 text-[10px] uppercase tracking-widest">✓ Discount Verified</span>
                                                    <span class="font-black text-emerald-500 text-xs">₱{{ number_format($uiDiscount, 2) }}</span>
                                                </div>
                                                <button wire:click="removeDiscount" class="w-full mt-4 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 font-black py-2.5 px-4 rounded-xl text-[9px] uppercase tracking-[0.2em] transition-all shadow-sm">
                                                    Remove the Discounted
                                                </button>
                                            </div>
                                        @else
                                            <div class="space-y-5 w-full">
                                                <div class="space-y-2">
                                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Discount (%)</label>
                                                    <div class="relative group">
                                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 font-black text-[10px]">%</span>
                                                        <input type="number" wire:model="discountPercentage" step="0.01" min="0" max="100" autocomplete="off"
                                                            class="w-full px-4 py-3 bg-white border border-blue-500/10 rounded-2xl text-slate-800 placeholder-slate-200 font-black text-[11px] focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all shadow-sm"
                                                            placeholder="0">
                                                    </div>
                                                </div>
                                                <button wire:click="applyDiscount" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-3.5 px-5 rounded-2xl uppercase tracking-[0.2em] transition-all text-[9px] shadow-lg shadow-blue-600/20 active:scale-95">
                                                    Apply Protocol
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @elseif($activeTab === 'balance')
                            <div class="space-y-6 animate-in zoom-in-95 duration-300">

                                <!-- SEPARATED SUMMARY CARDS (Para hindi maguluhan ang Cashier) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- PREVIOUS BALANCE CARD -->
                                    <div class="bg-rose-50/50 border border-rose-100 rounded-[32px] p-6 shadow-sm relative overflow-hidden">
                                        <div class="relative z-10">
                                            <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-[0.2em] mb-1">Previous Balance</h4>
                                            <span class="text-3xl font-black text-rose-600 tracking-tighter block mb-3">₱ {{ number_format($actualRemainingPrevious, 2) }}</span>

                                            <div class="space-y-1 mt-4 pt-4 border-t border-rose-200/50">
                                                <div class="flex justify-between text-[9px] font-black text-rose-800/60 uppercase">
                                                    <span>Original Debt:</span><span>₱ {{ number_format($prevDebt, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between text-[9px] font-black text-rose-800/60 uppercase">
                                                    <span>Paid:</span><span>₱ {{ number_format(min($paidSoFar, $prevDebt), 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($actualRemainingPrevious <= 0 && $prevDebt > 0)
                                            <div class="absolute inset-0 bg-emerald-500/90 flex flex-col items-center justify-center z-20 transition-all">
                                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-white font-black text-[10px] uppercase tracking-widest">Fully Settled</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- CURRENT ASSESSMENT CARD -->
                                    <div class="bg-blue-50/50 border border-blue-100 rounded-[32px] p-6 shadow-sm relative overflow-hidden">
                                        <div class="relative z-10">
                                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-1">Current Assessment</h4>
                                            <span class="text-3xl font-black text-blue-600 tracking-tighter block mb-3">₱ {{ number_format($actualRemainingCurrent, 2) }}</span>

                                            <div class="space-y-1 mt-4 pt-4 border-t border-blue-200/50">
                                                <div class="flex justify-between text-[9px] font-black text-blue-800/60 uppercase">
                                                    <span>Net Payable:</span><span>₱ {{ number_format($uiCurrentTermAssessment, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between text-[9px] font-black text-blue-800/60 uppercase">
                                                    <span>Paid:</span><span>₱ {{ number_format(max(0, $paidSoFar - $prevDebt), 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($actualRemainingCurrent <= 0 && $uiCurrentTermAssessment > 0)
                                            <div class="absolute inset-0 bg-emerald-500/90 flex flex-col items-center justify-center z-20 transition-all">
                                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-white font-black text-[10px] uppercase tracking-widest">Fully Settled</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- THE CHRONOLOGICAL HISTORY TABLE (Keep this so history isn't removed) -->
                                <div class="bg-white border border-blue-500/10 rounded-[32px] shadow-sm overflow-hidden">
                                    <div class="bg-blue-50/30 border-b border-blue-500/10 px-8 py-6 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-lg font-black text-black tracking-tight uppercase">Statement of Accounts</h3>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Chronological history of assessments and payments</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Total Outstanding</span>
                                            <span class="text-2xl font-black text-blue-600 tracking-tighter">₱ {{ number_format($totalOutstanding, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto custom-scrollbar p-2">
                                        <table class="w-full text-left font-medium text-sm">
                                            <thead class="text-[9px] text-slate-400 uppercase tracking-[0.2em] border-b-2 border-slate-50">
                                                <tr>
                                                    <th class="px-6 py-4 font-black">Date</th>
                                                    <th class="px-6 py-4 font-black">Particulars</th>
                                                    <th class="px-6 py-4 text-right font-black">Debit (Charges)</th>
                                                    <th class="px-6 py-4 text-right font-black">Credit (Payments)</th>
                                                    <th class="px-6 py-4 text-right font-black">Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-50">
                                                @if($prevDebt > 0)
                                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                                    <td class="px-6 py-5 text-slate-400 font-bold whitespace-nowrap text-[11px] uppercase tracking-wider">{{ isset($enrollment) && $enrollment ? $enrollment->created_at->format('M d, Y') : '-' }}</td>
                                                    <td class="px-6 py-5 font-black text-slate-700 uppercase tracking-tight text-xs">Previous Balance Carried Over</td>
                                                    <td class="px-6 py-5 text-right text-rose-500 font-black tracking-tighter">₱ {{ number_format($prevDebt, 2) }}</td>
                                                    <td class="px-6 py-5 text-right text-slate-300 font-bold">-</td>
                                                    <td class="px-6 py-5 text-right font-black text-black tracking-tighter">₱ {{ number_format($prevDebt, 2) }}</td>
                                                </tr>
                                                @endif

                                                @if(($uiTuition + $uiMisc) > 0)
                                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                                    <td class="px-6 py-5 text-slate-400 font-bold whitespace-nowrap text-[11px] uppercase tracking-wider">{{ isset($enrollment) && $enrollment ? $enrollment->created_at->format('M d, Y') : '-' }}</td>
                                                    <td class="px-6 py-5 font-black text-slate-700 uppercase tracking-tight text-xs">
                                                        Term Assessment
                                                        <span class="text-[9px] text-slate-400 font-bold tracking-widest block mt-1">Tuition & Miscellaneous Fees</span>
                                                    </td>
                                                    <td class="px-6 py-5 text-right text-rose-500 font-black tracking-tighter">₱ {{ number_format($uiTuition + $uiMisc, 2) }}</td>
                                                    <td class="px-6 py-5 text-right text-slate-300 font-bold">-</td>
                                                    <td class="px-6 py-5 text-right font-black text-black tracking-tighter">₱ {{ number_format($prevDebt + $uiTuition + $uiMisc, 2) }}</td>
                                                </tr>
                                                @endif

                                                @if($uiDiscount > 0)
                                                <tr class="bg-emerald-50/30 hover:bg-emerald-50/50 transition-colors group">
                                                    <td class="px-6 py-5 text-emerald-600/60 font-bold whitespace-nowrap text-[11px] uppercase tracking-wider">{{ isset($enrollment) && $enrollment ? $enrollment->created_at->format('M d, Y') : '-' }}</td>
                                                    <td class="px-6 py-5 font-black text-emerald-600 uppercase tracking-tight text-xs">Discount Applied</td>
                                                    <td class="px-6 py-5 text-right text-emerald-300 font-bold">-</td>
                                                    <td class="px-6 py-5 text-right text-emerald-500 font-black tracking-tighter">₱ {{ number_format($uiDiscount, 2) }}</td>
                                                    <td class="px-6 py-5 text-right font-black text-black tracking-tighter">₱ {{ number_format($prevDebt + $uiTuition + $uiMisc - $uiDiscount, 2) }}</td>
                                                </tr>
                                                @endif

                                                @php
                                                    $runningBalance = $prevDebt + $uiTuition + $uiMisc - $uiDiscount;
                                                @endphp

                                                @if(isset($paymentHistory))
                                                    @forelse(collect($paymentHistory)->where('status', 'Paid')->sortBy('payment_date') as $payment)
                                                        @php
                                                            $runningBalance -= (float)$payment->amount;
                                                        @endphp
                                                        <tr class="hover:bg-blue-50/30 transition-colors group">
                                                            <td class="px-6 py-5 text-slate-400 font-bold whitespace-nowrap text-[11px] uppercase tracking-wider">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : $payment->created_at->format('M d, Y') }}</td>
                                                            <td class="px-6 py-5 font-black text-slate-700 uppercase tracking-tight text-xs">
                                                                Payment Received
                                                                <span class="block text-[9px] text-slate-400 font-bold tracking-widest mt-1">Method: {{ $payment->payment_method }} | Ref: {{ $payment->transaction_id ?? 'N/A' }}</span>
                                                            </td>
                                                            <td class="px-6 py-5 text-right text-slate-300 font-bold">-</td>
                                                            <td class="px-6 py-5 text-right text-emerald-500 font-black tracking-tighter">₱ {{ number_format($payment->amount, 2) }}</td>
                                                            <td class="px-6 py-5 text-right font-black text-black tracking-tighter">₱ {{ number_format(max(0, $runningBalance), 2) }}</td>
                                                        </tr>
                                                    @empty
                                                        @if(($uiTuition + $uiMisc) == 0 && $prevDebt == 0)
                                                            <tr>
                                                                <td colspan="5" class="py-16 text-center">
                                                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">No Payment records found</p>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforelse
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        @elseif($activeTab === 'history')
                            <!-- TRANSACTIONS TAB -->
                            <div class="space-y-4 animate-in fade-in duration-300">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Historical Transaction</h4>
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

                    @if($activeTab === 'assessment')
                    <div x-data="{ target: '{{ $actualRemainingPrevious > 0 ? 'previous' : 'current' }}' }"
                        class="px-8 py-6 border-t-2 {{ $isDropPayMode ? 'border-amber-100 bg-amber-50/30' : 'border-slate-100 bg-slate-50/50' }} flex-shrink-0 animate-in slide-in-from-bottom-4 duration-300">

                        <div class="flex justify-between items-center mb-4 font-black">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 {{ $isDropPayMode ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isDropPayMode ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4' }}"></path></svg>
                                </div>
                                <div>
                                    <h4 class="{{ $isDropPayMode ? 'text-amber-600' : 'text-blue-600' }} uppercase tracking-widest text-[9px]">Transaction Phase</h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $isDropPayMode ? 'Drop Protocol Active' : 'Standard Payment Processing' }}</p>
                                </div>
                            </div>
                            <div class="text-right uppercase tracking-tight">
                                <p class="text-[9px] text-slate-400">Amount Due</p>
                                <p x-show="target === 'previous'" class="text-xl text-rose-600">₱ {{ number_format($actualRemainingPrevious, 2) }}</p>
                                <p x-show="target === 'current'" class="text-xl text-blue-600">₱ {{ number_format($actualRemainingCurrent, 2) }}</p>
                                <p x-show="target === 'combined'" class="text-xl text-blue-600">₱ {{ number_format($totalOutstanding, 2) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="col-span-1">
                                <label class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] block mb-2">Payment Target</label>
                                <div class="relative group">
                                    <select x-model="target" class="w-full bg-white text-blue-600 border-2 border-slate-100 py-3 px-3 rounded-xl outline-none text-[10px] font-black tracking-widest focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 appearance-none transition-all cursor-pointer shadow-sm uppercase">
                                        @if($actualRemainingPrevious > 0)
                                            <option value="previous">Previous Term</option>
                                        @endif
                                        @if($actualRemainingCurrent > 0)
                                            <option value="current">Current Term</option>
                                        @endif
                                        @if($actualRemainingPrevious > 0 && $actualRemainingCurrent > 0)
                                            <option value="combined">Total Balance</option>
                                        @endif
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] block mb-2">Tendered Amount</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-xs">₱</span>
                                    <input type="number" wire:model.live="amount" step="0.01" min="0" autocomplete="off"
                                        class="w-full pl-8 pr-3 py-3 bg-white border-2 border-slate-100 rounded-xl text-slate-800 placeholder-slate-300 font-black text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm"
                                        placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] block mb-2">Reference</label>
                                <input type="text" wire:model="reference_no" autocomplete="off"
                                    class="w-full px-4 py-3 bg-white border-2 border-slate-100 rounded-xl text-slate-800 placeholder-slate-300 font-black text-[10px] uppercase focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all shadow-sm"
                                    placeholder="TXN-XXXX">
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button wire:click="submitPayment"
                                    class="w-full h-[48px] text-white text-[9px] font-black rounded-xl uppercase tracking-[0.2em] transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 active:shadow-md
                                    {{ $isDropPayMode ? 'bg-amber-500 hover:bg-amber-400 shadow-amber-500/30' : 'bg-blue-600 hover:bg-blue-500 shadow-blue-600/30' }}">
                                    {{ $isDropPayMode ? 'Confirm' : 'Submit' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
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
    </div> <!-- END OF TOP SECTION -->

    <!-- BOTTOM SECTION: SECONDARY TABLE LAYOUT -->
    <div class="space-y-6 animate-in fade-in duration-500 mt-10">
        <div class="bg-white rounded-2xl border border-blue-500/10 overflow-hidden relative shadow-xl shadow-blue-900/5">

            <div class="px-8 py-6 border-b border-blue-500/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-blue-50/30">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-2xl bg-white text-blue-600 border border-blue-500/10 shadow-sm transition-transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-black text-xl leading-none uppercase tracking-tight">
                            @if($level === 'shs')
                                SHS PAYMENT LOGS
                            @elseif($level === 'college')
                                COLLEGE PAYMENT LOGS
                            @else
                                PAYMENT LOGS
                            @endif
                        </h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Manage and Verify Student Collections</p>
                    </div>
                </div>
            </div>

            <div class="bg-white px-8 py-5 border-b border-blue-500/10">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="relative flex-grow group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                            <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.500ms="search"
                            class="pl-11 w-full bg-slate-50 border border-slate-200 rounded-xl py-3 text-sm text-black focus:border-blue-500/40 outline-none transition-all placeholder-slate-300 font-bold uppercase tracking-tight shadow-sm"
                            placeholder="Search by student name or reference number...">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="w-full sm:w-48 relative">
                            <select wire:model.live="filterCourse" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                                <option value="ALL">All Programs</option>
                                @if($level === 'college')
                                    @foreach(['ACT', 'DIT', 'BSIS', 'BTVTED', 'DHRT'] as $prog)
                                        <option value="{{ $prog }}">{{ $prog }}</option>
                                    @endforeach
                                @elseif($level === 'shs')
                                    @foreach(['ABM', 'STEM', 'HUMSS', 'GAS', 'HE', 'TVL'] as $strand)
                                        <option value="{{ $strand }}">{{ $strand }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <div class="w-full sm:w-40 relative">
                            <select wire:model.live="statusFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-5 text-xs text-black font-black uppercase tracking-widest focus:border-blue-500/40 outline-none cursor-pointer appearance-none transition-all shadow-sm">
                                <option value="All statuses">All Statuses</option>
                                @foreach(['Paid', 'Pending', 'Rejected'] as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <button wire:click="resetFilters" class="bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-500/40 text-xs font-black uppercase tracking-widest px-6 flex items-center justify-center rounded-xl transition-all active:scale-95 shadow-sm">Reset</button>
                        <a href="{{ route('cashier.payments.export', ['level' => $level ?? 'college']) }}" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-black uppercase tracking-widest px-8 flex items-center justify-center rounded-xl transition-all active:scale-95 shadow-lg shadow-blue-600/20">
                            BACKUP {{ $level === 'shs' ? 'SHS' : ($level === 'college' ? 'COLLEGE' : 'RECORDS') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] border-b border-blue-500/10 bg-blue-50/20">
                        <tr>
                            <th class="py-5 px-8 font-black">ID</th>
                            <th class="py-5 px-5 font-black">Student Details</th>
                            <th class="py-5 px-5 font-black">Program & Year</th>
                            <th class="py-5 px-5 font-black">Date</th>
                            <th class="py-5 px-5 text-right font-black">Amount</th>
                            <th class="py-5 px-8 text-center font-black">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($paymentLogs as $log)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="py-5 px-8 font-black text-xs text-slate-700">#{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-5 px-5">
                                    <div class="text-xs font-black text-black uppercase">{{ $log->user->last_name ?? 'N/A' }}, {{ $log->user->first_name ?? 'N/A' }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">{{ $log->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="py-5 px-5">
                                    <div class="text-xs font-black text-blue-600 uppercase">{{ $log->application->course_code ?? 'N/A' }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">{{ $log->application->year_level ?? 'N/A' }}</div>
                                </td>
                                <td class="py-5 px-5 text-xs font-bold text-slate-600 uppercase">{{ $log->payment_date ? \Carbon\Carbon::parse($log->payment_date)->format('M d, Y') : $log->created_at->format('M d, Y') }}</td>
                                <td class="py-5 px-5 text-right font-black text-black text-xs">₱ {{ number_format($log->amount, 2) }}</td>
                                <td class="py-5 px-8 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $log->status === 'Paid' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center bg-white">
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] leading-loose">No transaction records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($paymentLogs) && method_exists($paymentLogs, 'hasPages') && $paymentLogs->hasPages())
                <div class="px-8 py-6 border-t border-blue-500/10 bg-white">
                    {{ $paymentLogs->links('pagination') }}
                </div>
            @endif
        </div>
    </div> <!-- END OF BOTTOM SECTION -->

    @if($showModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <button wire:click="closeModal" class="absolute inset-0 bg-blue-900/40 backdrop-blur-md transition-all cursor-default"></button>
            <div class="bg-white border border-blue-500/20 w-full max-w-lg rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(30,58,138,0.2)] relative z-10 transform animate-in zoom-in-95 duration-300 p-12 text-center">
                 <p class="text-black font-black uppercase tracking-widest">Special Protocol in Progress</p>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</div> <!-- END OF SINGLE ROOT WRAPPER -->
