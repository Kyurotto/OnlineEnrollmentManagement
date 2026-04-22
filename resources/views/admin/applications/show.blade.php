<x-layouts.admin>
    <div class="py-8 max-w-5xl mx-auto">
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34, 211, 238, 0.2); border-radius: 4px; }
        </style>

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ redirect()->back()->getTargetUrl() }}" class="p-2 rounded-lg bg-white/5 border border-white/10 text-white/40 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <span class="bg-cyan-500/20 text-cyan-400 font-mono text-xs font-black px-3 py-1.5 rounded-xl border border-cyan-500/30 shadow-sm">#{{ $application->id }}</span>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight uppercase">Application List</h2>
                <p class="text-cyan-400/60 text-xs font-bold uppercase tracking-widest mt-1">Full record for {{ $application->user?->name }}</p>
            </div>

            @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                <div class="flex gap-3">
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white px-6 py-2.5 rounded-xl text-xs font-black transition shadow-lg shadow-emerald-500/20 uppercase tracking-widest">
                            Approve Candidate
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="rounded-3xl border overflow-hidden shadow-2xl relative"
             style="background: #0a1628; border-color: rgba(255,255,255,0.1); box-shadow: 0 0 80px rgba(0,0,0,0.6);">

            <div class="p-8 md:p-12 overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                    <!-- Sidebar Column -->
                    <div class="lg:col-span-1 space-y-10">
                        <!-- Program Card -->
                        <div class="p-8 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <svg class="w-24 h-24 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <div class="flex items-center justify-between mb-6 border-b border-cyan-500/20 pb-2">
                                <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest">Program Preference</h3>
                                @php
                                    $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                                @endphp
                                <span class="px-2 py-1 text-[9px] font-bold rounded-full border {{ $isSHS ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-blue-500/10 text-blue-400 border-blue-500/30' }}">
                                    {{ $isSHS ? 'SHS' : 'COLLEGE' }}
                                </span>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-4xl font-black text-white tracking-tighter">{{ $application->course_code ?? 'N/A' }}</h3>
                                <p class="text-white/40 text-sm italic">{{ $application->year_level }}</p>
                                <div class="pt-6">
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-widest block mb-2 px-1">Current Status</span>
                                    @php
                                        $statusStyle = match (ucfirst($application->status)) {
                                            'Approved' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                            'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            default => 'bg-white/5 text-white/40 border-white/10',
                                        };
                                    @endphp
                                    <span class="inline-block px-4 py-2 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-lg {{ $statusStyle }}">
                                        {{ strtoupper($application->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Info -->
                        <div class="px-2">
                             <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-8 flex items-center gap-2 border-b border-white/5 pb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Guardian Information
                            </h3>
                            <div class="space-y-6">
                                <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1 px-1">Father's Name</span><span class="text-sm font-bold text-white uppercase">{{ $application->father_name ?? 'NOT RECORDED' }}</span></div>
                                <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1 px-1">Mother's Name</span><span class="text-sm font-bold text-white uppercase">{{ $application->mother_maiden_name ?? 'NOT RECORDED' }}</span></div>
                                <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1 px-1">Guardian</span><span class="text-sm font-bold text-cyan-300 uppercase">{{ $application->guardian_name ?? 'N/A' }}</span></div>
                                <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1 px-1">Emergency Ph.</span><span class="text-sm font-mono text-white tracking-widest">{{ $application->guardian_contact ?? 'N/A' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Data Column -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- Bio Data -->
                        <div>
                            <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-white/5 pb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Student Profile
                            </h3>
                            <div class="grid grid-cols-2 gap-y-8 gap-x-12 p-8 rounded-3xl bg-white/[0.02] border border-white/5">
                                <div class="col-span-2 md:col-span-1 border-l-2 border-cyan-500/20 pl-4">
                                    <span class="block text-[10px] text-white/20 uppercase font-black mb-1">Given Name</span>
                                    <span class="text-xl font-black text-white uppercase tracking-tight">{{ $application->user?->first_name }} {{ $application->user?->middle_name }}</span>
                                </div>
                                <div class="col-span-2 md:col-span-1 border-l-2 border-cyan-500/20 pl-4">
                                    <span class="block text-[10px] text-white/20 uppercase font-black mb-1">Surname</span>
                                    <span class="text-xl font-black text-white uppercase tracking-tight">{{ $application->user?->last_name }}</span>
                                </div>
                                <div class="col-span-2 md:col-span-1 border-l-2 border-white/10 pl-4">
                                    <span class="block text-[10px] text-white/20 uppercase font-black mb-1">Contact Email</span>
                                    <span class="text-sm font-bold text-cyan-400 lowercase italic">{{ $application->user?->email }}</span>
                                </div>
                                <div class="col-span-2 md:col-span-1 border-l-2 border-white/10 pl-4">
                                    <span class="block text-[10px] text-white/20 uppercase font-black mb-1">Birth Date</span>
                                    <span class="text-sm font-bold text-white uppercase">{{ $application->user?->birth_date }} ({{ $application->user?->age }} yrs)</span>
                                </div>
                                <div class="col-span-2 border-l-2 border-white/10 pl-4">
                                    <span class="block text-[10px] text-white/20 uppercase font-black mb-1">Address</span>
                                    <span class="text-sm font-medium text-white/60 leading-relaxed uppercase">{{ $application->user?->address_full }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div>
                            <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-white/5 pb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Required Documents
                            </h3>

                            @php
                                $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                                $docs = $isSHS
                                    ? [
                                        'form_137_path' => 'JHS Report Card (SF9)',
                                        'sf10_path' => 'SF10 (Permanent Record)',
                                        'good_moral_path' => 'Certificate of Good Moral',
                                        'psa_path' => 'PSA Birth Certificate',
                                        'id_picture_path' => '2x2 ID Portrait'
                                    ]
                                    : [
                                        'form_137_path' => 'Form 137 (Report Card)',
                                        'good_moral_path' => 'Certificate of Good Moral',
                                        'psa_path' => 'PSA Birth Certificate',
                                        'id_picture_path' => '2x2 ID Portrait'
                                    ];
                            @endphp

                            <div class="mb-6">
                                <div class="inline-block px-3 py-1.5 rounded-full border text-[10px] font-black uppercase tracking-widest {{ $isSHS ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-blue-500/10 text-blue-400 border-blue-500/30' }}">
                                    {{ $isSHS ? 'Senior High School' : 'College' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                @foreach ($docs as $path => $label)
                                    <div class="space-y-4 group/doc">
                                        <div class="flex items-center gap-2">
                                            @if (!empty($application->$path))
                                                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                                <span class="text-[9px] font-black uppercase text-white tracking-widest">{{ $label }}</span>
                                            @else
                                                <div class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]"></div>
                                                <span class="text-[9px] font-black uppercase text-rose-500 tracking-widest opacity-60">{{ $label }}</span>
                                            @endif
                                        </div>

                                        @if (!empty($application->$path))
                                            @php
                                                $fileUrl = route('document.show', ['path' => $application->$path]);
                                                $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $application->$path);
                                            @endphp

                                            <a href="{{ $fileUrl }}" target="_blank" class="block aspect-[3/4] relative rounded-2xl overflow-hidden border border-white/10 bg-white/5 group-hover/doc:border-cyan-500/50 transition-all shadow-inner">
                                                @if($isImage)
                                                    <img src="{{ $fileUrl }}" class="w-full h-full object-cover grayscale opacity-60 group-hover/doc:grayscale-0 group-hover/doc:opacity-100 transition-all duration-700">
                                                @else
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-cyan-400 opacity-30 group-hover/doc:opacity-100 transition-opacity">
                                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                        <span class="text-[8px] font-black mt-3">VIEW PDF</span>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/doc:opacity-100 transition-opacity flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </div>
                                            </a>
                                        @else
                                            <div class="aspect-[3/4] rounded-2xl bg-rose-500/5 border border-dashed border-rose-500/20 flex flex-col items-center justify-center opacity-40">
                                                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span class="text-[9px] font-black text-rose-500 mt-3 tracking-widest uppercase">Missing File</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="bg-white/5 px-8 py-6 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-[10px] font-black text-white/20 uppercase tracking-widest text-center md:text-left">
                    Application Submitted: {{ $application->created_at->diffForHumans() }}
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('registrar.applications.index') }}" class="text-xs font-black text-white/40 hover:text-white uppercase tracking-widest transition">
                        Return to Queue
                    </a>

                    @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                        <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white px-10 py-4 rounded-2xl text-xs font-black shadow-xl shadow-emerald-500/20 transition-all uppercase tracking-widest">
                                Confirm Approval
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
