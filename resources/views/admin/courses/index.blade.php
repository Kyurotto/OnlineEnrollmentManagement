<x-layouts.admin>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-white tracking-tight">Course Management</h2>
                <p class="text-blue-400/60 text-xs font-bold uppercase tracking-widest mt-1">Catalog and academic offerings</p>
            </div>

            @if(session('success'))
                <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 px-4 py-3 rounded-xl relative mb-6 font-bold shadow-lg backdrop-blur-md flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add Course Form -->
                <div class="lg:col-span-1 p-8 rounded-2xl border h-fit"
                     style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">
                    <h3 class="font-black text-white uppercase tracking-widest text-xs mb-8 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Register New Course
                    </h3>
                    
                    <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2 px-1">Code</label>
                            <input type="text" name="course_code" placeholder="BSIT" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2 px-1">Full Name</label>
                            <input type="text" name="course_name" placeholder="Bachelor of Science in IT" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2 px-1">Credits</label>
                            <input type="number" name="credits" value="3" 
                                class="w-24 bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-center shadow-inner font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest mb-2 px-1">Description</label>
                            <textarea name="description" rows="3" 
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-white/20 shadow-inner resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-400 text-white font-black py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all uppercase tracking-widest text-xs">
                            Add to Catalog
                        </button>
                    </form>
                </div>

                <!-- Course List -->
                <div class="lg:col-span-2 rounded-2xl border overflow-hidden"
                     style="background: rgba(255,255,255,0.05); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/5">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <span class="w-1 h-5 rounded-full bg-blue-500 inline-block"></span>
                            Active Courses
                            <span class="text-white/40 text-xs font-normal ml-1">({{ count($courses) }})</span>
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-blue-300 uppercase tracking-widest">Code</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-blue-300 uppercase tracking-widest">Name</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-blue-300 uppercase tracking-widest">Units</th>
                                    <th class="px-6 py-4 text-center text-[10px] font-black text-blue-300 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($courses as $course)
                                <tr class="hover:bg-white/5 transition-all group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-black text-blue-400 bg-blue-500/10 px-2 py-1 rounded border border-blue-500/20">
                                            {{ $course->course_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white group-hover:text-blue-200 transition-colors uppercase">{{ $course->course_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-white/50 font-bold">{{ $course->credits }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('admin.courses.edit', $course->id) }}" 
                                               class="p-2 rounded-lg bg-white/5 border border-white/10 text-blue-300 hover:bg-blue-500 hover:text-white transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
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
    </div>
</x-layouts.admin>
html>