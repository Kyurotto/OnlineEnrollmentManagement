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
                            <div class="text-xs font-black {{ in_array($enrollment->status, ['Dropped','Withdrawn']) ? 'text-rose-400' : 'text-emerald-400' }} uppercase tracking-wider">
                                #{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }} {{ $student->last_name }}, {{ $student->first_name }}
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @if(in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-rose-500/20 border border-rose-500/30 text-rose-400 uppercase tracking-widest">
                                        {{ $enrollment->status }}
                                    </span>
                                @endif
                                @if($enrollment->voucher_type)
                                    @php $voucherColor = $enrollment->voucher_type === 'free_tuition' ? '#86efac' : '#fbbf24'; @endphp
                                    <svg class="w-4 h-4" style="color: {{ $voucherColor }};" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-white/40 font-bold uppercase tracking-widest">{{ $enrollment->course_code ?? 'N/A' }} • {{ $enrollment->year_level ?? 'N/A' }}</div>
                        @if($enrollment->semester)
                        <div class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1">{{ $enrollment->semester }} semester</div>
                        @endif
                    </button>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic">No students found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: PAYMENT DETAILS -->
        <div class="flex-grow flex flex-col bg-white/[0.05] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 overflow-y-auto">
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
                                {{-- DROPPED badge --}}
                                @if($enrollment && in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest bg-rose-600 text-white shadow-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        {{ $enrollment->status }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-white/40 font-bold uppercase tracking-widest truncate">
                                {{ $enrollment->year_level ?? 'N/A' }} | {{ $enrollment->course_code ?? 'N/A' }}@if($enrollment->semester) | {{ $enrollment->semester }}@endif
                            </p>
                            {{-- Academic Classification --}}
                            @if($enrollment)
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                @php
                                    $stype = ucfirst(strtolower($enrollment->student_type ?? 'New'));
                                @endphp
                                <span class="text-[8px] font-black px-2 py-0.5 rounded-full border uppercase tracking-widest
                                    {{ $stype === 'Transferee' ? 'text-amber-400 bg-amber-400/10 border-amber-400/20' :
                                       ($stype === 'Shifter'    ? 'text-sky-400 bg-sky-400/10 border-sky-400/20' :
                                       ($stype === 'Returnee'   ? 'text-violet-400 bg-violet-400/10 border-violet-400/20' :
                                                                  'text-white/30 bg-white/5 border-white/10')) }}">
                                    {{ $stype }}
                                </span>
                                @if($enrollment->is_regular === null)
                                    <span class="text-[8px] font-black px-2 py-0.5 rounded-full border border-white/10 text-white/20 uppercase tracking-widest">Not Audited</span>
                                @elseif($enrollment->is_regular)
                                    <span class="text-[8px] font-black px-2 py-0.5 rounded-full border border-emerald-400/20 bg-emerald-400/10 text-emerald-400 uppercase tracking-widest">Regular</span>
                                @else
                                    <span class="text-[8px] font-black px-2 py-0.5 rounded-full border border-rose-400/20 bg-rose-400/10 text-rose-400 uppercase tracking-widest">Irregular</span>
                                    @if($enrollment->classification_reason)
                                        <span class="text-[8px] font-bold text-rose-300/60 italic">— {{ $enrollment->classification_reason }}</span>
                                    @endif
                                @endif
                            </div>
                            @endif
                        </div>
<div class="flex items-center gap-3">
    <button wire:click="setPaymentMode"
        class="text-xs font-black py-3 px-6 rounded-lg uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95
            {{ !$isDropPayMode ? 'bg-cyan-500 hover:bg-cyan-400 text-white ring-2 ring-cyan-300/40' : 'bg-white/10 text-white/50 hover:bg-white/20' }}">
        Payment
    </button>
    <button wire:click="setDropPayMode"
        class="text-xs font-black py-3 px-6 rounded-lg uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95
            {{ $isDropPayMode ? 'bg-amber-500 hover:bg-amber-400 text-white ring-2 ring-amber-300/40' : 'bg-white/10 text-white/50 hover:bg-white/20' }}">
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
<div class="px-8 py-4 space-y-4">
    @if($activeTab === 'assessment')
        <div class="space-y-3">
            {{-- Dropped/Withdrawn banner --}}
            @if($enrollment && in_array($enrollment->status, ['Dropped', 'Withdrawn']))
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-500/10 border border-rose-500/20">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    <div>
                        <p class="text-rose-400 text-[10px] font-black uppercase tracking-widest">{{ $enrollment->status }} Student</p>
                        @if($enrollment->drop_reason)
                            <p class="text-rose-300/60 text-[9px] font-bold mt-0.5">Reason: {{ $enrollment->drop_reason }}</p>
                        @endif
                    </div>
                </div>
            @endif
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
<span class="font-black text-white text-xs">Transaction #{{ $transaction->id }}</span>
<div class="flex items-center gap-1.5">
    @if($transaction->is_drop_payment)
        <span class="text-[9px] font-black px-2 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 uppercase tracking-widest">DROP</span>
    @endif
    <span class="text-xs font-black {{ $transaction->status === 'Paid' ? 'text-emerald-400' : ($transaction->status === 'Pending' ? 'text-amber-400' : 'text-rose-400') }} bg-white/5 px-2 py-1 rounded-lg">
        {{ $transaction->status }}
    </span>
</div>
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
<div class="px-6 py-2 border-t {{ $isDropPayMode ? 'border-amber-500/30 bg-amber-500/5' : 'border-white/5 bg-white/[0.02]' }} space-y-2">
    <div class="flex items-center gap-2">
        <h4 class="font-black text-white uppercase tracking-tight text-xs">Transaction Entry</h4>
        @if($isDropPayMode)
            <span class="text-[9px] font-black px-2 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 uppercase tracking-widest">Drop Pay Mode</span>
        @endif
    </div>

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
<button wire:click="submitPayment"
    class="w-full text-white text-xs font-black py-2 px-4 rounded-lg uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95
        {{ $isDropPayMode ? 'bg-amber-500 hover:bg-amber-400' : 'bg-cyan-500 hover:bg-cyan-400' }}">
    {{ $isDropPayMode ? 'Dropping Payment' : 'Pay' }}
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
