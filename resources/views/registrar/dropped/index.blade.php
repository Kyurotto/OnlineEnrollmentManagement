<x-layouts.registrar title="Dropped Students Report">

    <div class="space-y-8 animate-in fade-in duration-500">

        {{-- Flash --}}
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Page Header --}}
        <div>
            <h1 class="text-3xl font-black text-white uppercase italic tracking-tight">Dropped Students Report</h1>
            <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.3em] mt-1">Official Drop & Withdrawal Registry — Penalty & Financial Summary</p>
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
                <h2 class="text-xs font-black text-white uppercase tracking-[0.3em] italic">Drop Reason Breakdown</h2>
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
                    <h2 class="text-xs font-black text-white uppercase tracking-[0.3em] italic">Officially Dropped / Withdrawn</h2>
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
                                <th class="py-4 px-6 text-center">Actions</th>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── AT-RISK TABLE ── --}}
        <div class="glass-card rounded-[28px] border border-white/5 overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-orange-400"></span>
                    <h2 class="text-xs font-black text-white uppercase tracking-[0.3em] italic">At-Risk Students</h2>
                    <span class="text-[9px] text-white/30 font-bold normal-case tracking-normal">≥5 absences or no payment in 30+ days</span>
                </div>
                <span class="text-[9px] font-black text-orange-400/60 uppercase tracking-widest">{{ $atRiskStudents->count() }} flagged</span>
            </div>

            @if($atRiskStudents->isEmpty())
                <div class="py-16 text-center text-white/20 text-[10px] font-black uppercase tracking-widest">
                    No at-risk students detected.
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
                                <th class="py-4 px-6 text-center">Action</th>
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
                                                <span class="text-[8px] font-black px-2 py-0.5 rounded-full bg-orange-400/10 border border-orange-400/20 text-orange-400 uppercase tracking-widest whitespace-nowrap">
                                                    {{ $flag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <button @click="showModal = true"
                                            class="text-[9px] font-black px-3 py-1.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 uppercase tracking-widest transition-all">
                                            Mark Drop
                                        </button>

                                        {{-- Drop Modal --}}
                                        <div x-show="showModal" x-cloak
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
                                            <div class="relative z-10 w-full max-w-sm bg-[#0f0f1a] border border-white/10 rounded-[28px] shadow-2xl p-8 space-y-5 animate-in fade-in zoom-in-95 duration-200">
                                                <div>
                                                    <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Mark as Dropped</p>
                                                    <h3 class="text-lg font-black text-white uppercase italic mt-0.5">{{ $s->name }}</h3>
                                                </div>

                                                <form action="{{ route('registrar.dropped.mark', $s->enrollment_id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PATCH')

                                                    {{-- Drop Period --}}
                                                    <div class="space-y-1">
                                                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Drop Period *</label>
                                                        <select name="drop_period" required
                                                            class="w-full bg-white/[0.03] text-white border border-white/10 py-3 px-4 rounded-xl outline-none text-sm font-bold cursor-pointer">
                                                            @foreach(\App\Services\DroppedStudentReportService::getDropPeriods($s->level === 'SHS') as $key => $label)
                                                                <option value="{{ $key }}" style="background-color:#0d1b2e;color:#fff;">{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Drop Reason *</label>
                                                        <select name="drop_reason" required
                                                            class="w-full bg-white/[0.03] text-white border border-white/10 py-3 px-4 rounded-xl outline-none text-sm font-bold cursor-pointer">
                                                            @foreach(['Financial','Personal','Transfer','Academic','Health','Other'] as $reason)
                                                                <option value="{{ $reason }}" style="background-color:#0d1b2e;color:#fff;">{{ $reason }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Base Tuition (₱) <span class="text-white/20 normal-case">for half-tuition calculation</span></label>
                                                        <input type="number" name="base_tuition" step="0.01" min="0" placeholder="0.00"
                                                            class="w-full bg-white/[0.03] text-white border border-white/10 py-3 px-4 rounded-xl outline-none text-sm font-bold placeholder-white/20">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Notes (optional)</label>
                                                        <textarea name="drop_notes" rows="2" placeholder="Additional notes..."
                                                            class="w-full bg-white/[0.03] text-white border border-white/10 py-3 px-4 rounded-xl outline-none text-sm font-bold placeholder-white/20 resize-none"></textarea>
                                                    </div>

                                                    <div class="flex gap-3 pt-1">
                                                        <button type="button" @click="showModal = false"
                                                            class="flex-1 py-3 text-[9px] font-black text-white/40 uppercase tracking-[0.3em] border border-white/10 rounded-xl hover:bg-white/5 transition-all">
                                                            Cancel
                                                        </button>
                                                        <button type="submit"
                                                            class="flex-[2] bg-rose-500 hover:bg-rose-400 text-white text-[9px] font-black py-3 rounded-xl uppercase tracking-[0.3em] transition-all">
                                                            Confirm Drop
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-layouts.registrar>
