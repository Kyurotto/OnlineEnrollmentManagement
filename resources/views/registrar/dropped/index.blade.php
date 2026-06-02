@php $isAdmin = request()->routeIs('admin.*'); @endphp
<x-dynamic-component :component="$isAdmin ? 'layouts.admin' : 'layouts.registrar'" title="Dropped Students Report">

    <div class="space-y-8 animate-in fade-in duration-500">

        {{-- Flash --}}
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-white uppercase tracking-tight">Dropped Students Report</h1>
                <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.3em] mt-1">Official Drop & Withdrawal Registry — Penalty & Financial Summary</p>
            </div>
            <a href="{{ route($isAdmin ? 'admin.reports.dropped.print' : 'registrar.reports.dropped.print') }}" target="_blank"
                class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 transition-all text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print PDF
            </a>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card rounded-2xl p-6 border border-white/5">
                <p class="text-[9px] font-black text-rose-400/60 uppercase tracking-[0.3em]">Dropped</p>
                <p class="text-4xl font-black text-rose-400 mt-2">{{ $droppedCount }}</p>
            </div>
            <div class="glass-card rounded-2xl p-6 border border-white/5">
                <p class="text-[9px] font-black text-amber-400/60 uppercase tracking-[0.3em]">Withdrawn</p>
                <p class="text-4xl font-black text-amber-400 mt-2">{{ $withdrawnCount }}</p>
            </div>
            <div class="glass-card rounded-2xl p-6 border border-white/5">
                <p class="text-[9px] font-black text-orange-400/60 uppercase tracking-[0.3em]">At Risk</p>
                <p class="text-4xl font-black text-orange-400 mt-2">{{ $atRiskCount }}</p>
            </div>
            <div class="glass-card rounded-2xl p-6 border border-white/5">
                <p class="text-[9px] font-black text-purple-400/60 uppercase tracking-[0.3em]">Total Penalties</p>
                <p class="text-2xl font-black text-purple-400 mt-2">₱{{ number_format($totalPenalties, 2) }}</p>
            </div>
        </div>

        {{-- Reason Summary Chart --}}
        @if(count($reasonSummary) > 0)
        <div class="glass-card rounded-[28px] border border-white/5 p-8">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1 h-4 rounded-full bg-purple-400"></span>
                <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">Drop Reason Breakdown</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($reasonSummary as $item)
                    @php
                        $colors = [
                            'Financial' => 'text-rose-400 bg-rose-400/10 border-rose-400/20',
                            'Personal'  => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                            'Transfer'  => 'text-sky-400 bg-sky-400/10 border-sky-400/20',
                            'Academic'  => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
                            'Health'    => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                            'Other'     => 'text-white/40 bg-white/5 border-white/10',
                        ];
                        $color = $colors[$item['reason']] ?? 'text-white/40 bg-white/5 border-white/10';
                    @endphp
                    <div class="rounded-2xl border p-4 text-center {{ $color }}">
                        <p class="text-[9px] font-black uppercase tracking-widest opacity-70">{{ $item['reason'] }}</p>
                        <p class="text-3xl font-black mt-1">{{ $item['count'] }}</p>
                        <p class="text-[9px] font-bold mt-1 opacity-60">{{ $item['percentage'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── OFFICIALLY DROPPED TABLE ── --}}
        <div class="glass-card rounded-[28px] border border-white/5 overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-rose-400"></span>
                    <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">Officially Dropped / Withdrawn</h2>
                </div>
                <span class="text-[9px] font-black text-rose-400/60 uppercase tracking-widest">{{ $officiallyDropped->count() }} record(s)</span>
            </div>

            @if($officiallyDropped->isEmpty())
                <div class="py-16 text-center text-white/20 text-[10px] font-black uppercase tracking-widest">
                    No dropped or withdrawn students on record.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] text-white/20 uppercase tracking-[0.25em] border-b border-white/5">
                                <th class="py-4 px-6">Student</th>
                                <th class="py-4 px-6 text-center">Program</th>
                                <th class="py-4 px-6 text-center">Level</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Drop Period</th>
                                <th class="py-4 px-6 text-center">Drop Date</th>
                                <th class="py-4 px-6 text-center">Reason</th>
                                <th class="py-4 px-6 text-right">Total Paid</th>
                                <th class="py-4 px-6 text-right">Charge</th>
                                <th class="py-4 px-6 text-right">Refundable</th>
                                @unless($isAdmin)
                                <th class="py-4 px-6 text-center">Actions</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($officiallyDropped as $s)
                                <tr class="hover:bg-white/[0.02] transition-colors" x-data="{ showNotes: false, showCharge: false }">
                                    <td class="py-4 px-6">
                                        <p class="text-white font-black text-xs uppercase tracking-wider">{{ $s->name }}</p>
                                        <p class="text-white/30 text-[9px] mt-0.5">{{ $s->email }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-purple-400 font-black text-[10px] uppercase">{{ $s->course }}</span>
                                        <p class="text-white/30 text-[9px]">{{ $s->year_level }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest
                                            {{ $s->level === 'SHS' ? 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20' : 'text-sky-400 bg-sky-400/10 border-sky-400/20' }}">
                                            {{ $s->level }}
                                            @if($s->voucher_type) · Voucher @endif
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="text-[9px] font-black px-3 py-1 rounded-full border uppercase tracking-widest
                                            {{ $s->drop_status === 'Dropped' ? 'text-rose-400 bg-rose-400/10 border-rose-400/20' : 'text-amber-400 bg-amber-400/10 border-amber-400/20' }}">
                                            {{ $s->drop_status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center text-white/50 text-xs font-bold whitespace-nowrap">
                                        {{ str_replace('_', ' ', ucwords($s->drop_period, '_')) }}
                                    </td>
                                    <td class="py-4 px-6 text-center text-white/50 text-xs font-bold">{{ $s->drop_date }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @php
                                            $rc = ['Financial'=>'text-rose-400','Personal'=>'text-amber-400','Transfer'=>'text-sky-400','Academic'=>'text-purple-400','Health'=>'text-emerald-400','Other'=>'text-white/40'];
                                        @endphp
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $rc[$s->drop_reason] ?? 'text-white/40' }}">
                                            {{ $s->drop_reason }}
                                        </span>
                                        @if($s->drop_notes)
                                            <button @click="showNotes = !showNotes" class="block mx-auto mt-1 text-[8px] text-white/20 hover:text-white/50 uppercase tracking-widest">notes ▾</button>
                                            <p x-show="showNotes" x-cloak class="text-[9px] text-white/40 mt-1 max-w-[140px] text-left">{{ $s->drop_notes }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right text-white font-black text-xs">₱{{ number_format($s->total_paid, 2) }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="text-rose-400 font-black text-xs">₱{{ number_format($s->drop_charge, 2) }}</span>
                                        <button @click="showCharge = !showCharge" class="block ml-auto mt-0.5 text-[8px] text-white/20 hover:text-white/50 uppercase tracking-widest">details ▾</button>
                                        <p x-show="showCharge" x-cloak class="text-[9px] text-white/30 mt-1 max-w-[180px] text-right leading-relaxed">{{ $s->charge_description }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="{{ $s->net_refundable > 0 ? 'text-emerald-400' : 'text-white/30' }} font-black text-xs">
                                            ₱{{ number_format($s->net_refundable, 2) }}
                                        </span>
                                    </td>
                                    @unless($isAdmin)
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('registrar.dropped.restore', $s->enrollment_id) }}" method="POST"
                                              onsubmit="return confirm('Restore this student to Enrolled status?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-[9px] font-black px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/20 uppercase tracking-widest transition-all">
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── ENROLLED STUDENTS TABLE ── --}}
        <div class="glass-card rounded-[28px] border border-white/5 overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-blue-400"></span>
                    <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">Enrolled Students</h2>
                    <span class="text-[9px] text-white/30 font-bold normal-case tracking-normal">Candidates for dropping</span>
                </div>
                <span class="text-[9px] font-black text-blue-400/60 uppercase tracking-widest">{{ $atRiskStudents->count() }} active</span>
            </div>

            @if($atRiskStudents->isEmpty())
                <div class="py-16 text-center text-white/20 text-[10px] font-black uppercase tracking-widest">
                    No active students detected.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] text-white/20 uppercase tracking-[0.25em] border-b border-white/5">
                                <th class="py-4 px-6">Student</th>
                                <th class="py-4 px-6 text-center">Program</th>
                                <th class="py-4 px-6 text-center">Absences</th>
                                <th class="py-4 px-6 text-center">Last Payment</th>
                                <th class="py-4 px-6 text-right">Total Paid</th>
                                <th class="py-4 px-6">Risk Flags</th>
                                @unless($isAdmin)
                                <th class="py-4 px-6 text-center">Action</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($atRiskStudents as $s)
                                <tr class="hover:bg-white/[0.02] transition-colors" x-data="{ showModal: false }">
                                    <td class="py-4 px-6">
                                        <p class="text-white font-black text-xs uppercase tracking-wider">{{ $s->name }}</p>
                                        <p class="text-white/30 text-[9px] mt-0.5">{{ $s->email }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-purple-400 font-black text-[10px] uppercase">{{ $s->course }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="text-[10px] font-black {{ $s->consecutive_absences >= 5 ? 'text-rose-400' : 'text-white/40' }}">
                                            {{ $s->consecutive_absences }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center text-white/50 text-xs font-bold">{{ $s->last_payment_date }}</td>
                                    <td class="py-4 px-6 text-right text-white font-black text-xs">₱{{ number_format($s->total_paid, 2) }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($s->risk_flags as $flag)
                                                @php
                                                    $isNormal = $flag === 'Normal';
                                                    $flagColor = $isNormal ? 'emerald-500' : 'orange-400';
                                                @endphp
                                                <span class="text-[8px] font-black px-2 py-0.5 rounded-full bg-{{ $flagColor }}/10 border border-{{ $flagColor }}/20 text-{{ $flagColor }} uppercase tracking-widest whitespace-nowrap">
                                                    {{ $flag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    @unless($isAdmin)
                                    <td class="py-4 px-6 text-center">
                                        <button @click="showModal = true"
                                            class="text-[9px] font-black px-3 py-1.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 uppercase tracking-widest transition-all">
                                            Mark Drop
                                        </button>

                                        {{-- Drop Modal --}}
                                        <template x-teleport="body">
                                            <div x-show="showModal" x-cloak
                                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                                 class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-md" @click="showModal = false"></div>
                                                <div class="relative z-10 w-full max-w-md bg-white border border-blue-500/20 rounded-[32px] shadow-[0_32px_120px_rgba(30,58,138,0.2)] p-8 md:p-10 space-y-6"
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                                                    {{-- Modal Header --}}
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <span class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] block mb-1">Mark as Dropped</span>
                                                            <h3 class="text-xl font-black text-black uppercase tracking-tight">{{ $s->name }}</h3>
                                                            <p class="text-[10px] font-bold text-slate-400 mt-1">{{ $s->course }} · {{ $s->level }}</p>
                                                        </div>
                                                        <button @click="showModal = false" class="p-2 rounded-xl bg-slate-100 text-slate-400 hover:text-black hover:bg-slate-200 transition-all border border-slate-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>

                                                    <form action="{{ route('registrar.dropped.mark', $s->enrollment_id) }}" method="POST" class="space-y-5">
                                                        @csrf
                                                        @method('PATCH')

                                                        {{-- Drop Period --}}
                                                        <div class="space-y-1.5">
                                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Drop Period <span class="text-rose-400">*</span></label>
                                                            <select name="drop_period" required
                                                                class="w-full bg-blue-50/50 text-black border border-slate-200 py-3 px-4 rounded-2xl outline-none text-[11px] font-bold cursor-pointer focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm appearance-none">
                                                                @foreach(\App\Services\DroppedStudentReportService::getDropPeriods($s->level === 'SHS') as $key => $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Drop Reason --}}
                                                        <div class="space-y-1.5">
                                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Drop Reason <span class="text-rose-400">*</span></label>
                                                            <select name="drop_reason" required
                                                                class="w-full bg-blue-50/50 text-black border border-slate-200 py-3 px-4 rounded-2xl outline-none text-[11px] font-bold cursor-pointer focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm appearance-none">
                                                                @foreach(['Financial','Personal','Transfer','Academic','Health','Other'] as $reason)
                                                                    <option value="{{ $reason }}">{{ $reason }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Base Tuition --}}
                                                        <div class="space-y-1.5">
                                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Base Tuition (₱) <span class="text-slate-300 normal-case font-bold">for half-tuition calculation</span></label>
                                                            <input type="number" name="base_tuition" step="0.01" min="0" placeholder="0.00"
                                                                class="w-full bg-blue-50/50 text-black border border-slate-200 py-3 px-4 rounded-2xl outline-none text-[11px] font-bold placeholder-slate-300 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm">
                                                        </div>

                                                        {{-- Notes --}}
                                                        <div class="space-y-1.5">
                                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Notes <span class="text-slate-300 normal-case font-bold">(optional)</span></label>
                                                            <textarea name="drop_notes" rows="2" placeholder="Additional notes..."
                                                                class="w-full bg-blue-50/50 text-black border border-slate-200 py-3 px-4 rounded-2xl outline-none text-[11px] font-bold placeholder-slate-300 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm resize-none"></textarea>
                                                        </div>

                                                        {{-- Action Buttons --}}
                                                        <div class="flex gap-3 pt-2">
                                                            <button type="button" @click="showModal = false"
                                                                class="flex-1 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border border-slate-200 rounded-2xl hover:bg-slate-50 hover:text-black transition-all">
                                                                Cancel
                                                            </button>
                                                            <button type="submit"
                                                                class="flex-[2] bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black py-3.5 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-600/20 active:scale-95">
                                                                Confirm Drop
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-dynamic-component>
