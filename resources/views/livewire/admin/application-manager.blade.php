<div wire:poll.3s>
    @if(session('success'))
        <div class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-2">
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
                    <span class="w-1 h-5 rounded-full bg-cyan-500 inline-block"></span>
                    Applications List
                </h2>
                <p class="text-sm text-white/40 mt-1 uppercase tracking-widest font-bold">Review and process student admissions</p>
            </div>
        </div>

        <div class="bg-white/5 px-6 py-5 border-b border-white/5 backdrop-blur-sm">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-cyan-400 group-focus-within:text-cyan-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="pl-10 w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition-all placeholder-white/20 shadow-inner"
                        placeholder="Search student or email...">
                </div>
                <div class="w-full sm:w-48">
                    <select wire:model.live="statusFilter"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition-all appearance-none cursor-pointer shadow-inner font-bold">
                        <option value="" class="bg-[#0a1628]">All Statuses</option>
                        <option value="Pending" class="bg-[#0a1628]">PENDING</option>
                        <option value="Approved" class="bg-[#0a1628]">APPROVED</option>
                        <option value="Enrolled" class="bg-[#0a1628]">PAID / ENROLLED</option>
                        <option value="Rejected" class="bg-[#0a1628]">REJECTED</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead class="text-xs text-cyan-300 uppercase tracking-widest border-b border-white/5"
                       style="background: rgba(255,255,255,0.03);">
                    <tr>
                        <th class="py-5 px-6 font-black">ID</th>
                        <th class="py-5 px-6 font-black">Student Name</th>
                        <th class="py-5 px-6 font-black">Email</th>
                        <th class="py-5 px-6 font-black">Program</th>
                        <th class="py-5 px-6 font-black">Date</th>
                        <th class="py-5 px-6 font-black text-center">Status</th>
                        <th class="py-5 px-6 font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    @forelse($applications as $application)
                    <tr class="hover:bg-white/5 transition group">
                        <td class="py-5 px-6 whitespace-nowrap">
                            <span class="bg-cyan-500/10 text-cyan-400 font-mono text-xs font-black px-2 py-1 rounded border border-cyan-500/20 shadow-sm">
                                #{{ $application->id }}
                            </span>
                        </td>
                        <td class="py-5 px-6 font-bold text-white group-hover:text-cyan-200 transition-colors uppercase tracking-tight">
                            {{ $application->first_name }} {{ $application->last_name }}
                        </td>
                        <td class="py-5 px-6 text-white/40 lowercase italic text-sm">
                            {{ $application->email }}
                        </td>
                        <td class="py-5 px-6">
                            <span class="font-black text-white text-sm">{{ $application->course_code }}</span>
                            <span class="text-xs text-white/30 block mt-0.5 font-bold uppercase">{{ $application->year_level }}</span>
                        </td>
                        <td class="py-5 px-6 text-white/60 font-medium">
                            {{ $application->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                                $statusStyle = match (ucfirst($application->status)) {
                                    'Approved' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                    'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                    'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    default => 'bg-white/5 text-white/40 border-white/10',
                                };
                                $displayText = ucfirst($application->status) === 'Enrolled' ? 'PAID' : strtoupper($application->status);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-black border uppercase tracking-widest shadow-sm {{ $statusStyle }}">
                                {{ $displayText }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-right whitespace-nowrap">
                            <button wire:click="viewApplication({{ $application->id }})"
                                class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-white/5 border border-white/5 text-cyan-400 hover:text-white hover:bg-cyan-500 hover:border-cyan-400 transition-all text-sm font-black uppercase tracking-widest active:scale-95 shadow-lg group/btn">
                                View Details
                                <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-2 opacity-20">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="italic text-sm font-bold uppercase tracking-widest text-white">No applications found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-white/5 font-bold" style="background: rgba(255,255,255,0.02);">
            {{ $applications->links('livewire.glass-pagination') }}
        </div>
    </div>

    <!-- Application Preview Modal -->
    <div class="fixed inset-0 z-50 p-4 flex items-center justify-center transition-all duration-300 backdrop-blur-md bg-[#060d1a]/60 {{ $showModal ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}">
        <div class="absolute inset-0" wire:click="closeModal"></div>

        <div class="rounded-3xl shadow-2xl w-full max-w-4xl relative z-10 border overflow-hidden transform transition-all duration-300 {{ $showModal ? 'scale-100' : 'scale-95' }}"
             style="background: #0a1628; border-color: rgba(255,255,255,0.1); box-shadow: 0 0 80px rgba(0,0,0,0.6);">

            @if ($selectedApp)
                <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/5 relative">
                    <div class="flex items-center gap-3">
                        <span class="bg-cyan-500/20 text-cyan-400 font-mono text-xs font-black px-2 py-1 rounded border border-cyan-500/30">#{{ $selectedApp->id }}</span>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Application Details</h3>
                    </div>
                    <button wire:click="closeModal" class="text-white/40 hover:text-white transition focus:outline-none p-2 rounded-lg hover:bg-white/5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="absolute bottom-0 left-0 h-[2px] bg-cyan-500 w-full opacity-30"></div>
                </div>

                <div class="overflow-y-auto max-h-[75vh] custom-scrollbar">
                    <!-- Top Ribbon: Program & Status Banner -->
                    <div class="bg-cyan-500/10 border-b border-cyan-500/20 px-8 py-10 flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity duration-1000 rotate-12">
                            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5-10-5-10 5z"></path></svg>
                        </div>
                        <div class="relative z-10 space-y-3">
                            <span class="text-xs font-black text-cyan-400 uppercase tracking-[0.4em] block drop-shadow-sm">Enrollment</span>
                            <h2 class="text-5xl font-black text-white tracking-tighter uppercase">{{ $selectedApp->course_code }}</h2>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest pl-4 border-l-2 border-cyan-500/40">{{ $selectedApp->year_level }}</p>
                        </div>
                        <div class="relative z-10 flex flex-col items-center md:items-end gap-3">
                            <span class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]">Status</span>
                            @php
                                $modalStatusStyle = match (ucfirst($selectedApp->status)) {
                                    'Approved' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30 shadow-cyan-500/20',
                                    'Enrolled' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30 shadow-emerald-500/10',
                                    'Rejected' => 'bg-rose-500/20 text-rose-400 border-rose-500/30 shadow-rose-500/10',
                                    'Pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30 shadow-amber-500/10',
                                    default => 'bg-white/5 text-white/40 border-white/10',
                                };
                            @endphp
                            <div class="px-8 py-3 rounded-2xl border text-sm font-black uppercase tracking-[0.2em] shadow-2xl backdrop-blur-md {{ $modalStatusStyle }}">
                                {{ $selectedApp->status }}
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-12 space-y-16">
                        <!-- Personal Profile -->
                        <section>
                            <h4 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Student Profile
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 p-10 rounded-[32px] bg-white/[0.02] border border-white/5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 blur-3xl rounded-full -mr-16 -mt-16"></div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Full Name</span>
                                    <span class="text-2xl font-black text-white uppercase tracking-tight">{{ $selectedApp->last_name }}, {{ $selectedApp->first_name }} {{ $selectedApp->middle_name }}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Email Address</span>
                                    <span class="text-lg font-bold text-cyan-400 lowercase italic border-b border-cyan-500/20 pb-1">{{ $selectedApp->email }}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Birth Date & Age</span>
                                    <span class="text-lg font-bold text-white uppercase tracking-wider">{{ $selectedApp->birth_date }} <span class="text-cyan-400/50">/</span> {{ $selectedApp->age }} Years</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Gender Identification</span>
                                    <span class="text-lg font-bold text-white uppercase tracking-wider">{{ $selectedApp->gender }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- Family Linkage -->
                        <section>
                            <h4 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Guardian Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 p-10 rounded-[32px] bg-white/[0.02] border border-white/5">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Paternal Entry</span>
                                    <span class="text-lg font-bold text-white uppercase tracking-wide">{{ $selectedApp->father_name ?? 'N/A' }}</span>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Maternal Entry</span>
                                    <span class="text-lg font-bold text-white uppercase tracking-wide">{{ $selectedApp->mother_maiden_name ?? 'N/A' }}</span>
                                </div>
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-10 pt-8 border-t border-white/5">
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Designated Proxy (Guardian)</span>
                                        <span class="text-lg font-bold text-cyan-300 uppercase tracking-wide">{{ $selectedApp->guardian_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-white/20 uppercase font-black tracking-widest block mb-2">Emergency Contact</span>
                                        <span class="text-lg font-mono font-bold text-white tracking-[0.2em]">{{ $selectedApp->guardian_contact ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Compliance Documents -->
                        <section>
                            <h4 class="text-sm font-black text-cyan-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3 border-b border-white/5 pb-3 italic">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Required Documents
                            </h4>
                            <div class="space-y-12">
                                @php
                                    $docs = [
                                        ['key' => 'form_138_path', 'label' => 'PROGRESS REPORT (FORM 137)'],
                                        ['key' => 'good_moral_path' , 'label' => 'CERTIFICATE OF GOOD MORAL'],
                                        ['key' => 'psa_path', 'label' => 'PSA BIRTH CERTIFICATE'],
                                        ['key' => 'id_picture_path', 'label' => 'STUDENT IDENTIFICATION PHOTO']
                                    ];
                                @endphp

                                @foreach ($docs as $doc)
                                    @php $hasFile = !empty($selectedApp[$doc['key']]); @endphp
                                    <div class="group/doc space-y-4">
                                        <div class="flex items-center justify-between px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-2 w-2 rounded-full {{ $hasFile ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-rose-500 animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.5)]' }}"></div>
                                                <span class="text-xs font-black tracking-[0.2em] uppercase {{ $hasFile ? 'text-white' : 'text-rose-500 opacity-60' }}">{{ $doc['label'] }}</span>
                                            </div>
                                            @if($hasFile)
                                                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest hidden md:inline">File Uploaded</span>
                                            @endif
                                        </div>
                                        @if ($hasFile)
                                            @php $fileUrl = \Storage::disk('public')->url($selectedApp[$doc['key']]); @endphp
                                            <a href="{{ $fileUrl }}" target="_blank" class="block aspect-video w-full rounded-3xl border border-white/10 bg-white/5 hover:border-cyan-500/50 transition-all overflow-hidden relative shadow-2xl group-hover/doc:shadow-cyan-500/10">
                                                @if (preg_match('/\.(jpeg|jpg|png|gif|webp)$/i', $selectedApp[$doc['key']]))
                                                    <img src="{{ $fileUrl }}" class="w-full h-full object-cover grayscale opacity-60 group-hover/doc:grayscale-0 group-hover/doc:opacity-100 transition-all duration-700">
                                                @else
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-cyan-400 opacity-30 group-hover/doc:opacity-100 transition-opacity">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                        <span class="text-[10px] font-black mt-4 uppercase tracking-[0.3em]">View PDF</span>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-[#060d1a]/60 opacity-0 group-hover/doc:opacity-100 transition-opacity flex flex-col items-center justify-center gap-4 backdrop-blur-md">
                                                    <div class="p-4 rounded-full bg-cyan-500 text-black shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </div>
                                                    <span class="text-xs font-black text-white uppercase tracking-[0.4em]">Open in Full Resolution</span>
                                                </div>
                                            </a>
                                        @else
                                            <div class="aspect-video w-full rounded-3xl bg-rose-500/5 border-2 border-dashed border-rose-500/10 flex flex-col items-center justify-center opacity-40">
                                                <svg class="w-12 h-12 text-rose-500/20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span class="text-xs font-black text-rose-500 uppercase tracking-widest italic opacity-60">Missing Entry Documentation</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>

                <div class="bg-white/5 px-8 py-6 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-xs font-black text-white/20 uppercase tracking-widest">
                        Application Submitted: {{ $selectedApp->created_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center gap-3">
                        @if(ucfirst($selectedApp->status) === 'Pending')
                            <button wire:click="reject({{ $selectedApp->id }})" class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 px-8 py-3 rounded-2xl text-xs font-black transition-all uppercase tracking-widest border border-rose-500/20">
                                REJECT
                            </button>
                            <button wire:click="approve({{ $selectedApp->id }})" class="bg-cyan-500 hover:bg-cyan-400 text-black px-10 py-3 rounded-2xl text-xs font-black transition-all uppercase tracking-widest shadow-xl shadow-cyan-500/20">
                                APPROVE
                            </button>
                        @endif
                        <button wire:click="closeModal" class="bg-white/10 hover:bg-white/20 text-white px-10 py-3 rounded-2xl text-xs font-black transition-all uppercase tracking-widest border border-white/10 ml-2">
                                CLOSE
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
