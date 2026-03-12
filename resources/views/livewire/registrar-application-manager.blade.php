<div>
    @if(session('success'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <style>
        .modal-backdrop { background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    </style>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Applications List</h3>
            <span class="bg-white text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                {{ $pendingCount }} Pending
            </span>
        </div>

        <div class="overflow-x-auto" x-data="{ 
            showModal: false, 
            app: null, 
            storageBase: '{{ asset('storage') }}/',
            openModal(application) {
                this.app = application;
                this.showModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.showModal = false;
                this.app = null;
                document.body.style.overflow = 'auto';
            }
        }">
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
                                <button type="button" @click="openModal({{ json_encode($application) }})"
                                    class="bg-[#10B981] hover:bg-[#059669] text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm">
                                    View
                                </button>

                                <button type="button" wire:click="delete({{ $application->id }})"
                                    wire:confirm="Delete this application?"
                                    class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-1 rounded text-xs font-bold transition border border-gray-300 shadow-sm">
                                    Delete
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

            <!-- Application Modal -->
            <div x-show="showModal" style="display: none;" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 p-4 modal-backdrop flex items-center justify-center">
                <div @click.away="closeModal()" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="scale-95"
                    x-transition:enter-end="scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="scale-100"
                    x-transition:leave-end="scale-95"
                    class="bg-white w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[90vh]">
                    
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0">
                        <h2 class="text-xl font-bold text-gray-900" x-text="app ? 'Application #' + app.id : ''"></h2>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto custom-scrollbar bg-white">
                        <div class="space-y-8" x-if="app">
                            
                            <div>
                                <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-200 pt-4">
                                    <div><span class="block text-gray-500 text-xs mb-1">Full Name:</span><span class="font-bold text-gray-900 uppercase" x-text="(app?.last_name || '') + ', ' + (app?.first_name || '') + (app?.middle_name ? ' ' + app?.middle_name : '')"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Email:</span><span class="font-medium text-gray-900" x-text="app?.email || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Date of Birth:</span><span class="font-medium text-gray-900" x-text="app?.birth_date || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Age:</span><span class="font-medium text-gray-900" x-text="app?.age || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Gender:</span><span class="font-medium text-gray-900 capitalize" x-text="app?.gender || 'N/A'"></span></div>
                                    <div class="col-span-1 md:col-span-2"><span class="block text-gray-500 text-xs mb-1">Address:</span><span class="font-medium text-gray-900" x-text="app?.address_full || 'N/A'"></span></div>
                                </div>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm">
                                <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                                <div class="space-y-4 text-sm">
                                    <p><span class="font-bold text-gray-500 mr-2">Program:</span><span class="text-[#10B981] font-bold uppercase" x-text="app?.course_code || 'N/A'"></span></p>
                                    <div class="flex gap-4">
                                        <span><span class="font-bold text-gray-500">Year:</span> <span class="text-gray-900" x-text="app?.year_level || 'N/A'"></span></span>
                                        <span><span class="font-bold text-gray-500">Status:</span> <span class="text-[#10B981] font-bold uppercase" x-text="app?.status === 'Enrolled' ? 'Paid' : app?.status"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Guardian Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-200 pt-4">
                                    <div><span class="block text-gray-500 text-xs mb-1">Father's Name:</span><span class="font-bold text-gray-900 uppercase" x-text="app?.father_name || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Mother's Name:</span><span class="font-bold text-gray-900 uppercase" x-text="app?.mother_maiden_name || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Guardian:</span><span class="font-bold text-gray-900 uppercase" x-text="app?.guardian_name || 'N/A'"></span></div>
                                    <div><span class="block text-gray-500 text-xs mb-1">Contact #:</span><span class="font-medium text-gray-900" x-text="app?.guardian_contact || 'N/A'"></span></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-5 border-t border-gray-200 flex justify-between items-center shrink-0 rounded-b-xl">
                        <div x-show="app?.status === 'Pending'" class="flex gap-3">
                            <button type="button" x-on:click="$wire.approve(app.id); closeModal()" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-[#10B981]/20 transition">Approve</button>
                            <button type="button" x-on:click="if(confirm('Reject application?')) { $wire.reject(app.id); closeModal(); }" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-rose-600/20 transition">Reject</button>
                        </div>
                        <button @click="closeModal()" class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm ml-auto">Close</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="p-4 border-t border-gray-200 bg-gray-50">
            @if(method_exists($applications, 'links'))
            <div class="custom-pagination">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
