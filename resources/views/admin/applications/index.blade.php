<div class="py-6">
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34, 211, 238, 0.2); border-radius: 4px; }
    </style>

    <div class="w-full">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-white tracking-tight">Admissions Portal</h2>
            <p class="text-cyan-400/60 text-xs font-bold uppercase tracking-widest mt-1">Reviewing incoming student applications</p>
        </div>

        @if (session('success'))
            <div class="bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-4 py-3 rounded-xl relative mb-8 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border overflow-hidden"
             style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">
            
            <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-cyan-500 inline-block"></span>
                    Applications Queue
                    @if ($applications->total() > 0)
                        <span class="text-white/40 text-xs font-normal ml-1">({{ $applications->total() }})</span>
                    @endif
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-white/70">
                    <thead class="text-[10px] text-cyan-300 uppercase tracking-widest border-b border-white/5"
                           style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="px-6 py-5 font-black">ID</th>
                            <th class="px-6 py-5 font-black">Student Candidate</th>
                            <th class="px-6 py-5 font-black">Email Contact</th>
                            <th class="px-6 py-5 font-black">Program/Year</th>
                            <th class="px-6 py-5 font-black">Submission Date</th>
                            <th class="px-6 py-5 font-black text-center">Status</th>
                            <th class="px-6 py-5 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($applications as $application)
                            <tr class="hover:bg-white/5 transition-all group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-[10px] font-mono font-black text-white/30 group-hover:text-cyan-400/50 transition-colors">
                                        #{{ $application->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="font-bold text-white group-hover:text-cyan-200 transition-colors uppercase tracking-tight">
                                        {{ $application->first_name }} {{ $application->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-xs text-white/40 italic lowercase">
                                    {{ $application->email }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-black text-[10px] uppercase">
                                        {{ $application->course_code }}
                                    </span>
                                    <span class="text-[10px] text-white/30 font-bold ml-1">{{ $application->year_level }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-xs text-white/50 font-bold">
                                    {{ $application->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @php
                                        $badgeStyle = match (ucfirst($application->status)) {
                                            'Approved' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                            'Enrolled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            default => 'bg-white/5 text-white/40 border-white/10',
                                        };
                                        $displayText = ucfirst($application->status) === 'Enrolled' ? 'PAID' : strtoupper($application->status);
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-sm {{ $badgeStyle }}">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <button wire:click="viewApplication({{ $application->id }})"
                                        class="p-2 rounded-lg bg-white/5 border border-white/10 text-cyan-300 hover:bg-cyan-500 hover:text-white hover:border-cyan-400 transition-all shadow-sm group/btn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <svg class="w-16 h-16 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="italic text-sm font-black uppercase tracking-widest">No applications found in queue</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div class="px-6 py-4 border-t border-white/5" style="background: rgba(255,255,255,0.02);">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>

        <!-- Glass Modal -->
        <div class="fixed inset-0 z-50 p-4 backdrop-blur-md bg-[#060d1a]/60 flex items-center justify-center transition-all duration-300 {{ $showModal ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
            <div class="absolute inset-0" wire:click="closeModal"></div>

            <div class="w-full max-w-5xl rounded-3xl shadow-2xl border flex flex-col max-h-[90vh] relative z-10 overflow-hidden transform transition-all {{ $showModal ? 'scale-100 translate-y-0' : 'scale-95 translate-y-10' }}"
                 style="background: #0a1628; border-color: rgba(255,255,255,0.1); box-shadow: 0 0 80px rgba(0,0,0,0.6);">
                
                @if ($selectedApp)
                    <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/5 shrink-0">
                        <div class="flex items-center gap-4">
                            <span class="bg-cyan-500/20 text-cyan-400 font-mono text-xs font-black px-3 py-1.5 rounded-xl border border-cyan-500/30">#{{ $selectedApp->id }}</span>
                            <h2 class="text-xl font-black text-white uppercase tracking-tight">Applications</h2>
                        </div>
                        <button wire:click="closeModal" class="text-white/20 hover:text-white transition focus:outline-none">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-8 overflow-y-auto custom-scrollbar flex-grow">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            <!-- Sidebar Info -->
                            <div class="lg:col-span-1 space-y-8">
                                <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                                    <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 px-1">Program Choice</h3>
                                    <div class="space-y-4">
                                        <div class="text-3xl font-black text-white tracking-tighter">{{ $selectedApp->course_code }}</div>
                                        <div class="text-xs font-bold text-white/40 uppercase tracking-widest">{{ $selectedApp->year_level }}</div>
                                        <div class="pt-4 border-t border-white/5">
                                            <span class="text-[10px] font-black text-white/20 uppercase tracking-widest block mb-2">Current Status</span>
                                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-lg {{ $badgeStyle }}">
                                                {{ $displayText }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-2">
                                    <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        Guardian Details
                                    </h3>
                                    <div class="space-y-4">
                                        <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1">Father</span><span class="text-sm font-bold text-white uppercase">{{ $selectedApp->father_name ?? 'N/A' }}</span></div>
                                        <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1">Mother</span><span class="text-sm font-bold text-white uppercase">{{ $selectedApp->mother_maiden_name ?? 'N/A' }}</span></div>
                                        <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1">Emergency Contact</span><span class="text-sm font-bold text-cyan-300 uppercase">{{ $selectedApp->guardian_name ?? 'N/A' }}</span></div>
                                        <div><span class="block text-[10px] text-white/30 uppercase font-black mb-1">Contact Phone</span><span class="text-sm font-mono text-white tracking-widest">{{ $selectedApp->guardian_contact ?? 'N/A' }}</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Content Area -->
                            <div class="lg:col-span-2 space-y-10">
                                <div>
                                    <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Biographical Data
                                    </h3>
                                    <div class="grid grid-cols-2 gap-8 p-8 rounded-3xl bg-white/[0.02] border border-white/5">
                                        <div><span class="block text-[10px] text-white/20 uppercase font-black mb-1">Given Name</span><span class="text-base font-black text-white uppercase tracking-tight">{{ $selectedApp->first_name }} {{ $selectedApp->middle_name }}</span></div>
                                        <div><span class="block text-[10px] text-white/20 uppercase font-black mb-1">Surname</span><span class="text-base font-black text-white uppercase tracking-tight">{{ $selectedApp->last_name }}</span></div>
                                        <div><span class="block text-[10px] text-white/20 uppercase font-black mb-1">Electronic Mail</span><span class="text-sm font-bold text-cyan-400 lowercase italic">{{ $selectedApp->email }}</span></div>
                                        <div><span class="block text-[10px] text-white/20 uppercase font-black mb-1">Birth Date</span><span class="text-sm font-bold text-white uppercase">{{ $selectedApp->birth_date }} ({{ $selectedApp->age }} yrs)</span></div>
                                        <div class="col-span-2"><span class="block text-[10px] text-white/20 uppercase font-black mb-1">Residential Address</span><span class="text-sm font-medium text-white/60 leading-relaxed uppercase">{{ $selectedApp->address_full }}</span></div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Supporting Documentation
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach ([['key' => 'form_138_path', 'label' => 'FORM 138'], ['key' => 'good_moral_path', 'label' => 'GOOD MORAL'], ['key' => 'psa_path', 'label' => 'PSA BIRTH'], ['key' => 'id_picture_path', 'label' => 'ID PHOTO']] as $doc)
                                            @php $hasFile = !empty($selectedApp[$doc['key']]); @endphp
                                            <div class="space-y-3 group/doc">
                                                <div class="flex items-center gap-2">
                                                    @if($hasFile)
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                                                        <span class="text-[9px] font-black uppercase text-white/60 tracking-tighter">{{ $doc['label'] }}</span>
                                                    @else
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.8)]"></span>
                                                        <span class="text-[9px] font-black uppercase text-rose-500/60 tracking-tighter">{{ $doc['label'] }}</span>
                                                    @endif
                                                </div>

                                                @if ($hasFile)
                                                    @php
                                                        $fileUrl = \Storage::disk('public')->url($selectedApp[$doc['key']]);
                                                        $isImage = preg_match('/\.(jpeg|jpg|png|gif|webp)$/i', $selectedApp[$doc['key']]);
                                                    @endphp
                                                    <a href="{{ $fileUrl }}" target="_blank" class="block relative overflow-hidden rounded-xl border border-white/10 aspect-[3/4] group-hover/doc:border-cyan-500/50 transition-all shadow-inner bg-white/5">
                                                        @if ($isImage)
                                                            <img src="{{ $fileUrl }}" class="w-full h-full object-cover grayscale group-hover/doc:grayscale-0 transition-all duration-500">
                                                        @else
                                                            <div class="w-full h-full flex flex-col items-center justify-center text-cyan-400 opacity-40 group-hover/doc:opacity-100 transition-opacity">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                                <span class="text-[8px] font-black mt-2">VIEW PDF</span>
                                                            </div>
                                                        @endif
                                                        <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/doc:opacity-100 transition-opacity flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="aspect-[3/4] rounded-xl bg-rose-500/5 border border-dashed border-rose-500/20 flex flex-col items-center justify-center opacity-40">
                                                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        <span class="text-[8px] font-black text-rose-500 mt-2 uppercase tracking-widest">Missing</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-6 border-t border-white/5 bg-white/5 flex justify-between items-center shrink-0">
                        @if ($selectedApp->status === 'Pending')
                            <div class="flex gap-4">
                                <button wire:click="approveApplication({{ $selectedApp->id }})" 
                                    class="bg-emerald-500 hover:bg-emerald-400 text-white px-8 py-3 rounded-xl text-xs font-black transition shadow-lg shadow-emerald-500/20 uppercase tracking-widest">
                                    Approve Candidate
                                </button>
                                <button wire:click="rejectApplication({{ $selectedApp->id }})" 
                                    class="bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white px-8 py-3 rounded-xl text-xs font-black transition uppercase tracking-widest">
                                    Reject Application
                                </button>
                            </div>
                        @else
                            <div class="text-[10px] font-black text-white/20 uppercase tracking-widest">This record is {{ $selectedApp->status }} and cannot be modified.</div>
                        @endif
                        <button wire:click="closeModal" class="px-8 py-3 text-xs font-black text-white/40 hover:text-white uppercase tracking-widest transition">Close Panel</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
