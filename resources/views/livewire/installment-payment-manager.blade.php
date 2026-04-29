<div class="installment-payment-manager p-8 bg-white border border-blue-500/10 rounded-[32px] shadow-xl shadow-blue-900/5 animate-in fade-in duration-500">
    <div class="mb-10 flex items-center gap-4">
        <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-sm transition-transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-black uppercase tracking-tight">Installment Payment Manager</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Protocol-Based Payment Schedule Management</p>
        </div>
    </div>

    <!-- Configuration Section -->
    <div class="mb-10 p-8 bg-blue-50/30 rounded-3xl border border-blue-500/10 shadow-inner">
        <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-6">⚙ Configuration Protocol</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    Downpayment Percentage (% of Total)
                </label>
                <input 
                    type="number" 
                    wire:change="updateDownpaymentPercentage($event.target.value)"
                    value="{{ $downpaymentPercentage }}"
                    min="5"
                    max="100"
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-black focus:border-blue-500/40 outline-none transition-all shadow-sm"
                >
            </div>
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    Distribution Logic
                </label>
                <div class="relative group">
                    <select wire:change="updateInstallmentType($event.target.value)" class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-black focus:border-blue-500/40 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="equal">Equal Distribution (33.33% each)</option>
                        <option value="weighted">Weighted (30%, 30%, 40%)</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="mb-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white border border-slate-100 rounded-2xl shadow-sm space-y-2">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Assessment</p>
            <p class="text-2xl font-black text-black tracking-tighter">₱{{ number_format($totalAssessment, 2) }}</p>
        </div>
        <div class="p-6 bg-blue-50/50 border border-blue-500/10 rounded-2xl shadow-sm space-y-2">
            <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest">Down Payment ({{ $downpaymentPercentage }}%)</p>
            <p class="text-2xl font-black text-blue-600 tracking-tighter">₱{{ number_format($downpaymentAmount, 2) }}</p>
        </div>
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl shadow-sm space-y-2">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Remaining Balance</p>
            <p class="text-2xl font-black text-slate-700 tracking-tighter">₱{{ number_format($totalAssessment - $downpaymentAmount, 2) }}</p>
        </div>
    </div>

    <!-- Installment Breakdown -->
    <div class="mb-10 space-y-6">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Breakdown Protocol</h3>
        <div class="grid grid-cols-1 gap-4">
            @foreach(['Prelim' => 0, 'Midterm' => 1, 'Final' => 2] as $phase => $index)
            <div class="p-6 bg-white border rounded-2xl transition-all hover:shadow-md {{ in_array($phase, $paidInstallments) ? 'border-emerald-500/10 bg-emerald-50/10' : 'border-slate-100' }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-3">
                            <h4 class="font-black text-black text-lg uppercase tracking-tight">{{ $phase }} Period</h4>
                            @if(in_array($phase, $paidInstallments))
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg border border-emerald-100 uppercase tracking-widest">✓ PAID</span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-500 text-[10px] font-black rounded-lg border border-amber-100 uppercase tracking-widest">PENDING</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Due Date: {{ $dueDates[$phase] }}</p>
                        <p class="text-2xl font-black text-blue-600 tracking-tighter mt-3">₱{{ number_format($installments[$phase], 2) }}</p>
                    </div>
                    @unless(in_array($phase, $paidInstallments))
                    <button 
                        wire:click="recordInstallmentPayment('{{ $phase }}', {{ $installments[$phase] }}, 'Cash', '')"
                        class="px-8 py-4 bg-blue-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-[0.2em] transition-all hover:bg-blue-500 shadow-xl shadow-blue-500/20 active:scale-95 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Record Payment
                    </button>
                    @endunless
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Payment History -->
    <div class="mb-10 space-y-6">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Transaction History</h3>
        @if(count($paymentHistory) > 0)
            <div class="overflow-hidden bg-white border border-slate-100 rounded-3xl shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-blue-50/30 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Phase</th>
                            <th class="px-5 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                            <th class="px-5 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-5 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reference</th>
                            <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($paymentHistory as $payment)
                        <tr class="hover:bg-blue-50/10 transition-colors">
                            <td class="px-8 py-5 text-xs font-black text-black uppercase tracking-tight">{{ $payment['installment_type'] }}</td>
                            <td class="px-5 py-5 text-sm font-black text-blue-600 tracking-tighter">₱{{ number_format($payment['amount'], 2) }}</td>
                            <td class="px-5 py-5 text-[10px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($payment['payment_date'])->format('M d, Y') }}</td>
                            <td class="px-5 py-5 text-[9px] font-mono text-slate-300 font-bold uppercase tracking-widest">#{{ $payment['transaction_id'] }}</td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded-lg border border-emerald-100 uppercase tracking-widest">{{ $payment['status'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center bg-white border border-slate-100 rounded-3xl">
                <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.4em]">No transactions found</p>
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8 bg-slate-50 rounded-3xl border border-slate-100 shadow-inner">
        <div class="text-center space-y-1">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Collection</p>
            <p class="text-xl font-black text-emerald-500 tracking-tighter">₱{{ number_format($getTotalPaid(), 2) }}</p>
        </div>
        <div class="text-center space-y-1">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Remaining Collection</p>
            <p class="text-xl font-black text-blue-600 tracking-tighter">₱{{ number_format($remainingBalance, 2) }}</p>
        </div>
        <div class="text-center space-y-1">
            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Status</p>
            <p class="text-xl font-black tracking-tighter {{ $isFullyPaid() ? 'text-emerald-500' : 'text-amber-500' }}">
                {{ $isFullyPaid() ? 'FULLY COMPLETED ✓' : 'PARTIAL COLLECTION' }}
            </p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
        <div class="mt-6 p-5 bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold rounded-2xl animate-in zoom-in-95">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mt-6 p-5 bg-rose-50 border border-rose-100 text-rose-500 text-xs font-bold rounded-2xl animate-in zoom-in-95">
            {{ session('error') }}
        </div>
    @endif
</div>
