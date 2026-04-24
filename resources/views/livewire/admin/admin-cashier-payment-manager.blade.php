<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="h-[calc(100vh-200px)] flex gap-6">
        <!-- LEFT SIDEBAR: STUDENT LIST -->
        <div class="w-80 flex flex-col bg-white/[0.05] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-white/5 bg-white/[0.02]">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <h3 class="font-black text-white uppercase text-sm tracking-tight">Student List</h3>
                </div>
                <div class="relative group">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs text-white focus:border-emerald-500/50 outline-none transition-all placeholder-white/20 font-bold uppercase tracking-tight"
                        placeholder="Search student...">
                </div>
            </div>

            <!-- Student List -->
            <div class="flex-grow overflow-y-auto custom-scrollbar divide-y divide-white/5">
                @forelse($enrolledStudents as $enrollment)
                    @php
                        $isSelected = $selectedStudentId === $enrollment->user_id;
                        $student = $enrollment->user;
                    @endphp
                    <button wire:click="selectStudent({{ $enrollment->user_id }}, {{ $enrollment->id }})"
                        class="w-full text-left px-6 py-4 hover:bg-emerald-500/5 transition-colors {{ $isSelected ? 'bg-emerald-500/10 border-l-4 border-emerald-400' : 'border-l-4 border-transparent' }}">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="text-xs font-black text-emerald-400 uppercase tracking-wider">#{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }} {{ $student->last_name }}, {{ $student->first_name }}</div>
                            @if($enrollment->voucher_type)
                                @php
                                    $voucherColor = $enrollment->voucher_type === 'free_tuition' ? '#86efac' : '#fbbf24';
                                @endphp
                                <svg class="w-4 h-4 flex-shrink-0" style="color: {{ $voucherColor }};" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                            @endif
                        </div>
                        <div class="text-xs text-white/40 font-bold uppercase tracking-widest">{{ $enrollment->course_code ?? 'N/A' }} • {{ $enrollment->year_level ?? 'N/A' }}</div>
                        <div class="text-xs text-white/20 font-bold uppercase tracking-widest mt-1">{{ $enrollment->semester ?? 'N/A' }} semester</div>
                    </button>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em] italic">No students found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: PAYMENT DETAILS -->
        <div class="flex-grow flex flex-col bg-white/[0.05] border border-white/10 rounded-2xl shadow-2xl shadow-black/40 overflow-hidden">
            @if($selectedStudentId)
                <!-- Header with Student Info & Action Buttons -->
                <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-black text-white text-lg uppercase tracking-tight">#{{ str_pad($selectedStudent->id, 3, '0', STR_PAD_LEFT) }} {{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }}</h3>
                                @if($selectedVoucherType)
                                    @if($selectedVoucherType === 'free_tuition')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest bg-green-600 text-white shadow-md">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                            Free Tuition
                                        </span>
                                    @elseif($selectedVoucherType === 'discounted')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest bg-yellow-500 text-gray-900 shadow-md">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                            Discounted
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-xs text-white/40 font-bold uppercase tracking-widest">{{ $enrollment->year_level ?? 'N/A' }} | {{ $enrollment->course_code ?? 'N/A' }} | {{ $enrollment->semester ?? 'N/A' }} semester • {{ $enrollment->academic_year ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Buttons removed for Admin -->
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-8 py-5 border-b border-white/5 bg-white/[0.01] flex gap-12">
                    <button wire:click="$set('activeTab', 'assessment')" class="text-xs font-black uppercase tracking-[0.15em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'assessment' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        ✓ Payment Assessment
                    </button>
                    <button wire:click="$set('activeTab', 'balance')" class="text-xs font-black uppercase tracking-[0.15em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'balance' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        ⓘ Balance
                    </button>
                    <button wire:click="$set('activeTab', 'history')" class="text-xs font-black uppercase tracking-[0.15em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'history' ? 'text-white border-cyan-400' : 'text-white/50 border-transparent hover:text-white/70' }}">
                        History
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="flex-grow overflow-y-auto custom-scrollbar px-8 py-4 space-y-4">
                    @if($activeTab === 'assessment')
                        <div class="space-y-3">
                            <!-- Fee Items -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center pb-2">
                                    <span class="font-medium text-white/70 text-xs uppercase tracking-wide">Tuition</span>
                                    <span class="font-black text-white text-xs">₱ {{ number_format($tuitionFees, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-2">
                                    <span class="font-medium text-white/70 text-xs uppercase tracking-wide">Miscellaneous Fees</span>
                                    <span class="font-black text-white text-xs">₱ {{ number_format($miscellaneousFees, 2) }}</span>
                                </div>
                                @if($appliedDiscount > 0)
                                <div class="flex justify-between items-center pb-2">
                                    <span class="font-medium text-rose-300 text-xs uppercase tracking-wide">Additional Discount</span>
                                    <span class="font-black text-rose-400 text-xs">(₱ {{ number_format($appliedDiscount, 2) }})</span>
                                </div>
                                @endif
                            </div>

                            <!-- Separator -->
                            <div class="h-px bg-gradient-to-r from-white/5 via-white/10 to-white/5"></div>

                            <!-- Total Assessment -->
                            <div class="flex justify-between items-center pt-2">
                                <span class="font-black text-white/80 text-xs uppercase tracking-wider">TOTAL ASSESSMENT:</span>
                                <span class="font-black text-cyan-300 text-sm">₱ {{ number_format((float)$totalAssessment - (float)$appliedDiscount, 2) }}</span>
                            </div>

                            <!-- Discount Section -->
                            <div class="pt-2 space-y-2">
                                @if($appliedDiscount > 0)
                                    <!-- Discount Applied Display -->
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-black text-emerald-400 text-xs uppercase tracking-widest">✓ Discount Applied</span>
                                            <span class="font-black text-emerald-300 text-xs">₱{{ number_format($appliedDiscount, 2) }} ({{ number_format(($totalAssessment > 0 ? ($appliedDiscount / $totalAssessment * 100) : 0), 2) }}%)</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @elseif($activeTab === 'balance')
                        <div class="space-y-2">
                            <div class="text-center p-3 bg-white/[0.05] rounded-xl border border-white/10">
                                <p class="text-white/50 text-xs font-bold uppercase mb-2 tracking-widest">Current Balance</p>
                                <p class="text-2xl font-black text-cyan-400">₱ {{ number_format($currentBalance, 2) }}</p>
                            </div>
                        </div>

                    @elseif($activeTab === 'history')
                        <div class="space-y-2">
                            @forelse($paymentHistory as $transaction)
                                <div class="p-3 bg-white/[0.05] rounded-xl border border-white/10 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-black text-white text-xs">Transaction #{{ $transaction->id }}</span>
                                        <span class="text-xs font-black {{ $transaction->status === 'Paid' ? 'text-emerald-400' : ($transaction->status === 'Pending' ? 'text-amber-400' : 'text-rose-400') }} bg-white/5 px-2 py-1 rounded-lg">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-white/40 font-bold">{{ $transaction->created_at->format('M d, Y • h:i A') }}</div>
                                    <div class="space-y-1 pt-2 border-t border-white/5">
                                        <div class="flex justify-between items-center">
                                            <span class="text-white/60 text-xs">Amount:</span>
                                            <span class="text-white font-black text-sm">₱ {{ number_format($transaction->amount, 2) }}</span>
                                        </div>
                                        @if($transaction->reference_no)
                                        <div class="flex justify-between items-center">
                                            <span class="text-white/60 text-xs">Reference:</span>
                                            <span class="text-white/80 font-mono text-xs bg-white/5 px-2 py-0.5 rounded">{{ $transaction->reference_no }}</span>
                                        </div>
                                        @endif
                                        @if($transaction->notes)
                                        <div class="mt-1 p-2 bg-white/5 rounded-lg text-xs text-white/60">
                                            {{ $transaction->notes }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-xs font-black text-white/10 uppercase tracking-[0.4em] italic py-4">No payment history found</p>
                            @endforelse
                        </div>
                    @endif
                </div>

            @else
                <div class="flex-grow flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white/10 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19v-2a6 6 0 0112 0v2a2 2 0 01-2 2H7a2 2 0 01-2-2z"></path></svg>
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.3em]">Select a student to view payment details</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showModal)
        <!-- Payment Modal (same as before) -->
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <button wire:click="closeModal" class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl transition-all cursor-default"></button>
            <div class="bg-[#0d1f3c] border border-white/10 w-full max-w-lg rounded-[32px] overflow-hidden shadow-[0_32px_120px_rgba(0,0,0,0.6)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-10">
                    <div class="mb-10 text-center">
                        <h3 class="text-xl font-black text-white uppercase tracking-tight leading-none">{{ $isEditMode ? 'Edit Payment' : 'New Payment' }}</h3>
                        <p class="text-white/30 text-xs font-black uppercase tracking-[0.3em] mt-2 italic shadow-sm leading-none">Payment Information</p>
                    </div>

                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Student</label>
                            <select wire:model="user_id" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 appearance-none transition-all cursor-pointer shadow-inner" required>
                                <option value="" class="bg-[#0d1f3c]">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" class="bg-[#0d1f3c] text-white">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Amount</label>
                                <input type="number" wire:model="amount" step="0.01"
                                    class="w-full bg-white/5 text-emerald-400 border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-base font-black tracking-tighter focus:border-emerald-500/50 transition-all shadow-inner" placeholder="0.00" required>
                                @error('amount') <span class="text-rose-500 text-xs font-bold uppercase tracking-tighter">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2 hover:translate-y-[-2px] transition-transform">
                                <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Payment Method</label>
                                <select wire:model="payment_type" class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 appearance-none transition-all cursor-pointer shadow-inner uppercase font-black tracking-widest" required>
                                    @foreach(['Cash', 'Gcash', 'PayMaya', 'Bank Transfer'] as $method)
                                        <option value="{{ $method }}" class="bg-[#0d1f3c]">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-white/40 uppercase tracking-[0.2em] ml-1">Reference No.</label>
                            <input type="text" wire:model="reference_no"
                                class="w-full bg-white/5 text-white border border-white/10 py-4 px-6 rounded-xl outline-none placeholder-white/10 text-sm font-bold tracking-wider focus:border-emerald-500/50 transition-all uppercase shadow-inner"
                                placeholder="DOC NO / OR NO">
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 pt-6">
                            <button type="button" wire:click="closeModal"
                                class="flex-1 px-8 py-5 text-center text-xs font-black text-white/40 uppercase tracking-widest border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black text-xs font-black py-5 px-8 rounded-2xl uppercase tracking-widest transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
                                {{ $isEditMode ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar { -ms-overflow-style: auto; scrollbar-width: thin; scrollbar-color: rgba(34,211,238,0.3) transparent; }
        .custom-scrollbar::-webkit-scrollbar { display: block; height: 5px; width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34,211,238,0.3); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(34,211,238,0.6); }
    </style>
</div>
