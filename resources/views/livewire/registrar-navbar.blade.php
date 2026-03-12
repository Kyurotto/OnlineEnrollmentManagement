<nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">
                        RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                        <span class="text-xs text-gray-500">
                            @if(request()->routeIs('registrar.dashboard')) Dashboard
                            @elseif(request()->routeIs('registrar.students.index')) Manage Students
                            @elseif(request()->routeIs('registrar.applications.index')) Manage Applications
                            @elseif(request()->routeIs('registrar.programs.index')) Manage Programs
                            @elseif(request()->routeIs('registrar.academic_years.index')) Academic Years
                            @elseif(request()->routeIs('registrar.semesters.index')) Manage Semesters
                            @elseif(request()->routeIs('registrar.sections.index')) Manage Sections
                            @else Manage Academic Records
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex space-x-6 text-sm font-medium text-gray-600 h-16">
                    <a wire:navigate href="{{ route('registrar.dashboard') }}"
                        class="flex items-center hover:text-[#10B981] transition h-full {{ request()->routeIs('registrar.dashboard') ? 'text-[#10B981] border-b-2 border-[#10B981]' : '' }}">Dashboard</a>
                    <a wire:navigate href="{{ route('registrar.applications.index') }}"
                        class="flex items-center hover:text-[#10B981] transition h-full {{ request()->routeIs('registrar.applications.index') ? 'text-[#10B981] border-b-2 border-[#10B981]' : '' }}">Applications</a>
                    <a wire:navigate href="{{ route('registrar.students.index') }}"
                        class="flex items-center hover:text-[#10B981] transition h-full {{ request()->routeIs('registrar.students.index') ? 'text-[#10B981] border-b-2 border-[#10B981]' : '' }}">Students</a>
                    <div class="relative flex items-center h-full group">
                        <span class="flex items-center hover:text-[#10B981] transition cursor-pointer {{ request()->is('registrar/programs*') || request()->is('registrar/academic-years*') || request()->is('registrar/semesters*') || request()->is('registrar/sections*') ? 'text-[#10B981] border-b-2 border-[#10B981]' : '' }}">Settings<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></span>
                        <div class="absolute top-16 left-0 w-48 bg-white border border-gray-200 shadow-xl rounded-b-lg hidden group-hover:block z-50">
                            <a wire:navigate href="{{ route('registrar.programs.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#10B981]">Programs</a>
                            <a wire:navigate href="{{ route('registrar.academic_years.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#10B981]">Academic Years</a>
                            <a wire:navigate href="{{ route('registrar.semesters.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#10B981]">Semesters</a>
                            <a wire:navigate href="{{ route('registrar.sections.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 hover:text-[#10B981]">Sections</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications Dropdown -->
                <div class="relative cursor-pointer group mr-2" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500 transition">
                        <svg class="w-6 h-6 group-hover:text-[#10B981] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($newEnrolleesCount > 0)
                        <span class="absolute top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
                        </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition.opacity class="absolute right-0 top-12 w-80 bg-white border border-gray-200 shadow-2xl rounded-xl z-50 overflow-hidden" style="display: none;">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Notifications</h3>
                            @if($newEnrolleesCount > 0)
                            <span class="bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $newEnrolleesCount }} New</span>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto custom-scrollbar bg-gray-50 p-2 space-y-2">
                            @if (isset($notifications) && count($notifications) > 0)
                                @foreach ($notifications as $notif)
                                    <a wire:navigate href="{{ route('registrar.applications.index') }}"
                                        class="block bg-white p-3 rounded-lg border border-gray-200 hover:border-[#10B981] hover:shadow-sm transition group cursor-pointer">
                                        @if ($notif->status === 'Enrolled')
                                            @php
                                                $paidAmount = \App\Models\Payment::where('application_id', $notif->id)->value('amount');
                                            @endphp
                                            <p class="text-sm font-bold text-[#10B981] group-hover:text-[#059669] flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Student Paid
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="font-bold text-gray-900 uppercase">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }} {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span> is now <span class="font-bold text-[#10B981]">PAID ₱{{ number_format($paidAmount ?? 0, 2) }}</span>.
                                            </p>
                                        @else
                                            <p class="text-sm font-bold text-gray-900 group-hover:text-[#10B981]">New Application</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="font-medium text-gray-900 uppercase">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }} {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span> applied for <span class="uppercase font-bold text-[#10B981]">{{ $notif->course_code }}</span>.
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-gray-500 mt-2 text-right">{{ $notif->updated_at->diffForHumans() }}</p>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-6 text-gray-500 text-sm">No notifications</div>
                            @endif
                        </div>
                        <div class="bg-gray-50 p-2 border-t border-gray-200 text-center">
                            <a wire:navigate href="{{ route('registrar.applications.index') }}" class="text-xs font-bold text-[#10B981] hover:text-[#059669]">View All Applications →</a>
                        </div>
                    </div>
                </div>

                <div class="text-right hidden sm:block">
                    <div class="text-xs text-gray-500">Signed in as</div>
                    <div class="text-sm font-bold text-gray-900">Registrar</div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
