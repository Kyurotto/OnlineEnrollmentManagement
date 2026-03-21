<div class="space-y-6 animate-in fade-in duration-700" wire:poll.3s>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 1 — Header Node (Matching Admin Styling)
    ═══════════════════════════════════════════════════════ --}}
    <div class="p-8 rounded-2xl border relative overflow-hidden group shadow-2xl shadow-black/40"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
        
        <div class="absolute top-0 right-0 p-12 opacity-5 mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
            <svg class="w-64 h-64 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <div class="flex justify-between items-center relative z-10">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-lg shadow-emerald-500/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-white leading-none tracking-tight">Financial Overview</h2>
                    <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Daily Revenue & Collection Metrics</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold text-white/60 tracking-widest uppercase">
                    {{ now()->format('F d, Y') }}
                </div>
                <a href="{{ route('cashier.payments.index') }}" wire:navigate class="bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-black py-3.5 px-8 rounded-xl uppercase tracking-[0.15em] transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    New Payment
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 2 — Core Metrics Grid (3nd Card Style Reverted)
    ═══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Collected Today --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(16,185,129,0.04); border-color: rgba(16,185,129,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-emerald-400/80 uppercase tracking-[0.2em] mb-1">Collected Today</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        ₱{{ number_format($stats['daily_collection'], 2) }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full w-[65%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Confirmed Revenue</p>
        </div>

        {{-- Transaction Count --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(99,179,237,0.04); border-color: rgba(99,179,237,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-blue-400/80 uppercase tracking-[0.2em] mb-1">Receipts Today</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        {{ $stats['transactions_today'] }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full w-[45%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Daily Transaction Volume</p>
        </div>

        {{-- Pending Verifications --}}
        <div class="p-8 rounded-2xl border group transition-all duration-500 hover:scale-[1.02]"
             style="background: rgba(251,191,36,0.04); border-color: rgba(251,191,36,0.15); box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-amber-400/80 uppercase tracking-[0.2em] mb-1">Verification Queue</h4>
                    <div class="text-3xl font-black text-white tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                        {{ $stats['pending_verifications'] }}
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400 group-hover:rotate-12 transition-transform shadow-lg shadow-amber-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full w-[30%] group-hover:w-full transition-all duration-1000"></div>
            </div>
            <p class="text-xs mt-4 font-bold text-white/20 uppercase tracking-widest italic">Pending Financial Audit</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 3 — Transaction Logs (Matches Photo 2 Style)
    ═══════════════════════════════════════════════════════ --}}
    <div class="p-6 rounded-2xl border shadow-2xl shadow-black/40 overflow-hidden"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">
        
        <div class="flex items-center gap-3 mb-6 px-4">
            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
            <div>
                <h3 class="font-bold text-white text-base">Today's Collections</h3>
                <p class="text-xs font-bold text-white/20 uppercase tracking-widest">{{ now()->format('M d, Y') }} — Transaction Record</p>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left">
                <thead class="text-xs text-white/20 uppercase tracking-[0.2em] border-b border-white/5">
                    <tr>
                        <th class="py-4 px-6 font-bold">Receipt #</th>
                        <th class="py-4 px-6 font-bold">Student Name</th>
                        <th class="py-4 px-6 font-bold">Time</th>
                        <th class="py-4 px-6 text-right font-bold">Amount</th>
                        <th class="py-4 px-6 text-center font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($paymentsToday as $payment)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="py-5 px-6 font-mono text-xs text-white/30 italic group-hover:text-emerald-400 transition-colors">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-5 px-6 font-bold text-white uppercase tracking-tight">{{ optional($payment->user)->name }}</td>
                        <td class="py-5 px-6 text-xs text-white/40">{{ $payment->updated_at->format('H:i A') }}</td>
                        <td class="py-5 px-6 text-right font-black text-emerald-400">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="py-5 px-6 text-center">
                            @php
                                $statusStyle = match($payment->status) {
                                    'Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'Rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    default => 'bg-white/5 text-white/40 border-white/10'
                                };
                            @endphp
                            <span class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
                                {{ $payment->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic leading-loose">No activity detected for today.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="p-6 rounded-2xl border shadow-2xl shadow-black/40 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700"
         style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.05);">
        <div class="flex items-center gap-3 mb-6 px-4">
            <div class="w-1.5 h-6 bg-white/20 rounded-full"></div>
            <h3 class="font-bold text-white/40 text-xs uppercase tracking-widest">Archived Records: {{ now()->subDay()->format('M d, Y') }}</h3>
        </div>
        @if($paymentsYesterday->isEmpty())
            <div class="py-8 text-center text-xs font-bold text-white/5 uppercase tracking-widest italic">No archival records found.</div>
        @else
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="text-xs text-white/20 uppercase tracking-[0.2em] border-b border-white/5">
                        <tr>
                            <th class="py-4 px-6 font-bold">Receipt #</th>
                            <th class="py-4 px-6 font-bold">Student Name</th>
                            <th class="py-4 px-6 font-bold">Time</th>
                            <th class="py-4 px-6 text-right font-bold">Amount</th>
                            <th class="py-4 px-6 text-center font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($paymentsYesterday as $payment)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-5 px-6 font-mono text-xs text-white/30 italic group-hover:text-emerald-400 transition-colors">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-5 px-6 font-bold text-white uppercase tracking-tight">{{ optional($payment->user)->name }}</td>
                            <td class="py-5 px-6 text-xs text-white/40">{{ $payment->updated_at->format('H:i A') }}</td>
                            <td class="py-5 px-6 text-right font-black text-emerald-400">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-5 px-6 text-center">
                                @php
                                    $statusStyle = match($payment->status) {
                                        'Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'Rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                        default => 'bg-white/5 text-white/40 border-white/10'
                                    };
                                @endphp
                                <span class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
