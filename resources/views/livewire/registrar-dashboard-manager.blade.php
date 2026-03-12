<div class="space-y-6">
    @if (session('status'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome, Registrar</h2>
        <p class="text-gray-500 mb-2">Manage academic records and configuration.</p>
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-[#10B981]/10 p-2 rounded-lg text-[#10B981]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg text-gray-900">Application Summary This Month</h3>
            </div>
            <div class="px-4 py-1.5 bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] text-sm font-semibold rounded-full shadow-sm">
                {{ $weekRange }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach ($weekDates as $day)
                <div class="border {{ $day['is_today'] ? 'border-[#10B981] bg-[#10B981]/5 shadow-sm' : 'border-gray-200 bg-gray-50' }} rounded-xl flex flex-col h-[400px]">

                    <div class="text-center py-4 border-b {{ $day['is_today'] ? 'border-[#10B981]/30 bg-[#10B981]/10 rounded-t-xl' : 'border-gray-200' }}">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $day['day_name'] }}</p>
                        <p class="text-2xl font-bold {{ $day['is_today'] ? 'text-[#10B981]' : 'text-gray-900' }} mt-1">{{ $day['day_num'] }}</p>
                    </div>

                    <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar bg-white rounded-b-xl">
                        @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp

                        @if ($dayApps->isEmpty())
                            <div class="h-full flex items-center justify-center text-gray-400 italic text-[11px] uppercase tracking-wider font-bold">
                                NO APPLICATIONS
                            </div>
                        @else
                            @foreach ($dayApps as $app)
                                @php
                                    $borderColor = 'border-gray-200';
                                    $dotColor = 'bg-gray-400';
                                    $textColor = 'text-gray-500';
                                    if ($app->status === 'Pending') {
                                        $borderColor = 'border-amber-300 bg-amber-50';
                                        $dotColor = 'bg-amber-400';
                                        $textColor = 'text-amber-600';
                                    } elseif (in_array($app->status, ['Enrolled', 'Approved'])) {
                                        $borderColor = 'border-[#10B981]/30 bg-[#10B981]/5';
                                        $dotColor = 'bg-[#10B981]';
                                        $textColor = 'text-[#10B981]';
                                    }
                                @endphp

                                <div class="bg-white p-3 rounded-lg border {{ $borderColor }} shadow-sm hover:shadow-md transition cursor-default">
                                    <p class="text-sm font-bold text-gray-900 truncate" title="{{ $app->user->first_name ?? ($app->first_name ?? 'Unknown') }} {{ $app->user->last_name ?? ($app->last_name ?? '') }}">
                                        {{ $app->user->first_name ?? ($app->first_name ?? 'Unknown') }} {{ $app->user->last_name ?? ($app->last_name ?? '') }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider {{ $textColor }}">{{ $app->status }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <a wire:navigate href="{{ route('registrar.students.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage Students</h3>
                    <p class="text-sm text-gray-500 mt-2">View and update student records.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </a>

        <a wire:navigate href="{{ route('registrar.applications.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage Applications</h3>
                    <p class="text-sm text-gray-500 mt-2">Review, accept, or decline.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </a>

        <a wire:navigate href="{{ route('registrar.programs.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage Programs</h3>
                    <p class="text-sm text-gray-500 mt-2">Add or edit academic programs.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </a>

        <a wire:navigate href="{{ route('registrar.academic-years.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Academic Years</h3>
                    <p class="text-sm text-gray-500 mt-2">Open/Close school years.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </a>

        <a wire:navigate href="{{ route('registrar.semesters.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage Semesters</h3>
                    <p class="text-sm text-gray-500 mt-2">Set active semester.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
            </div>
        </a>

        <a wire:navigate href="{{ route('registrar.sections.index') }}" class="block">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage Sections</h3>
                    <p class="text-sm text-gray-500 mt-2">Organize student blocks.</p>
                </div>
                <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-lg text-gray-900 mb-6">System Overview</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Students</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Applications</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['applications'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Enrolled</div>
                    <div class="text-2xl font-bold text-[#10B981]">{{ $stats['enrolled'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Programs</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['programs'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
