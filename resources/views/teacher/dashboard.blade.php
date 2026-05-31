<x-layouts.teacher title="Teacher Dashboard">
    <div class="w-full">
        <div class="space-y-8 animate-in fade-in duration-700">

            {{-- SECTION 1 — Header --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                        </path>
                    </svg>
                </div>

                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-8">
                        <div
                            class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Academic
                                Overview
                            </h2>
                            <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">
                                Welcome, {{ Auth::user()->first_name ?? Auth::user()->name }} —
                                @if ($activeYear)
                                    {{ $activeYear->year_name }}
                                @else
                                    No Active Year
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2 — Core Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Enrolled Students --}}
                <div class="p-8 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2">Enrolled
                                Students</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                {{ $enrolledCount }}
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-blue-50 text-blue-600 group-hover:rotate-12 transition-transform shadow-lg shadow-blue-600/5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                            class="bg-blue-600 h-full w-[70%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-blue-600/30">
                        </div>
                    </div>
                    <p class="text-[10px] mt-4 font-black text-slate-400 uppercase tracking-widest">Total Active
                        Enrollments</p>
                </div>

                {{-- SHS Count --}}
                <div class="p-8 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.25em] mb-2">SHS
                                Students</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                {{ $shsCount }}
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:rotate-12 transition-transform shadow-lg shadow-emerald-600/5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                            class="bg-emerald-500 h-full w-[50%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-emerald-500/30">
                        </div>
                    </div>
                    <p class="text-[10px] mt-4 font-black text-slate-400 uppercase tracking-widest">Senior High School
                    </p>
                </div>

                {{-- College Count --}}
                <div class="p-8 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.25em] mb-2">
                                College Students</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                {{ $collegeCount }}
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-600/5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                            class="bg-indigo-500 h-full w-[60%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-indigo-500/30">
                        </div>
                    </div>
                    <p class="text-[10px] mt-4 font-black text-slate-400 uppercase tracking-widest">Higher Education</p>
                </div>

                {{-- Sections --}}
                <div class="p-8 rounded-[2rem] border group transition-all duration-500 hover:scale-[1.02] bg-white shadow-xl shadow-blue-900/5"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-[0.25em] mb-2">
                                Sections</h4>
                            <div
                                class="text-4xl font-black text-slate-900 tracking-tighter transition-transform group-hover:translate-x-1 duration-500">
                                {{ $sectionsCount }}
                            </div>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-amber-50 text-amber-600 group-hover:rotate-12 transition-transform shadow-lg shadow-amber-600/5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                            class="bg-amber-500 h-full w-[40%] group-hover:w-full transition-all duration-1000 shadow-sm shadow-amber-500/30">
                        </div>
                    </div>
                    <p class="text-[10px] mt-4 font-black text-slate-400 uppercase tracking-widest">Active Sections</p>
                </div>
            </div>

            {{-- SECTION 3 — Programs Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-8 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5 group hover:scale-[1.01] transition-all duration-500"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 rounded-xl bg-blue-50">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">College
                                Programs</p>
                            <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ $programsCount }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Active degree programs available for enrollment.</p>
                </div>
                <div class="p-8 rounded-[2rem] border bg-white shadow-xl shadow-blue-900/5 group hover:scale-[1.01] transition-all duration-500"
                    style="border-color: rgba(37,99,235,0.1);">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 rounded-xl bg-emerald-50">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">SHS Strands</p>
                            <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ $strandsCount }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Active Senior High School strands.</p>
                </div>
            </div>

            {{-- SECTION 4 — Recent Enrollments Table --}}
            <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Recent Enrolled Students</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">
                            Latest 10 Enrollments</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                            <tr>
                                <th class="py-5 px-6 font-black">Student Name</th>
                                <th class="py-5 px-6 font-black">Email</th>
                                <th class="py-5 px-6 font-black text-center">Program</th>
                                <th class="py-5 px-6 font-black text-center">Year Level</th>
                                <th class="py-5 px-6 text-center font-black">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentEnrollments as $enrollment)
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td class="py-5 px-6">
                                        <div
                                            class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                            {{ $enrollment->last_name }}, {{ $enrollment->first_name }}
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <span class="text-xs text-slate-500 font-medium">{{ $enrollment->email }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span
                                            class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100 uppercase tracking-widest">
                                            {{ $enrollment->course_code }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span
                                            class="text-xs font-bold text-slate-600">{{ $enrollment->year_level }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span
                                            class="bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full font-black text-[10px] border border-emerald-100 uppercase tracking-widest">
                                            {{ $enrollment->status }}
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
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            <p class="text-sm font-black uppercase tracking-widest">No enrolled
                                                students yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-layouts.teacher>
