<div class="space-y-6" wire:poll.3s>
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 mb-6 font-bold animate-in fade-in duration-300">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden relative"
         style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">


        <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-lg shadow-cyan-500/5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-lg leading-tight uppercase tracking-tight">Applications</h3>
                    <p class="text-xs text-white/30 font-bold uppercase tracking-widest mt-0.5"></p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <span class="bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-lg shadow-cyan-500/5">
                    {{ $pendingCount }} Pending Approval
                </span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left font-medium text-sm">
                <thead class="text-xs text-white/20 uppercase tracking-widest border-b border-white/5" style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th class="py-5 px-6 font-black">Ref ID</th>
                        <th class="py-5 px-6 font-black">Full Name</th>
                        <th class="py-5 px-6 font-black">Email</th>
                        <th class="py-5 px-6 font-black">Program</th>
                        <th class="py-5 px-6 font-black">Date</th>
                        <th class="py-5 px-6 font-black">Status</th>
                        <th class="py-5 px-6 text-right font-black">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($applications as $application)
                    <tr wire:key="app-{{ $application->id }}" class="hover:bg-cyan-500/[0.03] transition-all group">
                        <td class="py-5 px-6">
                            <span class="text-white/20 font-mono text-xs">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <span class="text-white font-bold uppercase tracking-tight group-hover:text-cyan-400 transition-colors">
                                {{ $application->last_name }}, {{ $application->first_name }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-white/40 lowercase text-sm">{{ $application->email }}</td>
                        <td class="py-5 px-6">
                            <span class="text-white font-bold uppercase text-xs tracking-widest">{{ $application->course_code }}</span>
                            <span class="text-white/20 text-xs ml-1">({{ $application->year_level }})</span>
                        </td>
                        <td class="py-5 px-6 text-white/30 text-sm italic">{{ $application->created_at->format('M d, Y') }}</td>
                        <td class="py-5 px-6">
                            @php
                            $badgeColor = match(ucfirst($application->status)) {
                                'Approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'Enrolled' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                default => 'bg-white/5 text-white/40 border-white/10',
                            };
                            $displayText = ucfirst($application->status);
                            if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border {{ $badgeColor }}">
                                {{ $displayText }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-right whitespace-nowrap">
                            <button wire:click="viewApplication({{ $application->id }})"
                                class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-white/5 border border-white/5 text-cyan-400 hover:text-white hover:bg-cyan-500 hover:border-cyan-400 transition-all text-sm font-black uppercase tracking-widest active:scale-95 shadow-lg group/btn">
                                Analyze
                                <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-24 text-center">
                            <div class="flex flex-col items-center gap-4 opacity-20">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Enrollment Application</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($applications, 'links'))
            <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                {{ $applications->links('livewire.glass-pagination') }}
            </div>
        @endif
    </div>

    {{-- Detail Analysis Modal --}}
    @if($selectedApp)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 animate-in fade-in duration-300">
        <div class="absolute inset-0 bg-[#060d1a]/95 backdrop-blur-2xl" wire:click="closeModal"></div>

        <div class="bg-[#0d1f3c] w-full max-w-5xl rounded-[32px] shadow-[0_32px_120px_rgba(0,0,0,0.7)] border border-white/10 overflow-hidden flex flex-col max-h-[92vh] relative z-10 transform animate-in zoom-in-95 duration-300">

            <div class="px-10 py-8 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400">
                        <svg class="w-6 h-6 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight uppercase">Application List #{{ str_pad($selectedApp->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-xs text-white/30 uppercase tracking-[0.2em] font-black mt-0.5"></p>
                    </div>
                </div>
                <button wire:click="closeModal" class="w-10 h-10 rounded-xl bg-white/5 text-white/20 hover:text-white hover:bg-white/10 transition-all flex items-center justify-center text-2xl font-light">&times;</button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-grow bg-white/[0.01]">
                <!-- Top Ribbon: Program & Status Banner -->
                <div class="bg-cyan-500/10 border-b border-cyan-500/20 px-10 py-12 flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity duration-1000 rotate-12">
                        <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"></path></svg>
                    </div>
                    <div class="relative z-10 space-y-3 text-center md:text-left">
                        <span class="text-xs font-black text-cyan-400 uppercase tracking-[0.4em] block drop-shadow-sm">Enrollment</span>
                        <h2 class="text-5xl font-black text-white tracking-tighter uppercase">{{ $selectedApp->course_code }}</h2>
                        <p class="text-xs font-bold text-white/40 uppercase tracking-widest pl-4 border-l-2 border-cyan-500/40 mx-auto md:mx-0 w-max">{{ $selectedApp->year_level }} Academic Tier</p>
                    </div>
                    <div class="relative z-10 flex flex-col items-center md:items-end gap-3">
                        <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]">Validation State</span>
                        @php
                            $modalStatusStyle = match (ucfirst($selectedApp->status)) {
                                'Approved' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30 shadow-cyan-500/20',
                                'Enrolled' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30 shadow-emerald-500/10',
                                'Rejected' => 'bg-rose-500/20 text-rose-400 border-rose-500/30 shadow-rose-500/10',
                                'Pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30 shadow-amber-500/10',
                                default => 'bg-white/5 text-white/40 border-white/10',
                            };
                        @endphp
                        <div class="px-10 py-4 rounded-3xl border text-base font-black uppercase tracking-[0.2em] shadow-2xl backdrop-blur-md {{ $modalStatusStyle }}">
                            {{ $selectedApp->status === 'Enrolled' ? 'PAID' : $selectedApp->status }}
                        </div>
                    </div>
                </div>

                <div class="p-10 md:p-14 space-y-20">
                    <!-- Applicant Identity -->
                    <section>
                        <h3 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] ml-2 mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Applicant Credentials
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 p-10 rounded-[40px] bg-white/[0.02] border border-white/5 relative overflow-hidden group/card shadow-inner">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-cyan-500/5 blur-3xl rounded-full -mr-20 -mt-20 group-hover/card:bg-cyan-500/10 transition-colors duration-700"></div>
                            <div class="space-y-1">
                                <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2 italic">Full Name</span>
                                <h3 class="text-xl font-black text-white uppercase tracking-tight">Application Details</h3>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2 italic">Email</span>
                                <p class="text-lg font-bold text-cyan-400 litalic border-b border-cyan-500/20 pb-1 w-max">{{ $selectedApp->email ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2 italic">Age & Gender</span>
                                <p class="text-lg font-bold text-white uppercase tracking-wider">{{ $selectedApp->age ?? 'N/A' }} Years <span class="text-white/20 mx-2">|</span> {{ ucfirst($selectedApp->gender ?? 'N/A') }} Identity</p>
                            </div>
                        </div>
                    </section>

                    <!-- Family Context -->
                    <section>
                        <h3 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] ml-2 mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Family Context
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 p-10 rounded-[40px] bg-white/[0.02] border border-white/5 shadow-inner">
                            @foreach (['father_name' => 'Father', 'mother_maiden_name' => 'Mother', 'guardian_name' => 'Guardian', 'guardian_contact' => 'Emergency Contact'] as $key => $label)
                                <div class="space-y-2">
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-widest italic leading-none block">{{ $label }}</span>
                                    <p class="text-base font-bold text-white uppercase tracking-wider group-hover:text-cyan-400 transition-colors">{{ $selectedApp->$key ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <!-- Compliance Documents -->
                    <section>
                        <h3 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] ml-2 mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Artifact Verification Status
                        </h3>
                        <div class="space-y-12">
                            @foreach ([['key' => 'form_138_path', 'label' => 'PROGRESS REPORT (FORM 137)'], ['key' => 'good_moral_path', 'label' => 'CERTIFICATE OF GOOD MORAL'], ['key' => 'psa_path', 'label' => 'PSA BIRTH CERTIFICATE'], ['key' => 'id_picture_path', 'label' => 'STUDENT IDENTIFICATION PHOTO']] as $doc)
                                @php $hasFile = !empty($selectedApp[$doc['key']]); @endphp
                                <div class="space-y-4 group">
                                    <div class="flex items-center justify-between px-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-2.5 h-2.5 rounded-full {{ $hasFile ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)]' : 'bg-rose-500 shadow-[0_0_12px_rgba(244,63,94,0.5)]' }}"></div>
                                            <span class="text-xs font-black uppercase tracking-[0.3em] {{ $hasFile ? 'text-white' : 'text-rose-500/50' }}">{{ $doc['label'] }}</span>
                                        </div>
                                        @if($hasFile)
                                            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest hidden md:block">Verified Secure Asset</span>
                                        @endif
                                    </div>
                                    <div class="aspect-video w-full rounded-[40px] overflow-hidden bg-white/5 border border-white/10 relative shadow-2xl transition-all duration-500 group-hover:border-cyan-500/40 group-hover:shadow-cyan-500/10">
                                        @if ($hasFile)
                                            @php
                                                $fileUrl = \Storage::disk('public')->url($selectedApp[$doc['key']]);
                                                $isImage = preg_match('/\.(jpeg|jpg|png|gif|webp)$/i', $selectedApp[$doc['key']]);
                                            @endphp
                                            @if ($isImage)
                                                <img src="{{ $fileUrl }}" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-cyan-400 opacity-40 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="text-[10px] font-black mt-4 uppercase tracking-[0.3em]">Examine Secure PDF</span>
                                                </div>
                                            @endif
                                            <a href="{{ $fileUrl }}" target="_blank" class="absolute inset-0 bg-[#060d1a]/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center backdrop-blur-md gap-4">
                                                <div class="p-5 rounded-full bg-cyan-500 text-black shadow-[0_0_30px_rgba(6,182,212,0.4)] transform scale-90 group-hover:scale-100 transition-transform duration-500">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </div>
                                                <span class="text-xs font-black text-white uppercase tracking-[0.4em]">Open Full Resolution Image</span>
                                            </a>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center opacity-20 bg-rose-500/5">
                                                <svg class="w-12 h-12 text-rose-500/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span class="text-xs font-black tracking-widest uppercase italic text-rose-500/60">Missing Entry Documentation</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            <div class="px-10 py-8 border-t border-white/5 bg-white/[0.02] flex flex-col md:flex-row justify-between items-center gap-6">
                @if($selectedApp->status === 'Pending')
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button wire:click="approve({{ $selectedApp->id }})" class="flex-1 md:flex-none px-10 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-black rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                        Approve Data
                    </button>
                    <button wire:confirm="Reject application #{{ $selectedApp->id }}?" wire:click="reject({{ $selectedApp->id }})" class="flex-1 md:flex-none px-10 py-3.5 bg-rose-500 hover:bg-rose-400 text-white text-xs font-black rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-rose-500/20 active:scale-95">
                        Reject
                    </button>
                </div>
                @else
                <div class="hidden md:block"></div>
                @endif
                <button wire:click="closeModal" class="w-full md:w-auto px-10 py-3.5 border border-white/10 bg-white/5 hover:bg-white/10 text-white/40 hover:text-white text-xs font-bold rounded-xl uppercase tracking-widest transition-all">
                    Exit Details
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
