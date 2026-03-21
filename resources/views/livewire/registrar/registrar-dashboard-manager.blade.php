<div class="space-y-6" wire:poll.3s>

    {{-- ———————————————————————————————————————————————————————
         SECTION 1 — Academic Operations Calendar
    ——————————————————————————————————————————————————————— --}}
    <div class="p-6 rounded-2xl border"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl" style="background: rgba(34,211,238,0.15); color: #22d3ee;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white leading-none">Welcome, Registrar</h2>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.5);">School Operations & Registry</p>
                </div>
            </div>
            <div class="px-4 py-1.5 rounded-full text-xs font-bold tracking-wide"
                 style="background: rgba(34,211,238,0.15); border: 1px solid rgba(34,211,238,0.3); color: #22d3ee;">
                {{ $weekRange }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            @foreach ($weekDates as $day)
                <div class="rounded-xl flex flex-col h-[380px]"
                     style="{{ $day['is_today']
                        ? 'border: 1px solid rgba(34,211,238,0.5); background: rgba(34,211,238,0.08); box-shadow: 0 0 20px rgba(34,211,238,0.15);'
                        : 'border: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.03);' }}">

                    <div class="text-center py-3 rounded-t-xl"
                         style="{{ $day['is_today']
                            ? 'border-bottom: 1px solid rgba(34,211,238,0.3); background: rgba(34,211,238,0.12);'
                            : 'border-bottom: 1px solid rgba(255,255,255,0.07);' }}">
                        <p class="text-xs font-bold uppercase tracking-widest"
                           style="color: {{ $day['is_today'] ? '#22d3ee' : 'rgba(255,255,255,0.35)' }};">
                            {{ $day['day_name'] }}</p>
                        <p class="text-2xl font-bold mt-0.5"
                           style="color: {{ $day['is_today'] ? '#67e8f9' : 'rgba(255,255,255,0.8)' }};">
                            {{ $day['day_num'] }}</p>
                        @if($day['is_today'])
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-black uppercase tracking-tighter bg-cyan-500 text-black">Current</span>
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
                                        $cardBg   = 'rgba(34,211,238,0.08)';
                                        $cardBdr  = 'rgba(34,211,238,0.3)';
                                        $dotColor = '#22d3ee';
                                        $txtColor = '#67e8f9';
                                    } else {
                                        $cardBg   = 'rgba(255,255,255,0.04)';
                                        $cardBdr  = 'rgba(255,255,255,0.1)';
                                        $dotColor = 'rgba(255,255,255,0.4)';
                                        $txtColor = 'rgba(255,255,255,0.5)';
                                    }
                                @endphp
                                <div class="p-2.5 rounded-lg transition-all border group/card"
                                     style="background: {{ $cardBg }}; border-color: {{ $cardBdr }};">
                                    <p class="text-xs font-bold text-white truncate uppercase tracking-tight"
                                       title="{{ $app->user->name ?? 'Unknown Student' }}">
                                        {{ $app->user->name ?? 'Unknown Student' }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                              style="background: {{ $dotColor }}; shadow: 0 0 5px {{ $dotColor }};"></span>
                                        <span class="text-xs font-black uppercase tracking-widest"
                                              style="color: {{ $txtColor }};">{{ $app->status === 'Enrolled' ? 'Paid' : $app->status }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ———————————————————————————————————————————————————————
         SECTION 2 — Quick Actions
    ——————————————————————————————————————————————————————— --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Student Registry --}}
        <a wire:navigate href="{{ route('registrar.students.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(167,139,250,0.08); border-color: rgba(167,139,250,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(167,139,250,0.15)'; this.style.borderColor='rgba(167,139,250,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(167,139,250,0.08)'; this.style.borderColor='rgba(167,139,250,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(167,139,250,0.2);">
                    <svg class="w-6 h-6" style="color: #a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Students</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Admissions Queue --}}
        <a wire:navigate href="{{ route('registrar.applications.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(34,211,238,0.08); border-color: rgba(34,211,238,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(34,211,238,0.15)'; this.style.borderColor='rgba(34,211,238,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(34,211,238,0.08)'; this.style.borderColor='rgba(34,211,238,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(34,211,238,0.2);">
                    <svg class="w-6 h-6" style="color: #22d3ee;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Applications</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Program Mgmt --}}
        <a wire:navigate href="{{ route('registrar.programs.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(129,140,248,0.08); border-color: rgba(129,140,248,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(129,140,248,0.15)'; this.style.borderColor='rgba(129,140,248,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(129,140,248,0.08)'; this.style.borderColor='rgba(129,140,248,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(129,140,248,0.2);">
                    <svg class="w-6 h-6" style="color: #818cf8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Programs</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- School Periods --}}
        <a wire:navigate href="{{ route('registrar.academic_years.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(251,191,36,0.15)'; this.style.borderColor='rgba(251,191,36,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(251,191,36,0.08)'; this.style.borderColor='rgba(251,191,36,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(251,191,36,0.2);">
                    <svg class="w-6 h-6" style="color: #fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Academic Years</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Academic Terms --}}
        <a wire:navigate href="{{ route('registrar.semesters.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(45,212,191,0.08); border-color: rgba(45,212,191,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(45,212,191,0.15)'; this.style.borderColor='rgba(45,212,191,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(45,212,191,0.08)'; this.style.borderColor='rgba(45,212,191,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(45,212,191,0.2);">
                    <svg class="w-6 h-6" style="color: #2dd4bf;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Semesters</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

        {{-- Sectioning Blocks --}}
        <a wire:navigate href="{{ route('registrar.sections.index') }}" class="block group">
            <div class="p-5 rounded-2xl border h-full transition-all duration-300 flex items-center gap-4"
                 style="background: rgba(244,114,182,0.08); border-color: rgba(244,114,182,0.2); box-shadow: 0 2px 12px rgba(0,0,0,0.2);"
                 onmouseover="this.style.background='rgba(244,114,182,0.15)'; this.style.borderColor='rgba(244,114,182,0.5)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(244,114,182,0.08)'; this.style.borderColor='rgba(244,114,182,0.2)'; this.style.transform='translateY(0)';">
                <div class="p-3 rounded-xl flex-shrink-0" style="background: rgba(244,114,182,0.2);">
                    <svg class="w-6 h-6" style="color: #f472b6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Manage Sections</h3>
                    <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.45);"></p>
                </div>
            </div>
        </a>

    </div>

    {{-- ———————————————————————————————————————————————————————
         SECTION 3 — Overview
    ——————————————————————————————————————————————————————— --}}
    <div class="p-6 rounded-2xl border"
         style="background: rgba(255,255,255,0.06); backdrop-filter: blur(16px); border-color: rgba(255,255,255,0.10); box-shadow: 0 4px 24px rgba(0,0,0,0.3);">

        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <span class="w-1 h-5 rounded-full inline-block" style="background: #22d3ee;"></span>
            Overview
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Active Sessions --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(167,139,250,0.08); border-color: rgba(167,139,250,0.2);">
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #a78bfa;">Total Students</div>
                <div class="text-3xl font-black text-white">{{ $stats['students'] }}</div>
            </div>
            {{-- Admissions --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(34,211,238,0.08); border-color: rgba(34,211,238,0.2);">
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #22d3ee;">Applications</div>
                <div class="text-3xl font-black text-white">{{ $stats['applications'] ?? 0 }}</div>
            </div>
            {{-- Registry --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(129,140,248,0.08); border-color: rgba(129,140,248,0.2);">
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #818cf8;">Total Programs</div>
                <div class="text-3xl font-black text-white">{{ $stats['programs'] }}</div>
            </div>
            {{-- Programs --}}
            <div class="p-4 rounded-xl border text-center"
                 style="background: rgba(244,114,182,0.08); border-color: rgba(244,114,182,0.2);">
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #f472b6;">Sections</div>
                <div class="text-3xl font-black text-white">{{ $stats['sections'] }}</div>
            </div>
        </div>
    </div>

</div>
