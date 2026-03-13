<div>
    @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Academic Years List</h2>
            <button wire:click="openModal" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition shadow-sm">
                Add New Academic Year
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="py-3 px-4 font-medium">ID</th>
                        <th class="py-3 px-4 font-medium">Academic Year</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm bg-white">
                    @forelse($years as $year)
                    <tr wire:key="year-{{ $year->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition group">
                        <td class="py-4 px-4 text-gray-500">{{ $year->id }}</td>
                        <td class="py-4 px-4 font-medium text-gray-900">{{ $year->year_name }}</td>
                        <td class="py-4 px-4">
                            @if($year->is_active)
                                <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-2 py-1 rounded-full border border-[#10B981]/20 shadow-sm">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 border border-gray-200 text-xs font-bold px-2 py-1 rounded-full shadow-sm">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right">
                            <button wire:click="editModal({{ $year->id }})"
                                class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-wider transition mr-3">
                                Edit
                            </button>

                            <button wire:click="delete({{ $year->id }})" wire:confirm="Are you sure you want to delete this Academic Year?"
                                class="text-red-500 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500 text-sm italic">No academic years found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $years->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-6 transform transition-all border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditMode ? 'Edit Academic Year' : 'Add New Academic Year' }}</h3>

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Academic Year</label>
                    <input type="text" wire:model.defer="year_name" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" placeholder="2025 - 2026" required>
                    @error('year_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" wire:model.defer="is_active" id="is_active" class="w-4 h-4 text-[#10B981] rounded border-gray-300 bg-white focus:ring-[#10B981]">
                    <label for="is_active" class="ml-2 text-sm text-gray-600 font-medium cursor-pointer">Set as Active Academic Year</label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 rounded transition shadow-sm font-medium">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded shadow transition">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
