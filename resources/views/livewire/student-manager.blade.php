<div>
    @if (session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6 relative">
        <div wire:loading class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#10B981]"></div>
        </div>
        
        <div class="px-6 py-4 border-b border-gray-200 bg-white flex justify-between items-center sm:flex-row flex-col gap-4">
            <h3 class="text-lg font-bold text-gray-900">Student List <span class="text-sm font-normal text-gray-500">({{ $students->total() }})</span></h3>
            
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" class="pl-10 w-full bg-white border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm" placeholder="Search by name, email, account...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 min-w-[800px]">
                <thead class="text-xs text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Last Name</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">First Name</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Program</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Section</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">User Account</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($students as $student)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                                {{ $student->last_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                                {{ $student->first_name ?? 'N/A' }}</td>

                            <td class="px-6 py-4 text-gray-500 lowercase">{{ $student->email }}</td>

                            <td class="px-6 py-4 font-bold text-[#10B981] text-center uppercase">
                                {{ $student->program }}</td>
                            <td class="px-6 py-4 font-medium text-gray-600 text-center whitespace-nowrap">
                                {{ $student->year_display }}</td>

                            <td class="px-6 py-4 text-gray-700 font-medium lowercase">
                                {{ $student->username }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-3 py-1 rounded-full border border-[#10B981]/20 shadow-sm">
                                    {{ $student->status ?? 'Enrolled' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No approved students found matching "{{ $search }}".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 custom-pagination">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
