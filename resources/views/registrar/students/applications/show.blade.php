<x-layouts.registrar title="Application Details">
    <div class="max-w-5xl mx-auto animate-in fade-in slide-in-from-bottom-8 duration-700">
        <div class="mb-10 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('registrar.applications.index') }}" class="group/back flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all active:scale-95 shadow-xl">
                    <svg class="w-5 h-5 group-hover/back:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight uppercase italic">Application Details</h2>
                    <p class="text-[10px] text-white/30 uppercase tracking-[0.3em] mt-1 italic">Review Process • ID #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                <div class="hidden md:flex items-center gap-4">
                    <form action="{{ route('registrar.applications.toggle-physical', $application->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all active:scale-95 shadow-xl {{ $application->physical_documents_received ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-cyan-500 text-black' }}">
                            {{ $application->physical_documents_received ? 'Cancel Physical Receipt' : 'Received Physical Documents' }}
                        </button>
                    </form>
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/10 active:scale-95">
                            Approve
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="glass-card rounded-[48px] border-white/5 shadow-2xl overflow-hidden relative group">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-500/10 blur-[100px] rounded-full group-hover:bg-cyan-500/15 transition-colors duration-1000"></div>

            <div class="p-10 md:p-14 relative z-10 space-y-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    {{-- Student Information --}}
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Student Profile</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-8 bg-white/[0.02] border border-white/5 rounded-[40px] p-10">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                <span class="text-xl font-black text-white uppercase italic tracking-tight">{{ $application->user?->last_name ?? 'N/A' }}, {{ $application->user?->first_name ?? 'N/A' }} {{ $application->user?->middle_name ?? '' }}</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Application ID</span>
                                    <span class="text-sm font-bold text-white/60 lowercase">{{ $application->user?->email ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Submitted On</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->user?->birth_date ?? 'N/A' }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Age</span>
                                    <span class="text-sm font-bold text-white/60">{{ $application->user?->age ?? 'N/A' }} Years</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Program & Lifecycle --}}
                    <div class="space-y-8">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Program Details</h3>
                        </div>
                        <div class="bg-cyan-500/5 border border-cyan-500/10 rounded-[40px] p-10 space-y-10 h-full flex flex-col justify-center shadow-inner">
                            <div class="flex flex-col gap-2">
                                <span class="text-[9px] font-black text-cyan-400 uppercase tracking-widest italic">Program</span>
                                <span class="text-3xl font-black text-white uppercase italic tracking-tighter leading-tight">{{ $application->course_code ?? 'N/A' }}</span>
                                <span class="text-[10px] font-bold text-white/30 uppercase tracking-wider">{{ $application->course?->course_description ?? '' }}</span>
                            </div>
                            <div class="flex items-center gap-12 pt-8 border-t border-cyan-500/10">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Year Level</span>
                                    <span class="text-lg font-black text-white uppercase italic">{{ $application->year_level }}</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Status</span>
                                    <span class="text-lg font-black text-cyan-400 uppercase tracking-[0.1em] italic">{{ $application->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Document Assets --}}
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Required Documents</h3>
                        </div>
                        @php
                            $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                        @endphp
                        <span class="px-3 py-1 text-[9px] font-bold rounded-full border {{ $isSHS ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-blue-500/10 text-blue-400 border-blue-500/30' }}">
                            {{ $isSHS ? 'SHS Documents' : 'College Documents' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @php
                            $isSHS = in_array($application->course_code, ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
                            $docs = $isSHS
                                ? [
                                    'form_137_path' => 'JHS Card (SF9)',
                                    'sf10_path' => 'SF10 Record',
                                    'good_moral_path' => 'Good Moral',
                                    'psa_path' => 'PSA Birth Cert',
                                    'id_picture_path' => '2x2 ID Picture'
                                ]
                                : [
                                    'form_137_path' => 'Form 137',
                                    'good_moral_path' => 'Good Moral',
                                    'psa_path' => 'PSA Birth Cert',
                                    'id_picture_path' => 'ID Picture'
                                ];
                        @endphp

                        @foreach ($docs as $path => $label)
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    @if (!empty($application->$path))
                                        <div class="flex items-center justify-center w-5 h-5 bg-emerald-500/20 border-2 border-emerald-500 rounded-full shrink-0">
                                            <span class="text-emerald-500 font-black text-xs">✓</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase text-white tracking-widest">{{ $label }}</span>
                                    @else
                                        <div class="flex items-center justify-center w-5 h-5 bg-rose-500/20 border-2 border-rose-500 rounded-full shrink-0">
                                            <span class="text-rose-500 font-black text-xs">!</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase text-rose-500 tracking-widest">{{ $label }}</span>
                                    @endif
                                </div>

                                @if (!empty($application->$path))
                                    @php
                                        $fileUrl = \Storage::disk('public')->url($application->$path);
                                        $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $application->$path);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" class="group/asset block relative overflow-hidden rounded-[32px] border border-white/10 bg-white/[0.03] shadow-xl">
                                        @if($isImage)
                                            <img src="{{ $fileUrl }}" class="w-full h-44 object-cover transition-transform duration-700 group-hover/asset:scale-110">
                                            <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                                <svg class="w-8 h-8 text-white scale-75 group-hover/asset:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-full h-44 flex flex-col items-center justify-center text-cyan-400/40 group-hover/asset:text-cyan-400 transition-colors">
                                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-[8px] font-black uppercase tracking-[0.4em]">VIEW PDF</span>
                                            </div>
                                        @endif
                                    </a>
                                @else
                                    <div class="w-full h-44 rounded-[32px] bg-rose-500/5 border-2 border-dashed border-rose-500/10 flex flex-col items-center justify-center opacity-40">
                                        <span class="text-[8px] font-black text-rose-500 tracking-[0.4em] italic uppercase">Missing</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Promissory Note Review --}}
                @if($application->promissory_note_path || $application->promissory_reason)
                <div class="space-y-8 pt-10 border-t border-white/5">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Promissory Note & Reasoning</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-amber-500/5 border border-amber-500/10 rounded-[40px] p-10">
                        <div class="lg:col-span-1 space-y-4">
                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Note Attachment</span>
                            @if($application->promissory_note_path)
                                @php
                                    $noteUrl = \Storage::disk('public')->url($application->promissory_note_path);
                                    $isPdf = Str::endsWith($application->promissory_note_path, '.pdf');
                                @endphp
                                <a href="{{ $noteUrl }}" target="_blank" class="group/note block p-6 rounded-3xl border border-amber-500/20 bg-amber-500/5 hover:bg-amber-500/10 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                                            @if($isPdf)
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-white uppercase tracking-wider">Download Note</p>
                                            <p class="text-[8px] text-amber-500/60 uppercase font-bold mt-0.5">{{ $isPdf ? 'PDF Format' : 'Word Document' }}</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="p-6 rounded-3xl border border-dashed border-white/5 bg-white/[0.02] flex items-center justify-center">
                                    <span class="text-[8px] font-black text-white/20 uppercase tracking-widest italic">No File Attached</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="lg:col-span-2 space-y-4">
                            <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic">Student's Explanation</span>
                            <div class="p-8 rounded-3xl bg-white/[0.03] border border-white/5 min-h-[100px]">
                                <p class="text-xs text-white/70 leading-relaxed italic">
                                    {{ $application->promissory_reason ?? 'No explanation provided.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex md:hidden flex-col gap-4 pt-10">
                    @if (in_array(strtolower($application->status), ['pending', 'enrolled', 'paid']))
                        <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Approved">
                            <button type="submit" class="w-full bg-emerald-500 text-white text-[10px] font-black py-5 px-10 rounded-2xl uppercase tracking-[0.2em]">
                                Approve
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.registrar>
