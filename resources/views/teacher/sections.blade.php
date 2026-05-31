<x-layouts.teacher title="Sections">
    <div class="w-full">
        <div class="space-y-8 animate-in fade-in duration-700">

            {{-- Header --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>

                <div class="flex items-center gap-8 relative z-10">
                    <div
                        class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Sections</h2>
                        <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">All Sections —
                            Read-Only View</p>
                    </div>
                </div>
            </div>

            {{-- Sections Table --}}
            <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">All Sections</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">
                            Showing {{ $sections->firstItem() ?? 0 }} - {{ $sections->lastItem() ?? 0 }} of
                            {{ $sections->total() }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                            <tr>
                                <th class="py-5 px-6 font-black">#</th>
                                <th class="py-5 px-6 font-black">Section Name</th>
                                <th class="py-5 px-6 font-black">Program / Strand</th>
                                <th class="py-5 px-6 font-black text-center">Academic Year</th>
                                <th class="py-5 px-6 text-center font-black">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sections as $index => $section)
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td
                                        class="py-5 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                        {{ $sections->firstItem() + $index }}
                                    </td>
                                    <td class="py-5 px-6">
                                        <div
                                            class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                            {{ $section->section_name }}
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        @if ($section->course)
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100 uppercase tracking-widest">
                                                    {{ $section->course->course_code }}
                                                </span>
                                                <span
                                                    class="text-xs text-slate-500 font-medium">{{ $section->course->course_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-300 font-bold">—</span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span
                                            class="text-xs font-black text-slate-700 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100 uppercase tracking-widest">
                                            {{ $section->academic_year }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span class="text-xs text-slate-400 font-medium">
                                            {{ $section->created_at ? $section->created_at->format('M d, Y') : '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="text-slate-300">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                </path>
                                            </svg>
                                            <p class="text-sm font-black uppercase tracking-widest">No sections
                                                created yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($sections->hasPages())
                    <div class="mt-8 px-4">
                        {{ $sections->links('pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.teacher>
