<div>
    @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Semesters List</h2>
            <button wire:click="openModal" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition shadow-sm">
                Add New Semester
            </button>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="py-3 px-4 font-medium">ID</th>
                        <th class="py-3 px-4 font-medium">Academic Year</th>
                        <th class="py-3 px-4 font-medium">Name</th>
                        <th class="py-3 px-4 font-medium">Start Date</th>
                        <th class="py-3 px-4 font-medium">End Date</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm bg-white">
                    @forelse($semesters as $semester)
                    <tr wire:key="semester-{{ $semester->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition group">
                        <td class="py-4 px-4 text-gray-500">{{ $semester->id }}</td>
                        <td class="py-4 px-4 font-medium text-gray-900">{{ $semester->academic_year }}</td>
                        <td class="py-4 px-4 text-gray-600">{{ $semester->name }}</td>
                        <td class="py-4 px-4 text-gray-500">{{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }}</td>
                        <td class="py-4 px-4 text-gray-500">{{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}</td>
                        <td class="py-4 px-4">
                            @if($semester->is_active)
                                <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-2 py-1 rounded-full border border-[#10B981]/20 shadow-sm">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 border border-gray-200 text-xs font-bold px-2 py-1 rounded-full shadow-sm">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            <button wire:click="editModal({{ $semester->id }})"
                                class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-wider transition mr-3">
                                Edit
                            </button>

                            <button wire:click="delete({{ $semester->id }})" wire:confirm="Delete this semester?"
                                class="text-red-500 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-500 text-sm italic">No semesters found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $semesters->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg p-6 transform transition-all border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditMode ? 'Edit Semester' : 'Add New Semester' }}</h3>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Academic Year</label>
                        <select wire:model.defer="academic_year" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                            <option value="" disabled>Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                        @error('academic_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Semester Name</label>
                        <select wire:model.defer="name" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Start Date</label>
                        <input type="date" wire:model.defer="start_date" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">End Date</label>
                        <input type="date" wire:model.defer="end_date" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" wire:model.defer="is_active" id="is_active_checkbox" class="w-4 h-4 text-[#10B981] rounded border-gray-300 bg-white focus:ring-[#10B981]">
                    <label for="is_active_checkbox" class="ml-2 text-sm text-gray-600 font-medium cursor-pointer">Set as Active Semester</label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm text-gray-700 border border-gray-300 hover:bg-gray-50 rounded transition font-medium shadow-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded shadow transition">
                        <span wire:loading.remove wire:target="save">Save Semester</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
