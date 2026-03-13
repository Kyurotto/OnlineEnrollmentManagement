<div>
    @if (session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Student List</h3>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th scope="col" class="py-3 px-4 font-medium">Last Name</th>
                        <th scope="col" class="py-3 px-4 font-medium">First Name</th>
                        <th scope="col" class="py-3 px-4 font-medium">Email</th>
                        <th scope="col" class="py-3 px-4 font-medium text-center">Program</th>
                        <th scope="col" class="py-3 px-4 font-medium text-center">Section</th>
                        <th scope="col" class="py-3 px-4 font-medium">Status</th>
                        <th scope="col" class="py-3 px-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm bg-white">
                    @forelse($students as $student)
                        <tr wire:key="student-{{ $student->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition group">
                            <td class="py-4 px-4 font-bold text-gray-900 uppercase">{{ $student->last_name ?? 'N/A' }}</td>
                            <td class="py-4 px-4 font-bold text-gray-900 uppercase">{{ $student->first_name ?? 'N/A' }}</td>
                            <td class="py-4 px-4 text-gray-600 lowercase">{{ $student->email }}</td>
                            <td class="py-4 px-4 font-bold text-[#10B981] text-center uppercase">{{ $student->program }}</td>
                            <td class="py-4 px-4 font-medium text-gray-600 text-center whitespace-nowrap">{{ $student->year_display }}</td>
                            <td class="py-4 px-4">
                                <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-3 py-1 rounded-full border border-[#10B981]/20 shadow-sm">
                                    {{ $student->status ?? 'Enrolled' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <button wire:click="edit({{ $student->id }})" class="text-[#10B981] hover:text-[#059669] font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer mr-2">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500 italic text-sm">No approved students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="mt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>


    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-white z-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-white p-10 rounded-xl shadow-md border border-[#ffffff]">
            <div class="flex justify-between items-center mb-8 border-b border-[#ffffff] pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Edit Student Record</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition text-2xl font-bold">&times;</button>
            </div>

            <form wire:submit.prevent="update">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">First Name</label>
                        <input type="text" wire:model="first_name" class="w-full bg-white text-gray-900 border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400 shadow-sm">
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name" class="w-full bg-white text-gray-900 border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400 shadow-sm">
                        @error('middle_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Last Name</label>
                        <input type="text" wire:model="last_name" class="w-full bg-white text-gray-900 border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400 shadow-sm">
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="w-full bg-white text-gray-900 border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none placeholder-gray-400 shadow-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                    <select wire:model="status" class="w-full bg-white text-gray-900 border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none shadow-sm cursor-pointer">
                        <option value="Not Enrolled">Not Enrolled</option>
                        <option value="Enrolled">Enrolled</option>
                        <option value="Active">Active</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" wire:click="closeModal" class="px-6 py-2.5 border border-gray-300 bg-white shadow-sm rounded text-gray-700 font-medium hover:bg-gray-50 transition text-sm">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded shadow-md shadow-[#10B981]/20 transition text-sm">
                        <span wire:loading.remove wire:target="update">Update Student</span>
                        <span wire:loading wire:target="update">Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
