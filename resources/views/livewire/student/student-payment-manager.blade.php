<div>
    <div class="max-w-6xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">My Payments</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Transaction History & Revenue Verification</p>
            </div>
            <a href="{{ route('student.dashboard') }}" wire:navigate class="text-xs font-bold text-[#10B981] hover:text-[#34d399] transition-colors flex items-center gap-2 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
            
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-bold text-white flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-emerald-400 rounded-full"></span>
                    Payment Records
                    <span class="text-white/20 font-black tracking-widest ml-2">[{{ count($payments ?? []) }}]</span>
                </h3>
            </div>

            <div class="space-y-4">
                @forelse($payments ?? [] as $payment)
                <div class="p-6 rounded-2xl border transition-all duration-300 flex justify-between items-center group
                     {{ $payment['status'] === 'Completed' ? 'bg-emerald-500/5 border-emerald-500/10 hover:border-emerald-500/30 shadow-lg shadow-emerald-500/5' : 'bg-white/5 border-white/10 hover:border-white/20' }}">
                    
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors
                             {{ $payment['status'] === 'Completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-white/10 text-white/40' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <p class="text-xl font-black text-white tracking-tight">₱{{ number_format($payment['amount'], 2) }}</p>
                                <span class="text-xs font-bold text-white/20 uppercase tracking-widest bg-white/5 px-2 py-0.5 rounded border border-white/5">Txn: {{ $payment['txn_id'] }}</span>
                            </div>
                             <p class="text-xs font-bold text-white/40 uppercase tracking-widest mt-1 italic">{{ $payment['date'] }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        @if($payment['status'] === 'Paid' || $payment['status'] === 'Completed')
                             <span class="bg-emerald-500/20 text-emerald-400 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest border border-emerald-500/30 shadow-sm">Paid</span>
                        @else
                             <span class="bg-amber-500/20 text-amber-400 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest border border-amber-500/30 shadow-sm">Pending Verification</span>
                        @endif
                         <p class="text-xs font-bold text-white/10 mt-2 uppercase tracking-tighter">Receipt #{{ $payment['id'] }}</p>
                    </div>
                </div>
                @empty
                <div class="py-24 text-center border-2 border-white/5 border-dashed rounded-2xl">
                     <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic leading-loose">No archival records detected in current stream.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Help Card --}}
        <div class="p-6 rounded-2xl border"
             style="background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); box-shadow: 0 4px 20px rgba(16,185,129,0.1);">
            <div class="flex items-center gap-4">
                <div class="text-emerald-400 p-3 rounded-xl bg-emerald-500/10 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-emerald-400 font-bold text-sm mb-0.5 italic">Payment Details</h3>
                     <p class="text-white/40 text-xs leading-relaxed">
                        All recorded transactions are subject to administrative audit. Status updates usually propagate within <strong class="text-white/60">24-48 business hours</strong> after verification of the payment.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
