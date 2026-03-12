<div>
    @if(session('success'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 font-medium shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
        <div wire:loading class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#10B981]"></div>
        </div>

        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
            <h3 class="font-bold text-lg text-gray-900 mb-6">{{ $isEditMode ? 'Edit Course' : 'Add Course' }}</h3>
            
            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Code</label>
                    <input type="text" wire:model="course_code" placeholder="e.g. BSIT" class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm">
                    @error('course_code') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course Name</label>
                    <input type="text" wire:model="course_name" placeholder="Full Course Title" class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm">
                    @error('course_name') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Credits</label>
                    <input type="number" wire:model="credits" class="w-20 bg-white border border-gray-300 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all text-center shadow-sm">
                    @error('credits') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-white border border-gray-300 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm"></textarea>
                    @error('description') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-bold py-2.5 px-4 rounded shadow-md shadow-[#10B981]/10 transition-all uppercase tracking-wide text-xs">
                        {{ $isEditMode ? 'Update Course' : 'Add Course' }}
                    </button>
                    
                    @if($isEditMode)
                    <button type="button" wire:click="cancelEdit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded shadow-md transition-all uppercase tracking-wide text-xs">
                        Cancel
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-900">Existing Courses <span class="text-gray-500 text-sm font-normal">({{ count($courses) }})</span></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg hidden sm:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Credits</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $course)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-[#10B981]">{{ $course->course_code }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900 font-medium uppercase">{{ $course->course_name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $course->credits }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex flex-col gap-2">
                                    <button wire:click="edit({{ $course->id }})" class="bg-white border border-gray-300 text-gray-700 hover:text-gray-900 hover:bg-gray-100 hover:border-gray-400 shadow-sm px-3 py-1 rounded text-[10px] uppercase font-bold transition-all w-full text-center">Edit</button>
                                    
                                    <button wire:click="destroy({{ $course->id }})" onclick="confirm('Are you sure you want to delete this course?') || event.stopImmediatePropagation()" class="w-full bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-500 hover:text-white px-3 py-1 rounded text-[10px] uppercase font-bold transition-all shadow-sm">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
