<div>
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden relative"
         style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">


        <div class="px-6 py-5 border-b border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
             style="background: rgba(255,255,255,0.02);">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-emerald-500 inline-block"></span>
                    Transaction Records
                </h2>
                <p class="text-xs text-white/40 mt-1 uppercase tracking-widest font-bold">Manage and verify student payments</p>
            </div>
        </div>

        <div class="bg-white/5 px-6 py-5 border-b border-white/5 backdrop-blur-sm">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-emerald-400 group-focus-within:text-emerald-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="pl-10 w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all placeholder-white/20 shadow-inner" 
                        placeholder="Search student, receipt or email...">
                </div>
                <div class="w-full sm:w-52">
                    <select wire:model.live="filter_course" 
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none cursor-pointer shadow-inner font-bold">
                        <option value="ALL" class="bg-[#0a1628]">All Programs</option>
                        @foreach(['BSIS','DIT','ACT','DHRT','BTVTED'] as $p)
                            @foreach([1,2,3,4] as $y)
                                <option value="{{$p}}-{{$y}}" class="bg-[#0a1628]">{{$p}} {{$y}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="status" 
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none cursor-pointer shadow-inner font-bold">
                        <option value="All statuses" class="bg-[#0a1628]">All Status</option>
                        <option value="Paid" class="bg-[#0a1628]">PAID</option>
                        <option value="Pending" class="bg-[#0a1628]">PENDING</option>
                        <option value="Rejected" class="bg-[#0a1628]">REJECTED</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead class="text-[10px] text-emerald-300 uppercase tracking-widest border-b border-white/5"
                       style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th class="py-5 px-6 font-black">Receipt #</th>
                        <th class="py-5 px-6 font-black">Student Info</th>
                        <th class="py-5 px-6 font-black">Program/Year</th>
                        <th class="py-5 px-6 font-black">Date & Method</th>
                        <th class="py-5 px-6 font-black text-right">Amount</th>
                        <th class="py-5 px-6 font-black text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-white/5 transition group">
                        <td class="py-5 px-6 whitespace-nowrap">
                            <span class="bg-emerald-500/10 text-emerald-400 font-mono text-[10px] font-black px-2 py-1 rounded border border-emerald-500/20 shadow-sm uppercase">
                                #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="font-bold text-white group-hover:text-emerald-200 transition-colors uppercase tracking-tight">{{ optional($payment->user)->name ?? 'Unknown' }}</div>
                            <div class="text-[10px] text-white/30 lowercase mt-0.5 italic">{{ optional($payment->user)->email }}</div>
                        </td>
                        <td class="py-5 px-6">
                            @if(optional($payment->application)->course_code)
                                <span class="font-black text-white text-xs">{{ $payment->application->course_code }}</span>
                                <span class="text-[10px] text-white/40 block mt-0.5 font-bold uppercase">{{ $payment->application->year_level }}</span>
                            @else
                                <span class="text-white/20 italic text-[10px]">NO RECORD</span>
                            @endif
                        </td>
                        <td class="py-5 px-6">
                            <div class="text-white/70 font-bold text-xs">{{ $payment->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-white/30 uppercase font-black tracking-widest mt-0.5">{{ $payment->payment_method ?? 'CASH' }}</div>
                        </td>
                        <td class="py-5 px-6 font-black text-white text-right text-base tracking-tighter">
                            ₱{{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="py-5 px-6 text-center">
                            @if($payment->status === 'Paid') 
                                <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-black px-2.5 py-1 rounded-full border border-emerald-500/20 uppercase tracking-widest shadow-sm">PAID</span>
                            @elseif($payment->status === 'Rejected') 
                                <span class="bg-rose-500/10 text-rose-400 text-[10px] font-black px-2.5 py-1 rounded-full border border-rose-500/20 uppercase tracking-widest shadow-sm">REJECTED</span>
                            @else 
                                <span class="bg-amber-500/10 text-amber-400 text-[10px] font-black px-2.5 py-1 rounded-full border border-amber-500/20 uppercase tracking-widest shadow-sm">PENDING</span> 
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-20 text-center"><div class="flex flex-col items-center gap-2 opacity-20"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg><p class="italic text-sm font-bold uppercase tracking-widest">No payment records found</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-white/5 font-bold" style="background: rgba(255,255,255,0.02);"> 
            {{ $payments->links('livewire.glass-pagination') }} 
        </div>
    </div>

</div>
