<x-layouts.student title="Review Enrollment Application">
<div class="space-y-6">
    <div class="max-w-5xl mx-auto space-y-6 animate-in fade-in duration-700">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Review Enrollment Application</h2>
                <p class="text-xs mt-2 font-medium uppercase tracking-[0.2em]" style="color: rgba(255,255,255,0.4);">View Your Submitted Application</p>
            </div>
        </div>

        {{-- Status Alert --}}
        <div class="p-6 rounded-2xl border flex items-center gap-4"
             style="background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.2); box-shadow: 0 4px 20px rgba(251,191,36,0.1);">
            <div class="p-3 rounded-xl {{ $enrollment->status === 'Enrolled' || $enrollment->status === 'Approved' ? 'bg-emerald-500/20' : ($enrollment->status === 'Pending' ? 'bg-amber-500/20' : 'bg-rose-500/20') }} flex-shrink-0">
                <svg class="w-6 h-6 {{ $enrollment->status === 'Enrolled' || $enrollment->status === 'Approved' ? 'text-emerald-400' : ($enrollment->status === 'Pending' ? 'text-amber-400' : 'text-rose-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-white mb-0.5">Application Status: <span class="uppercase {{ $enrollment->status === 'Enrolled' || $enrollment->status === 'Approved' ? 'text-emerald-400' : ($enrollment->status === 'Pending' ? 'text-amber-400' : 'text-rose-400') }}">{{ $enrollment->status }}</span></h3>
                <p class="text-xs text-white/60">
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
        <div class="p-6 rounded-2xl border flex items-start gap-4 bg-amber-500/5 group relative"
             style="border-color: rgba(251,191,36,0.3);">
            <div class="p-2 rounded-lg bg-amber-500/20 text-amber-400 flex-shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-amber-200 mb-1 text-sm">Need a Request at Registrar to Edit Your Enrollment Form</h4>
                <p class="text-xs text-white/70">If there are any errors or misspellings in your information, please proceed to the <strong>School Registrar</strong> directly for manual correction. Do not attempt to resubmit the form.</p>
            </div>
        </div>

        {{-- Academic Information --}}
        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

            <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 rounded-full" style="background-color: {{ $enrollment->isSHS() ? '#10B981' : '#3B82F6' }};"></span>
                Academic Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Enrollment Level</label>
                    <p class="text-white font-semibold capitalize">{{ $enrollment->getLevel() }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">{{ $enrollment->isSHS() ? 'Strand' : 'Program' }}</label>
                    <p class="text-white font-semibold">{{ $enrollment->course_code }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Year Level</label>
                    <p class="text-white font-semibold">{{ $enrollment->year_level }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Semester</label>
                    <p class="text-white font-semibold">{{ $enrollment->semester }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Academic Year</label>
                    <p class="text-white font-semibold">{{ $enrollment->academic_year }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Submission Date</label>
                    <p class="text-white font-semibold">{{ $enrollment->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>

        {{-- Student Information --}}
        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

            <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-purple-400 rounded-full"></span>
                Student Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Last Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->last_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">First Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->first_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Middle Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->middle_name ?? 'N/A' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Birth Date</label>
                    <p class="text-white font-semibold">{{ $enrollment->birth_date ? \Carbon\Carbon::parse($enrollment->birth_date)->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Age</label>
                    <p class="text-white font-semibold">{{ $enrollment->age ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Gender</label>
                    <p class="text-white font-semibold capitalize">{{ $enrollment->gender ?? 'N/A' }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Email</label>
                    <p class="text-white font-semibold">{{ $enrollment->email ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Contact Number</label>
                    <p class="text-white font-semibold">{{ $enrollment->contact ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Address Information --}}
        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

            <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-teal-400 rounded-full"></span>
                Address Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Prk. / Blk. Lot / Vill.</label>
                    <p class="text-white font-semibold">{{ $enrollment->prk_blk_lot_vill ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Barangay</label>
                    <p class="text-white font-semibold">{{ $enrollment->barangay ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">City / Municipality</label>
                    <p class="text-white font-semibold">{{ $enrollment->city ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Province</label>
                    <p class="text-white font-semibold">{{ $enrollment->province ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">ZIP / Postal Code</label>
                    <p class="text-white font-semibold">{{ $enrollment->zip ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Parent/Guardian Information --}}
        <div class="p-8 rounded-2xl border shadow-2xl shadow-black/40"
             style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10);">

            <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-pink-400 rounded-full"></span>
                Parent / Guardian Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Father's Full Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->father_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Mother's Maiden Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->mother_maiden_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Guardian's Full Name</label>
                    <p class="text-white font-semibold">{{ $enrollment->guardian_name ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/40 uppercase tracking-widest ml-1">Guardian Contact Number</label>
                    <p class="text-white font-semibold">{{ $enrollment->guardian_contact ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Edit Request Action (Livewire) --}}
        @livewire('student-profile-manager', ['context' => 'enrollment-actions'])

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="{{ route('student.dashboard') }}" class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-bold border border-white/20 transition-all text-center">
                Back to Dashboard
            </a>
            @if($enrollment->status === 'Pending')
            <div class="flex-1 flex items-center justify-center px-6 py-3 rounded-xl font-bold bg-amber-500/10 text-amber-300 border border-amber-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Awaiting Review
            </div>
            @elseif($enrollment->status === 'Approved')
            <a href="{{ route('student.payment') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold transition-all text-center">
                Proceed to Payment
            </a>
            @endif
        </div>

    </div>
</div>
</x-layouts.student>
