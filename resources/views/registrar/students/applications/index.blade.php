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
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase italic">Applications</h2>
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
                        <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] bg-white/[0.02]">
                            <th class="py-6 px-8">ID</th>
                            <th class="py-6 px-8">Applicant Name</th>
                            <th class="py-6 px-8">Email</th>
                            <th class="py-6 px-8">Program</th>
                            <th class="py-6 px-8">Submission Date</th>
                            <th class="py-6 px-8">Status</th>
                            <th class="py-6 px-8 text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($applications as $application)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-6 px-8">
                                <div class="flex flex-col">
                                    <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider block font-bold">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</span>
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
                                    'Enrolled' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                    'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                    'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    default => 'bg-white/5 text-white/40 border-white/10',
                                };
                                $displayText = ucfirst($application->status);
                                if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeColor }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    <button type="button" data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn">
                                        View Details
                                    </button>

                                    <form action="{{ route('registrar.applications.destroy', $application->id) }}"
                                        method="POST" onsubmit="return confirm('Delete this application record?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-lg shadow-rose-500/5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
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
                    {{ $applications->links() }}
                @endif
            </div>
        </div>
    </div>

    {{-- Universal Application Analysis Modal --}}
    <div id="applicationModal" class="fixed inset-0 z-[100] hidden opacity-0 pointer-events-none transition-all duration-300 items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#060d1a]/90 backdrop-blur-2xl" onclick="closeModal()"></div>

        <div class="bg-[#0d1f3c] w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden flex flex-col max-h-[95vh] relative z-10 transform scale-95 transition-all duration-300" id="modalContent">

            <div class="px-8 md:px-12 py-8 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <div>
                    <h2 class="text-2xl font-black text-white italic uppercase tracking-tight" id="modalTitle">Application Details</h2>
                    <p class="text-[9px] text-white/30 uppercase tracking-[0.3em] mt-1 italic">Review Process</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-xl bg-white/5 text-white/20 hover:text-white hover:bg-white/10 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                <span class="text-lg font-black text-white uppercase italic tracking-tight" id="modalName"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-6 pt-4 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Application ID</span>
                                    <span class="text-xs font-bold text-white/60 lowercase" id="modalEmail"></span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Submitted On</span>
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
                            </div>
                            <div class="pt-4 border-t border-white/5">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Address</span>
                                    <span class="text-xs font-bold text-white/60 italic" id="modalAddress"></span>
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
                    <form id="rejectForm" method="POST" onsubmit="return confirm('Are you sure you want to reject this application?');">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="bg-rose-500 hover:bg-rose-400 text-white text-[10px] font-black py-4 px-10 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-xl shadow-rose-500/10 active:scale-95">
                            Reject Application
                        </button>
                    </form>
                </div>
                <button onclick="closeModal()" class="w-full md:w-auto px-10 py-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em] border border-white/10 rounded-2xl hover:bg-white/5 hover:text-white transition-all ml-auto italic">
                    Close Details
                </button>
            </div>
        </div>
    </div>

    <script>
    function openModal(app, user, course) {
        document.getElementById('modalTitle').innerText = 'Application Details #' + String(app.id).padStart(5, '0');
        const middle = app.middle_name ? ' ' + app.middle_name : '';
        const fullName = (app.last_name || '') + ', ' + (app.first_name || '') + middle;
        document.getElementById('modalName').innerText = fullName;
        document.getElementById('modalEmail').innerText = app.email || 'N/A';
        document.getElementById('modalDob').innerText = app.birth_date || 'N/A';
        document.getElementById('modalAge').innerText = app.age || 'N/A';
        document.getElementById('modalGender').innerText = app.gender || 'N/A';
        document.getElementById('modalAddress').innerText = app.address_full || 'N/A';

        let courseCode = app.course_code || 'N/A';
        document.getElementById('modalCourse').innerText = courseCode;

        document.getElementById('modalYear').innerText = app.year_level || 'N/A';

        let statusText = app.status;
        if (statusText === 'Enrolled') { statusText = 'Paid / Finalized'; }
        document.getElementById('modalStatus').innerText = statusText;

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
        if (app.status === 'Pending') {
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

    window.onclick = function(event) {
        const modal = document.getElementById('applicationModal');
        if (event.target == modal) closeModal();
    }
    </script>
</x-layouts.registrar>
