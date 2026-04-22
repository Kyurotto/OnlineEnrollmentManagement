<x-layouts.registrar title="Enrollment Applications">
    <div class="space-y-6 animate-in fade-in duration-500">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-xl backdrop-blur-md flex items-center gap-3 mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        @endif

        <div class="glass-card rounded-[32px] overflow-hidden border-white/5 shadow-2xl shadow-black/40">
            <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase italic">Application Request</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1"></p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-cyan-500/5">
                        {{ $pendingCount }} Pending Approval
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse font-bold">
                    <thead>
                        <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] border-b border-white/5 bg-white/[0.01]">
                            <th class="py-6 px-8 text-left">ID</th>
                            <th class="py-6 px-8 text-left">Full Name</th>
                            <th class="py-6 px-8 text-left">Account Details</th>
                            <th class="py-6 px-8 text-left">Program</th>
                            <th class="py-6 px-8 text-left">Date</th>
                            <th class="py-6 px-8 text-center">Status</th>
                            <th class="py-6 px-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($applications as $application)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider font-bold">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</span>
                                        @if($application->extension)
                                        <span class="text-sm font-bold text-white/40 italic">{{ $application->extension }}</span>
                                        @endif
                                    </div>
                                    <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Applicant Profile</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-white/40 lowercase tracking-tight">{{ $application->email }}</td>
                            <td class="py-6 px-8 whitespace-nowrap">
                                <span class="text-cyan-400 font-black uppercase text-[10px] tracking-widest">{{ $application->course_code }}</span>
                                <span class="text-white/20 text-[9px] ml-1 font-bold">({{ $application->year_level }})</span>
                            </td>
                            <td class="py-6 px-8 text-white/30 font-medium italic tracking-tight">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="py-6 px-8">
                                @php
                                $badgeColor = match(ucfirst($application->status)) {
                                    'Approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'Enrolled','Paid' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                    'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                    'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    default => 'bg-white/5 text-white/40 border-white/10',
                                };
                                $displayText = ucfirst($application->status);
                                if (in_array($displayText, ['Enrolled', 'Paid'])) { $displayText = 'Paid'; }
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeColor }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <!-- Test Button -->
                                    <button style="background-color: #9333ea; color: white; padding: 10px 16px; border-radius: 8px; border: 1px solid #a855f7; font-weight: bold; font-size: 11px;">
                                        ★ VOUCHER TEST
                                    </button>

                                    <!-- Voucher Dropdown Button -->
                                    <div class="relative group/voucherTable">
                                        <button type="button" class="px-4 py-2.5 rounded-lg bg-purple-600 text-white hover:bg-purple-500 transition-all text-[9px] font-black uppercase tracking-widest shadow-lg border border-purple-400">
                                            ★ Voucher
                                        </button>
                                        <div class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-700 rounded-lg shadow-2xl opacity-0 invisible group-hover/voucherTable:opacity-100 group-hover/voucherTable:visible transition-all z-50">
                                            @if($application->voucher_type)
                                                <div class="p-2 border-b border-gray-700">
                                                    <p class="text-[8px] text-gray-400 uppercase mb-1 font-bold">Current:</p>
                                                    <span class="text-[9px] font-black {{ $application->voucher_type === 'free_tuition' ? 'text-green-400' : 'text-yellow-400' }}">
                                                        {{ $application->voucher_type === 'free_tuition' ? '🟢 Free Tuition' : '🟡 Discounted' }}
                                                    </span>
                                                </div>
                                            @endif
                                            <button onclick="applyVoucherDirect({{ $application->id }}, 'free_tuition')" class="w-full text-left px-3 py-2 text-[9px] font-bold text-green-400 hover:bg-green-900/30">🟢 Free Tuition</button>
                                            <button onclick="applyVoucherDirect({{ $application->id }}, 'discounted')" class="w-full text-left px-3 py-2 text-[9px] font-bold text-yellow-400 hover:bg-yellow-900/30 border-t border-gray-700">🟡 Discounted</button>
                                            <button onclick="removeVoucherDirect({{ $application->id }})" class="w-full text-left px-3 py-2 text-[9px] font-bold text-red-400 hover:bg-red-900/30 border-t border-gray-700">✕ Remove</button>
                                        </div>
                                    </div>

                                    <button type="button" data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn shadow-lg shadow-black/20">
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] italic">No Applications Found</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                @if(method_exists($applications, 'links'))
                    {{ $applications->links('pagination') }}
                @endif
            </div>
        </div>
    </div>

    {{-- Universal Application Analysis Modal --}}
    <div id="applicationModal" class="fixed inset-0 z-[100] hidden opacity-0 pointer-events-none transition-all duration-300 items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#060d1a]/90 backdrop-blur-2xl" onclick="closeModal()"></div>

        <div class="bg-[#0d1f3c] w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden flex flex-col max-h-[95vh] relative z-10 transform scale-95 transition-all duration-300" id="modalContent">

            <div class="px-8 md:px-12 py-8 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div class="flex items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-white italic uppercase tracking-tight" id="modalTitle">Application Details</h2>
                        <p class="text-[9px] text-white/30 uppercase tracking-[0.3em] mt-1 italic">Review Process</p>
                    </div>

                    <!-- VOUCHER BUTTON HERE -->
                    <div class="relative group/voucher">
                        <button class="px-5 py-3 bg-purple-600 hover:bg-purple-500 text-white font-black text-[11px] uppercase rounded-lg flex items-center gap-2 transition-all shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                            Voucher
                        </button>

                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-56 bg-gray-900 border border-gray-700 rounded-lg shadow-2xl opacity-0 invisible group-hover/voucher:opacity-100 group-hover/voucher:visible transition-all z-50">
                            <div id="voucherStatus" class="p-3 border-b border-gray-700 hidden">
                                <p class="text-[8px] font-bold text-gray-400 uppercase mb-2">Current</p>
                                <div id="voucherBadge" class="flex gap-1 items-center p-2 rounded">
                                    <span id="voucherLabel" class="text-[9px] font-bold uppercase"></span>
                                </div>
                            </div>
                            <button onclick="removeVoucher()" class="w-full text-left px-4 py-2 text-[9px] font-bold text-red-400 hover:bg-red-900/30">Remove</button>
                            <button onclick="applyVoucher('free_tuition')" class="w-full text-left px-4 py-2 text-[9px] font-bold text-green-400 hover:bg-green-900/30">🟢 Free Tuition</button>
                            <button onclick="applyVoucher('discounted')" class="w-full text-left px-4 py-2 text-[9px] font-bold text-yellow-400 hover:bg-yellow-900/30 border-t border-gray-700">🟡 Discounted</button>
                        </div>
                    </div>
                </div>

                <button onclick="closeModal()" class="w-10 h-10 rounded-lg bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-8 md:p-12 overflow-y-auto custom-scrollbar flex-grow space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {{-- Student Information --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Student Profile</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6 bg-white/[0.02] border border-white/5 rounded-[32px] p-8">
                            <div class="grid grid-cols-2 gap-8">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                    <span class="text-xs font-bold text-cyan-400 capitalize" id="modalNameValue"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Email Address</span>
                                    <span class="text-xs font-bold text-white/60 lowercase" id="modalEmail"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Application ID</span>
                                    <span class="text-xs font-bold text-white/40 font-mono tracking-tighter" id="modalAppId"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Submitted On</span>
                                    <span class="text-xs font-bold text-white/60" id="modalSubmitted"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Date of Birth</span>
                                    <span class="text-xs font-bold text-white/60" id="modalDob"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Age</span>
                                    <span class="text-xs font-bold text-white/60" id="modalAge"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Gender</span>
                                    <span class="text-xs font-bold text-white/60 capitalize" id="modalGender"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Address</span>
                                    <span class="text-xs font-bold text-white/60" id="modalAddress"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Program & Lifecycle --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Program Details</h3>
                        </div>
                        <div class="bg-cyan-500/5 border border-cyan-500/10 rounded-[32px] p-8 space-y-8 h-full flex flex-col justify-center">
                            <div class="flex flex-col gap-2">
                                <span class="text-[9px] font-black text-cyan-400 uppercase tracking-widest italic">Program</span>
                                <span class="text-2xl font-black text-white uppercase italic tracking-tighter" id="modalCourse"></span>
                            </div>
                            <div class="flex items-center gap-10 pt-6 border-t border-cyan-500/10">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Year Level</span>
                                    <span class="text-sm font-black text-white uppercase" id="modalYear"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Status</span>
                                    <span class="text-sm font-black text-cyan-400 uppercase tracking-[0.1em]" id="modalStatus"></span>
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
            </div>

            <div class="px-8 md:px-12 py-8 border-t border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-center gap-6">
                <div id="actionButtons" class="items-center gap-4 hidden">
                    <form id="approveForm" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/10 active:scale-95">
                            Approve
                        </button>
                    </form>
            <div class="px-8 md:px-12 py-8 border-t border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-center gap-6 shrink-0">
            <div class="flex gap-4" id="actionButtons">
                {{-- Action buttons injected via JS --}}
            </div>
            <button onclick="closeModal()" class="w-full md:w-auto px-10 py-4 text-[10px] font-black text-white/40 hover:text-white uppercase tracking-[0.2em] border border-white/10 rounded-2xl hover:bg-white/5 transition-all ml-auto italic">Close Details</button>
        </div>
        </div>
    </div>

    <script>
    function openModal(app, user, course) {
        currentApplicationId = app.id;
        document.getElementById('modalTitle').innerText = 'Application Details #' + String(app.id).padStart(5, '0');
        const updateRoute = "{{ route('registrar.applications.index') }}/" + app.id;
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
        // Document Assets
        // ... (existing doc logic)

        // Action Buttons
        const actionButtons = document.getElementById('actionButtons');
        if (app.status === 'Pending') {
            actionButtons.innerHTML = `
                <form action="${updateRoute}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Approved">
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-emerald-500/10 active:scale-95">
                        Approve
                    </button>
                </form>
                <form action="${updateRoute}" method="POST" onsubmit="return confirm('Reject this application?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Rejected">
                    <button type="submit" class="bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white px-8 py-4 rounded-2xl text-[10px] font-black transition-all uppercase tracking-[0.2em]">
                        Reject Application
                    </button>
                </form>
            `;
        } else {
            actionButtons.innerHTML = `<div class="text-[10px] font-black text-white/20 uppercase tracking-widest italic tracking-[0.2em]">Status: ${app.status.toUpperCase()}</div>`;
        }

        let courseCode = app.course_code || 'N/A';
        document.getElementById('modalCourse').innerText = courseCode;

        document.getElementById('modalYear').innerText = app.year_level || 'N/A';

        let statusText = app.status;
        if (statusText === 'Enrolled' || statusText === 'Paid') { statusText = 'Paid / Finalized'; }
        document.getElementById('modalStatus').innerText = statusText;

        // Display voucher status
        if (app.voucher_type) {
            updateVoucherDisplay(app.voucher_type);
        } else {
            updateVoucherDisplay(null);
        }

        document.getElementById('modalFather').innerText = app.father_name || 'N/A';
        document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
        document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
        document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

        const docsContainer = document.getElementById('modalDocuments');
        docsContainer.innerHTML = '';

        const documents = [
            { key: 'form_138_path', label: 'Form 138' },
            { key: 'good_moral_path', label: 'Good Moral' },
            { key: 'psa_path', label: 'PSA Birth' },
            { key: 'id_picture_path', label: 'ID Image' }
        ];

        const storageBase = @json(asset('storage')) + '/';

        documents.forEach(doc => {
            const hasFile = app[doc.key] ? true : false;
            let headerHtml = '';
            let boxHtml = '';

            if (hasFile) {
                const fileUrl = storageBase + app[doc.key];
                const isImage = app[doc.key].match(/\.(jpeg|jpg|png|gif|webp)$/i);

                headerHtml = `
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center justify-center w-5 h-5 bg-emerald-500/20 border-2 border-emerald-500 rounded-full shrink-0">
                            <span class="text-emerald-500 font-black text-xs">✓</span>
                        </div>
                        <span class="text-[9px] font-black uppercase text-white tracking-widest">${doc.label}</span>
                    </div>
                `;

                if (isImage) {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
                            <img src="${fileUrl}" class="w-full h-32 object-cover transition-transform duration-500 group-hover/asset:scale-110">
                            <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            </div>
                        </a>
                    `;
                } else {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] h-32 flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 text-cyan-400 opacity-40 group-hover/asset:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span class="text-[8px] font-black text-cyan-400 mt-2 tracking-[0.3em]">VIEW PDF</span>
                        </a>
                    `;
                }
            } else {
                headerHtml = `
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex items-center justify-center w-5 h-5 bg-rose-500/20 border-2 border-rose-500 rounded-full shrink-0">
                            <span class="text-rose-500 font-black text-xs">!</span>
                        </div>
                        <span class="text-[9px] font-black uppercase text-rose-500 tracking-widest">${doc.label}</span>
                    </div>
                `;

                boxHtml = `
                    <div class="w-full h-32 rounded-2xl border-2 border-dashed border-rose-500/10 bg-rose-500/5 flex flex-col items-center justify-center opacity-40">
                        <span class="text-[8px] font-black text-rose-500 tracking-[0.3em]">MISSING FILE</span>
                    </div>
                `;
            }

            docsContainer.innerHTML += `<div>${headerHtml}${boxHtml}</div>`;
        });

        const baseUrl = "{{ url('registrar/applications') }}";
        document.getElementById('approveForm').action = `${baseUrl}/${app.id}`;
        document.getElementById('rejectForm').action = `${baseUrl}/${app.id}`;

        const actionButtons = document.getElementById('actionButtons');
        const status = (app.status || '').toLowerCase();
        if (['pending', 'paid', 'enrolled'].includes(status)) {
            actionButtons.classList.add('flex');
            actionButtons.classList.remove('hidden');
        } else {
            actionButtons.classList.remove('flex');
            actionButtons.classList.add('hidden');
        }

        const modal = document.getElementById('applicationModal');
        const content = document.getElementById('modalContent');
        modal.classList.add('flex');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('applicationModal');
        const content = document.getElementById('modalContent');
        modal.classList.add('opacity-0', 'pointer-events-none');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    let currentApplicationId = null;

    function applyVoucher(voucherType) {
        if (!currentApplicationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${currentApplicationId}/apply-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ voucher_type: voucherType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVoucherDisplay(voucherType);
                alert('✓ Voucher applied successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to apply voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error applying voucher');
        });
    }

    function removeVoucher() {
        if (!currentApplicationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${currentApplicationId}/remove-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVoucherDisplay(null);
                alert('✓ Voucher removed successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to remove voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing voucher');
        });
    }

    function updateVoucherDisplay(voucherType) {
        const statusDiv = document.getElementById('voucherStatus');
        const badge = document.getElementById('voucherBadge');
        const label = document.getElementById('voucherLabel');

        if (voucherType) {
            statusDiv.classList.remove('hidden');
            if (voucherType === 'free_tuition') {
                badge.className = 'flex items-center gap-2 p-2 rounded-lg bg-green-500/10 border border-green-500/20';
                label.className = 'text-[9px] font-bold text-green-400 uppercase tracking-wider';
                label.textContent = 'Free Tuition';
            } else {
                badge.className = 'flex items-center gap-2 p-2 rounded-lg bg-yellow-500/10 border border-yellow-500/20';
                label.className = 'text-[9px] font-bold text-yellow-400 uppercase tracking-wider';
                label.textContent = 'Discounted';
            }
        } else {
            statusDiv.classList.add('hidden');
        }
    }

    // Direct voucher functions for table actions
    function applyVoucherDirect(applicationId, voucherType) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${applicationId}/apply-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ voucher_type: voucherType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Voucher applied successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to apply voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error applying voucher');
        });
    }

    function removeVoucherDirect(applicationId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`{{ url('registrar/applications') }}/${applicationId}/remove-voucher`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Voucher removed successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to remove voucher'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing voucher');
        });
    }

    window.onclick = function(event) {
        const modal = document.getElementById('applicationModal');
        if (event.target == modal) closeModal();
    }
    </script>
</x-layouts.registrar>
