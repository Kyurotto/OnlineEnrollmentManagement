<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="h-[calc(100vh-200px)] flex gap-6">
        <!-- LEFT SIDEBAR: STUDENT LIST -->
        <div class="w-80 flex flex-col bg-white/[0.05] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-white/5 bg-white/[0.02]">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <h3 class="font-black text-white uppercase text-sm tracking-tight">Student List</h3>
                </div>
                <div class="relative group">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs text-white focus:border-emerald-500/50 outline-none transition-all placeholder-white/20 font-bold uppercase tracking-tight"
                        placeholder="Search student...">
                </div>
            </div>

            <!-- Student List -->
            <div class="flex-grow overflow-y-auto custom-scrollbar divide-y divide-white/5">
                @forelse($payments as $enrollment)
                    @php
                        $isSelected = $selectedStudentId === $enrollment->user_id;
                        $student = $enrollment->user;
                    @endphp
                    <button wire:click="selectStudent({{ $enrollment->user_id }}, {{ $enrollment->id }})"
                        class="w-full text-left px-6 py-4 hover:bg-emerald-500/5 transition-colors {{ $isSelected ? 'bg-emerald-500/10 border-l-4 border-emerald-400' : 'border-l-4 border-transparent' }}">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="text-xs font-black text-emerald-400 uppercase tracking-wider">#{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }} {{ $student->last_name }}, {{ $student->first_name }}</div>
                            @if($enrollment->voucher_type)
                                @php
                                    $voucherColor = $enrollment->voucher_type === 'free_tuition' ? '#86efac' : '#fbbf24';
                                @endphp
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $voucherColor }};" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                            @endif
                        </div>
                        <div class="text-xs text-white/40 font-bold uppercase tracking-widest">{{ $enrollment->course_code ?? 'N/A' }} • {{ $enrollment->year_level ?? 'N/A' }}</div>
                        <div class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1">{{ $enrollment->semester ?? 'N/A' }} semester</div>
                    </button>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic">No students found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: PAYMENT DETAILS -->
        <div class="flex-grow flex flex-col bg-white/[0.05] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
            @if($selectedStudentId)
                <!-- Header with Student Info & Action Buttons -->
                <div class="px-6 py-3 border-b border-white/5 bg-white/[0.02]">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-black text-white text-base uppercase tracking-tight">#{{ str_pad($selectedStudent->id, 3, '0', STR_PAD_LEFT) }} {{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }}</h3>
                                @if($selectedVoucherType)
                                    @if($selectedVoucherType === 'free_tuition')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-black uppercase tracking-widest bg-green-600 text-white shadow-md">
                                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                            Free Tuition
                                        </span>
                                    @elseif($selectedVoucherType === 'discounted')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-black uppercase tracking-widest bg-yellow-500 text-gray-900 shadow-md">
                                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                            Discounted
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-xs text-white/40 font-bold uppercase tracking-widest truncate">{{ $enrollment->year_level ?? 'N/A' }} | {{ $enrollment->course_code ?? 'N/A' }} | {{ $enrollment->semester ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-2.5 flex-shrink-0">
                            <button wire:click="openCreateModal" class="bg-cyan-500 hover:bg-cyan-400 text-white text-xs font-black py-2 px-5 rounded-lg uppercase tracking-[0.1em] transition-all shadow-lg active:scale-95">
                                Payment
                            </button>
                            <button class="bg-amber-500 hover:bg-amber-400 text-white text-xs font-black py-2 px-5 rounded-lg uppercase tracking-[0.1em] transition-all shadow-lg active:scale-95">
                                Drop Pay
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-2 border-b border-white/5 bg-white/[0.01] flex gap-6">
                    <button wire:click="$set('activeTab', 'assessment')" class="text-xs font-black uppercase tracking-[0.15em] pb-3 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'assessment' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        ✓ Assessment
                    </button>
                    <button wire:click="$set('activeTab', 'balance')" class="text-xs font-black uppercase tracking-[0.15em] pb-3 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'balance' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        ⓘ Balance
                    </button>
                    <button wire:click="$set('activeTab', 'history')" class="text-xs font-black uppercase tracking-[0.15em] pb-3 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'history' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        History
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="flex-grow overflow-y-auto custom-scrollbar px-6 py-3 space-y-2">
                    @if($activeTab === 'assessment')
                        <div class="space-y-1">
                            <!-- Fee Items -->
                            <div class="space-y-1">
                                <div class="flex justify-between items-center pb-1">
                                    <span class="font-medium text-white/70 text-xs uppercase tracking-wide">Tuition</span>
                                    <span class="font-black text-white text-xs">₱ {{ number_format($tuitionFees, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-1">
                                    <span class="font-medium text-white/70 text-xs uppercase tracking-wide">Misc Fees</span>
                                    <span class="font-black text-white text-xs">₱ {{ number_format($miscellaneousFees, 2) }}</span>
                                </div>
                                @if($appliedDiscount > 0)
                                <div class="flex justify-between items-center pb-1">
                                    <span class="font-medium text-rose-300 text-xs uppercase tracking-wide">Add Discount</span>
                                    <span class="font-black text-rose-400 text-xs">(₱ {{ number_format($appliedDiscount, 2) }})</span>
                                </div>
                                @endif
                            </div>

                            <!-- Separator -->
                            <div class="h-px bg-gradient-to-r from-white/5 via-white/10 to-white/5"></div>

                            <!-- Total Assessment -->
                            <div class="flex justify-between items-center pt-1">
                                <span class="font-black text-white/80 text-xs uppercase tracking-wider">TOTAL:</span>
                                <span class="font-black text-cyan-300 text-sm">₱ {{ number_format((float)$totalAssessment - (float)$appliedDiscount, 2) }}</span>
                            </div>

                            <!-- Discount Section -->
                            <div class="h-px bg-gradient-to-r from-white/5 via-white/10 to-white/5 my-2"></div>
                            <div class="pt-2 space-y-1">
                                @if($appliedDiscount > 0)
                                    <!-- Discount Applied Display -->
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-black text-emerald-400 text-xs uppercase tracking-widest">✓ Discount Applied</span>
                                            <span class="font-black text-emerald-300 text-xs">₱{{ number_format($appliedDiscount, 2) }} ({{ number_format(($totalAssessment > 0 ? ($appliedDiscount / $totalAssessment * 100) : 0), 2) }}%)</span>
                                        </div>
                                        <button wire:click="removeDiscount" class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-black py-0.5 px-2 rounded text-xs uppercase tracking-[0.15em] transition-all">
                                            Remove Discount
                                        </button>
                                    </div>
                                @else
                                    <!-- Discount Input -->
                                    <div class="space-y-1">
                                        <label class="font-black text-white/70 text-xs uppercase tracking-widest block">Apply Discount:</label>
                                        <div class="flex gap-1">
                                            <div class="flex-1 relative">
                                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-white/60 font-bold text-xs">₱</span>
                                                <input type="number" wire:model="discountAmount" step="0.01" min="0"
                                                    class="w-full pl-6 pr-2 py-1 bg-white/5 border border-white/10 rounded text-white placeholder-white/30 font-bold text-xs focus:border-blue-500/50 outline-none transition-all"
                                                    placeholder="0.00">
                                            </div>
                                            <button wire:click="applyDiscount" class="bg-blue-600 hover:bg-blue-500 text-white font-black py-1 px-3 rounded uppercase tracking-[0.15em] transition-all text-xs">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @elseif($activeTab === 'balance')
                        <div class="space-y-1">
                            <div class="text-center p-2 bg-white/[0.05] rounded-lg border border-white/10">
                                <p class="text-white/50 text-xs font-bold uppercase mb-1 tracking-widest">Current Balance</p>
                                <p class="text-xl font-black text-cyan-400">₱ {{ number_format($currentBalance, 2) }}</p>
                            </div>
                        </div>

                    @elseif($activeTab === 'history')
                        <div class="space-y-1">
                            @forelse($paymentHistory as $transaction)
                                <div class="p-2 bg-white/[0.05] rounded border border-white/10 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-black text-white text-xs">Txn #{{ $transaction->id }}</span>
                                        <span class="text-xs font-black {{ $transaction->status === 'Paid' ? 'text-emerald-400' : ($transaction->status === 'Pending' ? 'text-amber-400' : 'text-rose-400') }} bg-white/5 px-1.5 py-0.5 rounded text-xs">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-white/40 font-bold">{{ $transaction->created_at->format('M d • h:i A') }}</div>
                                    <div class="space-y-0.5 pt-1 border-t border-white/5">
                                        <div class="flex justify-between items-center">
                                            <span class="text-white/60 text-xs">Amount:</span>
                                            <span class="text-white font-black text-xs">₱ {{ number_format($transaction->amount, 2) }}</span>
                                        </div>
                                        @if($transaction->reference_no)
                                        <div class="flex justify-between items-center">
                                            <span class="text-white/60 text-xs">Ref:</span>
                                            <span class="text-white/80 font-mono text-xs bg-white/5 px-1 py-0.5 rounded">{{ substr($transaction->reference_no, 0, 15) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-xs font-black text-white/10 uppercase tracking-[0.2em] italic py-2">No history</p>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- TRANSACTION ENTRY SECTION -->
                <div class="px-6 py-2 border-t border-white/5 bg-white/[0.02] space-y-1.5">
                    <h4 class="font-black text-white uppercase tracking-tight text-xs">Transaction Entry</h4>

                    <!-- Amount Due Display -->
                    <div class="flex justify-center items-center p-2 bg-white/[0.05] rounded border border-white/10">
                        <div class="text-center">
                            <p class="text-xs text-white/60 font-bold uppercase tracking-widest mb-1">Amount Due</p>
                            <p class="font-black text-cyan-300 text-lg">₱ {{ number_format($currentBalance, 2) }}</p>
                        </div>
                    </div>

                    <!-- Amount Paid & Reference -->
                    <div class="grid grid-cols-2 gap-1.5">
                        <div>
                            <label class="text-xs text-white/60 font-bold uppercase tracking-widest block mb-1">Amount Paid:</label>
                            <div class="relative">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-white/60 font-bold text-xs">₱</span>
                                <input type="number" wire:model.live="amount" step="0.01" min="0"
                                    class="w-full pl-6 pr-2 py-1 bg-white/5 border border-white/10 rounded text-white placeholder-white/30 font-bold text-xs focus:border-blue-500/50 outline-none transition-all"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-white/60 font-bold uppercase tracking-widest mb-1 block">Ref No:</label>
                            <input type="text" wire:model="reference_no"
                                class="w-full px-2 py-1 bg-white/5 border border-white/10 rounded text-white placeholder-white/30 font-bold text-xs focus:border-blue-500/50 outline-none transition-all"
                                placeholder="xxxxx">
                        </div>
                    </div>

                    <!-- Pay Button -->
                    <button wire:click="submitPayment" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white text-xs font-black py-1.5 px-4 rounded-lg uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95">
                        Pay
                    </button>
                </div>
            @else
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white/10 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19v-2a6 6 0 0112 0v2a2 2 0 01-2 2H7a2 2 0 01-2-2z"></path></svg>
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.3em]">Select a student to view payment details</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showModal)
        <!-- Payment Modal (same as before) -->
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <button wire:click="closeModal" class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl transition-all cursor-default"></button>
            <!-- Modal content would go here -->
        </div>
    @endif

    <style>
        .custom-scrollbar { -ms-overflow-style: auto; scrollbar-width: thin; scrollbar-color: rgba(34,211,238,0.3) transparent; }
        .custom-scrollbar::-webkit-scrollbar { display: block; height: 5px; width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34,211,238,0.3); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(34,211,238,0.6); }
    </style>
</div>
