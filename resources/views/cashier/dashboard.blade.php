<div wire:poll.60s class="w-full">
    <div class="space-y-8 animate-in fade-in duration-700">

        {{-- SECTION 1 — Header Node --}}
        <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
            style="border-color: rgba(37,99,235,0.1);">

            <div
                class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>

            <div class="flex justify-between items-center relative z-10">
                <div class="flex items-center gap-8">
                    <div
                        class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Financial Overview
                        </h2>
                        <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Daily Revenue &
                            Collection Metrics</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- SECTION 2 — Core Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Collected Today --}}
            <div class="p-10 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                style="border-color: rgba(37,99,235,0.1);">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">Collected
                            Today</h4>
                        <div
                            class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                            ₱{{ number_format($stats['daily_collection'], 2) }}
                        </div>
                    </div>
                    <div
                        class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-600/5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div
                        class="bg-emerald-500 h-full w-[65%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-emerald-500/30">
                    </div>
                </div>
                <p class="text-[10px] mt-6 font-black text-slate-400 uppercase tracking-widest">Confirmed Revenue Pool
                </p>
            </div>

            {{-- Transaction Count --}}
            <div class="p-10 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                style="border-color: rgba(37,99,235,0.1);">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">Receipts
                            Issued</h4>
                        <div
                            class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                            {{ $stats['transactions_today'] }}
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20H5a2 2 0 01-2-2V6a2 2 0 012-2h3m4 0h3a2 2 0 012 2v1M9 12h6m-6 4h6" />
                            </svg>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                {{ $stats['students_paid_today'] }}
                                {{ Str::plural('student', $stats['students_paid_today']) }} processed
                            </span>
                        </div>
                    </div>
                    <div
                        class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-600/5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div
                        class="bg-blue-600 h-full w-[45%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-blue-600/30">
                    </div>
                </div>
                <p class="text-[10px] mt-6 font-black text-slate-400 uppercase tracking-widest">Daily Transaction
                    Volume</p>
            </div>
        </div>

        {{-- SECTION 3 — Transaction Logs --}}
        <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden bg-white"
            style="border-color: rgba(37,99,235,0.1);">

            <div class="flex items-center gap-4 mb-8 px-4">
                <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">
                        Official Audit Record — Core Database</p>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left">
                    <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                        <tr>
                            <th class="py-5 px-6 font-black">Receipt #</th>
                            <th class="py-5 px-6 font-black">Student Name</th>
                            <th class="py-5 px-6 font-black text-center">Classification</th>
                            <th class="py-5 px-6 font-black text-center">Timestamp</th>
                            <th class="py-5 px-6 text-right font-black">Amount</th>
                            <th class="py-5 px-6 text-center font-black">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($paymentsToday as $payment)
                            <tr class="hover:bg-blue-50/30 transition-all group">
                                <td
                                    class="py-6 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                    #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-6">
                                    <div
                                        class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                        {{ optional($payment->user)->name }}
                                    </div>
                                </td>
                                <td class="py-6 px-6 text-center">
                                    @php
                                        $latestEnrollment = $payment->user->enrollments()->latest()->first();
                                        $isReturning = $payment->user
                                            ->enrollments()
                                            ->where('id', '<', $latestEnrollment?->id)
                                            ->exists();
                                        $classification =
                                            $latestEnrollment?->student_type ?? ($isReturning ? 'Returning' : 'New');
                                        $classColor = match (strtolower($classification)) {
                                            'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'returning' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                            'transferee' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'shifter' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            default => 'bg-slate-50 text-slate-400 border-slate-100',
                                        };
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $classColor }} rounded-full border">
                                        {{ $classification }}
                                    </span>
                                </td>
                                <td class="py-6 px-6 text-center text-xs font-bold text-slate-400">
                                    {{ $payment->updated_at->format('H:i A') }}</td>
                                <td class="py-6 px-6 text-right font-black text-blue-600 text-lg">
                                    ₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="py-6 px-6 text-center">
                                    @php
                                        $statusStyle = match ($payment->status) {
                                            'Paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default => 'bg-slate-50 text-slate-400 border-slate-100',
                                        };
                                    @endphp
                                    <span
                                        class="{{ $statusStyle }} text-[9px] font-black px-4 py-1.5 rounded-full border shadow-sm uppercase tracking-widest">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-6 rounded-full bg-slate-50 text-slate-200">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No
                                            activity detected for today.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 bg-white overflow-hidden"
            style="border-color: rgba(37,99,235,0.1);">
            <div class="flex items-center gap-4 mb-8 px-4">
                <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">Archived Records
                        — Previous Activity</p>
                </div>
            </div>
            @if ($paymentsYesterday->isEmpty())
                <div class="py-16 text-center text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No
                    archival records found.</div>
            @else
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                            <tr>
                                <th class="py-5 px-6 font-black">Receipt #</th>
                                <th class="py-4 px-6 font-black">Student Name</th>
                                <th class="py-4 px-6 font-black text-center">Classification</th>
                                <th class="py-4 px-6 font-black">Time</th>
                                <th class="py-4 px-6 text-right font-black">Amount</th>
                                <th class="py-4 px-6 text-center font-black">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($paymentsYesterday as $payment)
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td
                                        class="py-6 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                        #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td
                                        class="py-5 px-6 font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                        {{ optional($payment->user)->name }}</td>
                                    <td class="py-5 px-6 text-center">
                                        @php
                                            $latestEnrollment = $payment->user->enrollments()->latest()->first();
                                            $isReturning = $payment->user
                                                ->enrollments()
                                                ->where('id', '<', $latestEnrollment?->id)
                                                ->exists();
                                            $classification =
                                                $latestEnrollment?->student_type ??
                                                ($isReturning ? 'Returning' : 'New');
                                            $classColor = match (strtolower($classification)) {
                                                'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'returning' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'transferee' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'shifter' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                default => 'bg-slate-50 text-slate-400 border-slate-100',
                                            };
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $classColor }} rounded-full border">
                                            {{ $classification }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-xs font-bold text-slate-400">
                                        {{ $payment->updated_at->format('H:i A') }}</td>
                                    <td class="py-5 px-6 text-right font-black text-blue-600">
                                        ₱{{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-5 px-6 text-center">
                                        @php
                                            $statusStyle = match ($payment->status) {
                                                'Paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                default => 'bg-slate-50 text-slate-400 border-slate-100',
                                            };
                                        @endphp
                                        <span
                                            class="{{ $statusStyle }} text-[9px] font-black px-3 py-1 rounded-full border shadow-sm uppercase tracking-widest">
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
</div>
