<div class="space-y-6 animate-in fade-in duration-500" x-data="{ modalOpen: false, selectedId: null }">
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
                <h2 class="text-2xl font-black text-black tracking-tight uppercase">Applications</h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Student Enrollment Lifecycle & Review Pipeline</p>
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
                        <option value="Paid">Fully Paid</option>
                    </select>
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="course_filter" class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-black focus:outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none cursor-pointer transition-all shadow-sm">
                        @php
                            $courseLabel = 'ALL PROGRAMS/STRANDS';
                            if ($level === 'college') $courseLabel = 'ALL PROGRAMS';
                            if ($level === 'shs') $courseLabel = 'ALL STRANDS';
                        @endphp
                        <option value="All Programs">{{ $courseLabel }}</option>
                        @if($level === 'All Levels' || $level === 'college')
                            @foreach($collegePrograms as $course)
                                <option value="{{ $course->course_code }}">{{ $course->course_code }}</option>
                            @endforeach
                        @endif
                        @if($level === 'All Levels' || $level === 'shs')
                            @foreach($shsStrands as $course)
                                <option value="{{ $course->course_code }}">{{ $course->course_code }}</option>
                                @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar bg-white">
            <table class="min-w-max w-full text-left border-collapse font-bold">
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
                        <th class="py-6 px-8 text-left cursor-pointer group/th hover:text-blue-600 transition-colors" wire:click="sortBy('last_name')">
                            <div class="flex items-center gap-2">
                                Full Name
                                <span class="transition-opacity {{ $sortField === 'last_name' ? 'opacity-100' : 'opacity-0 group-hover/th:opacity-50' }}">
                                    @if($sortField === 'last_name' && $sortDirection === 'asc')
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
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 bg-white">
                    @forelse($applications as $application)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="py-6 px-8 text-slate-400 font-mono tracking-tighter whitespace-nowrap">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-black group-hover:text-blue-600 transition-colors uppercase tracking-wider font-bold">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</span>
                                        @if($application->extension)
                                        <span class="text-sm font-bold text-slate-400">{{ $application->extension }}</span>
                                        @endif
                                    </div>
                                    <span class="text-[9px] text-slate-400 uppercase tracking-widest mt-0.5">Applicant Profile</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-slate-500 lowercase tracking-tight whitespace-nowrap">{{ $application->email }}</td>
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
                            <td class="py-6 px-8 text-slate-500 font-medium tracking-tight whitespace-nowrap">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                @php
                                    $isFullyPaid = $application->status === 'Paid' && $application->is_fully_paid;
                                    $badgeColor = match(true) {
                                        $application->status === 'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        $isFullyPaid => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                        $application->status === 'Paid' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        $application->status === 'Approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        $application->status === 'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                        $application->status === 'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        default => 'bg-white/5 text-white/40 border-white/10',
                                    };
                                    $displayText = match(true) {
                                        $application->status === 'Enrolled' => 'Enrolled',
                                        $isFullyPaid => 'Fully Paid',
                                        $application->status === 'Paid' => 'Partially Paid',
                                        $application->status === 'Approved' => 'Approved',
                                        $application->status === 'Pending' => 'Pending',
                                        default => $application->status
                                    };
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeColor }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                <div class="flex justify-end items-center gap-3">
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
                        <td colspan="9" class="py-24 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="w-16 h-16 mb-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300">No applications found in the review pipeline</span>
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

    <!-- Details Modal -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 p-4 flex items-center justify-center transition-all duration-300" x-cloak>
        <div class="absolute inset-0 bg-blue-900/40 backdrop-blur-md" @click="modalOpen = false"></div>

        <div class="bg-white/85 w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(30,58,138,0.15)] border border-blue-500/10 overflow-hidden flex flex-col max-h-[95vh] relative z-10 backdrop-blur-2xl" id="modalContent" wire:ignore>
            <div class="px-8 md:px-12 py-8 border-b border-blue-500/10 flex justify-between items-center bg-blue-50/20">
                <div>
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] mb-1 block">Analysis Protocol</span>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight" id="modalTitle">Application Details</h2>
                </div>
                <button @click="modalOpen = false" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-800 transition-all border border-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-8 md:px-12 md:py-10 overflow-y-auto custom-scrollbar flex-grow space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Student Profile -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">Applicant Profile</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6 bg-white/50 border border-blue-500/10 rounded-[32px] p-8">
                            <div class="grid grid-cols-2 gap-8">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Full Name</span>
                                    <span class="text-xs font-bold text-blue-600 uppercase" id="modalNameValue"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email Address</span>
                                    <span class="text-xs font-bold text-slate-600 lowercase" id="modalEmail"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Reference ID</span>
                                    <span class="text-xs font-bold text-blue-500 uppercase" id="modalAppId"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Applied On</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase" id="modalSubmitted"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Birth Date</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase" id="modalDob"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Age</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase" id="modalAge"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Gender</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase" id="modalGender"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Address</span>
                                    <span class="text-xs font-bold text-slate-600 uppercase" id="modalAddress"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program & Lifecycle -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">Program Details</h3>
                        </div>
                        <div class="bg-blue-500/5 border border-blue-500/10 rounded-[32px] p-8 h-full flex flex-col justify-center">
                            <div class="space-y-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-blue-500/70 uppercase tracking-widest">Applied Program</span>
                                    <span class="text-2xl font-black text-blue-600 uppercase tracking-tighter" id="modalCourse"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-8 pt-6 border-t border-slate-200">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Academic Level</span>
                                        <span class="text-xs font-bold text-slate-700 uppercase" id="modalYear"></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status Now</span>
                                        <span class="text-xs font-black text-blue-600 uppercase tracking-widest" id="modalStatus"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guardian Records -->
                <div class="space-y-6 pt-6">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">Guardian Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white/50 border border-blue-500/10 rounded-[32px] p-8">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Father's Name</span>
                            <span class="text-xs font-bold text-slate-700 uppercase" id="modalFather"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mother's Name</span>
                            <span class="text-xs font-bold text-slate-700 uppercase" id="modalMother"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Guardian Name</span>
                            <span class="text-xs font-bold text-slate-700 uppercase" id="modalGuardian"></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Emergency Contact</span>
                            <span class="text-xs font-bold text-slate-700 uppercase" id="modalContact"></span>
                        </div>
                    </div>
                </div>

                <!-- Document Assets -->
                <div class="space-y-6 pt-6">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">Required Documents</h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="modalDocuments">
                        <!-- Injected via JS -->
                    </div>
                </div>

                <!-- Promissory Note Asset -->
                <div class="space-y-6 pt-6 hidden" id="modalPromissorySection">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.3em]">Promissory Note & Reason</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-amber-500/5 border border-amber-500/15 rounded-[32px] p-8">
                        <div id="modalPromissoryFile" class="lg:col-span-1">
                            <!-- Injected via JS -->
                        </div>
                        <div class="lg:col-span-2 space-y-2">
                            <span class="text-[9px] font-black text-amber-600/70 uppercase tracking-widest">Student's Explanation</span>
                            <div class="p-6 rounded-2xl bg-white/50 border border-amber-500/10 min-h-[80px]">
                                <p class="text-[11px] text-slate-700 leading-relaxed" id="modalPromissoryReason"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 md:px-12 py-8 border-t border-blue-500/10 bg-blue-50/20 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="hidden" id="actionButtons"></div>
                
            </div>
        </div>
    </div>

    <script>
    function openModal(app, docMapping) {
        document.getElementById('modalTitle').innerText = 'Application Details #' + String(app.id).padStart(5, '0');
        const middle = app.middle_name ? ' ' + app.middle_name : '';
        const fullName = (app.last_name || '') + ', ' + (app.first_name || '') + middle;

        // Student Profile section
        document.getElementById('modalNameValue').innerText = fullName;
        document.getElementById('modalEmail').innerText = app.email || 'N/A';
        document.getElementById('modalAppId').innerText = '#' + String(app.id).padStart(5, '0');
        document.getElementById('modalSubmitted').innerText = new Date(app.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('modalDob').innerText = app.birth_date || 'N/A';
        document.getElementById('modalAge').innerText = app.age || 'N/A';
        document.getElementById('modalGender').innerText = app.gender || 'N/A';
        document.getElementById('modalAddress').innerText = app.address_full || 'N/A';

        // Program details
        document.getElementById('modalCourse').innerText = app.course_code || 'N/A';
        document.getElementById('modalYear').innerText = app.year_level || 'N/A';
        let statusText = app.status || 'N/A';
        if (statusText === 'Paid' || statusText.toLowerCase() === 'paid') {
            statusText = (app.is_fully_paid) ? 'Fully Paid' : 'Partially Paid';
        }
        document.getElementById('modalStatus').innerText = statusText;

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
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-blue-500/10 bg-blue-50/30">
                            <img src="${fileUrl}" class="w-full h-32 object-cover transition-transform duration-500 group-hover/asset:scale-110">
                            <div class="absolute inset-0 bg-blue-500/10 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"><\/path><\/svg>
                            <\/div>
                        <\/a>
                    `;
                } else {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-blue-500/10 bg-blue-50/30 h-32 flex flex-col items-center justify-center">
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
                let paths = [];
                try {
                    paths = JSON.parse(app.promissory_note_path);
                    if (!Array.isArray(paths)) {
                        paths = [app.promissory_note_path];
                    }
                } catch (e) {
                    paths = [app.promissory_note_path];
                }

                let fileHtml = '<div class="space-y-3"><span class="text-[9px] font-black text-amber-600/70 uppercase tracking-widest">Note Attachment(s)<\/span><div class="space-y-2">';
                
                paths.forEach((path, index) => {
                    const noteUrl = storageBase + path;
                    const isPdf = path.toLowerCase().endsWith('.pdf');
                    fileHtml += `
                        <a href="${noteUrl}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl border border-amber-500/20 bg-amber-50/50 hover:bg-amber-50 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"><\/path><\/svg>
                            <\/div>
                            <div>
                                <p class="text-[10px] font-black text-slate-800 uppercase tracking-wider">Download Note ${paths.length > 1 ? '#' + (index + 1) : ''}<\/p>
                                <p class="text-[8px] text-amber-600/80 uppercase font-bold mt-0.5">${isPdf ? 'PDF Format' : 'Word Doc'}<\/p>
                            <\/div>
                        <\/a>
                    `;
                });
                
                fileHtml += '<\/div><\/div>';
                promissoryFile.innerHTML = fileHtml;
            } else {
                promissoryFile.innerHTML = `
                    <div class="space-y-3">
                        <span class="text-[9px] font-black text-amber-600/70 uppercase tracking-widest">Note Attachment<\/span>
                        <div class="p-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center opacity-30">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">No File<\/span>
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
        if (['pending', 'enrolled', 'paid'].includes(status)) {
            actionButtons.classList.remove('hidden');
            actionButtons.classList.add('flex');
        } else {
            actionButtons.classList.add('hidden');
            actionButtons.classList.remove('flex');
        }
    }
    </script>
</div>
