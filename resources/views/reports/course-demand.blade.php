<x-layouts.admin title="Course Demand Report">
    <div class="space-y-6 animate-in fade-in duration-500">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-tight">Course Demand Analysis</h2>
                <p class="text-slate-500 text-xs font-black uppercase tracking-[0.2em] mt-1">Proactive Monitoring of Course Capacity</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-slate-900 text-white text-xs font-black py-3 px-6 rounded-xl uppercase tracking-widest transition-all hover:bg-slate-800 active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H11a2 2 0 00-2 2v4a2 2 0 002 2zm0 0V11"></path></svg>
                    Print Report
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-medium text-sm">
                    <thead class="text-xs text-slate-400 uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="py-5 px-8 font-black">Course Name</th>
                            <th class="py-5 px-8 font-black text-center">Total Capacity</th>
                            <th class="py-5 px-8 font-black text-center">Applicants</th>
                            <th class="py-5 px-8 font-black text-center">Demand %</th>
                            <th class="py-5 px-8 font-black text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reportData as $item)
                        <tr class="hover:bg-slate-50 transition-all group">
                            <td class="py-5 px-8">
                                <span class="text-slate-800 font-bold group-hover:text-blue-600 transition-colors uppercase tracking-tight">
                                    {{ $item['course']->course_name }} ({{ $item['course']->course_code }})
                                </span>
                            </td>
                            <td class="py-5 px-8 text-center text-slate-500 font-bold">{{ $item['capacity'] }}</td>
                            <td class="py-5 px-8 text-center text-slate-500 font-bold">{{ $item['demand'] }}</td>
                            <td class="py-5 px-8 text-center">
                                <span class="font-mono font-black {{ $item['percentage'] >= 90 ? 'text-rose-600' : ($item['percentage'] >= 70 ? 'text-amber-600' : 'text-emerald-600') }}">
                                    {{ $item['percentage'] }}%
                                </span>
                            </td>
                            <td class="py-5 px-8 text-center">
                                @php
                                    $color = match($item['status']) {
                                        'Critical' => 'bg-rose-100 text-rose-600',
                                        'Warning' => 'bg-amber-100 text-amber-600',
                                        'Normal' => 'bg-emerald-100 text-emerald-600',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="{{ $color }} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <p class="text-xs font-black text-slate-300 uppercase tracking-[0.4em]">No demand data available</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
