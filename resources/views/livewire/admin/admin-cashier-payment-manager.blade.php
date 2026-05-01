<div class="space-y-6 animate-in fade-in duration-500">
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="h-[calc(100vh-200px)] flex gap-6">
        <!-- LEFT SIDEBAR: STUDENT LIST -->
        <div class="w-80 flex flex-col bg-white border border-blue-500/10 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-blue-500/10 bg-blue-50/30">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <h3 class="font-black text-black uppercase text-sm tracking-tight">Student List</h3>
                </div>
                <div class="relative group">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-black focus:border-blue-500/40 outline-none transition-all placeholder-slate-300 font-bold uppercase tracking-tight shadow-sm"
                        placeholder="Search student...">
                </div>
            </div>

            <!-- Student List -->
            <div class="flex-grow overflow-y-auto custom-scrollbar divide-y divide-slate-100 bg-white">
                @forelse($enrolledStudents as $enrollment)
                    @php
                        $isSelected = $selectedStudentId === $enrollment->user_id;
                        $student = $enrollment->user;
                    @endphp
                    <button wire:click="selectStudent({{ $enrollment->user_id }}, {{ $enrollment->id }})"
                        class="w-full text-left px-6 py-4 hover:bg-blue-50/50 transition-colors {{ $isSelected ? 'bg-blue-50 border-l-4 border-blue-600' : 'border-l-4 border-transparent' }}">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="text-xs font-black text-black uppercase tracking-wider group-hover:text-blue-600 transition-colors">#{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }} {{ $student->last_name }}, {{ $student->first_name }}</div>
                            @if($enrollment->voucher_type)
                                <svg class="w-4 h-4 flex-shrink-0 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                            @endif
                        </div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $enrollment->course_code ?? 'N/A' }} • {{ $enrollment->year_level ?? 'N/A' }}</div>
                        <div class="text-[9px] text-slate-300 font-bold uppercase tracking-widest mt-1">{{ $enrollment->semester ?? 'N/A' }} semester</div>
                    </button>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-xs font-black text-white/10 uppercase tracking-[0.4em]">No students found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT SIDE: PAYMENT DETAILS -->
        <div class="flex-grow flex flex-col bg-white border border-blue-500/10 rounded-2xl shadow-xl overflow-hidden">
            @if($selectedStudentId)
                <!-- Header with Student Info & Action Buttons -->
                <div class="px-8 py-8 border-b border-blue-500/10 bg-blue-50/30">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-black text-black text-xl uppercase tracking-tight">#{{ str_pad($selectedStudent->id, 3, '0', STR_PAD_LEFT) }} {{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }}</h3>
                                @if($selectedVoucherType)
                                    @php
                                        $voucherClass = $selectedVoucherType === 'free_tuition' ? 'bg-blue-600 text-white' : 'bg-amber-500 text-black';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $voucherClass }} shadow-md">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5"/></svg>
                                        {{ $selectedVoucherType === 'free_tuition' ? 'Free Tuition' : 'Discounted' }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $enrollment->year_level ?? 'N/A' }} | {{ $enrollment->course_code ?? 'N/A' }} | {{ $enrollment->semester ?? 'N/A' }} semester • {{ $enrollment->academic_year ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="px-8 py-5 border-b border-blue-500/10 bg-white flex gap-12">
                    <button wire:click="$set('activeTab', 'assessment')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'assessment' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-500' }}">
                        ✓ Payment Assessment
                    </button>
                    <button wire:click="$set('activeTab', 'balance')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'balance' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-500' }}">
                        ⓘ Balance
                    </button>
                    <button wire:click="$set('activeTab', 'history')" class="text-[10px] font-black uppercase tracking-[0.2em] pb-5 border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'history' ? 'text-blue-600 border-blue-600' : 'text-slate-300 border-transparent hover:text-slate-500' }}">
                        History
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="flex-grow overflow-y-auto custom-scrollbar px-8 py-6 space-y-6 bg-white">
                    @if($activeTab === 'assessment')
                        <div class="space-y-4">
                            <!-- Fee Items -->
                            <div class="space-y-3">
                                <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                                    <span class="font-black text-slate-400 text-[10px] uppercase tracking-widest">Tuition</span>
                                    <span class="font-black text-black text-xs">₱ {{ number_format($tuitionFees, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                                    <span class="font-black text-slate-400 text-[10px] uppercase tracking-widest">Miscellaneous Fees</span>
                                    <span class="font-black text-black text-xs">₱ {{ number_format($miscellaneousFees, 2) }}</span>
                                </div>
                                @if($appliedDiscount > 0)
                                <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                                    <span class="font-black text-rose-400 text-[10px] uppercase tracking-widest">Additional Discount</span>
                                    <span class="font-black text-rose-500 text-xs">(₱ {{ number_format($appliedDiscount, 2) }})</span>
                                </div>
                                @endif
                            </div>

                            <!-- Total Assessment -->
                            <div class="flex justify-between items-center py-4 px-6 bg-blue-50/50 rounded-2xl border border-blue-500/10">
                                <span class="font-black text-black text-[10px] uppercase tracking-[0.2em]">TOTAL ASSESSMENT:</span>
                                <span class="font-black text-blue-600 text-lg tracking-tighter">₱ {{ number_format((float)$totalAssessment - (float)$appliedDiscount, 2) }}</span>
                            </div>
                        </div>

                    @elseif($activeTab === 'balance')
                        <div class="space-y-2">
                            <div class="text-center p-8 bg-blue-50/30 rounded-3xl border border-blue-500/10">
                                <p class="text-slate-400 text-[10px] font-black uppercase mb-3 tracking-[0.3em]">Current Balance</p>
                                <p class="text-3xl font-black text-blue-600 tracking-tighter">₱ {{ number_format($currentBalance, 2) }}</p>
                            </div>
                        </div>

                    @elseif($activeTab === 'history')
                        <div class="space-y-3">
                            @forelse($paymentHistory as $transaction)
                                <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="font-black text-black text-[10px] uppercase tracking-widest">Transaction #{{ $transaction->id }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $transaction->status === 'Paid' ? 'text-emerald-500' : ($transaction->status === 'Pending' ? 'text-amber-400' : 'text-rose-500') }} bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $transaction->created_at->format('M d, Y • h:i A') }}</div>
                                    <div class="space-y-2 pt-3 border-t border-slate-50">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400 text-[10px] uppercase tracking-widest">Amount:</span>
                                            <span class="text-black font-black text-sm tracking-tight">₱ {{ number_format($transaction->amount, 2) }}</span>
                                        </div>
                                        @if($transaction->reference_no)
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400 text-[10px] uppercase tracking-widest">Reference:</span>
                                            <span class="text-blue-600 font-mono text-[10px] font-black bg-blue-50 px-2 py-1 rounded uppercase">{{ $transaction->reference_no }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.4em]">No payment history found</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>

            @else
                <div class="flex-grow flex items-center justify-center bg-white">
                    <div class="text-center opacity-30">
                        <svg class="w-16 h-16 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19v-2a6 6 0 0112 0v2a2 2 0 01-2 2H7a2 2 0 01-2-2z"></path></svg>
                        <p class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Select a student to view payment details</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 animate-in fade-in duration-300">
            <button wire:click="closeModal" class="absolute inset-0 bg-blue-900/40 backdrop-blur-md transition-all cursor-default"></button>
            <div class="bg-white border border-blue-500/20 w-full max-w-xl rounded-[40px] overflow-hidden shadow-[0_32px_120px_rgba(30,58,138,0.2)] relative z-10 transform animate-in zoom-in-95 duration-300">
                <div class="p-12">
                    <div class="mb-10 text-center">
                        <h3 class="text-2xl font-black text-black uppercase tracking-tight leading-none">{{ $isEditMode ? 'Update Payment' : 'New Payment' }}</h3>
                        <p class="text-blue-600 text-[10px] font-black uppercase tracking-[0.4em] mt-3">Payment Protocol Review</p>
                    </div>

                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="space-y-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Select Student</label>
                            <div class="relative group">
                                <select wire:model="user_id" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-wider focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm" required>
                                    <option value="">CHOOSE APPLICANT</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount To Pay</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-600 font-black text-sm">₱</span>
                                    <input type="number" wire:model="amount" step="0.01"
                                        class="w-full bg-slate-50 text-blue-600 border border-slate-200 py-4.5 pl-12 pr-6 rounded-2xl outline-none text-base font-black tracking-tighter focus:border-blue-500/40 transition-all shadow-sm" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Channel</label>
                                <div class="relative group">
                                    <select wire:model="payment_type" class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-black tracking-widest focus:border-blue-500/40 appearance-none transition-all cursor-pointer shadow-sm uppercase" required>
                                        @foreach(['Cash', 'Gcash', 'PayMaya', 'Bank Transfer'] as $method)
                                            <option value="{{ $method }}">{{ $method }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference Number (OR/DOC)</label>
                            <input type="text" wire:model="reference_no"
                                class="w-full bg-slate-50 text-black border border-slate-200 py-4.5 px-6 rounded-2xl outline-none text-sm font-bold tracking-widest focus:border-blue-500/40 transition-all uppercase shadow-sm"
                                placeholder="INPUT OR NUMBER...">
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 pt-8">
                            <button type="button" wire:click="closeModal"
                                class="flex-1 px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-2 border-slate-100 rounded-2xl hover:bg-slate-50 hover:text-black transition-all">
                                Cancel Protocol
                            </button>
                            <button type="submit"
                                class="flex-1 bg-white border-2 border-blue-500/30 text-black text-[10px] font-black py-5 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-500/10 hover:bg-blue-50 hover:border-blue-500/50 active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                {{ $isEditMode ? 'Update Payment' : 'Confirm Payment' }}
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
