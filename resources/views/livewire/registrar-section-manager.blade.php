<div>
    @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Sections List</h2>
            <button wire:click="openModal" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition shadow-sm">
                Add New Section
            </button>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="py-3 px-4 font-medium">ID</th>
                        <th class="py-3 px-4 font-medium">Academic Year</th>
                        <th class="py-3 px-4 font-medium">Section</th>
                        <th class="py-3 px-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm bg-white">
                    @forelse($sections as $section)
                    <tr wire:key="section-{{ $section->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition group">
                        <td class="py-4 px-4 text-gray-500">{{ $section->id }}</td>
                        <td class="py-4 px-4 font-medium text-gray-600">{{ $section->academic_year }}</td>
                        <td class="py-4 px-4 text-gray-900 font-bold">{{ $section->section_name }}</td>
                        <td class="py-4 px-4 text-right">
                            <button wire:click="editModal({{ $section->id }})"
                                class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-wider transition mr-3">
                                Edit
                            </button>

                            <button wire:click="delete({{ $section->id }})" wire:confirm="Delete this Section?"
                                class="text-red-500 hover:text-red-600 text-xs font-bold uppercase tracking-wider transition">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500 text-sm italic">No sections found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sections->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md p-6 transform transition-all border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditMode ? 'Edit Section' : 'Add New Section' }}</h3>

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Academic Year</label>
                    <select wire:model.defer="academic_year" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                        <option value="" disabled>Select Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
                        @endforeach
                    </select>
                    @error('academic_year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Program</label>
                    <select wire:model.defer="course_id" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none shadow-sm" required>
                        <option value="" disabled>Select Program</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ preg_replace('/[0-9]+/', '', $course->course_code) }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Section Name</label>
                    <input type="text" wire:model.defer="section_name" class="w-full bg-white border border-gray-300 text-gray-900 rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none uppercase placeholder-gray-400 shadow-sm" placeholder="e.g. BSIS 1A" required>
                    <p class="text-[10px] text-gray-500 mt-1">Enter the full section name (e.g., BSIS 1A).</p>
                    @error('section_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 border border-gray-300 rounded transition shadow-sm font-medium">Cancel</button>
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
