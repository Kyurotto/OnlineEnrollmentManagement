<x-layouts.cashier title="{{ strtoupper($level) }} Payment Assessment">
<div class="max-w-2xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 mb-2">
            {{ strtoupper($level) }} Payment Assessment
        </h1>
        <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Edit payment assessment fees and discount settings</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Assessment Form Card -->
    <div class="bg-white rounded-3xl p-10 mb-6 shadow-[0_32px_120px_rgba(30,58,138,0.08)] border border-blue-500/10">

        <!-- Card Title -->
        <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Assessment Configuration</h2>
        </div>

        <form action="{{ route('cashier.assessment.store', $level) }}" method="POST" class="space-y-6">
            @csrf
            <!-- Program & Year Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Select {{ $level === 'shs' ? 'Strand' : 'Program' }}</label>
                    <select 
                        name="program"
                        onchange="updateFilters()"
                        id="programSelect"
                        class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 transition-all cursor-pointer shadow-sm"
                    >
                        <option value="all" {{ $program === 'all' ? 'selected' : '' }}>All {{ $level === 'shs' ? 'Strands' : 'Programs' }}</option>
                        @foreach($programs as $p)
                            <option value="{{ $p->course_code }}" {{ $program === $p->course_code ? 'selected' : '' }}>
                                {{ $p->course_code }} {{ $p->track ? '(' . $p->track . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Select Year Level</label>
                    <select 
                        name="yearLevel"
                        onchange="updateFilters()"
                        id="yearLevelSelect"
                        class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 transition-all cursor-pointer shadow-sm"
                    >
                        <option value="all" {{ $yearLevel === 'all' ? 'selected' : '' }}>All Levels</option>
                        @foreach($yearLevels as $yl)
                            @php
                                if ($level === 'shs') {
                                    $label = "Grade " . $yl;
                                } else {
                                    $suffix = match($yl) {
                                        '1' => 'st',
                                        '2' => 'nd',
                                        '3' => 'rd',
                                        default => 'th'
                                    };
                                    $label = $yl . $suffix . " Year";
                                }
                            @endphp
                            <option value="{{ $yl }}" {{ $yearLevel == $yl ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-white/5 mt-4"></div>

            <!-- Tuition Fee -->
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tuition Fee</label>
                <div class="relative group">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                    <input
                        type="number"
                        name="tuitionFee"
                        value="{{ $tuitionFee }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm"
                    />
                </div>
                @error('tuitionFee') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Miscellaneous Fees -->
            <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Miscellaneous Fees</label>
                <div class="relative group">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                    <input
                        type="number"
                        name="miscellaneousFees"
                        value="{{ $miscellaneousFees }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm"
                    />
                </div>
                @error('miscellaneousFees') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Discount Percentage -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Discount (%)</label>
                    <div class="relative group">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-black text-sm">%</span>
                        <input
                            type="number"
                            name="discountPercentage"
                            value="{{ $discountPercentage ?? 0 }}"
                            step="0.01"
                            min="0"
                            max="100"
                            class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm"
                        />
                    </div>
                </div>
                <!-- Discount Amount -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Fixed Discount (₱)</label>
                    <div class="relative group">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                        <input
                            type="number"
                            name="discountAmount"
                            value="{{ $discountAmount ?? 0 }}"
                            step="0.01"
                            min="0"
                            class="w-full bg-slate-50 text-slate-900 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all shadow-sm"
                        />
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="p-8 rounded-[24px] bg-blue-50/50 border border-blue-500/10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Fee Summary</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Tuition Fee:</span>
                        <span class="text-lg font-black text-blue-600 tracking-tighter">₱{{ number_format($tuitionFee, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Miscellaneous Fees:</span>
                        <span class="text-lg font-black text-blue-600 tracking-tighter">₱{{ number_format($miscellaneousFees, 2) }}</span>
                    </div>
                    @php
                        $subtotal = $tuitionFee + $miscellaneousFees;
                        $percDiscount = $subtotal * (($discountPercentage ?? 0) / 100);
                        $totalDiscount = $percDiscount + ($discountAmount ?? 0);
                        $finalTotal = max(0, $subtotal - $totalDiscount);
                    @endphp
                    @if($totalDiscount > 0)
                    <div class="flex justify-between items-center py-4 border-t border-blue-500/5 mt-4">
                        <span class="text-xs font-black text-rose-500 uppercase tracking-wider">Applied Discounts:</span>
                        <span class="text-lg font-black text-rose-500 tracking-tighter">- ₱{{ number_format($totalDiscount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-6 border-t border-blue-500/10 mt-4">
                        <span class="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Total Payable:</span>
                        <span class="text-3xl font-black text-blue-600 tracking-tighter">₱{{ number_format($finalTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-8">
                <button
                    type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-[20px] font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 shadow-lg shadow-blue-600/20 flex items-center justify-center gap-3 active:scale-95"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Save Assessment
                </button>
                <a
                    href="{{ route('cashier.dashboard') }}"
                    class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 py-5 rounded-[20px] font-black uppercase tracking-[0.2em] text-xs transition-all duration-300 flex items-center justify-center gap-3 border border-slate-200/60 active:scale-95"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
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
            Note: These base fees will be applied to all new and existing payments for <span class="font-black underline">{{ strtoupper($level) }}</span> students. Additional discounts can be applied per payment.
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
</script>
</x-layouts.cashier>
