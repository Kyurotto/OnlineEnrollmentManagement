<div>
    <div class="space-y-8 max-w-[1600px] mx-auto animate-in fade-in duration-700">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white/[0.02] border border-white/5 p-8 rounded-[32px] backdrop-blur-xl shadow-2xl">
            <div class="space-y-1">
                <h2 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                    Enrollment Archives
                </h2>
                <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.4em] ml-5">
                    @if($selectedFolder)
                        <button wire:click="backToFolders" class="text-amber-400 hover:text-amber-300 transition-colors">← Back to Folders</button>
                        <span class="mx-2 text-white/10">|</span>
                        {{ $selectedFolder === 'legacy' ? 'Legacy Records' : str_replace('|', ' — ', $selectedFolder) }}
                    @else
                        Folderized Historical Records
                    @endif
                </p>
            </div>
            
            @if($selectedFolder)
            <div class="flex flex-wrap items-center gap-4">
                <div class="relative group">
                    <input type="text" wire:model.live="search" placeholder="Search archives..." 
                        class="bg-white/5 border border-white/10 text-white text-xs rounded-2xl pl-10 pr-4 py-3 w-72 focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all outline-none placeholder-white/20">
                    <svg class="w-4 h-4 text-white/20 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                @if($search || $selectedCourse || $level)
                    <button wire:click="resetFilters" class="flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 text-amber-500 text-[10px] font-black uppercase tracking-widest rounded-2xl px-6 py-3.5 hover:bg-amber-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Clear
                    </button>
                @endif
            </div>
            @endif
        </div>

        @if(!$selectedFolder)
        {{-- FOLDER VIEW --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($folders as $folder)
                <button wire:click="selectFolder('{{ $folder->semester_name }}|{{ $folder->academic_year_name }}')"
                    class="group bg-[#060d1a]/80 border border-white/5 rounded-[28px] p-8 text-left hover:border-amber-500/30 hover:bg-amber-500/[0.03] transition-all duration-300 shadow-xl hover:shadow-amber-500/5">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 group-hover:bg-amber-500/20 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-white/20 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full">{{ $folder->student_count }} students</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-black text-white uppercase tracking-tight group-hover:text-amber-400 transition-colors">{{ $folder->semester_name }}</p>
                        <p class="text-[10px] font-bold text-white/30 uppercase tracking-widest">{{ $folder->academic_year_name }}</p>
                    </div>
                </button>
            @endforeach

            @if($legacyCount > 0)
                <button wire:click="selectFolder('legacy')"
                    class="group bg-[#060d1a]/80 border border-white/5 rounded-[28px] p-8 text-left hover:border-white/20 hover:bg-white/[0.02] transition-all duration-300 shadow-xl">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white/40 group-hover:text-white/60 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-white/20 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full">{{ $legacyCount }} records</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-black text-white/40 uppercase tracking-tight group-hover:text-white/60 transition-colors">Legacy Archives</p>
                        <p class="text-[10px] font-bold text-white/20 uppercase tracking-widest">Pre-folder records</p>
                    </div>
                </button>
            @endif

            @if($folders->isEmpty() && $legacyCount === 0)
                <div class="col-span-full py-20 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    <p class="text-sm font-black text-white/20 uppercase tracking-[0.4em]">No Archives Yet</p>
                    <p class="text-[10px] text-white/10 uppercase tracking-widest mt-2">Archives are created when a new semester is activated</p>
                </div>
            @endif
        </div>

        @else
        {{-- STUDENT LIST VIEW (inside a folder) --}}
        <div class="bg-[#060d1a]/80 border border-white/5 rounded-[40px] overflow-hidden shadow-2xl backdrop-blur-md">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-8 py-6 text-[10px] font-black text-white/40 uppercase tracking-widest">Student Information</th>
                            <th class="px-6 py-6 text-[10px] font-black text-white/40 uppercase tracking-widest text-center">Type</th>
                            <th class="px-6 py-6 text-[10px] font-black text-white/40 uppercase tracking-widest text-center">Program/Year</th>
                            <th class="px-6 py-6 text-[10px] font-black text-white/40 uppercase tracking-widest text-center">Archive Term</th>
                            <th class="px-8 py-6 text-[10px] font-black text-white/40 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($applications as $app)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-500 font-black text-lg shadow-inner">
                                            {{ strtoupper(substr($app->user->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-white tracking-tight group-hover:text-amber-400 transition-colors">
                                                {{ $app->user->first_name }} {{ $app->user->last_name }}
                                            </div>
                                            <div class="text-[10px] font-bold text-white/30 uppercase tracking-wider mt-0.5">{{ $app->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $app->level === 'college' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                        {{ $app->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="text-xs font-bold text-white/80">{{ $app->course_code }}</div>
                                    <div class="text-[10px] font-black text-white/20 uppercase tracking-tighter mt-1">{{ $app->status }}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="inline-flex flex-col items-center px-4 py-2 rounded-2xl bg-white/[0.03] border border-white/10">
                                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest leading-none">{{ $app->year_level }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button onclick="openArchiveModal({{ json_encode($app) }})" class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-white hover:bg-amber-500/20 hover:border-amber-500/30 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4 opacity-20">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        <p class="text-sm font-black uppercase tracking-[0.4em]">No Records Found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications instanceof \Illuminate\Pagination\LengthAwarePaginator && $applications->hasPages())
                <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Archived Detail Modal --}}
    <div id="archiveModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 md:p-8">
            <div class="fixed inset-0 bg-[#060d1a]/95 backdrop-blur-xl transition-opacity" onclick="closeArchiveModal()"></div>
            <div class="relative bg-[#0d1f3c] border border-white/10 w-full max-w-6xl rounded-[48px] shadow-[0_32px_120px_rgba(0,0,0,0.8)] overflow-hidden animate-in zoom-in-95 duration-300">
                <div class="px-8 md:px-12 py-10 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-6">
                        <div id="modalAvatar" class="w-16 h-16 rounded-[24px] bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-500 font-black text-2xl shadow-inner"></div>
                        <div>
                            <h3 id="modalName" class="text-3xl font-black text-white tracking-tight"></h3>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span id="modalLevelBadge" class="px-3 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border"></span>
                                <span class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] italic" id="modalEmailLabel"></span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeArchiveModal()" class="p-4 rounded-2xl bg-white/5 text-white/40 hover:text-white transition-all border border-transparent hover:border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-8 md:p-12 max-h-[70vh] overflow-y-auto custom-scrollbar space-y-12">
                    <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 w-full">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest">Physical Documents</p>
                                <div id="modalPhysicalStatus" class="flex items-center gap-2 pt-1"></div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest">Registrar Clearance</p>
                                <div id="modalClearanceStatus" class="flex items-center gap-2 pt-1"></div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest">Enrollment Status</p>
                                <div id="modalEnrollmentStatus" class="flex items-center gap-2 pt-1"></div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest">Archive Term</p>
                                <div id="modalArchiveTerm" class="text-xs font-black text-amber-500 pt-1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Submitted Documents</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="modalDocuments"></div>
                    </div>

                    {{-- Promissory Note Section --}}
                    <div class="space-y-6 pt-6 hidden border-t border-white/5" id="modalPromissorySection">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Promissory Explanation</h3>
                        </div>
                        <div class="bg-amber-500/5 border border-amber-500/10 rounded-[32px] p-8">
                            <div class="space-y-2">
                                <span class="text-[9px] font-black text-amber-500/40 uppercase tracking-widest italic text-shadow shadow-amber-500/20">Reason for Delayed Requirements:</span>
                                <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 min-h-[80px]">
                                    <p class="text-[11px] text-white/60 leading-relaxed italic" id="modalPromissoryReason"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-8 md:px-12 py-8 bg-white/[0.01] border-t border-white/5 text-center">
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.4em] italic w-full md:w-auto text-center md:text-right">Archived System Record — Historical Reference</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentArchiveId = null;

        function openArchiveModal(app) {
            currentArchiveId = app.id;
            const modal = document.getElementById('archiveModal');
            const storageBase = @json(url('/documents')) + '/';
            document.getElementById('modalAvatar').innerText = app.user.first_name.charAt(0).toUpperCase();
            document.getElementById('modalName').innerText = `${app.user.first_name} ${app.user.last_name}`;
            document.getElementById('modalEmailLabel').innerText = app.user.email;
            document.getElementById('modalArchiveTerm').innerText = app.year_level;
            const levelBadge = document.getElementById('modalLevelBadge');
            levelBadge.innerText = app.level;
            levelBadge.className = `px-3 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border ${app.level === 'college' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'}`;
            document.getElementById('modalPhysicalStatus').innerHTML = app.physical_documents_received == 1 ? `<span class="text-emerald-400 text-xs font-black">✓ RECEIVED<\/span>` : `<span class="text-white/20 text-xs font-black">PENDING<\/span>`;
            document.getElementById('modalClearanceStatus').innerHTML = app.credentials_verified == 1 ? `<span class="text-emerald-400 text-xs font-black">✓ CLEARED<\/span>` : `<span class="text-white/20 text-xs font-black">PENDING<\/span>`;
            document.getElementById('modalEnrollmentStatus').innerHTML = `<span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-tighter ${app.status === 'Enrolled' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/10 text-white/40'}">${app.status}<\/span>`;
            const docsContainer = document.getElementById('modalDocuments');
            docsContainer.innerHTML = '';
            const docFields = {'form_137_path':'Report Card','psa_path':'PSA Birth Cert','good_moral_path':'Good Moral','sf10_path':'SF10 Record','id_picture_path':'2x2 ID Portrait','promissory_note_path':'Promissory Note'};
            Object.entries(docFields).forEach(([field, label]) => {
                if (app[field]) {
                    const isPdf = app[field].toLowerCase().endsWith('.pdf');
                    const cleanPath = app[field].replace(/^\//, '');
                    const assetUrl = storageBase + cleanPath;
                    docsContainer.innerHTML += `<div class="group relative bg-white/[0.03] border border-white/10 rounded-3xl p-4 hover:border-emerald-500/30 transition-all"><p class="text-[9px] font-black text-white/30 uppercase tracking-widest mb-3">${label}<\/p><a href="${assetUrl}" target="_blank" class="block aspect-[4/3] rounded-2xl overflow-hidden bg-black/40 border border-white/5 relative">${isPdf ? `<div class="absolute inset-0 flex items-center justify-center text-emerald-400 font-black text-[10px] tracking-widest">PDF DOCUMENT<\/div>` : `<img src="${assetUrl}" class="w-full h-full object-cover transition-transform group-hover:scale-110">`}<\/a><\/div>`;
                }
            });

            // Handle Promissory Section
            const promissorySection = document.getElementById('modalPromissorySection');
            const promissoryReason = document.getElementById('modalPromissoryReason');
            if (app.promissory_reason) {
                promissorySection.classList.remove('hidden');
                promissoryReason.innerText = app.promissory_reason;
            } else {
                promissorySection.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeArchiveModal() {
            document.getElementById('archiveModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</div>
