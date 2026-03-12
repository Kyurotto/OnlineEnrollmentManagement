<div>
    @if(session('success'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded relative mb-6 shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Programs List</h2>
            <button wire:click="openModal" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition shadow-sm">
                Add New Program
            </button>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs text-gray-600 uppercase tracking-wider bg-gray-50">
                        <th class="py-3 px-4 font-bold">ID</th>
                        <th class="py-3 px-4 font-bold">Program Name</th>
                        <th class="py-3 px-4 font-bold">Description</th>
                        <th class="py-3 px-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm bg-white">
                    @forelse($programs as $program)
                    <tr wire:key="program-{{ $program->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition group">
                        <td class="py-4 px-4 text-gray-500 font-mono">#{{ $program->id }}</td>
                        <td class="py-4 px-4 font-bold text-gray-900">{{ $program->course_name }}</td>
                        <td class="py-4 px-4 text-gray-600">{{ $program->description }}</td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex justify-end gap-4">
                                <button wire:click="editModal({{ $program->id }})"
                                    class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-widest transition">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $program->id }})"
                                    wire:confirm="Are you sure you want to delete this program?"
                                    class="text-rose-500 hover:text-rose-600 text-xs font-bold uppercase tracking-widest transition">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500 italic text-sm">No programs found in the database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($programs, 'links'))
        <div class="mt-6 border-t border-gray-200 pt-4">
            {{ $programs->links() }}
        </div>
        @endif
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-6">{{ $isEditMode ? 'Edit Program' : 'Add New Program' }}</h3>

            <form wire:submit.prevent="save">
                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Program Name</label>
                    <input type="text" wire:model.defer="course_name"
                           class="w-full bg-white text-gray-900 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm"
                           placeholder="e.g. BS Information Systems" required>
                    @error('course_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description (Optional)</label>
                    <textarea wire:model.defer="description" rows="3"
                        class="w-full bg-white text-gray-900 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm"
                        placeholder="Brief description of the program"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end items-center gap-3">
                    <button type="button" wire:click="closeModal"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 rounded shadow-sm transition">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all">
                        <span wire:loading.remove wire:target="save">Save Program</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
