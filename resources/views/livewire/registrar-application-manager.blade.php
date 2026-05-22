<div x-data="{ modalOpen: false, selectedId: null }"
     x-init="
        $watch('modalOpen', value => {
            if (value === false) {
                selectedId = null;
            }
        })
     "
     @keydown.escape.window="modalOpen = false; selectedId = null"
     @modal-reset.window="modalOpen = false; selectedId = null"
     wire:poll.15s
     class="space-y-6 animate-in fade-in duration-500">

    <style>
        /* Global scrollbar hide */
        * { -ms-overflow-style: none; scrollbar-width: none; }
        *::-webkit-scrollbar { display: none; }

        /* Custom scrollbar exception */
        .custom-scrollbar { -ms-overflow-style: auto; scrollbar-width: thin; scrollbar-color: rgba(34,211,238,0.3) transparent; }
        .custom-scrollbar::-webkit-scrollbar { display: block; height: 5px; width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34,211,238,0.3); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(34,211,238,0.6); }
    </style>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
            <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[32px] border border-blue-500/10 shadow-xl overflow-hidden">
        <div class="p-8 md:p-10 border-b border-blue-500/10 bg-blue-50/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex-shrink-0">
                <h2 class="text-2xl font-black text-black tracking-tight uppercase">
                    @if($level === 'college') Applications
                    @elseif($level === 'shs') Applications
                    @else Applications @endif
                </h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                    @if($level === 'college') College Application Review Pipeline
                    @elseif($level === 'shs') Senior High School Application Review Pipeline
                    @else Student Application Review Pipeline @endif
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative group w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Search Applicant or Course..."
                        class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-bold text-black placeholder:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500/40 transition-all shadow-sm tracking-wider">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="status" class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-black focus:outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none cursor-pointer transition-all shadow-sm">
                        <option value="All statuses">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="course_filter" class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-black focus:outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none cursor-pointer transition-all shadow-sm">
                        <option value="All Programs">{{ $level === 'shs' ? 'ALL STRANDS' : 'ALL PROGRAMS' }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->course_code }}">{{ $course->course_code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-max w-full text-left border-collapse font-bold bg-white">
                <thead class="text-[10px] text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 bg-slate-50/50">
                    <tr>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('enrollments.id')">
                            <div class="flex items-center gap-2">
                                ID
                                <span class="transition-opacity {{ $sortField === 'enrollments.id' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'enrollments.id' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('users.last_name')">
                            <div class="flex items-center gap-2">
                                Full Name
                                <span class="transition-opacity {{ $sortField === 'users.last_name' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'users.last_name' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left text-slate-400">Account Details</th>
                        <th class="py-6 px-8 text-left text-slate-400">Classification</th>
                        <th class="py-6 px-8 text-left text-slate-400 cursor-pointer group/th hover:text-blue-600 transition-colors">
                            <div class="flex items-center gap-2">
                                Level
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('enrollments.course_code')">
                            <div class="flex items-center gap-2">
                                Program
                                <span class="transition-opacity {{ $sortField === 'enrollments.course_code' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'enrollments.course_code' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('enrollments.created_at')">
                            <div class="flex items-center gap-2">
                                Date
                                <span class="transition-opacity {{ $sortField === 'enrollments.created_at' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'enrollments.created_at' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('status')">
                            <div class="flex items-center gap-2 justify-center pl-5">
                                Status
                                <span class="transition-opacity {{ $sortField === 'status' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'status' && $sortDirection === 'asc')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    @endif
                                </span>
                            </div>
                        </th>
                        <th class="py-6 px-8 text-right text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 bg-white">
                    @forelse($applications as $application)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="py-6 px-8 text-slate-400 font-mono tracking-tighter">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span class="text-black group-hover:text-blue-600 transition-colors uppercase tracking-wider block font-bold">{{ $application->user->last_name }}, {{ $application->user->first_name }} {{ $application->user->middle_name }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase tracking-widest mt-0.5">Applicant Profile</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-slate-500 lowercase tracking-tight">{{ $application->user->email }}</td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                @php
                                    $classification = $application->classification ?? 'New';
                                    $classColor = match(strtolower($classification)) {
                                        'new' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        'returning' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                        'transferee' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'shifter' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                        default => 'bg-white/5 text-white/40 border-white/10'
                                    };
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter {{ $classColor }} rounded-lg">
                                    {{ $classification }}
                                </span>
                            </td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                @php
                                    $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
                                    $isSHS = in_array($application->course_code, $shsStrands);
                                @endphp

                                @if($isSHS)
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-lg">
                                        Senior High
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg">
                                        College
                                    </span>
                                @endif
                            </td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                <span class="text-blue-600 font-black uppercase text-[10px] tracking-widest">{{ $application->course_code }}</span>
                                <span class="text-slate-400 text-[9px] ml-1 font-bold">({{ $application->year_display }})</span>
                            </td>
                            <td class="py-6 px-8 text-slate-500 font-medium tracking-tight">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="py-6 px-8">
                                @php
                                    $badgeColor = match(ucfirst($application->status)) {
                                        'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'Paid' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                        'Approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        default => 'bg-white/5 text-white/40 border-white/10',
                                    };
                                    $displayText = match($application->status) {
                                        'Enrolled' => 'Enrolled',
                                        'Paid' => 'Paid',
                                        'Approved' => 'Approved',
                                        'Pending' => 'Pending',
                                        default => $application->status
                                    };
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeColor }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex justify-end items-center gap-3">
                                    @if($application->credentials_verified && !($application->classification === 'Returning' || $application->student_type === 'Returning'))
                                        <button type="button" wire:click="revokeClearance({{ $application->id }})"
                                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border-2 border-slate-200 text-black hover:bg-rose-50 hover:border-rose-500/30 transition-all text-[9px] font-black uppercase tracking-widest group/btn shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            REJECT CLEARANCE
                                        </button>
                                    @endif
                                    @php
                                        $isCurrentTerm = $activeYear && $activeSemester &&
                                                         $application->academic_year_name === $activeYear->year_name &&
                                                         $application->semester_name === $activeSemester->name;
                                    @endphp
                                    @if(($application->classification === 'Returning' || $application->student_type === 'Returning') && !$application->credentials_verified && (!$isCurrentTerm || $application->status !== 'Enrolled'))
                                        <button type="button" wire:click="grantClearance({{ $application->id }})"
                                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border-2 border-blue-500/20 text-black hover:bg-blue-50 hover:border-blue-500/40 transition-all text-[9px] font-black uppercase tracking-widest group/btn shadow-sm whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            APPROVE CLEARANCE
                                        </button>
                                    @endif
                                    <button type="button" @click="modalOpen = true; selectedId = {{ $application->id }}; openModal(@js($application), @js($application->getDocumentFields()))"
                                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border-2 border-blue-500/30 text-black hover:bg-blue-50 hover:border-blue-500 transition-all text-[10px] font-black uppercase tracking-widest group/btn shadow-lg shadow-blue-500/10 whitespace-nowrap">
                                        <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <svg class="w-16 h-16 mb-6 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-800">No applications found in the review pipeline</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="p-8 border-t border-blue-500/10 bg-blue-50/20">
                {{ $applications->links('pagination') }}
            </div>
        @endif
    </div>

    {{-- Universal Application Analysis Modal --}}
    <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-md" @click="modalOpen = false; selectedId = null"></div>

        <div class="bg-white w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(30,58,138,0.2)] border border-blue-500/20 overflow-hidden flex flex-col max-h-[95vh] relative z-10 transform transition-all duration-300" id="modalContent" wire:ignore>

            <div class="px-8 md:px-12 py-8 border-b border-blue-500/10 flex justify-between items-center gap-6 bg-blue-50/30">
                <div>
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] mb-1 block">Analysis Protocol</span>
                    <h2 class="text-2xl font-black text-black uppercase tracking-tight" id="modalTitle">Application Details</h2>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Voucher Dropdown Button in Modal Header -->
                    <div class="relative group/voucherModal">
                        <button type="button" class="px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-all text-[10px] font-black uppercase tracking-widest shadow-lg border border-blue-400 flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4c1.1046 0 2 0.89543 2 2v3.1709c-0.0001 0.42374 -0.2675 0.80117 -0.667 0.9424C20.5549 10.3884 20 11.1308 20 12s0.5549 1.6116 1.333 1.8867c0.3995 0.1412 0.6669 0.5187 0.667 0.9424V18c0 1.1046 -0.8954 2 -2 2H4c-1.10457 0 -2 -0.8954 -2 -2v-3.1709c0.00008 -0.4237 0.26746 -0.8012 0.66699 -0.9424C3.44507 13.6116 4 12.8692 4 12s-0.55493 -1.6116 -1.33301 -1.8867C2.26746 9.97207 2.00008 9.59464 2 9.1709V6c0 -1.10457 0.89543 -2 2 -2zm-5.5 9c-0.8284 0 -1.5 0.6716 -1.5 1.5s0.6716 1.5 1.5 1.5 1.5 -0.6716 1.5 -1.5 -0.6716 -1.5 -1.5 -1.5m0.707 -4.20703c-0.3905 -0.39053 -1.0235 -0.39053 -1.414 0L8.79297 13.793c-0.39053 0.3905 -0.39053 1.0235 0 1.414 0.39052 0.3906 1.02354 0.3906 1.41403 0l5 -5c0.3906 -0.39049 0.3906 -1.02351 0 -1.41403M9.5 8C8.67157 8 8 8.67157 8 9.5c0 0.8284 0.67157 1.5 1.5 1.5 0.8284 0 1.5 -0.6716 1.5 -1.5 0 -0.82843 -0.6716 -1.5 -1.5 -1.5" stroke-width="1"></path>
                            </svg>
                            Voucher
                        </button>
                        <div class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-lg shadow-2xl opacity-0 invisible group-hover/voucherModal:opacity-100 group-hover/voucherModal:visible transition-all z-50 overflow-hidden">
                            <div id="voucherStatusModal" class="p-3 border-b border-slate-100 hidden">
                                <p class="text-[8px] font-bold text-slate-400 uppercase mb-2">Current Voucher:</p>
                                <div id="voucherBadgeModal" class="flex gap-1 items-center p-2 rounded text-[9px] font-black"></div>
                            </div>
                            <button onclick="applyVoucher('free_tuition')" class="w-full text-left px-4 py-3 text-[9px] font-bold text-emerald-600 hover:bg-emerald-50 transition-colors uppercase tracking-widest">🟢 Free Tuition</button>
                            <button onclick="applyVoucher('discounted')" class="w-full text-left px-4 py-3 text-[9px] font-bold text-blue-600 hover:bg-blue-50 transition-colors border-t border-slate-100 uppercase tracking-widest">🔵 Discounted</button>
                            <button onclick="removeVoucher()" class="w-full text-left px-4 py-3 text-[9px] font-bold text-rose-600 hover:bg-rose-50 transition-colors border-t border-slate-100 uppercase tracking-widest">✕ Remove</button>
                        </div>
                    </div>

                    <button @click="modalOpen = false; selectedId = null" class="p-3 rounded-xl bg-slate-100 text-slate-400 hover:text-black hover:bg-slate-200 transition-all border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <div class="p-8 md:p-12 overflow-y-auto custom-scrollbar flex-grow space-y-12">
                <div class="space-y-10">
                    {{-- Row 1: Program & Lifecycle --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Program Details</h3>
                        </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-[32px] p-8">
                            <div class="space-y-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Applied Program</span>
                                    <span class="text-3xl font-black text-black uppercase tracking-tighter" id="modalCourse"></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6 border-t border-blue-100">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Academic Level</span>
                                        <span class="text-sm font-extrabold text-black uppercase" id="modalYear"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Status</span>
                                        <span class="text-sm font-black text-blue-600 uppercase tracking-widest" id="modalStatus"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 1.5: Student Classification Override --}}
                    <div class="space-y-6" x-data="{
                        classType: null,
                        classReason: '',
                        isSHS: false,
                        showReasonDropdown: false,
                        collegeReasons: @js(array_keys(\App\Models\Enrollment::CLASSIFICATION_REASONS)),
                        shsReasons: @js(array_keys(\App\Models\Enrollment::SHS_CLASSIFICATION_REASONS)),
                        get reasons() { return this.isSHS ? this.shsReasons : this.collegeReasons; },
                        initClassification(isRegular, reason, isSHS) {
                            this.isSHS = isSHS;
                            if (isRegular === true || isRegular === 1) {
                                this.classType = 'regular';
                                this.classReason = '';
                                this.showReasonDropdown = false;
                            } else if (isRegular === false || isRegular === 0) {
                                this.classType = 'irregular';
                                this.classReason = reason || '';
                                this.showReasonDropdown = true;
                            } else {
                                this.classType = null;
                                this.classReason = '';
                                this.showReasonDropdown = false;
                            }
                        },
                        selectType(type) {
                            this.classType = type;
                            if (type === 'regular') {
                                this.classReason = '';
                                this.showReasonDropdown = false;
                            } else {
                                this.showReasonDropdown = true;
                            }
                        }
                    }" id="classificationSection">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-violet-600"></span>
                            <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Student Classification</h3>
                        </div>
                        <div class="bg-violet-50 border border-violet-200 rounded-[32px] p-8">
                            <div class="space-y-6">
                                {{-- Current Status Display --}}
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-violet-600 uppercase tracking-widest">Current Status</span>
                                    <div class="flex items-center gap-3">
                                        <span x-show="classType === 'regular'" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-emerald-500/10 text-emerald-500 border-emerald-500/20">Regular</span>
                                        <span x-show="classType === 'irregular'" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-amber-500/10 text-amber-500 border-amber-500/20">Irregular</span>
                                        <span x-show="!classType" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-slate-100 text-slate-400 border-slate-200">Not Set</span>
                                        <span x-show="classType === 'irregular' && classReason" class="text-[9px] font-bold text-slate-500 uppercase tracking-wider" x-text="'— ' + classReason"></span>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="pt-4 border-t border-violet-100">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-3">Set Classification</span>
                                    <div class="flex flex-wrap items-start gap-4">
                                        <button type="button"
                                            @click="selectType('regular')"
                                            :class="classType === 'regular' ? 'bg-emerald-600 text-white border-emerald-600 shadow-lg shadow-emerald-600/20' : 'bg-white text-emerald-600 border-emerald-300 hover:bg-emerald-50'"
                                            class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest border-2 transition-all active:scale-95">
                                            ✓ Regular
                                        </button>
                                        <button type="button"
                                            @click="selectType('irregular')"
                                            :class="classType === 'irregular' ? 'bg-amber-600 text-white border-amber-600 shadow-lg shadow-amber-600/20' : 'bg-white text-amber-600 border-amber-300 hover:bg-amber-50'"
                                            class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest border-2 transition-all active:scale-95">
                                            ✕ Irregular
                                        </button>
                                    </div>
                                </div>

                                {{-- Reason Dropdown (shown only when Irregular is selected) --}}
                                <div x-show="showReasonDropdown" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pt-4 border-t border-violet-100">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Reason for Irregular Classification</label>
                                    <select x-model="classReason"
                                        class="w-full sm:w-80 bg-white border border-slate-200 rounded-xl px-5 py-3 text-[11px] font-bold text-black focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500/40 appearance-none cursor-pointer transition-all shadow-sm">
                                        <option value="">Select a reason...</option>
                                        <template x-for="r in reasons" :key="r">
                                            <option :value="r" x-text="r" :selected="r === classReason"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Save Button --}}
                                <div class="pt-4">
                                    <button type="button"
                                        x-show="classType"
                                        :disabled="classType === 'irregular' && !classReason"
                                        @click="
                                            if (classType === 'irregular' && !classReason) return;
                                            $wire.setClassification(selectedId, classType, classReason);
                                            setTimeout(() => { location.reload(); }, 1000);
                                        "
                                        :class="(classType === 'irregular' && !classReason) ? 'opacity-40 cursor-not-allowed' : 'hover:bg-violet-500 active:scale-95 shadow-xl shadow-violet-600/10'"
                                        class="bg-violet-600 text-white text-[10px] font-black py-3.5 px-8 rounded-xl uppercase tracking-[0.2em] transition-all">
                                        Save Classification
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Applicant Profile --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Applicant Profile</h3>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-[32px] p-8 space-y-6 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-1 md:col-span-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Name</span>
                                    <span class="text-lg font-black text-black uppercase tracking-wide" id="modalNameValue"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Extension</span>
                                    <span class="text-sm font-extrabold text-black uppercase" id="modalExtension"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">LRN</span>
                                    <span class="text-sm font-extrabold text-black uppercase" id="modalLrn"></span>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-slate-100">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4">Identity Details</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reference ID</span>
                                            <span class="text-sm font-extrabold text-blue-600 uppercase" id="modalAppId"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Applied On</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalSubmitted"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gender</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalGender"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Birth Date</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalDob"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Age</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalAge"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Birthplace</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalBirthplace"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-slate-100">
                                <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-4">Contact & Background</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-1 sm:col-span-2">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Address</span>
                                            <span class="text-sm font-extrabold text-black lowercase" id="modalEmail"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact Number</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalContactNumber"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Facebook Account</span>
                                            <span class="text-sm font-extrabold text-black" id="modalFacebook"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Religion / Church</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalReligionChurch"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Junior High School</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalJuniorHigh"></span>
                                    </div>
                                    <div class="flex flex-col gap-1 sm:col-span-2">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Address</span>
                                            <span class="text-sm font-extrabold text-black uppercase" id="modalAddress"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-slate-100">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Health Concerns</span>
                                    <p class="text-sm font-semibold text-black leading-relaxed" id="modalHealthConcerns"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Guardian Information --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Guardian Information</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-slate-200 rounded-[32px] p-8 shadow-sm">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Father's Name</span>
                                <span class="text-sm font-extrabold text-black uppercase" id="modalFather"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mother's Name</span>
                                <span class="text-sm font-extrabold text-black uppercase" id="modalMother"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Guardian Name</span>
                                <span class="text-sm font-extrabold text-black uppercase" id="modalGuardian"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Emergency Contact</span>
                                <span class="text-sm font-extrabold text-black uppercase" id="modalContact"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Required Documents --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Required Documents</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="modalDocuments">
                            {{-- Injected via JS --}}
                        </div>
                    </div>
                </div>

                {{-- Promissory Note Asset --}}
                <div class="space-y-6 pt-6 hidden" id="modalPromissorySection">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-[10px] font-black text-black uppercase tracking-[0.3em]">Promissory Note & Reason</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-amber-50 border border-amber-200 rounded-[32px] p-8">
                        <div id="modalPromissoryFile" class="lg:col-span-1">
                            {{-- Injected via JS --}}
                        </div>
                        <div class="lg:col-span-2 space-y-2">
                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Student's Explanation</span>
                            <div class="p-6 rounded-2xl bg-white border border-amber-100 min-h-[80px]">
                                <p class="text-[11px] text-slate-700 leading-relaxed" id="modalPromissoryReason"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 md:px-12 py-8 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-wrap items-center gap-4 w-full" id="actionButtons">
                    <button type="button"
                        id="togglePhysicalBtn"
                        @click="$wire.togglePhysicalDocuments(selectedId); setTimeout(() => { location.reload(); }, 1000);"
                        class="text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 shrink-0">
                        Done Hard Docs
                    </button>
                    <button type="button" id="approveBtn"
                        @click="@this.approve(selectedId); setTimeout(() => { location.reload(); }, 800);"
                        class="bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-600/10 active:scale-95 shrink-0">
                        Approve Enrollment
                    </button>
                    <button type="button" id="rejectBtn"
                        @click="if(confirm('Are you sure you want to reject this application?')) { @this.reject(selectedId); setTimeout(() => { location.reload(); }, 800); }"
                        class="bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-rose-600/10 active:scale-95 shrink-0">
                        Reject Application
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentApplicationId = null;

    function openModal(app, docMapping) {
        currentApplicationId = app.id;
        document.getElementById('modalTitle').innerText = 'Application Details #' + String(app.id).padStart(5, '0');
        const middle = app.middle_name ? ' ' + app.middle_name : '';
        const extension = app.extension ? ' ' + app.extension : '';
        const fullName = (app.last_name || '') + ', ' + (app.first_name || '') + middle + extension;
        const valueOrNA = (value) => value && String(value).trim() !== '' ? value : 'N/A';

        // Student Profile section
        document.getElementById('modalNameValue').innerText = fullName;
        document.getElementById('modalExtension').innerText = valueOrNA(app.extension);
        document.getElementById('modalEmail').innerText = valueOrNA(app.email);
        document.getElementById('modalAppId').innerText = 'REF-' + String(app.id).padStart(5, '0');
        document.getElementById('modalSubmitted').innerText = new Date(app.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('modalDob').innerText = valueOrNA(app.birth_date);
        document.getElementById('modalAge').innerText = valueOrNA(app.age);
        document.getElementById('modalLrn').innerText = valueOrNA(app.lrn);
        document.getElementById('modalGender').innerText = valueOrNA(app.gender);
        document.getElementById('modalContactNumber').innerText = valueOrNA(app.contact);
        document.getElementById('modalFacebook').innerText = valueOrNA(app.facebook_account);
        document.getElementById('modalReligionChurch').innerText = valueOrNA(app.religion_church);
        document.getElementById('modalBirthplace').innerText = valueOrNA(app.birthplace);
        document.getElementById('modalAddress').innerText = valueOrNA(app.address_full);
        document.getElementById('modalJuniorHigh').innerText = valueOrNA(app.junior_high_school);
        document.getElementById('modalHealthConcerns').innerText = valueOrNA(app.health_concerns);

        // Program details
        document.getElementById('modalCourse').innerText = app.course_code || 'N/A';
        document.getElementById('modalYear').innerText = app.year_level || 'N/A';
        document.getElementById('modalStatus').innerText = app.status || 'N/A';

        // Initialize Student Classification section
        const shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        const appIsSHS = shsStrands.includes(app.course_code);
        const classSection = document.getElementById('classificationSection');
        if (classSection && classSection.__x) {
            classSection.__x.$data.initClassification(app.is_regular, app.classification_reason, appIsSHS);
        } else if (classSection) {
            // Fallback: wait for Alpine to initialize
            setTimeout(() => {
                const alpineData = Alpine.$data(classSection);
                if (alpineData) {
                    alpineData.initClassification(app.is_regular, app.classification_reason, appIsSHS);
                }
            }, 100);
        }

        // Toggle Physical Documents Button Styling
        const toggleBtn = document.getElementById('togglePhysicalBtn');
        if (app.physical_documents_received) {
            toggleBtn.textContent = 'Cancel Hard Docs';
            toggleBtn.className = 'px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 shrink-0';
        } else {
            toggleBtn.textContent = 'Done Hard Docs';
            toggleBtn.className = 'px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-xl bg-cyan-500 text-black shrink-0';
        }

        // Guardian info
        document.getElementById('modalFather').innerText = app.father_name || 'N/A';
        document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
        document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
        document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

        const docsContainer = document.getElementById('modalDocuments');
        docsContainer.innerHTML = '';

        const documents = docMapping;

        const storageBase = @json(url('/documents')) + '/';

        Object.keys(documents).forEach(key => {
            const label = documents[key];
            const hasFile = app[key] ? true : false;
            let headerHtml = '';
            let boxHtml = '';

            if (hasFile) {
                const fileUrl = storageBase + app[key];
                const isImage = app[key].match(/\.(jpeg|jpg|png|gif|webp)$/i);

                headerHtml = `
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center justify-center w-5 h-5 bg-emerald-500/20 border-2 border-emerald-500 rounded-full shrink-0">
                            <span class="text-emerald-500 font-black text-xs">✓<\/span>
                        <\/div>
                        <span class="text-[9px] font-black uppercase text-slate-700 tracking-widest">${label}<\/span>
                    <\/div>
                `;

                if (isImage) {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                            <img src="${fileUrl}" class="w-full h-32 object-cover transition-transform duration-500 group-hover/asset:scale-110">
                            <div class="absolute inset-0 bg-blue-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"><\/path><\/svg>
                            <\/div>
                        <\/a>
                    `;
                } else {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 h-32 flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 text-blue-500 opacity-40 group-hover/asset:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"><\/path><\/svg>
                            <span class="text-[8px] font-black text-blue-600 mt-2 tracking-[0.3em]">VIEW FILE<\/span>
                        <\/a>
                    `;
                }
            } else {
                headerHtml = `
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center justify-center w-5 h-5 bg-rose-500/20 border-2 border-rose-500 rounded-full shrink-0">
                            <span class="text-rose-500 font-black text-xs">!<\/span>
                        <\/div>
                        <span class="text-[9px] font-black uppercase text-rose-500 tracking-widest">${label}<\/span>
                    <\/div>
                `;

                boxHtml = `
                    <div class="w-full h-32 rounded-2xl border-2 border-dashed border-rose-500/10 bg-rose-500/5 flex flex-col items-center justify-center opacity-40">
                        <span class="text-[8px] font-black text-rose-500 tracking-[0.3em]">MISSING<\/span>
                    <\/div>
                `;
            }

            docsContainer.innerHTML += `<div>${headerHtml}${boxHtml}<\/div>`;
        });

        // Promissory Note Handling
        const promissorySection = document.getElementById('modalPromissorySection');
        const promissoryFile = document.getElementById('modalPromissoryFile');
        const promissoryReason = document.getElementById('modalPromissoryReason');

        if (app.promissory_note_path || app.promissory_reason) {
            promissorySection.classList.remove('hidden');
            promissoryReason.innerText = app.promissory_reason || 'No explanation provided.';

            if (app.promissory_note_path) {
                const noteUrl = storageBase + app.promissory_note_path;
                const isPdf = app.promissory_note_path.toLowerCase().endsWith('.pdf');

                promissoryFile.innerHTML = `
                    <div class="space-y-3">
                        <span class="text-[9px] font-black text-amber-600/60 uppercase tracking-widest">Note Attachment<\/span>
                        <a href="${noteUrl}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-amber-200 bg-white hover:bg-amber-100 transition-all shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"><\/path><\/svg>
                            <\/div>
                            <div>
                                <p class="text-[10px] font-black text-black uppercase tracking-wider">Download Note<\/p>
                                <p class="text-[8px] text-amber-600 uppercase font-bold mt-0.5">${isPdf ? 'PDF Format' : 'Word Doc'}<\/p>
                            <\/div>
                        <\/a>
                    <\/div>
                `;
            } else {
                promissoryFile.innerHTML = `
                    <div class="space-y-3">
                        <span class="text-[9px] font-black text-amber-600/60 uppercase tracking-widest">Note Attachment<\/span>
                        <div class="p-4 rounded-2xl border border-dashed border-amber-200 bg-white/50 flex items-center justify-center opacity-60">
                            <span class="text-[8px] font-black text-amber-600 uppercase tracking-widest">No File<\/span>
                        <\/div>
                    <\/div>
                `;
            }
        } else {
            promissorySection.classList.add('hidden');
        }

        // Action Buttons visibility
        const actionButtons = document.getElementById('actionButtons');
        const status = (app.status || '').toLowerCase();

        // Show buttons for Pending, Approved, and Paid (Enrolled) statuses
        if (['pending', 'approved', 'enrolled', 'paid'].includes(status)) {
            actionButtons.classList.remove('hidden');
            actionButtons.classList.add('flex');

            // Set up Toggle Hard Docs Button
            const docsBtn = document.getElementById('togglePhysicalBtn');
            if (app.physical_documents_received == 1) {
                docsBtn.textContent = 'Cancel Hard Docs';
                docsBtn.className = 'text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 shrink-0 bg-rose-500/10 text-rose-400 border border-rose-500/20';
            } else {
                docsBtn.textContent = 'Done Hard Docs';
                docsBtn.className = 'text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 shrink-0 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
            }
            // Logic moved to @click on the button element


        } else {
            actionButtons.classList.add('hidden');
            actionButtons.classList.remove('flex');
        }

        // Display voucher status
        if (app.voucher_type) {
            updateVoucherDisplay(app.voucher_type);
        } else {
            updateVoucherDisplay(null);
        }
    }

    function updateVoucherDisplay(voucherType) {
        const statusDiv = document.getElementById('voucherStatusModal');
        const badge = document.getElementById('voucherBadgeModal');

        if (voucherType) {
            statusDiv.classList.remove('hidden');
            if (voucherType === 'free_tuition') {
                badge.className = 'flex items-center gap-2 p-2 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400';
                badge.textContent = '🟢 Free Tuition';
            } else {
                badge.className = 'flex items-center gap-2 p-2 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-400';
                badge.textContent = '🟡 Discounted';
            }
        } else {
            statusDiv.classList.add('hidden');
        }
    }

    function applyVoucher(voucherType) {
        if (!currentApplicationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${currentApplicationId}/apply-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ voucher_type: voucherType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVoucherDisplay(voucherType);
                alert('✓ Voucher applied successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to apply voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error applying voucher');
        });
    }

    function removeVoucher() {
        if (!currentApplicationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${currentApplicationId}/remove-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVoucherDisplay(null);
                alert('✓ Voucher removed successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to remove voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing voucher');
        });
    }

    // Listen for Livewire modal-reset event
    document.addEventListener('livewire:navigated', () => {
        // Re-initialize if needed after Livewire updates
    });

    // Dispatch custom event when modal-reset Livewire event fires
    Livewire.on('modal-reset', () => {
        const event = new CustomEvent('modal-reset');
        window.dispatchEvent(event);
    });
    </script>
</div>
