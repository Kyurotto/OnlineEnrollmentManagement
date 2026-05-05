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

            <!-- Payment History Section -->
            <div class="mt-4">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        Statement of Account
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="font-bold text-slate-600 text-xs uppercase">Tuition Fees</span>
                            <span class="font-black text-black text-xs">₱ {{ number_format($tuitionFee, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="font-bold text-slate-600 text-xs uppercase">Miscellaneous</span>
                            <span class="font-black text-black text-xs">₱ {{ number_format($miscellaneousFees, 2) }}</span>
                        </div>
                        @if($cashierDiscount > 0)
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="font-bold text-slate-600 text-xs uppercase">Discounted</span>
                            <span class="font-black text-rose-600 text-xs">- ₱ {{ number_format($cashierDiscount, 2) }}</span>
                        </div>
                        @endif
                        @if($previousBalance > 0)
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="font-bold text-slate-600 text-xs uppercase">Previous Balance</span>
                            <span class="font-black text-rose-600 text-xs">₱ {{ number_format($previousBalance, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-6 border-t-2 border-indigo-500/10">
                            <div class="flex flex-col">
                                <span class="font-black text-black text-xs uppercase tracking-widest">Total Assessment</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Net Payable Amount</span>
                            </div>
                            <span class="font-black text-indigo-600 text-lg tracking-tighter">₱ {{ number_format((float)$totalAssessment, 2) }}</span>
                        </div>
                    </div>

                    <div class="bg-indigo-50/30 p-8 rounded-[32px] border border-indigo-500/10 flex flex-col items-center justify-center text-center">
                        <p class="text-slate-400 text-[10px] font-black uppercase mb-3 tracking-[0.4em]">Current Balance</p>
                        <p class="text-5xl font-black text-indigo-600 tracking-tighter">₱ {{ number_format(max(0, (float)$totalAssessment - (float)$totalPaymentsMade), 2) }}</p>
                        <div class="mt-6">
                            <span class="px-4 py-1 bg-white rounded-full text-[8px] font-black text-indigo-600 border border-indigo-100 uppercase tracking-widest shadow-sm">Total Paid: ₱ {{ number_format($totalPaymentsMade, 2) }}</span>
                        </div>
                    </div>
                </div>

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
                                            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 shadow-sm">{{ $payment['txn_id'] }}</span>
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
        </div>
    </div>
</div>
