<x-layouts.teacher title="Student Registry">
    <div class="w-full">
        <div class="space-y-8 animate-in fade-in duration-700">

            {{-- Header --}}
            <div class="p-10 rounded-[2rem] border relative overflow-hidden group shadow-xl shadow-blue-900/5 bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div
                    class="absolute top-0 right-0 p-12 opacity-[0.03] mt-[-20px] mr-[-20px] transition-transform group-hover:scale-110 duration-700">
                    <svg class="w-64 h-64 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 relative z-10">
                    <div class="flex items-center gap-8">
                        <div
                            class="w-20 h-20 rounded-2xl bg-blue-600 border border-blue-400/20 flex items-center justify-center text-white shadow-2xl shadow-blue-600/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-4xl font-black text-slate-900 leading-tight tracking-tight">Student
                                Registry</h2>
                            <p class="text-xs mt-2 font-bold uppercase tracking-[0.25em] text-slate-400">Enrolled
                                Students — Read-Only View</p>
                        </div>
                    </div>

                    {{-- Search --}}
                    <form method="GET" action="{{ route('teacher.students.index') }}" class="flex items-center gap-3">
                        <div class="relative">
                            <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search students..."
                                class="pl-12 pr-6 py-3.5 rounded-2xl border border-slate-200 bg-white text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 w-64 transition-all">
                        </div>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black px-6 py-3.5 rounded-2xl uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-600/20">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            {{-- Students Table --}}
            <div class="p-8 rounded-[2rem] border shadow-xl shadow-blue-900/5 overflow-hidden bg-white"
                style="border-color: rgba(37,99,235,0.1);">

                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full shadow-lg shadow-blue-600/30"></div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">All Enrolled Students</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mt-1">
                            Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of
                            {{ $students->total() }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-[0.25em] border-b border-slate-50">
                            <tr>
                                <th class="py-5 px-6 font-black">#</th>
                                <th class="py-5 px-6 font-black">Student Name</th>
                                <th class="py-5 px-6 font-black">Email</th>
                                <th class="py-5 px-6 font-black text-center">Program</th>
                                <th class="py-5 px-6 font-black text-center">Year Level</th>
                                <th class="py-5 px-6 text-center font-black">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $index => $student)
                                @php
                                    $enrollment = $student->application;
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td
                                        class="py-5 px-6 font-mono text-xs text-slate-400 group-hover:text-blue-600 transition-colors">
                                        {{ $students->firstItem() + $index }}
                                    </td>
                                    <td class="py-5 px-6">
                                        <div
                                            class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-blue-600 transition-colors">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                            {{ $student->middle_name ? substr($student->middle_name, 0, 1) . '.' : '' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-0.5">
                                            @{{ $student->username }}</div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <span
                                            class="text-xs text-slate-500 font-medium">{{ $student->email }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        @if ($enrollment)
                                            <span
                                                class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100 uppercase tracking-widest">
                                                {{ $enrollment->course_code }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-300 font-bold">—</span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        @if ($enrollment)
                                            <span
                                                class="text-xs font-bold text-slate-600">{{ $enrollment->year_level }}</span>
                                        @else
                                            <span class="text-xs text-slate-300 font-bold">—</span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        @if ($enrollment)
                                            @php
                                                $statusColors = match ($enrollment->status) {
                                                    'Enrolled'
                                                        => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Pending'
                                                        => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'Approved' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'Rejected'
                                                        => 'bg-rose-50 text-rose-600 border-rose-100',
                                                    default
                                                        => 'bg-slate-50 text-slate-600 border-slate-100',
                                                };
                                            @endphp
                                            <span
                                                class="{{ $statusColors }} px-4 py-1.5 rounded-full font-black text-[10px] border uppercase tracking-widest">
                                                {{ $enrollment->status }}
                                            </span>
                                        @else
                                            <span
                                                class="bg-slate-50 text-slate-400 px-4 py-1.5 rounded-full font-black text-[10px] border border-slate-100 uppercase tracking-widest">
                                                N/A
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="text-slate-300">
                                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            <p class="text-sm font-black uppercase tracking-widest">
                                                {{ $search ? 'No students found for "' . $search . '"' : 'No enrolled students yet' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($students->hasPages())
                    <div class="mt-8 px-4">
                        {{ $students->links('pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.teacher>
