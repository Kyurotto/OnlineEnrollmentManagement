<x-layouts.student title="Upload Documents">
<div class="space-y-6">
    <div class="max-w-5xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Upload Documents</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">Enrollment Verification</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-white/40 hover:text-white transition-colors uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-6 rounded-2xl shadow-xl shadow-emerald-900/10 w-full mb-6 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-bold">Success!</p>
                    <p class="text-sm opacity-80">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 p-6 rounded-2xl shadow-xl shadow-blue-900/10 w-full mb-6">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('student.enrollment.upload.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 pb-12">
            @csrf

            {{-- Document Requirements --}}
            <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
                 style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-white/5 pb-6">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-emerald-400 rounded-full"></span>
                            Document Verification
                        </h3>
                        <p class="text-xs font-bold text-white/20 uppercase tracking-widest italic mt-1">Upload high-resolution assets for verification</p>
                    </div>
                    <div class="bg-white/5 px-4 py-2 rounded-lg border border-white/10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/40 mr-2">Level:</span>
                        <span class="text-xs font-bold text-emerald-400 uppercase">{{ $enrollment->level }}</span>
                    </div>
                </div>

                @php
                    $docs = ($enrollment->level === 'shs') ? [
                        ['model' => 'form_137', 'label' => 'JHS Report Card (SF9)', 'desc' => 'Original SF9 with school seal and signature', 'path' => $enrollment->form_137_path],
                        ['model' => 'sf10', 'label' => 'SF10 (Permanent Record)', 'desc' => 'Certified copy of SF10', 'path' => $enrollment->sf10_path],
                        ['model' => 'good_moral', 'label' => 'Certificate of Good Moral', 'desc' => 'Optional - from previous school', 'path' => $enrollment->good_moral_path],
                        ['model' => 'id_picture', 'label' => '2x2 ID Portrait', 'desc' => 'White background, formal attire', 'path' => $enrollment->id_picture_path],
                        ['model' => 'psa', 'label' => 'PSA Birth Certificate', 'desc' => 'Authenticated copy of birth certificate', 'path' => $enrollment->psa_path]
                    ] : [
                        ['model' => 'form_137', 'label' => 'Form 137 (Report Card)', 'desc' => "Original copy with principal's signature", 'path' => $enrollment->form_137_path],
                        ['model' => 'good_moral', 'label' => 'Certificate of Good Moral', 'desc' => 'Issued by your previous institution', 'path' => $enrollment->good_moral_path],
                        ['model' => 'psa', 'label' => 'PSA Birth Certification', 'desc' => 'Clear copy of the original PSA document', 'path' => $enrollment->psa_path],
                        ['model' => 'id_picture', 'label' => '2x2 ID Portrait', 'desc' => 'White background, formal attire', 'path' => $enrollment->id_picture_path]
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($docs as $doc)
                        <div class="bg-white/5 p-6 rounded-2xl border border-white/10 group transition-all duration-300 hover:border-emerald-400/30 {{ $doc['path'] ? 'ring-1 ring-emerald-500/20 bg-emerald-500/5' : '' }}">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-sm font-bold text-white tracking-tight">{{ $doc['label'] }}</h4>
                                    <p class="text-xs text-white/30 mt-0.5">{{ $doc['desc'] }}</p>
                                </div>
                                @if($doc['path'])
                                    <span class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-wider">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Uploaded
                                    </span>
                                @endif
                            </div>
                            
                            <label for="file-{{ $doc['model'] }}" class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-white/10 border-dashed rounded-xl cursor-pointer bg-white/5 hover:bg-emerald-500/5 transition-all group overflow-hidden">
                                @if($doc['path'])
                                    <div class="absolute inset-0 z-0 opacity-20 transition-opacity group-hover:opacity-10 blur-[1px]">
                                        @if(Str::endsWith($doc['path'], ['.pdf']))
                                            <div class="flex items-center justify-center h-full bg-white/5 uppercase font-black text-white/20 text-[10px]">PDF DOCUMENT</div>
                                        @else
                                            <img src="{{ asset('storage/' . $doc['path']) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @endif

                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4 relative z-10 transition-transform group-hover:scale-105">
                                    <svg class="w-7 h-7 mb-3 {{ $doc['path'] ? 'text-emerald-400' : 'text-emerald-400/60' }} group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                    <p class="mb-1 text-xs text-white/40 leading-tight">
                                        <span class="font-bold text-white/60">{{ $doc['path'] ? 'Update Document' : 'Initialize Upload' }}</span>
                                    </p>
                                    <p class="text-[10px] text-white/20">PNG, JPG or PDF (Max 5MB)</p>
                                    <p class="text-[10px] text-emerald-400 mt-2 hidden" id="feedback-{{ $doc['model'] }}">File Selected</p>
                                </div>
                                <input type="file" id="file-{{ $doc['model'] }}" name="{{ $doc['model'] }}" class="sr-only" accept="image/*,application/pdf" onchange="document.getElementById('feedback-{{ $doc['model'] }}').classList.remove('hidden')" />
                            </label>
                            
                            @if($doc['path'])
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="text-[10px] font-bold text-emerald-400/60 hover:text-emerald-400 uppercase tracking-widest flex items-center gap-1.5 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View Current
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Error Handling & Submission --}}
            <div class="flex flex-col items-end gap-4">
                @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-6 rounded-2xl shadow-xl shadow-rose-900/10 w-full animate-in slide-in-from-bottom-2">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="font-black text-sm uppercase tracking-widest leading-none">File Upload Errors</p>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <button type="submit"
                    class="bg-emerald-500 hover:bg-emerald-400 text-black font-black py-4 px-12 rounded-2xl shadow-2xl shadow-emerald-500/20 transition-all transform active:scale-95 uppercase tracking-[0.2em] text-xs flex items-center gap-3">
                    Update All Documents
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                </button>
            </div>

        </form>
    </div>
</div>
</x-layouts.student>
