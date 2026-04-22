<x-layouts.admin>
<div class="py-6">
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(34, 211, 238, 0.2); border-radius: 4px; }
    </style>

    <div class="w-full">
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
                                <td class="py-6 px-8 text-white/20 font-mono tracking-tighter italic">
                                    #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider font-bold">
                                                {{ $application->last_name }}, {{ $application->first_name }}
                                            </span>
                                            @if($application->extension)
                                            <span class="text-sm font-bold text-white/40 italic">{{ $application->extension }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">Applicant Profile</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-white/40 lowercase tracking-tight">
                                    {{ $application->email }}
                                </td>
                                <td class="py-6 px-8 whitespace-nowrap">
                                    <span class="text-cyan-400 font-black uppercase text-[10px] tracking-widest">
                                        {{ $application->course_code }}
                                    </span>
                                    <span class="text-white/20 text-[9px] ml-1 font-bold">({{ $application->year_level }})</span>
                                </td>
                                <td class="py-6 px-8 text-white/30 font-medium italic tracking-tight">
                                    {{ $application->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-6 px-8 text-center">
                                    @php
                                        $badgeStyle = match (ucfirst($application->status)) {
                                            'Approved' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                            'Enrolled','Paid' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            default => 'bg-white/5 text-white/40 border-white/10',
                                        };
                                        $displayText = ucfirst($application->status) === 'Enrolled' ? 'PAID' : strtoupper($application->status);
                                        if (in_array(ucfirst($application->status), ['Enrolled', 'Paid'])) { $displayText = 'PAID'; }
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black border uppercase tracking-widest shadow-sm {{ $badgeStyle }}">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        <button onclick="openModal({{ json_encode($application) }})"
                                            class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-cyan-400 hover:border-cyan-500/30 transition-all text-[10px] font-black uppercase tracking-widest group/btn shadow-lg shadow-black/20">
                                            View Details
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-24 text-center">
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
                <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                    {{ $applications->links('pagination') }}
                </div>
            @endif
        </div>

        <!-- Glass Modal -->
        <div id="applicationModal" class="fixed inset-0 z-50 p-4 backdrop-blur-md bg-[#060d1a]/60 hidden opacity-0 transition-all duration-300 items-center justify-center">
            <div class="absolute inset-0" onclick="closeModal()"></div>

            <div id="modalContent" class="w-full max-w-5xl rounded-[40px] shadow-[0_32px_120px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden flex flex-col max-h-[95vh] relative z-10 transform scale-95 transition-all duration-300"
                 style="background: #0d1f3c;">
                
                <div id="modalInnerContent" class="flex flex-col h-full overflow-hidden">
                    {{-- Populated via JS/openModal --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(app) {
    const modal = document.getElementById('applicationModal');
    const content = document.getElementById('modalContent');
    const inner = document.getElementById('modalInnerContent');

    const badgeStyle = {
        'Approved': 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
        'Enrolled': 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
        'Rejected': 'bg-rose-500/10 text-rose-400 border-rose-500/20',
        'Pending':  'bg-amber-500/10 text-amber-400 border-amber-500/20'
    }[app.status] || 'bg-white/5 text-white/40 border-white/10';

    const displayText = (app.status === 'Enrolled') ? 'PAID' : app.status.toUpperCase();
    const updateRoute = `{{ url('admin/applications') }}/${app.id}`;

    inner.innerHTML = `
        <div class="px-8 md:px-12 py-8 border-b border-white/5 flex justify-between items-center bg-white/[0.01] shrink-0">
            <div>
                <h2 class="text-2xl font-black text-white italic uppercase tracking-tight">Application Details #${String(app.id).padStart(5, '0')}</h2>
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
                        <div class="grid grid-cols-2 gap-8">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Full Name</span>
                                <span class="text-xs font-bold text-cyan-400 capitalize">${app.last_name || ''}, ${app.first_name || ''}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Email Address</span>
                                <span class="text-xs font-bold text-white/60 lowercase">${app.email || 'N/A'}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Application ID</span>
                                <span class="text-xs font-bold text-white/40 font-mono tracking-tighter">#${String(app.id).padStart(5, '0')}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Submitted On</span>
                                <span class="text-xs font-bold text-white/60">${new Date(app.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Date of Birth</span>
                                <span class="text-xs font-bold text-white/60">${app.birth_date || 'N/A'}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Age</span>
                                <span class="text-xs font-bold text-white/60">${app.age || 'N/A'}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Gender</span>
                                <span class="text-xs font-bold text-white/60 capitalize">${app.gender || 'N/A'}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Address</span>
                                <span class="text-xs font-bold text-white/60">${app.address_full || 'N/A'}</span>
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
                            <span class="text-2xl font-black text-white uppercase italic tracking-tighter">${app.course_code}</span>
                        </div>
                        <div class="flex items-center gap-10 pt-6 border-t border-cyan-500/10">
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Year Level</span>
                                <span class="text-sm font-black text-white uppercase">${app.year_level}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Status</span>
                                <span class="text-sm font-black text-cyan-400 uppercase tracking-[0.1em]">${displayText}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Guardian Records --}}
            <div class="space-y-6 pt-6 px-12">
                <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Guardian Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white/[0.02] border border-white/5 rounded-[32px] p-8">
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Father's Name</span>
                        <span class="text-xs font-bold text-white uppercase">${app.father_name || 'N/A'}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Mother's Name</span>
                        <span class="text-xs font-bold text-white uppercase">${app.mother_maiden_name || 'N/A'}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Guardian Name</span>
                        <span class="text-xs font-bold text-white uppercase">${app.guardian_name || 'N/A'}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] font-black text-white/20 uppercase tracking-widest italic">Emergency Contact</span>
                        <span class="text-xs font-bold text-white uppercase">${app.guardian_contact || 'N/A'}</span>
                    </div>
                </div>
            </div>

            {{-- Document Assets --}}
            <div class="p-8 md:p-12 space-y-6 pt-6 mb-12">
                <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Required Documents</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    ${[
                        {key: 'form_138_path', label: 'Form 138'},
                        {key: 'good_moral_path', label: 'Good Moral'},
                        {key: 'psa_path', label: 'PSA Birth'},
                        {key: 'id_picture_path', label: 'ID Image'}
                    ].map(doc => {
                        const hasFile = app[doc.key] ? true : false;
                        const storageBase = "{{ url('/documents') }}/";
                        const fileUrl = hasFile ? storageBase + app[doc.key] : '#';
                        const isImage = hasFile && app[doc.key].match(/\.(jpeg|jpg|png|gif|webp)$/i);

                        return `
                            <div class="space-y-3 group/doc">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center justify-center w-5 h-5 ${hasFile ? 'bg-emerald-500/20 border-2 border-emerald-500' : 'bg-rose-500/20 border-2 border-rose-500'} rounded-full shrink-0">
                                        <span class="${hasFile ? 'text-emerald-500' : 'text-rose-500'} font-black text-xs">${hasFile ? '✓' : '!'}</span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase ${hasFile ? 'text-white' : 'text-rose-500'} tracking-widest">${doc.label}</span>
                                </div>

                                ${hasFile ? `
                                    <a href="${fileUrl}" target="_blank" class="block group/asset relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03]">
                                        ${isImage ? `
                                            <img src="${fileUrl}" class="w-full h-32 object-cover transition-transform duration-500 group-hover/asset:scale-110">
                                        ` : `
                                            <div class="w-full h-32 flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-cyan-400 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-[8px] font-black text-cyan-400 mt-2 tracking-[0.3em]">VIEW PDF</span>
                                            </div>
                                        `}
                                        <div class="absolute inset-0 bg-cyan-500/20 opacity-0 group-hover/asset:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </div>
                                    </a>
                                ` : `
                                    <div class="w-full h-32 rounded-2xl border-2 border-dashed border-rose-500/10 bg-rose-500/5 flex flex-col items-center justify-center opacity-40">
                                        <span class="text-[8px] font-black text-rose-500 tracking-[0.3em]">MISSING FILE</span>
                                    </div>
                                `}
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        </div>

        <div class="px-8 md:px-12 py-8 border-t border-white/5 bg-white/[0.01] flex flex-col md:flex-row justify-between items-center gap-6 shrink-0">
            <div class="flex gap-4">
                ${app.status === 'Pending' ? `
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
                ` : `<div class="text-[10px] font-black text-white/20 uppercase tracking-widest italic tracking-[0.2em]">Status: ${displayText}</div>`}
            </div>
            <button onclick="closeModal()" class="w-full md:w-auto px-10 py-4 text-[10px] font-black text-white/40 hover:text-white uppercase tracking-[0.2em] border border-white/10 rounded-2xl hover:bg-white/5 transition-all ml-auto italic">Close Details</button>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('applicationModal');
    const content = document.getElementById('modalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 300);
}
</script>
</x-layouts.admin>
