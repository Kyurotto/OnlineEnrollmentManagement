<x-layouts.cashier title="{{ strtoupper($level) }} Payment Assessment">
    <div class="max-w-3xl mx-auto py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 mb-2">
                {{ strtoupper($level) }} Payment Assessment
            </h1>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Edit payment assessment fees and
                discount settings</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Assessment Form Card -->
        <div
            class="bg-white rounded-3xl p-10 mb-6 shadow-[0_32px_120px_rgba(30,58,138,0.08)] border border-blue-500/10">

            <!-- Card Title -->
            <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Assessment Configuration</h2>
            </div>

            <form action="{{ route('cashier.assessment.store', $level) }}" method="POST" class="space-y-6"
                id="assessmentForm">
                @csrf
                <!-- Program & Year Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Select
                            {{ $level === 'shs' ? 'Strand' : 'Program' }}</label>
                        <select name="program" onchange="updateFilters()" id="programSelect"
                            class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 transition-all cursor-pointer shadow-sm">
                            <option value="all" {{ $program === 'all' ? 'selected' : '' }}>All
                                {{ $level === 'shs' ? 'Strands' : 'Programs' }}</option>
                            @foreach ($programs as $p)
                                <option value="{{ $p->course_code }}"
                                    {{ $program === $p->course_code ? 'selected' : '' }}>
                                    {{ $p->course_code }} {{ $p->track ? '(' . $p->track . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Select
                            Year Level</label>
                        <select name="yearLevel" onchange="updateFilters()" id="yearLevelSelect"
                            class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 transition-all cursor-pointer shadow-sm">
                            <option value="all" {{ $yearLevel === 'all' ? 'selected' : '' }}>All Levels</option>
                            @foreach ($yearLevels as $yl)
                                @php
                                    if ($level === 'shs') {
                                        $label = 'Grade ' . $yl;
                                    } else {
                                        $suffix = match ($yl) {
                                            '1' => 'st',
                                            '2' => 'nd',
                                            '3' => 'rd',
                                            default => 'th',
                                        };
                                        $label = $yl . $suffix . ' Year';
                                    }
                                @endphp
                                <option value="{{ $yl }}" {{ $yearLevel == $yl ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 mt-4"></div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- REGISTRATION FEE -->
                <!-- ═══════════════════════════════════════════ -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Registration Fee</h3>
                    </div>
                    <div class="relative group">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                        <input type="number" name="registrationFee" id="registrationFee"
                            value="{{ $feeData['registrationFee'] ?? 0 }}" step="0.01" min="0"
                            placeholder="0.00" oninput="recalcTotals()"
                            class="fee-input w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm" />
                    </div>
                    @error('registrationFee')
                        <span
                            class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- MISCELLANEOUS FEES (Itemized) -->
                <!-- ═══════════════════════════════════════════ -->
                <div class="space-y-4 mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Miscellaneous Fees
                            </h3>
                        </div>
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest"
                            id="miscTotalLabel">₱ 0.00</span>
                    </div>

                    <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-6 space-y-3">
                        @php
                            $miscItems = [
                                'guidanceFee' => 'Guidance Fee',
                                'trainingMaterials' => 'Training Materials',
                                'handbook' => 'Handbook',
                                'mailingFee' => 'Mailing Fee',
                                'medicalDental' => 'Medical and Dental Service',
                                'studentId' => 'Student Identification Card',
                                'socioCultural' => 'Socio-Cultural and Sports Development',
                                'insurance' => 'Insurance',
                                'schoolPublication' => 'School Publication',
                                'studentDevelopment' => 'Student Development',
                                'libraryFee' => 'Library Fee',
                                'energyFee' => 'Energy Fee',
                                'physicalFacilities' => 'Physical/Facilities Development',
                                'researchInnovation' => 'Research, Innovation and Extension',
                                'internetFee' => 'Internet Fee',
                                'audioVisual' => 'Audio Visual',
                                'itDevelopment' => 'IT Development',
                            ];
                        @endphp

                        @foreach ($miscItems as $key => $label)
                            <div class="flex items-center justify-between gap-4">
                                <label
                                    class="text-[11px] font-bold text-slate-600 uppercase tracking-wide min-w-[200px] flex-shrink-0"
                                    for="{{ $key }}">{{ $label }}</label>
                                <div class="relative group w-48 flex-shrink-0">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 font-black text-xs">₱</span>
                                    <input type="number" name="{{ $key }}" id="{{ $key }}"
                                        value="{{ $feeData[$key] ?? 0 }}" step="0.01" min="0"
                                        placeholder="0.00" oninput="recalcTotals()"
                                        class="misc-fee fee-input w-full bg-white text-slate-900 border border-slate-200 py-2.5 pl-9 pr-4 rounded-xl outline-none text-xs font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm text-right" />
                                </div>
                            </div>
                            @error($key)
                                <span
                                    class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span>
                            @enderror
                        @endforeach
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- LABORATORY FEE -->
                <!-- ═══════════════════════════════════════════ -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Laboratory Fee</h3>
                    </div>
                    <div class="relative group">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                        <input type="number" name="laboratoryFee" id="laboratoryFee"
                            value="{{ $feeData['laboratoryFee'] ?? 0 }}" step="0.01" min="0"
                            placeholder="0.00" oninput="recalcTotals()"
                            class="fee-input w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm" />
                    </div>
                    @error('laboratoryFee')
                        <span
                            class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- TUITION FEE -->
                <!-- ═══════════════════════════════════════════ -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Tuition Fee</h3>
                    </div>
                    <div class="relative group">
                        <span
                            class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                        <input type="number" name="tuitionFee" id="tuitionFee"
                            value="{{ $feeData['tuitionFee'] ?? $tuitionFee }}" step="0.01" min="0"
                            placeholder="0.00" oninput="recalcTotals()"
                            class="fee-input w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm" />
                    </div>
                    @error('tuitionFee')
                        <span
                            class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- GRAND TOTAL SUMMARY -->
                <!-- ═══════════════════════════════════════════ -->
                <div class="p-8 rounded-[24px] bg-blue-50/50 border border-blue-500/10 mt-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Fee Summary</p>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Registration
                                Fee:</span>
                            <span class="text-lg font-black text-blue-600 tracking-tighter" id="summaryRegistration">₱
                                0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total
                                Miscellaneous Fees:</span>
                            <span class="text-lg font-black text-blue-600 tracking-tighter" id="summaryMisc">₱
                                0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Laboratory
                                Fee:</span>
                            <span class="text-lg font-black text-blue-600 tracking-tighter" id="summaryLab">₱
                                0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Tuition
                                Fee:</span>
                            <span class="text-lg font-black text-blue-600 tracking-tighter" id="summaryTuition">₱
                                0.00</span>
                        </div>
                        <div class="flex justify-between items-center pt-6 border-t border-blue-500/10 mt-4">
                            <span class="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Total School
                                Fees:</span>
                            <span class="text-3xl font-black text-blue-600 tracking-tighter" id="summaryGrandTotal">₱
                                0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-[20px] font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 shadow-lg shadow-blue-600/20 flex items-center justify-center gap-3 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Save Assessment
                    </button>
                    <a href="{{ route('cashier.dashboard') }}"
                        class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 py-5 rounded-[20px] font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 flex items-center justify-center gap-3 border border-slate-200/60 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="p-6 rounded-2xl bg-blue-50 border border-blue-500/10 flex gap-4 items-start shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0 text-white">
                <span class="text-sm">💡</span>
            </div>
            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-widest leading-relaxed">
                Note: These base fees will be applied to all new and existing payments for <span
                    class="font-black underline">{{ strtoupper($level) }}</span> students. Additional discounts can be
                applied per payment.
            </p>
        </div>
    </div>

    <script>
        function updateFilters() {
            const program = document.getElementById('programSelect').value;
            const yearLevel = document.getElementById('yearLevelSelect').value;
            const url = new URL(window.location.href);
            url.searchParams.set('program', program);
            url.searchParams.set('yearLevel', yearLevel);
            window.location.href = url.toString();
        }

        function getVal(id) {
            return parseFloat(document.getElementById(id)?.value) || 0;
        }

        function fmt(n) {
            return '₱ ' + n.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function recalcTotals() {
            // Sum all misc items
            let miscTotal = 0;
            document.querySelectorAll('.misc-fee').forEach(el => {
                miscTotal += parseFloat(el.value) || 0;
            });

            const reg = getVal('registrationFee');
            const lab = getVal('laboratoryFee');
            const tui = getVal('tuitionFee');
            const grand = reg + miscTotal + lab + tui;

            document.getElementById('miscTotalLabel').textContent = fmt(miscTotal);
            document.getElementById('summaryRegistration').textContent = fmt(reg);
            document.getElementById('summaryMisc').textContent = fmt(miscTotal);
            document.getElementById('summaryLab').textContent = fmt(lab);
            document.getElementById('summaryTuition').textContent = fmt(tui);
            document.getElementById('summaryGrandTotal').textContent = fmt(grand);
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', recalcTotals);
    </script>
</x-layouts.cashier>
