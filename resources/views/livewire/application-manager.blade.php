<div>
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-gray-900">Applications List</h3>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#10B981] focus:border-[#10B981] block w-64 p-2 transition">
                
                <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#10B981] focus:border-[#10B981] block w-40 p-2 transition">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Enrolled">Paid / Enrolled</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            @if (isset($pendingCount) && $pendingCount > 0)
                <span class="bg-gray-100 text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingCount }} Pending</span>
            @endif
        </div>

        <div class="overflow-x-auto relative">
            <div wire:loading class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#10B981]"></div>
            </div>

            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Student Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Program</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $application->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 uppercase">
                                {{ $application->first_name }} {{ $application->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 lowercase">
                                {{ $application->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="font-bold text-[#10B981]">{{ $application->course_code }}</span>
                                <span class="text-gray-500 text-xs ml-1">({{ $application->year_level }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeColor = match (ucfirst($application->status)) {
                                        'Approved' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        'Enrolled' => 'bg-sky-50 text-sky-600 border border-sky-200',
                                        'Rejected' => 'bg-rose-50 text-rose-600 border border-rose-200',
                                        'Pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        default => 'bg-gray-100 text-gray-600 border border-gray-200',
                                    };
                                    $displayText = ucfirst($application->status);
                                    if ($displayText === 'Enrolled') {
                                        $displayText = 'Paid';
                                    }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button type="button" data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="bg-[#10B981] hover:bg-[#059669] text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm">
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-[#52525B]">No applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            @if (method_exists($applications, 'links'))
                {{ $applications->links() }}
            @endif
        </div>
    </div>
</div>
