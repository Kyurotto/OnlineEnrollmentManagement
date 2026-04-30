<x-layouts.student title="Review Enrollment Application">
<div class="space-y-8 pb-12">
    <div class="max-w-5xl mx-auto space-y-8 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Review Application</h2>
                <p class="text-[10px] mt-2 font-black uppercase tracking-[0.3em] text-slate-400">Portal Access — Enrollment Records</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-10 py-4 rounded-2xl bg-white border border-slate-100 text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] hover:bg-blue-50 transition-all shadow-xl shadow-blue-900/5 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>
        </div>

        {{-- Status Alert --}}
        <div class="p-8 rounded-[2rem] border flex items-center gap-6 bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">
            <div class="p-4 rounded-2xl {{ $enrollment->status === 'Enrolled' || $enrollment->status === 'Approved' ? 'bg-emerald-50 text-emerald-600' : ($enrollment->status === 'Pending' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }} flex-shrink-0 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-black text-xl text-slate-900 mb-1">Application Status: <span class="uppercase {{ $enrollment->status === 'Enrolled' || $enrollment->status === 'Approved' ? 'text-emerald-600' : ($enrollment->status === 'Pending' ? 'text-amber-600' : 'text-rose-600') }}">{{ $enrollment->status }}</span></h3>
                <p class="text-sm text-slate-500 font-medium">
                    @if($enrollment->status === 'Pending')
                        Your application is under review. Please wait for updates from the registrar.
                    @elseif($enrollment->status === 'Approved')
                        Your application has been approved. Proceed to payment to complete enrollment.
                    @elseif($enrollment->status === 'Enrolled')
                        You are successfully enrolled for this academic year.
                    @else
                        Your application has been rejected. Please contact the registrar for more information.
                    @endif
                </p>
            </div>
        </div>

        {{-- Important Notice --}}
        <div class="p-8 rounded-[2rem] border flex items-start gap-6 bg-amber-50/50"
             style="border-color: rgba(245,158,11,0.2);">
            <div class="p-4 rounded-2xl bg-amber-50 text-amber-600 flex-shrink-0 mt-1 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-black text-amber-700 mb-2 text-base">Registrar Correction Policy</h4>
                <p class="text-sm text-amber-600/80 font-medium leading-relaxed">If there are any errors or misspellings in your information, please proceed to the <strong class="text-amber-700">School Registrar</strong> directly for manual correction. Only submit an edit request if specifically instructed by staff.</p>
            </div>
        </div>

        {{-- Academic Information --}}
        <div class="p-10 rounded-[2.5rem] border bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">

            <h3 class="text-xl font-black text-slate-900 mb-10 flex items-center gap-4">
                <div class="w-1.5 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/20"></div>
                Academic Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Enrollment Level</label>
                    <p class="text-lg font-black text-slate-800 capitalize">{{ $enrollment->getLevel() }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">{{ $enrollment->isSHS() ? 'Strand' : 'Program' }}</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->course_code }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Year Level</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->year_level }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Semester</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->semester }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Academic Year</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->academic_year }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Submission Date</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Student Information --}}
        <div class="p-10 rounded-[2.5rem] border bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">

            <h3 class="text-xl font-black text-slate-900 mb-10 flex items-center gap-4">
                <div class="w-1.5 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-600/20"></div>
                Personal Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Full Name</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->first_name }} {{ $enrollment->middle_name }} {{ $enrollment->last_name }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Birth Date</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->birth_date ? \Carbon\Carbon::parse($enrollment->birth_date)->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Gender</label>
                    <p class="text-lg font-black text-slate-800 capitalize">{{ $enrollment->gender ?? 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Email Address</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->email ?? 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Contact Number</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->contact ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Address Information --}}
        <div class="p-10 rounded-[2.5rem] border bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">

            <h3 class="text-xl font-black text-slate-900 mb-10 flex items-center gap-4">
                <div class="w-1.5 h-8 bg-teal-500 rounded-full shadow-lg shadow-teal-500/20"></div>
                Residential Address
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Full Address</label>
                    <p class="text-lg font-black text-slate-800 leading-tight">
                        {{ $enrollment->prk_blk_lot_vill }}, {{ $enrollment->barangay }}, {{ $enrollment->city }}, {{ $enrollment->province }} {{ $enrollment->zip }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Parent/Guardian Information --}}
        <div class="p-10 rounded-[2.5rem] border bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">

            <h3 class="text-xl font-black text-slate-900 mb-10 flex items-center gap-4">
                <div class="w-1.5 h-8 bg-rose-500 rounded-full shadow-lg shadow-rose-500/20"></div>
                Family Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Father's Name</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->father_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Mother's Maiden Name</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->mother_maiden_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Guardian's Name</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->guardian_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Emergency Contact</label>
                    <p class="text-lg font-black text-slate-800">{{ $enrollment->guardian_contact ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Edit Request Action --}}
        @if(!in_array($enrollment->status, ['Enrolled', 'Paid']))
        <div class="p-10 rounded-[2.5rem] border bg-white shadow-xl shadow-blue-900/5"
             style="border-color: rgba(37,99,235,0.1);">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Request Edit Access</h3>
                    <p class="text-sm text-slate-500 font-medium">Need to correct something? Request permission to unlock your application form.</p>
                </div>
                
                @if($enrollment->edit_request_status === 'Approved')
                    <a href="{{ route('student.enrollment.edit') }}" class="px-10 py-5 bg-blue-600 text-white rounded-2xl font-black uppercase text-[11px] tracking-widest shadow-xl shadow-blue-600/30 hover:bg-blue-700 transition-all active:scale-95">
                        Edit Application Now
                    </a>
                @elseif($enrollment->edit_request_status === 'Pending')
                    <div class="px-10 py-5 bg-amber-50 text-amber-600 border border-amber-100 rounded-2xl font-black uppercase text-[11px] tracking-widest flex items-center gap-3">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Request Pending
                    </div>
                @else
                    <form action="{{ route('student.enrollment.request_edit') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-10 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase text-[11px] tracking-widest hover:bg-slate-800 transition-all active:scale-95 shadow-xl shadow-slate-900/20">
                            Submit Edit Request
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-6 pt-6">
            <a href="{{ route('student.dashboard') }}" class="flex-1 flex items-center justify-center gap-3 px-10 py-5 rounded-2xl bg-white border border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] hover:bg-slate-50 transition-all shadow-xl shadow-blue-900/5 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
            @if($enrollment->status === 'Pending')
            <div class="flex-1 flex items-center justify-center px-10 py-5 rounded-2xl font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest text-[11px]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Awaiting Registrar Review
            </div>
            @elseif($enrollment->status === 'Approved')
            <a href="{{ route('student.payment') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-2xl font-black transition-all text-center uppercase tracking-widest text-[11px] shadow-xl shadow-emerald-600/30 active:scale-95">
                Proceed to Payment Gate
            </a>
            @endif
        </div>

    </div>
</div>
</x-layouts.student>
