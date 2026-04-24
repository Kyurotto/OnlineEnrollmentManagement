<div>
    <div class="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">My Payments</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">{{ $studentLevel }} Level • Transaction History & Revenue Verification</p>
            </div>
        </div>

        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

            @if(!$hasEnrollment)
                <!-- No Enrollment Message -->
                <div class="p-6 rounded-lg flex items-center gap-4 mb-6" style="background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3);">
                    <svg class="w-6 h-6 flex-shrink-0" style="color: #fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a6 6 0 1110.84 0"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-white mb-1">No Enrollment Found</p>
                        <p class="text-xs" style="color: rgba(255,255,255,0.6);">Please complete your enrollment form first to view your payment assessment and fees.</p>
                    </div>
                </div>

                <!-- Empty State Fees -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs font-bold uppercase mb-1" style="color: rgba(138,180,216,0.7);">Tuition Fee</p>
                        <p class="text-xl font-black text-white">₱0.00</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs font-bold uppercase mb-1" style="color: rgba(138,180,216,0.7);">Misc. Fee</p>
                        <p class="text-xl font-black text-white">₱0.00</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.12); border: 2px solid rgba(99,179,237,0.4);">
                        <p class="text-xs font-bold uppercase mb-1" style="color: #63b3ed;">Total Assessment</p>
                        <p class="text-xl font-black text-white">₱0.00</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background: rgba(134,239,172,0.08); border: 1px solid rgba(134,239,172,0.2);">
                        <p class="text-xs font-bold uppercase mb-1" style="color: #86efac;">Total Paid</p>
                        <p class="text-xl font-black text-white">₱0.00</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background: rgba(134,239,172,0.12); border: 2px solid rgba(134,239,172,0.4);">
                        <p class="text-xs font-bold uppercase mb-1" style="color: #86efac;">Balance</p>
                        <p class="text-xl font-black text-white" style="color: #86efac;">₱0.00</p>
                    </div>
                </div>
            @else
            <!-- Enrollment Info -->
            @if($enrollment)
            <div class="mb-8 p-4 rounded-lg flex items-center justify-between" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" style="color: #63b3ed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-xs font-bold uppercase" style="color: #cbd5e1;">Your Enrollment</p>
                        <p class="text-sm font-bold text-white">{{ $enrollment->year_level }} • {{ $enrollment->course_code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Voucher Status Badge -->
                    @if($voucherType === 'free_tuition')
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full" style="background: rgba(134,239,172,0.2); border: 1px solid rgba(134,239,172,0.4);">
                            <svg class="w-4 h-4 fill-current" style="color: #86efac;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                            <span class="text-xs font-bold" style="color: #86efac;">Free Tuition</span>
                        </div>
                    @elseif($voucherType === 'discounted')
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full" style="background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.4);">
                            <svg class="w-4 h-4 fill-current" style="color: #fbbf24;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                            <span class="text-xs font-bold" style="color: #fbbf24;">Discounted</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full" style="background: rgba(160,164,168,0.2); border: 1px solid rgba(160,164,168,0.4);">
                            <svg class="w-4 h-4 fill-current" style="color: #a0a4a8;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                            <span class="text-xs font-bold" style="color: #a0a4a8;">No Voucher</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Fees & Balance Summary -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <!-- Tuition Fee -->
                <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                    <p class="text-xs font-bold uppercase mb-1" style="color: rgba(138,180,216,0.7);">Tuition Fee</p>
                    <p class="text-xl font-black text-white">₱{{ number_format($tuitionFee, 2) }}</p>
                </div>

                <!-- Miscellaneous Fee -->
                <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                    <p class="text-xs font-bold uppercase mb-1" style="color: rgba(138,180,216,0.7);">Misc. Fee</p>
                    <p class="text-xl font-black text-white">₱{{ number_format($miscellaneousFees, 2) }}</p>
                </div>

                <!-- Total Assessment -->
                <div class="p-4 rounded-lg" style="background: rgba(99,179,237,0.12); border: 2px solid rgba(99,179,237,0.4);">
                    <p class="text-xs font-bold uppercase mb-1" style="color: #63b3ed;">Total Assessment</p>
                    <p class="text-xl font-black text-white">₱{{ number_format($totalAssessment, 2) }}</p>
                    @if($cashierDiscount > 0)
                        <p class="text-[10px] mt-1" style="color: #86efac;">Discount: ₱{{ number_format($cashierDiscount, 2) }}</p>
                    @endif
                </div>

                <!-- Total Paid -->
                <div class="p-4 rounded-lg" style="background: rgba(134,239,172,0.08); border: 1px solid rgba(134,239,172,0.2);">
                    <p class="text-xs font-bold uppercase mb-1" style="color: #86efac;">Total Paid</p>
                    <p class="text-xl font-black text-white">₱{{ number_format($totalPaymentsMade, 2) }}</p>
                </div>

                <!-- Balance Remaining -->
                <div class="p-4 rounded-lg" style="background: {{ $totalAssessment - $totalPaymentsMade <= 0 ? 'rgba(134,239,172,0.12)' : 'rgba(251,191,36,0.12)' }}; border: 2px solid {{ $totalAssessment - $totalPaymentsMade <= 0 ? 'rgba(134,239,172,0.4)' : 'rgba(251,191,36,0.4)' }};">
                    <p class="text-xs font-bold uppercase mb-1" style="color: {{ $totalAssessment - $totalPaymentsMade <= 0 ? '#86efac' : '#fbbf24' }};">Balance</p>
                    <p class="text-xl font-black" style="color: {{ $totalAssessment - $totalPaymentsMade <= 0 ? '#86efac' : '#fbbf24' }};">₱{{ number_format(max(0, $totalAssessment - $totalPaymentsMade), 2) }}</p>
                </div>
            </div>

            <!-- Detailed Payment Breakdown -->
            <div class="p-6 rounded-lg mb-8" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: #63b3ed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Payment Details for {{ ucfirst($level) }} Level
                </h3>

                <div class="space-y-3">
                    <!-- Tuition Fee Breakdown -->
                    <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs" style="color: rgba(138,180,216,0.8);">Tuition Fee:</p>
                        <p class="text-sm font-bold text-white">₱{{ number_format($tuitionFee, 2) }}</p>
                    </div>

                    <!-- Miscellaneous Fee Breakdown -->
                    <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs" style="color: rgba(138,180,216,0.8);">Miscellaneous Fee:</p>
                        <p class="text-sm font-bold text-white">₱{{ number_format($miscellaneousFees, 2) }}</p>
                    </div>

                    @if($cashierDiscount > 0)
                    <!-- Cashier Discount -->
                    <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs font-bold" style="color: #86efac;">✓ Discount Applied by Cashier:</p>
                        <p class="text-sm font-bold" style="color: #86efac;">(₱{{ number_format($cashierDiscount, 2) }})</p>
                    </div>
                    @endif

                    <!-- Total to Pay -->
                    <div class="flex justify-between items-center pb-3" style="border-bottom: 2px solid rgba(99,179,237,0.4);">
                        <p class="text-xs font-bold uppercase" style="color: #63b3ed;">Total You Need to Pay:</p>
                        <p class="text-lg font-black" style="color: #63b3ed;">₱{{ number_format($totalAssessment, 2) }}</p>
                    </div>

                    <!-- Already Paid -->
                    <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                        <p class="text-xs" style="color: rgba(138,180,216,0.8);">Already Paid:</p>
                        <p class="text-sm font-bold" style="color: #86efac;">₱{{ number_format($totalPaymentsMade, 2) }}</p>
                    </div>

                    <!-- Remaining Balance -->
                    <div class="flex justify-between items-center pt-2">
                        <p class="text-xs font-bold uppercase" style="color: {{ $totalAssessment - $totalPaymentsMade <= 0 ? '#86efac' : '#fbbf24' }};">Amount You Still Need to Pay:</p>
                        <p class="text-lg font-black" style="color: {{ $totalAssessment - $totalPaymentsMade <= 0 ? '#86efac' : '#fbbf24' }};">₱{{ number_format(max(0, $totalAssessment - $totalPaymentsMade), 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment History Section -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white flex items-center gap-3">
                        <span class="w-1.5 h-6 rounded-full" style="background: #63b3ed;"></span>
                        Payment History
                        <span class="text-white/20 font-black tracking-widest ml-2">[{{ count($payments ?? []) }}]</span>
                    </h3>
                </div>

                <div class="space-y-3">
                    @forelse($payments ?? [] as $payment)
                        <div class="p-5 rounded-lg border transition-all duration-300 flex justify-between items-start"
                             style="{{ $payment['status'] === 'Paid' ? 'background: rgba(134,239,172,0.08); border-color: rgba(134,239,172,0.2);' : 'background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.2);' }}">

                            <div class="flex items-start gap-4 flex-1">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                     style="{{ $payment['status'] === 'Paid' ? 'background: rgba(134,239,172,0.2); color: #86efac;' : 'background: rgba(251,191,36,0.2); color: #fbbf24;' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <p class="text-lg font-black text-white">₱{{ number_format($payment['amount'], 2) }}</p>
                                        <span class="text-xs font-bold text-white/30 uppercase tracking-widest">{{ $payment['txn_id'] }}</span>
                                    </div>
                                    <p class="text-xs text-white/40 mb-1">{{ $payment['date'] }} at {{ $payment['time'] }}</p>
                                    @if($payment['reference'])
                                        <p class="text-xs text-white/30">Ref: {{ $payment['reference'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                @if($payment['status'] === 'Paid')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest" style="background: rgba(134,239,172,0.2); color: #86efac; border: 1px solid rgba(134,239,172,0.3);">
                                        ✓ Paid
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest" style="background: rgba(251,191,36,0.2); color: #fbbf24; border: 1px solid rgba(251,191,36,0.3);">
                                        ⏳ Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center rounded-lg border-2 border-dashed" style="border-color: rgba(255,255,255,0.1);">
                            <p class="text-xs text-white/20 uppercase tracking-widest italic">No payment records found</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- Fee Comparison: SHS vs College - VISIBLE FOR STUDENTS WITHOUT ENROLLMENT ONLY -->
            @if(!$hasEnrollment)
            <div class="mb-8 mt-8">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: #a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Fee Breakdown by Level
                </h3>

                <p class="text-xs mb-4" style="color: rgba(255,255,255,0.5);">See the fees charged at each level before completing your enrollment</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- SHS Level -->
                    <div class="p-6 rounded-lg" style="background: rgba(15,177,100,0.08); border: 2px solid rgba(15,177,100,0.3);">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-6 rounded-full" style="background: #0fb164;"></div>
                            <h4 class="text-lg font-bold text-white">SENIOR HIGH SCHOOL (SHS)</h4>
                        </div>

                        <div class="space-y-3">
                            <!-- SHS Tuition -->
                            <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(15,177,100,0.2);">
                                <p class="text-xs" style="color: rgba(138,180,216,0.8);">Tuition Fee:</p>
                                <p class="text-sm font-bold text-white">₱{{ number_format($shsFees['tuitionFee'], 2) }}</p>
                            </div>

                            <!-- SHS Misc -->
                            <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(15,177,100,0.2);">
                                <p class="text-xs" style="color: rgba(138,180,216,0.8);">Miscellaneous Fee:</p>
                                <p class="text-sm font-bold text-white">₱{{ number_format($shsFees['miscellaneousFees'], 2) }}</p>
                            </div>

                            <!-- SHS Total -->
                            <div class="flex justify-between items-center pt-2">
                                <p class="text-xs font-bold uppercase" style="color: #0fb164;">Total Assessment:</p>
                                <p class="text-lg font-black" style="color: #0fb164;">₱{{ number_format($shsFees['tuitionFee'] + $shsFees['miscellaneousFees'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- College Level -->
                    <div class="p-6 rounded-lg" style="background: rgba(99,179,237,0.08); border: 2px solid rgba(99,179,237,0.3);">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-6 rounded-full" style="background: #63b3ed;"></div>
                            <h4 class="text-lg font-bold text-white">COLLEGE</h4>
                        </div>

                        <div class="space-y-3">
                            <!-- College Tuition -->
                            <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                                <p class="text-xs" style="color: rgba(138,180,216,0.8);">Tuition Fee:</p>
                                <p class="text-sm font-bold text-white">₱{{ number_format($collegeFees['tuitionFee'], 2) }}</p>
                            </div>

                            <!-- College Misc -->
                            <div class="flex justify-between items-center pb-3" style="border-bottom: 1px solid rgba(99,179,237,0.2);">
                                <p class="text-xs" style="color: rgba(138,180,216,0.8);">Miscellaneous Fee:</p>
                                <p class="text-sm font-bold text-white">₱{{ number_format($collegeFees['miscellaneousFees'], 2) }}</p>
                            </div>

                            <!-- College Total -->
                            <div class="flex justify-between items-center pt-2">
                                <p class="text-xs font-bold uppercase" style="color: #63b3ed;">Total Assessment:</p>
                                <p class="text-lg font-black" style="color: #63b3ed;">₱{{ number_format($collegeFees['tuitionFee'] + $collegeFees['miscellaneousFees'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voucher Types Explanation -->
                <div class="p-6 rounded-lg" style="background: rgba(99,179,237,0.08); border: 1px solid rgba(99,179,237,0.2);">
                    <h4 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" style="color: #fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Voucher Types
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Free Tuition -->
                        <div class="p-4 rounded-lg" style="background: rgba(134,239,172,0.1); border: 1px solid rgba(134,239,172,0.3);">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 fill-current" style="color: #86efac;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                <p class="text-xs font-bold text-white">FREE TUITION</p>
                            </div>
                            <p class="text-xs" style="color: rgba(255,255,255,0.6);">Tuition fee is fully covered. You only pay miscellaneous fees.</p>
                        </div>

                        <!-- Discounted -->
                        <div class="p-4 rounded-lg" style="background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3);">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 fill-current" style="color: #fbbf24;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                <p class="text-xs font-bold text-white">DISCOUNTED</p>
                            </div>
                            <p class="text-xs" style="color: rgba(255,255,255,0.6);">You receive a discount on tuition fees. Pay reduced tuition + miscellaneous fees.</p>
                        </div>

                        <!-- No Voucher -->
                        <div class="p-4 rounded-lg" style="background: rgba(160,164,168,0.1); border: 1px solid rgba(160,164,168,0.3);">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 fill-current" style="color: #a0a4a8;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                <p class="text-xs font-bold text-white">NO VOUCHER</p>
                            </div>
                            <p class="text-xs" style="color: rgba(255,255,255,0.6);">No discount applied. Pay full tuition + miscellaneous fees.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
