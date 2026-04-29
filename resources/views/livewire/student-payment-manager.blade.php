<div>
    <div class="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">My Payments</h2>
                <p class="text-xs mt-2 font-black uppercase tracking-[0.2em] text-slate-400">{{ $studentLevel }} Level • Transaction History & Revenue Verification</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-8 py-3.5 rounded-full bg-white border border-slate-100 text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] hover:bg-indigo-50 transition-all shadow-lg shadow-indigo-600/5 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="p-10 rounded-[2.5rem] border bg-white shadow-2xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">

            @if(!$hasEnrollment)
                <!-- No Enrollment Message -->
                <div class="p-8 rounded-3xl flex items-center gap-6 mb-10 bg-blue-50 border border-blue-100">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-lg shadow-blue-600/10 flex items-center justify-center flex-shrink-0 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a6 6 0 1110.84 0"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-black text-slate-900 mb-1">No Enrollment Found</p>
                        <p class="text-sm font-medium text-slate-500">Please complete your enrollment form first to view your payment assessment and fees.</p>
                    </div>
                </div>

                <!-- Empty State Fees -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-slate-400 tracking-widest">Tuition Fee</p>
                        <p class="text-2xl font-black text-slate-900">₱0.00</p>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-slate-400 tracking-widest">Misc. Fee</p>
                        <p class="text-2xl font-black text-slate-900">₱0.00</p>
                    </div>
                    <div class="p-6 rounded-3xl bg-blue-50 border-2 border-blue-200">
                        <p class="text-[10px] font-black uppercase mb-2 text-blue-600 tracking-widest">Total Assessment</p>
                        <p class="text-2xl font-black text-slate-900">₱0.00</p>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-slate-400 tracking-widest">Total Paid</p>
                        <p class="text-2xl font-black text-slate-900">₱0.00</p>
                    </div>
                    <div class="p-6 rounded-3xl bg-indigo-50 border-2 border-indigo-200">
                        <p class="text-[10px] font-black uppercase mb-2 text-indigo-600 tracking-widest">Balance</p>
                        <p class="text-2xl font-black text-indigo-600">₱0.00</p>
                    </div>
                </div>
            @else
                <!-- Enrollment Info -->
                @if($enrollment)
                <div class="mb-10 p-6 rounded-3xl flex items-center justify-between bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Your Enrollment</p>
                            <p class="text-lg font-black text-slate-900 tracking-tight">{{ $enrollment->year_level }} • {{ $enrollment->course_code }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($voucherType === 'free_tuition')
                            <div class="flex items-center gap-2 px-5 py-2 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                                <span class="text-xs font-black uppercase tracking-widest">Free Tuition</span>
                            </div>
                        @elseif($voucherType === 'discounted')
                            <div class="flex items-center gap-2 px-5 py-2 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                                <span class="text-xs font-black uppercase tracking-widest">Discounted</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 px-5 py-2 rounded-2xl bg-slate-100 border border-slate-200 text-slate-400">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"/></svg>
                                <span class="text-xs font-black uppercase tracking-widest">No Voucher</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Fees & Balance Summary -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-slate-400 tracking-widest">Tuition Fee</p>
                        <p class="text-2xl font-black text-slate-900">₱{{ number_format($tuitionFee, 2) }}</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-slate-400 tracking-widest">Misc. Fee</p>
                        <p class="text-2xl font-black text-slate-900">₱{{ number_format($miscellaneousFees, 2) }}</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-blue-50 border-2 border-blue-200">
                        <p class="text-[10px] font-black uppercase mb-2 text-blue-600 tracking-widest">Total Assessment</p>
                        <p class="text-2xl font-black text-slate-900">₱{{ number_format($totalAssessment, 2) }}</p>
                        @if($cashierDiscount > 0)
                            <p class="text-[10px] mt-1 font-black text-blue-500 uppercase tracking-tight">Disc: -₱{{ number_format($cashierDiscount, 2) }}</p>
                        @endif
                    </div>

                    <div class="p-6 rounded-3xl bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] font-black uppercase mb-2 text-emerald-600 tracking-widest">Total Paid</p>
                        <p class="text-2xl font-black text-emerald-600">₱{{ number_format($totalPaymentsMade, 2) }}</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-indigo-50 border-2 border-indigo-200">
                        <p class="text-[10px] font-black uppercase mb-2 text-indigo-600 tracking-widest">Balance</p>
                        <p class="text-2xl font-black text-indigo-600">₱{{ number_format(max(0, $totalAssessment - $totalPaymentsMade), 2) }}</p>
                    </div>
                </div>

                <!-- Detailed Payment Breakdown -->
                <div class="p-8 rounded-3xl mb-10 bg-slate-50 border border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 mb-6 flex items-center gap-3">
                        <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                        Payment Details for {{ ucfirst($level) }} Level
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Tuition Fee:</p>
                            <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($tuitionFee, 2) }}</p>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Miscellaneous Fee:</p>
                            <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($miscellaneousFees, 2) }}</p>
                        </div>

                        @if($cashierDiscount > 0)
                        <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                            <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">✓ Discount Applied by Cashier:</p>
                            <p class="text-sm font-black text-emerald-600">(₱{{ number_format($cashierDiscount, 2) }})</p>
                        </div>
                        @endif

                        <div class="flex justify-between items-center pb-4 border-b-2 border-blue-200">
                            <p class="text-xs font-black text-blue-600 uppercase tracking-widest">Total to Pay:</p>
                            <p class="text-xl font-black text-slate-900 tracking-tight">₱{{ number_format($totalAssessment, 2) }}</p>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Already Paid:</p>
                            <p class="text-sm font-black text-emerald-600 tracking-tight">₱{{ number_format($totalPaymentsMade, 2) }}</p>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <p class="text-xs font-black text-indigo-600 uppercase tracking-widest">Amount You Still Need to Pay:</p>
                            <p class="text-xl font-black text-indigo-600 tracking-tight">₱{{ number_format(max(0, $totalAssessment - $totalPaymentsMade), 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment History Section -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            Payment History
                            <span class="text-slate-300 font-black tracking-widest ml-2">[{{ count($payments ?? []) }}]</span>
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($payments ?? [] as $payment)
                            <div class="p-6 rounded-3xl border bg-white shadow-sm transition-all duration-300 flex justify-between items-start border-slate-100 hover:border-indigo-200">
                                <div class="flex items-start gap-5 flex-1">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <p class="text-xl font-black text-slate-900 tracking-tight">₱{{ number_format($payment['amount'], 2) }}</p>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-0.5 rounded border border-slate-100">{{ $payment['txn_id'] }}</span>
                                        </div>
                                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $payment['date'] }} at {{ $payment['time'] }}</p>
                                        @if($payment['reference'])
                                            <p class="text-[10px] font-black text-indigo-400 mt-1 uppercase tracking-tight">Ref: {{ $payment['reference'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    @if($payment['status'] === 'Paid')
                                        <span class="inline-block px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm">✓ Paid</span>
                                    @else
                                        <span class="inline-block px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100 shadow-sm">⏳ Pending</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center rounded-3xl border-2 border-dashed border-slate-100 bg-slate-50/50">
                                <p class="text-xs font-black text-slate-300 uppercase tracking-[0.2em]">No payment records found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Fee Comparison: SHS vs College - VISIBLE FOR STUDENTS WITHOUT ENROLLMENT ONLY -->
            @if(!$hasEnrollment)
            <div class="mt-16 pt-16 border-t border-slate-100">
                <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    Fee Breakdown by Level
                </h3>

                <p class="text-sm font-medium text-slate-500 mb-8">Review the estimated fees for each academic level before finalizing your enrollment.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- SHS Level -->
                    <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <h4 class="text-lg font-black text-slate-900">SENIOR HIGH SCHOOL</h4>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Tuition Fee:</p>
                                <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($shsFees['tuitionFee'], 2) }}</p>
                            </div>

                            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Miscellaneous Fee:</p>
                                <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($shsFees['miscellaneousFees'], 2) }}</p>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <p class="text-xs font-black text-blue-600 uppercase tracking-widest">Total Assessment:</p>
                                <p class="text-xl font-black text-blue-600 tracking-tight">₱{{ number_format($shsFees['tuitionFee'] + $shsFees['miscellaneousFees'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- College Level -->
                    <div class="p-8 rounded-[2rem] bg-indigo-50 border border-indigo-100 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <h4 class="text-lg font-black text-slate-900">COLLEGE LEVEL</h4>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-indigo-200">
                                <p class="text-xs font-black text-indigo-500 uppercase tracking-widest">Tuition Fee:</p>
                                <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($collegeFees['tuitionFee'], 2) }}</p>
                            </div>

                            <div class="flex justify-between items-center pb-4 border-b border-indigo-200">
                                <p class="text-xs font-black text-indigo-500 uppercase tracking-widest">Miscellaneous Fee:</p>
                                <p class="text-sm font-black text-slate-900 tracking-tight">₱{{ number_format($collegeFees['miscellaneousFees'], 2) }}</p>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <p class="text-xs font-black text-indigo-600 uppercase tracking-widest">Total Assessment:</p>
                                <p class="text-xl font-black text-indigo-600 tracking-tight">₱{{ number_format($collegeFees['tuitionFee'] + $collegeFees['miscellaneousFees'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voucher Types Explanation -->
                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-xl shadow-blue-900/5">
                    <h4 class="text-sm font-black text-slate-900 mb-8 flex items-center gap-3">
                        <div class="w-1.5 h-5 bg-indigo-600 rounded-full"></div>
                        Voucher Types & Coverage
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-6 rounded-3xl bg-emerald-50 border border-emerald-100">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-emerald-600">
                                    <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Free Tuition</p>
                            </div>
                            <p class="text-xs font-medium text-slate-500 leading-relaxed">Tuition fee is fully covered. You only pay miscellaneous fees for the term.</p>
                        </div>

                        <div class="p-6 rounded-3xl bg-amber-50 border border-amber-100">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-amber-600">
                                    <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Discounted</p>
                            </div>
                            <p class="text-xs font-medium text-slate-500 leading-relaxed">You receive a partial discount on tuition fees based on your scholarship or voucher.</p>
                        </div>

                        <div class="p-6 rounded-3xl bg-slate-100 border border-slate-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2z"></path></svg>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No Voucher</p>
                            </div>
                            <p class="text-xs font-medium text-slate-400 leading-relaxed">Standard enrollment. Pay full tuition and miscellaneous fees as assessed.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
