<x-layouts.admin>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center gap-4">
                <a href="{{ route('admin.courses.index') }}" class="p-2 rounded-xl bg-white/5 border border-white/10 text-white/40 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">Edit Academic Program</h2>
                    <p class="text-blue-400/60 text-xs font-bold uppercase tracking-widest mt-1">Updating: {{ $course->course_code }}</p>
                </div>
            </div>

            <div class="p-10 rounded-3xl border"
                 style="background: rgba(255,255,255,0.05); backdrop-filter: blur(20px); border-color: rgba(255,255,255,0.1); box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
                
                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest px-1">Program Code</label>
                            <input type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}" 
                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold tracking-tight shadow-inner">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest px-1">Credit Units</label>
                            <input type="number" name="credits" value="{{ old('credits', $course->credits) }}" 
                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-black shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest px-1">Full Nomenclature</label>
                        <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold tracking-tight shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest px-1">Detailed Description</label>
                        <textarea name="description" rows="4" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder-white/20 shadow-inner resize-none">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="pt-6 flex flex-col gap-4">
                        <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-400 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-500/20 transition-all uppercase tracking-widest text-sm">
                            Update
                        </button>
                        <a href="{{ route('admin.courses.index') }}" 
                            class="w-full text-center text-xs font-black text-white/30 hover:text-white uppercase tracking-widest transition py-3">
                            Discard and Return
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>