<div wire:key="course-manager-root">
    @if (session('success'))
        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">
        <!-- Removed absolute overlay to prevent hanging -->

        <div class="lg:col-span-1 p-6 rounded-2xl border"
             style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">
            <form wire:submit.prevent="save" wire:key="course-form">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-blue-300 uppercase tracking-widest mb-2 px-1">Course Code</label>
                    <input type="text" wire:model="course_code" placeholder="e.g. BSIT"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner">
                    @error('course_code')
                        <span class="text-xs text-rose-400 font-bold uppercase mt-1 px-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-blue-300 uppercase tracking-widest mb-2 px-1">Course Name</label>
                    <input type="text" wire:model="course_name" placeholder="Full Course Title"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner">
                    @error('course_name')
                        <span class="text-xs text-rose-400 font-bold uppercase mt-1 px-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-blue-300 uppercase tracking-widest mb-2 px-1">Credits Units</label>
                    <input type="number" wire:model="credits"
                        class="w-24 bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-center shadow-inner font-bold">
                    @error('credits')
                        <span class="text-xs text-rose-400 font-bold uppercase mt-1 px-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-blue-300 uppercase tracking-widest mb-2 px-1">Description</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner resize-none"></textarea>
                    @error('description')
                        <span class="text-xs text-rose-400 font-bold uppercase mt-1 px-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="w-full bg-blue-500 hover:bg-blue-400 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black py-4 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ $isEditMode ? 'Update Course' : 'Create Course' }}</span>
                    </button>

                    @if ($isEditMode)
                        <button type="button" wire:click="cancelEdit"
                            class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 px-4 rounded-xl border border-white/10 transition-all uppercase tracking-widest text-xs">
                            Cancel Edit
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 p-6 rounded-2xl border flex flex-col"
             style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full bg-blue-500 inline-block"></span>
                    Existing Courses
                    <span class="text-white/40 text-xs font-normal ml-1">({{ count($courses) }})</span>
                </h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-white/5 border border-white/5 rounded-2xl hidden sm:table overflow-hidden">
                    <thead style="background: rgba(255,255,255,0.03);">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold text-blue-300 uppercase tracking-widest">
                                Code</th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-blue-300 uppercase tracking-widest">
                                Course Name</th>
                            <th class="px-5 py-4 text-center text-xs font-bold text-blue-300 uppercase tracking-widest">
                                Units</th>
                            <th class="px-5 py-4 text-center text-xs font-bold text-blue-300 uppercase tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($courses as $course)
                            <tr wire:key="course-{{ $course->id }}" class="hover:bg-white/5 transition-all group">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-black text-blue-400 bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20 shadow-sm">
                                        {{ $course->course_code }}
                                    </span>
                                </td>
                                <td class="px-5 py-5">
                                    <div class="text-sm text-white font-bold uppercase tracking-tight group-hover:text-blue-200 transition-colors">
                                        {{ $course->course_name }}
                                    </div>
                                    @if($course->description)
                                        <div class="text-xs text-white/30 truncate max-w-xs mt-0.5">
                                            {{ $course->description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap text-sm text-white/60 text-center font-bold">
                                    {{ $course->credits }}
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="edit({{ $course->id }})"
                                            class="p-2 rounded-lg bg-white/5 border border-white/10 text-blue-300 hover:bg-blue-500 hover:text-white hover:border-blue-400 transition-all shadow-sm"
                                            title="Edit Course">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>

                                        <button wire:click="destroy({{ $course->id }})"
                                            wire:confirm="Permanent delete course: {{ $course->course_name }}?"
                                            class="p-2 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white hover:border-rose-400 transition-all shadow-sm"
                                            title="Delete Course">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $courses->links('livewire.glass-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
