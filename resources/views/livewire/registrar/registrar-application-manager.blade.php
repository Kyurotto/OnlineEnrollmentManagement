<div>
    @if(session('success'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
        <div wire:loading class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#10B981]"></div>
        </div>
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Applications List</h3>
            <span class="bg-white text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                {{ $pendingCount }} Pending
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider bg-white">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Course Applied</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-200 bg-white">
                    @forelse($applications as $application)
                    <tr wire:key="app-{{ $application->id }}" class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">#{{ $application->id }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900 uppercase whitespace-nowrap">
                            {{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap lowercase">
                            {{ $application->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-[#10B981]">{{ $application->course_code }}</span>
                            <span class="text-gray-500 text-xs ml-1 font-normal">
                                ({{ $application->year_level }})
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $application->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $badgeColor = match(ucfirst($application->status)) {
                                'Approved' => 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20',
                                'Enrolled' => 'bg-sky-50 text-sky-600 border border-sky-200',
                                'Rejected' => 'bg-rose-50 text-rose-600 border border-rose-200',
                                'Pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                default => 'bg-gray-100 text-gray-600 border border-gray-200',
                            };
                            $displayText = ucfirst($application->status);
                            if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }} shadow-sm">
                                {{ $displayText }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button wire:click="viewApplication({{ $application->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-[#10B981] border border-[#10B981]/30 bg-[#10B981]/5 hover:bg-[#10B981]/10 rounded-lg transition-colors">
                                    View
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 bg-gray-50">
            @if(method_exists($applications, 'links'))
            <div class="custom-pagination">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- View Modal --}}
    @if($selectedApp)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(255,255,255,0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
        <div class="absolute inset-0" wire:click="closeModal"></div>
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[90vh] relative z-10">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white shrink-0">
                <h2 class="text-xl font-bold text-gray-900">Application #{{ $selectedApp->id }}</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-white" style="scrollbar-width: thin;">
                <div class="space-y-8">
                    {{-- Student Information --}}
                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-100 pt-4">
                            <div><span class="block text-gray-400 text-xs mb-1">Full Name:</span><span class="font-bold text-gray-900 uppercase">{{ $selectedApp->last_name ?? '' }}, {{ $selectedApp->first_name ?? '' }} {{ $selectedApp->middle_name ?? '' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Email:</span><span class="font-medium text-gray-900">{{ $selectedApp->email ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Date of Birth:</span><span class="font-medium text-gray-900">{{ $selectedApp->birth_date ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Age:</span><span class="font-medium text-gray-900">{{ $selectedApp->age ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Gender:</span><span class="font-medium text-gray-900 capitalize">{{ $selectedApp->gender ?? 'N/A' }}</span></div>
                            <div class="col-span-1 md:col-span-2"><span class="block text-gray-400 text-xs mb-1">Address:</span><span class="font-medium text-gray-900">{{ $selectedApp->address_full ?? 'N/A' }}</span></div>
                        </div>
                    </div>

                    {{-- Program Details --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                        <div class="space-y-4 text-sm">
                            <p><span class="font-bold text-gray-500 mr-2">Program:</span><span class="text-[#10B981] font-bold uppercase">{{ $selectedApp->course_code ?? 'N/A' }}</span></p>
                            <div class="flex gap-4">
                                <span><span class="font-bold text-gray-500">Year:</span> <span class="text-gray-900 font-medium">{{ $selectedApp->year_level ?? 'N/A' }}</span></span>
                                <span><span class="font-bold text-gray-500">Status:</span> <span class="text-[#10B981] font-bold uppercase">{{ $selectedApp->status === 'Enrolled' ? 'Paid' : $selectedApp->status }}</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Guardian Information --}}
                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-100 pt-4">
                            <div><span class="block text-gray-400 text-xs mb-1">Father's Name:</span><span class="font-bold text-gray-900 uppercase">{{ $selectedApp->father_name ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Mother's Name:</span><span class="font-bold text-gray-900 uppercase">{{ $selectedApp->mother_maiden_name ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Guardian:</span><span class="font-bold text-gray-900 uppercase">{{ $selectedApp->guardian_name ?? 'N/A' }}</span></div>
                            <div><span class="block text-gray-400 text-xs mb-1">Contact #:</span><span class="font-medium text-gray-900">{{ $selectedApp->guardian_contact ?? 'N/A' }}</span></div>
                        </div>
                    </div>

                    {{-- Submitted Documents --}}
                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3 text-center md:text-left">Submitted Documents</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-100 pt-4">
                            @foreach ([['key' => 'form_138_path', 'label' => 'Form 138'], ['key' => 'good_moral_path', 'label' => 'Good Moral'], ['key' => 'psa_path', 'label' => 'PSA Birth Cert'], ['key' => 'id_picture_path', 'label' => 'ID Picture']] as $doc)
                                @php $hasFile = !empty($selectedApp[$doc['key']]); @endphp
                                <div>
                                    @if ($hasFile)
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex items-center justify-center w-[22px] h-[22px] bg-emerald-500/20 border-2 border-emerald-500 rounded-full shrink-0">
                                                <span class="text-emerald-500 font-black text-sm">✓</span>
                                            </div>
                                            <span class="text-[11px] font-bold uppercase text-gray-900">{{ $doc['label'] }}</span>
                                        </div>
                                        @php
                                            $fileUrl = asset('storage/' . $selectedApp[$doc['key']]);
                                            $isImage = preg_match('/\.(jpeg|jpg|png|gif|webp)$/i', $selectedApp[$doc['key']]);
                                        @endphp
                                        @if ($isImage)
                                            <a href="{{ $fileUrl }}" target="_blank" class="block">
                                                <img src="{{ $fileUrl }}" class="w-full h-[120px] object-cover rounded-lg border border-gray-200 bg-gray-50">
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank" class="block no-underline">
                                                <div class="w-full h-[120px] rounded-lg border border-gray-200 bg-gray-50 flex flex-col items-center justify-center text-emerald-500">
                                                    <span class="text-3xl">📄</span>
                                                    <span class="text-[10px] font-bold uppercase mt-1">PDF</span>
                                                </div>
                                            </a>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="flex items-center justify-center w-[22px] h-[22px] bg-rose-500/20 border-2 border-rose-500 rounded-full shrink-0">
                                                <span class="text-rose-500 font-black text-sm">✗</span>
                                            </div>
                                            <span class="text-[11px] font-bold uppercase text-rose-500">{{ $doc['label'] }}</span>
                                        </div>
                                        <div class="w-full h-[120px] rounded-lg bg-gray-50 border border-dashed border-rose-500/40 flex flex-col items-center justify-center opacity-70">
                                            <span class="text-2xl opacity-60">⚠️</span>
                                            <span class="text-[10px] font-bold uppercase text-rose-500/60 mt-1">Missing</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-200 flex justify-between items-center shrink-0">
                @if($selectedApp->status === 'Pending')
                <div class="flex gap-3">
                    <button wire:click="approve({{ $selectedApp->id }})" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-[#10B981]/20 transition">Approve</button>
                    <button wire:confirm="Reject application?" wire:click="reject({{ $selectedApp->id }})" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-rose-600/20 transition">Reject</button>
                </div>
                @else
                <div></div>
                @endif
                <button wire:click="closeModal" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition ml-auto">Close</button>
            </div>
        </div>
    </div>
    @endif
</div>

