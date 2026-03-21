<div class="space-y-6" wire:poll.3s>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 1 — Application Calendar
    ═══════════════════════════════════════════════════════ --}}
    <div class="p-6 rounded-2xl border"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl" style="background: rgba(99,179,237,0.15); color: #63b3ed;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white leading-none">Welcome, Administrator</h2>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">Application Summary This Month</p>
                </div>
            </div>
            <div class="px-4 py-1.5 rounded-full text-xs font-bold tracking-wide"
                 style="background: rgba(99,179,237,0.15); border: 1px solid rgba(99,179,237,0.3); color: #63b3ed;">
                {{ $weekRange }}
            </div>
        </div>

        {{-- 5-Day Calendar --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @foreach ($weekDates as $day)
                <div class="rounded-xl flex flex-col h-[380px]"
                     style="{{ $day['is_today']
                        ? 'border: 1px solid rgba(99,179,237,0.5); background: rgba(99,179,237,0.08); box-shadow: 0 0 20px rgba(99,179,237,0.15);'
                        : 'border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.03);' }}">

                    <div class="text-center py-3 rounded-t-xl"
                         style="{{ $day['is_today']
                            ? 'border-bottom: 1px solid rgba(99,179,237,0.3); background: rgba(99,179,237,0.12);'
                            : 'border-bottom: 1px solid rgba(255,255,255,0.07);' }}">
                        <p class="text-[10px] font-bold uppercase tracking-widest"
                           style="color: {{ $day['is_today'] ? '#63b3ed' : 'rgba(255,255,255,0.35)' }};">
                            {{ $day['day_name'] }}</p>
                        <p class="text-2xl font-bold mt-0.5"
                           style="color: {{ $day['is_today'] ? '#90cdf4' : 'rgba(255,255,255,0.8)' }};">
                            {{ $day['day_num'] }}</p>
                        @if($day['is_today'])
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter bg-blue-400 text-black">Current</span>
                        @endif
                    </div>

                    <div class="p-2.5 flex-1 overflow-y-auto space-y-2 custom-scrollbar">
                        @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp

                        @if ($dayApps->isEmpty())
                            <div class="h-full flex items-center justify-center text-[10px] uppercase tracking-widest font-bold"
                                 style="color: rgba(255,255,255,0.15);">
                                No Applications
                            </div>
                        @else
                            @foreach ($dayApps as $app)
                                @php
                                    if ($app->status === 'Pending') {
                                        $cardBg   = 'rgba(251,191,36,0.08)';
                                        $cardBdr  = 'rgba(251,191,36,0.3)';
                                        $dotColor = '#fbbf24';
                                        $txtColor = '#fcd34d';
                                    } elseif (in_array($app->status, ['Enrolled','Approved'])) {
                                        $cardBg   = 'rgba(52,211,153,0.08)';
                                        $cardBdr  = 'rgba(52,211,153,0.3)';
                                        $dotColor = '#34d399';
                                        $txtColor = '#6ee7b7';
                                    } else {
                                        $cardBg   = 'rgba(255,255,255,0.04)';
                                        $cardBdr  = 'rgba(255,255,255,0.1)';
                                        $dotColor = 'rgba(255,255,255,0.4)';
                                        $txtColor = 'rgba(255,255,255,0.5)';
                                    }
                                @endphp
                                <div class="p-2.5 rounded-lg transition-all cursor-default"
                                     style="background: {{ $cardBg }}; border: 1px solid {{ $cardBdr }};">
                                    <p class="text-xs font-semibold text-white truncate"
                                       title="{{ $app->user->name ?? 'Unknown Student' }}">
                                        {{ $app->user->name ?? 'Unknown Student' }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                              style="background: {{ $dotColor }};"></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider"
                                              style="color: {{ $txtColor }};">{{ $app->status }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 2 — Quick Action Cards
    ═══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Courses --}}
        <a wire:navigate href="{{ route('admin.courses.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(99,179,237,0.08); border-color: rgba(99,179,237,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(99,179,237,0.15)'; this.style.borderColor='rgba(99,179,237,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(99,179,237,0.08)'; this.style.borderColor='rgba(99,179,237,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(99,179,237,0.2);">
                    <svg class="w-6 h-6" style="color: #63b3ed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Courses</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Students --}}
        <a wire:navigate href="{{ route('admin.students.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(167,139,250,0.08); border-color: rgba(167,139,250,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(167,139,250,0.15)'; this.style.borderColor='rgba(167,139,250,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(167,139,250,0.08)'; this.style.borderColor='rgba(167,139,250,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(167,139,250,0.2);">
                    <svg class="w-6 h-6" style="color: #a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Students</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Payments --}}
        <a wire:navigate href="{{ route('admin.payments.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(52,211,153,0.08); border-color: rgba(52,211,153,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(52,211,153,0.15)'; this.style.borderColor='rgba(52,211,153,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(52,211,153,0.08)'; this.style.borderColor='rgba(52,211,153,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(52,211,153,0.2);">
                    <svg class="w-6 h-6" style="color: #34d399;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Payments</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Applications --}}
        <a wire:navigate href="{{ route('admin.applications.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(251,146,60,0.08); border-color: rgba(251,146,60,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(251,146,60,0.15)'; this.style.borderColor='rgba(251,146,60,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(251,146,60,0.08)'; this.style.borderColor='rgba(251,146,60,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(251,146,60,0.2);">
                    <svg class="w-6 h-6" style="color: #fb923c;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Applications</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SECTION 3 — Overview Stats
    ═══════════════════════════════════════════════════════ --}}
    <div class="p-6 rounded-2xl border"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background: #63b3ed;"></span>
            Overview
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            {{-- Active Courses --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(99,179,237,0.08); border-color: rgba(99,179,237,0.2);">
                <div class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color: #63b3ed;">Active Courses</div>
                <div class="text-3xl font-black text-white">{{ $stats['active_courses'] }}</div>
            </div>
            {{-- Total Students --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(167,139,250,0.08); border-color: rgba(167,139,250,0.2);">
                <div class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color: #a78bfa;">Total Students</div>
                <div class="text-3xl font-black text-white">{{ $stats['students'] }}</div>
            </div>
            {{-- Enrolled --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(52,211,153,0.08); border-color: rgba(52,211,153,0.2);">
                <div class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color: #34d399;">Enrolled</div>
                <div class="text-3xl font-black" style="color: #6ee7b7;">{{ $stats['enrolled'] ?? 0 }}</div>
            </div>
            {{-- Payments --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(251,146,60,0.08); border-color: rgba(251,146,60,0.2);">
                <div class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color: #fb923c;">Payments</div>
                <div class="text-3xl font-black text-white">{{ $stats['total_payments'] }}</div>
            </div>
            {{-- Applications --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.2);">
                <div class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color: #fbbf24;">Applications</div>
                <div class="text-3xl font-black text-white">{{ $stats['applications'] }}</div>
            </div>
        </div>
    </div>

</div>
