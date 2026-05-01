<x-layouts.admin title="Academic Archives">
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="glass-card rounded-[32px] overflow-hidden border-white/5 shadow-2xl shadow-black/40">
        <!-- Header -->
        <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01]">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight uppercase">Historical Academic Records</h2>
                    <p class="text-white/30 text-[10px] font-black uppercase tracking-[0.2em] mt-1">
                        @if($selectedFolder)
                            FOLDER: {{ strtoupper(str_replace('|', ' - ', $selectedFolder)) }}
                        @else
                            COLLECTION OF HISTORICAL ACADEMIC RECORDS
                        @endif
                    </p>
                </div>

                @if($selectedFolder)
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.archives.index') }}"
                           class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-white/40 hover:text-white hover:border-white/20 transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"></path></svg>
                            Back to Collection
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if(!$selectedFolder)
            <!-- Folder Grid View -->
            <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($folders as $folder)
                    <a href="{{ route('admin.archives.index', ['selectedFolder' => $folder->semester_name . '|' . $folder->academic_year_name]) }}"
                       class="group relative bg-white/[0.02] border border-white/5 rounded-3xl p-8 hover:bg-white/[0.05] hover:border-cyan-500/30 transition-all duration-500 shadow-xl overflow-hidden">
                        <!-- Background Accent -->
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-cyan-500/5 rounded-full blur-3xl group-hover:bg-cyan-500/10 transition-all"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-cyan-500/20 transition-all duration-500">
                                <svg class="w-6 h-6 text-white/20 group-hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            </div>

                            <h3 class="text-white font-black uppercase tracking-tighter text-lg leading-tight group-hover:text-cyan-400 transition-colors">
                                {{ $folder->semester_name }}
                            </h3>
                            <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.2em] mt-1">{{ $folder->academic_year_name }}</p>

                            <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/40 group-hover:text-white transition-colors">
                                    {{ $folder->student_count }} Students
                                </span>
                                <div class="w-6 h-6 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-cyan-500 group-hover:text-white transition-all text-white/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 text-center opacity-20">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <p class="font-black uppercase tracking-[0.3em]">Archive Repository Empty</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Data Table View for Selected Folder -->
            <div class="p-8 md:p-10 border-b border-white/5 bg-white/[0.01] space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <form action="{{ route('admin.archives.index') }}" method="GET" class="md:col-span-2 relative group">
                        <input type="hidden" name="selectedFolder" value="{{ $selectedFolder }}">
                        <input type="hidden" name="level" value="{{ $level }}">
                        <input type="hidden" name="selectedCourse" value="{{ $selectedCourse }}">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/20 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search students in this folder..."
                            class="w-full bg-white/[0.03] border border-white/10 rounded-2xl pl-12 pr-4 py-3 text-xs text-white placeholder:text-white/20 focus:border-cyan-500/50 outline-none transition-all font-bold uppercase tracking-widest">
                    </form>

                    <select onchange="window.location.href=this.value" class="bg-white/[0.03] border border-white/10 rounded-2xl px-4 py-3 text-[10px] text-white/60 font-black uppercase tracking-widest focus:border-cyan-500/50 outline-none transition-all">
                        <option value="{{ route('admin.archives.index', ['selectedFolder' => $selectedFolder, 'search' => $search, 'selectedCourse' => $selectedCourse, 'level' => '']) }}">ALL LEVELS</option>
                        <option value="{{ route('admin.archives.index', ['selectedFolder' => $selectedFolder, 'search' => $search, 'selectedCourse' => $selectedCourse, 'level' => 'college']) }}" {{ $level === 'college' ? 'selected' : '' }}>COLLEGE</option>
                        <option value="{{ route('admin.archives.index', ['selectedFolder' => $selectedFolder, 'search' => $search, 'selectedCourse' => $selectedCourse, 'level' => 'shs']) }}" {{ $level === 'shs' ? 'selected' : '' }}>SHS</option>
                    </select>

                    <select onchange="window.location.href=this.value" class="bg-white/[0.03] border border-white/10 rounded-2xl px-4 py-3 text-[10px] text-white/60 font-black uppercase tracking-widest focus:border-cyan-500/50 outline-none transition-all">
                        <option value="{{ route('admin.archives.index', ['selectedFolder' => $selectedFolder, 'search' => $search, 'level' => $level, 'selectedCourse' => '']) }}">ALL COURSES</option>
                        @foreach($courses as $course)
                            <option value="{{ route('admin.archives.index', ['selectedFolder' => $selectedFolder, 'search' => $search, 'level' => $level, 'selectedCourse' => $course->course_code]) }}" {{ $selectedCourse === $course->course_code ? 'selected' : '' }}>
                                {{ $course->course_code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse font-bold">
                    <thead>
                        <tr class="text-[10px] text-white/20 uppercase tracking-[0.2em] bg-white/[0.02]">
                            <th class="py-6 px-8">Identifier</th>
                            <th class="py-6 px-8">Student Persona</th>
                            <th class="py-6 px-8 text-center">Academic Track</th>
                            <th class="py-6 px-8 text-center">Section</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-white/5">
                        @forelse($applications as $app)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-6 px-8 text-white/20 font-mono tracking-tighter">#{{ str_pad($app->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-white group-hover:text-cyan-400 transition-colors uppercase tracking-wider block">{{ $app->user->last_name }}, {{ $app->user->first_name }}</span>
                                        <span class="text-[9px] text-white/20 uppercase tracking-widest mt-0.5">{{ $app->user->email }}</span>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <span class="text-cyan-400 uppercase tracking-widest font-black text-[10px]">{{ $app->course_code }}</span>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <span class="text-white/40 uppercase tracking-widest font-black text-[10px]">{{ explode('|', $app->year_level)[0] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center opacity-20">
                                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">No Records in this Collection</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                    {{ $applications->appends(request()->query())->links('pagination') }}
                </div>
            @endif
        @endif
    </div>
</div>
</x-layouts.admin>
