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
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
            <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-[32px] border-white/5 shadow-2xl shadow-black/40">
        <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex-shrink-0">
                <h2 class="text-2xl font-black text-white tracking-tight uppercase italic text-shadow-lg shadow-black/40">
                    @if($level === 'college') Applications
                    @elseif($level === 'shs') Applications
                    @else Applications @endif
                </h2>
                <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1 italic">
                    @if($level === 'college') College Enrollment Lifecycle & Review Pipeline
                    @elseif($level === 'shs') Senior High School Enrollment Lifecycle & Review Pipeline
                    @else Student Enrollment Lifecycle & Review Pipeline @endif
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <div class="relative group w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Search Applicant or Course..."
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-3.5 text-[11px] font-bold text-white placeholder:text-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/40 transition-all shadow-inner tracking-wider">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-20 group-focus-within:opacity-100 transition-opacity">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="status" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none cursor-pointer transition-all shadow-inner">
                        <option value="All statuses" class="bg-[#0a0f1d]">All Status</option>
                        <option value="Pending" class="bg-[#0a0f1d]">Pending</option>
                        <option value="Approved" class="bg-[#0a0f1d]">Approved</option>
                        <option value="Rejected" class="bg-[#0a0f1d]">Rejected</option>
                        <option value="Paid" class="bg-[#0a0f1d]">Paid</option>
                    </select>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="year_level" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-white focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none cursor-pointer transition-all shadow-inner">
                        <option value="All Years" class="bg-[#0a0f1d]">All Years</option>
                        <option value="1st Year" class="bg-[#0a0f1d]">BSIS 1</option>
                        <option value="2nd Year" class="bg-[#0a0f1d]">BSIS 2</option>
                        <option value="3rd Year" class="bg-[#0a0f1d]">BSIS 3</option>
                        <option value="4th Year" class="bg-[#0a0f1d]">BSIS 4</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-max w-full text-left border-collapse font-bold">
                <thead class="text-[10px] text-white/20 uppercase tracking-[0.2em] border-b border-white/5 bg-white/[0.01]">
                    <tr>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('enrollments.id')">
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
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('users.last_name')">
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
                        <th class="py-6 px-8 text-left">Account Details</th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-white transition-colors">
                            <div class="flex items-center gap-2">
                                Level
                            </div>
                        </th>
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('enrollments.course_code')">
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
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('enrollments.created_at')">
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
                        <th class="py-6 px-8 text-center cursor-pointer group/th hover:text-white transition-colors" wire:click="sortBy('status')">
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
                        <th class="py-6 px-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-white/5">
                    @forelse($applications as $application)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider block font-bold">{{ $application->user->last_name }}, {{ $application->user->first_name }} {{ $application->user->middle_name }}</span>
                                    <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Applicant Profile</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-white/40 lowercase tracking-tight">{{ $application->user->email }}</td>
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
                                <span class="text-cyan-400 font-black uppercase text-[10px] tracking-widest">{{ $application->course_code }}</span>
                                <span class="text-white/20 text-[9px] ml-1 font-bold">({{ $application->year_display }})</span>
                            </td>
                            <td class="py-6 px-8 text-white/30 font-medium italic tracking-tight">{{ $application->created_at->format('M d, Y') }}</td>
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
                                <div class="flex justify-end items-center gap-4">

                                    <button type="button" @click="modalOpen = true; selectedId = {{ $application->id }}; openModal(@js($application), @js($application->getDocumentFields()))"
                                        class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn shadow-lg shadow-black/20 whitespace-nowrap">
                                        View Details
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-16 h-16 mb-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic text-white">No applications found in the review pipeline</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                {{ $applications->links('pagination') }}
            </div>
        @endif
    </div>

    {{-- Universal Application Analysis Modal --}}
    <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#060d1a]/90 backdrop-blur-2xl" @click="modalOpen = false; selectedId = null"></div>

        <div class="bg-[#0d1f3c] w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden flex flex-col max-h-[95vh] relative z-10 transform transition-all duration-300" id="modalContent">

            <div class="px-8 md:px-12 py-8 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div>
                    <span class="text-[9px] font-black text-cyan-400 uppercase tracking-[0.4em] mb-1 block italic text-shadow shadow-cyan-500/20">Analysis Protocol</span>
                    <h2 class="text-2xl font-black text-white uppercase italic tracking-tight" id="modalTitle">Application Details</h2>
                </div>
                <button @click="modalOpen = false; selectedId = null" class="p-4 rounded-2xl bg-white/5 text-white/20 hover:text-white transition-colors border border-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                </button>
            </div>

            <div class="p-8 md:p-12 overflow-y-auto custom-scrollbar flex-grow space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {{-- Student Profile --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Applicant Profile</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6 bg-white/[0.02] border border-white/5 rounded-[32px] p-8">
                            <div class="grid grid-cols-2 gap-8">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalNameValue"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Email Address</span>
                                    <span class="text-xs font-bold text-white lowercase" id="modalEmail"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Reference ID</span>
                                    <span class="text-xs font-bold text-cyan-400 uppercase" id="modalAppId"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Applied On</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalSubmitted"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Birth Date</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalDob"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Age</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalAge"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Gender</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalGender"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Address</span>
                                    <span class="text-xs font-bold text-white uppercase" id="modalAddress"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Program & Lifecycle --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Program Details</h3>
                        </div>
                        <div class="bg-blue-500/5 border border-blue-500/10 rounded-[32px] p-8 h-full flex flex-col justify-center">
                            <div class="space-y-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest italic">Applied Program</span>
                                    <span class="text-2xl font-black text-white uppercase italic tracking-tighter" id="modalCourse"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-8 pt-6 border-t border-white/5">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Academic Level</span>
                                        <span class="text-xs font-bold text-white uppercase" id="modalYear"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Status</span>
                                        <span class="text-xs font-black text-cyan-400 uppercase tracking-widest" id="modalStatus"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guardian Records --}}
                <div class="space-y-6 pt-6">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Guardian Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white/[0.02] border border-white/5 rounded-[32px] p-8">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Father's Name</span>
                            <span class="text-xs font-bold text-white uppercase" id="modalFather"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Mother's Name</span>
                            <span class="text-xs font-bold text-white uppercase" id="modalMother"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Guardian Name</span>
                            <span class="text-xs font-bold text-white uppercase" id="modalGuardian"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Emergency Contact</span>
                            <span class="text-xs font-bold text-white uppercase" id="modalContact"></span>
                        </div>
                    </div>
                </div>

                {{-- Document Assets --}}
                <div class="space-y-6 pt-6">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Required Documents</h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="modalDocuments">
                        {{-- Injected via JS --}}
                    </div>
                </div>

                {{-- Promissory Note Asset --}}
                <div class="space-y-6 pt-6 hidden" id="modalPromissorySection">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Promissory Note & Reason</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-amber-500/5 border border-amber-500/10 rounded-[32px] p-8">
                        <div id="modalPromissoryFile" class="lg:col-span-1">
                            {{-- Injected via JS --}}
                        </div>
                        <div class="lg:col-span-2 space-y-2">
                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Student's Explanation</span>
                            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 min-h-[80px]">
                                <p class="text-[11px] text-white/60 leading-relaxed italic" id="modalPromissoryReason"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 md:px-12 py-8 border-t border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4 w-full md:w-auto overflow-x-auto pb-2 md:pb-0" id="actionButtons">
                    <button type="button"
                        @click="
                            @this.togglePhysicalDocuments(selectedId);
                            modalOpen = false;
                            setTimeout(() => { location.reload(); }, 800);
                        "
                        id="togglePhysicalBtn"
                        class="text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 shrink-0">
                        Done Hard Docs
                    </button>
                    <button type="button" id="approveBtn"
                        @click="@this.approve(selectedId); setTimeout(() => { location.reload(); }, 800);"
                        class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/10 active:scale-95 shrink-0">
                        Approve Enrollment
                    </button>
                    <button type="button" id="rejectBtn"
                        @click="if(confirm('Are you sure you want to reject this application?')) { @this.reject(selectedId); setTimeout(() => { location.reload(); }, 800); }"
                        class="bg-rose-500 hover:bg-rose-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-rose-500/10 active:scale-95 shrink-0">
                        Reject Application
                    </button>
                </div>
                <button @click="modalOpen = false; selectedId = null" class="w-full md:w-auto px-10 py-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em] border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white transition-all ml-auto italic">
                    Close Protocol
                </button>
            </div>
        </div>
    </div>

    <script>
    function openModal(app, docMapping) {
        document.getElementById('modalTitle').innerText = (app.user.first_name || '') + ' ' + (app.user.last_name || '');
        const middle = app.user.middle_name ? ' ' + app.user.middle_name : '';
        const fullName = (app.user.first_name || '') + middle + ' ' + (app.user.last_name || '');

        // Student Profile section
        document.getElementById('modalNameValue').innerText = fullName;
        document.getElementById('modalEmail').innerText = app.user.email || 'N/A';
        document.getElementById('modalAppId').innerText = 'REF-' + String(app.id).padStart(5, '0');
        document.getElementById('modalSubmitted').innerText = new Date(app.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('modalDob').innerText = app.user.birth_date || 'N/A';
        document.getElementById('modalAge').innerText = app.user.age || 'N/A';
        document.getElementById('modalGender').innerText = app.user.gender || 'N/A';
        document.getElementById('modalAddress').innerText = app.user.address_full || 'N/A';

        // Program details
        document.getElementById('modalCourse').innerText = app.course_code || 'N/A';
        document.getElementById('modalYear').innerText = app.year_level || 'N/A';
        document.getElementById('modalStatus').innerText = app.status || 'N/A';

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
        document.getElementById('modalFather').innerText = app.user.father_name || 'N/A';
        document.getElementById('modalMother').innerText = app.user.mother_maiden_name || 'N/A';
        document.getElementById('modalGuardian').innerText = app.user.guardian_name || 'N/A';
        document.getElementById('modalContact').innerText = app.user.guardian_contact || 'N/A';

        const docsContainer = document.getElementById('modalDocuments');
        docsContainer.innerHTML = '';

        const documents = docMapping;

        const storageBase = @json(asset('storage')) + '/';

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
                        <span class="text-[9px] font-black uppercase text-white tracking-widest">${label}<\/span>
                    <\/div>
                `;

                if (isImage) {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
                            <img src="${fileUrl}" class="w-full h-32 object-cover transition-transform duration-500 group-hover/asset:scale-110">
                            <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"><\/path><\/svg>
                            <\/div>
                        <\/a>
                    `;
                } else {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] h-32 flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 text-cyan-400 opacity-40 group-hover/asset:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"><\/path><\/svg>
                            <span class="text-[8px] font-black text-cyan-400 mt-2 tracking-[0.3em]">VIEW FILE<\/span>
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
                        <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Note Attachment<\/span>
                        <a href="${noteUrl}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"><\/path><\/svg>
                            <\/div>
                            <div>
                                <p class="text-[10px] font-black text-white uppercase tracking-wider">Download Note<\/p>
                                <p class="text-[8px] text-amber-500/60 uppercase font-bold mt-0.5">${isPdf ? 'PDF Format' : 'Word Doc'}<\/p>
                            <\/div>
                        <\/a>
                    <\/div>
                `;
            } else {
                promissoryFile.innerHTML = `
                    <div class="space-y-3">
                        <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Note Attachment<\/span>
                        <div class="p-4 rounded-2xl border border-dashed border-white/5 bg-white/[0.01] flex items-center justify-center opacity-30">
                            <span class="text-[8px] font-black text-white uppercase tracking-widest italic">No File<\/span>
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
        } else {
            actionButtons.classList.add('hidden');
            actionButtons.classList.remove('flex');
        }
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
