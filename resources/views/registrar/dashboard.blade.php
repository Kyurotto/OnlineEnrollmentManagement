<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .modal-backdrop { background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 4px; }
    </style>
</head>

<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">
                        RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                        <span class="text-xs text-gray-500">Manage Students & Applications</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer group mr-4">
                        <div class="relative">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-[#10B981] transition shadow-sm" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if (isset($newEnrolleesCount) && $newEnrolleesCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse">
                                    {{ $newEnrolleesCount }}
                                </span>
                            @endif
                        </div>

                        <div
                            class="absolute right-0 top-10 w-80 bg-white border border-gray-200 shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">NOTIFICATIONS</h3>
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar bg-gray-50 p-2 space-y-2">
                                @forelse($notifications as $notif)
                                    <div data-application="{{ json_encode($notif) }}"
                                        data-user="{{ json_encode($notif->user) }}"
                                    onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user))"
                                        class="block bg-white p-3 rounded-lg border border-gray-200 hover:border-[#10B981] hover:shadow-sm transition group cursor-pointer">
                                        @if ($notif->status === 'Enrolled')
                                            @php
                                                $paidAmount = \App\Models\Payment::where(
                                                    'application_id',
                                                    $notif->id,
                                                )->value('amount');
                                            @endphp

                                            <p
                                                class="text-sm font-bold text-[#10B981] group-hover:text-[#059669] flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Student Paid
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span
                                                    class="font-medium text-gray-900">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }}
                                                    {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span>
                                                is now already <span class="font-bold text-[#10B981]">PAID
                                                    ₱{{ number_format($paidAmount ?? 0, 2) }}</span>.
                                            </p>
                                        @else
                                            <p class="text-sm font-bold text-gray-900 group-hover:text-[#10B981]">New
                                                Application</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span
                                                    class="font-medium text-gray-900">{{ $notif->first_name ?? ($notif->user->first_name ?? '') }}
                                                    {{ $notif->last_name ?? ($notif->user->last_name ?? '') }}</span>
                                                applied for <span
                                                    class="uppercase font-bold text-[#10B981]">{{ $notif->course_code }}</span>.
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-gray-400 mt-2 text-right">
                                            {{ $notif->updated_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-gray-400 text-sm">No notifications</div>
                                @endforelse
                            </div>

                            <div class="bg-white p-2 border-t border-gray-200 text-center">
                                <a href="{{ route('registrar.applications.index') }}"
                                    class="text-xs font-bold text-[#10B981] hover:text-[#059669]">View All
                                    Applications →</a>
                            </div>
                        </div>
                    </div>

                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-500">Signed in as</div>
                        <div class="text-sm font-bold text-gray-900">Registrar</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6">
        @if (session('status'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome, Registrar</h2>
            <p class="text-gray-600 mb-2">Manage academic records and configuration.</p>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981]/10 p-2 rounded-lg text-[#10B981]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">Application Summary This Month</h3>
                </div>
                <div
                    class="px-4 py-1.5 bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] text-sm font-semibold rounded-full">
                    {{ $weekRange }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach ($weekDates as $day)
                    <div
                        class="border {{ $day['is_today'] ? 'border-[#10B981] bg-[#10B981]/5' : 'border-gray-200 bg-gray-50' }} rounded-xl flex flex-col h-[400px]">

                        <div
                            class="text-center py-4 border-b {{ $day['is_today'] ? 'border-[#10B981]/30 bg-[#10B981]/20 rounded-t-xl' : 'border-gray-200' }}">
                            <p class="text-[10px] font-bold text-[#52525B] uppercase tracking-widest">
                                {{ $day['day_name'] }}</p>
                            <p
                                class="text-2xl font-bold {{ $day['is_today'] ? 'text-[#10B981]' : 'text-gray-900' }} mt-1">
                                {{ $day['day_num'] }}</p>
                        </div>

                        <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                            @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp

                            @if ($dayApps->isEmpty())
                                <div
                                    class="h-full flex items-center justify-center text-gray-400 italic text-[11px] uppercase tracking-wider font-bold">
                                    NO APPLICATIONS
                                </div>
                            @else
                                @foreach ($dayApps as $app)
                                    @php
                                        $borderColor = 'border-gray-200';
                                        $dotColor = 'bg-gray-400';
                                        $textColor = 'text-gray-400';
                                        if ($app->status === 'Pending') {
                                            $borderColor = 'border-amber-500/50';
                                            $dotColor = 'bg-amber-400';
                                            $textColor = 'text-amber-400';
                                        } elseif (in_array($app->status, ['Enrolled', 'Approved'])) {
                                            $borderColor = 'border-[#10B981]/50';
                                            $dotColor = 'bg-[#10B981]';
                                            $textColor = 'text-[#10B981]';
                                        }
                                    @endphp

                                    <div
                                        class="bg-white p-3 rounded-lg border {{ $borderColor }} shadow-sm hover:shadow-md transition cursor-default">
                                        <p class="text-sm font-bold text-gray-900 truncate"
                                            title="{{ $app->user->first_name ?? ($app->first_name ?? 'Unknown') }} {{ $app->user->last_name ?? ($app->last_name ?? '') }}">
                                            {{ $app->user->first_name ?? ($app->first_name ?? 'Unknown') }}
                                            {{ $app->user->last_name ?? ($app->last_name ?? '') }}
                                        </p>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider {{ $textColor }}">{{ $app->status }}</span>
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

            <a href="{{ route('registrar.students.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Students</h3>
                        <p class="text-sm text-gray-600 mt-2">View and update student records.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.applications.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Applications</h3>
                        <p class="text-sm text-gray-600 mt-2">Review, accept, or decline.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.programs.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Programs</h3>
                        <p class="text-sm text-gray-600 mt-2">Add or edit academic programs.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.academic_years.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Academic
                            Years</h3>
                        <p class="text-sm text-gray-600 mt-2">Open/Close school years.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.semesters.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Semesters</h3>
                        <p class="text-sm text-gray-600 mt-2">Set active semester.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.sections.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition cursor-pointer flex justify-between items-start group h-full hover:border-[#10B981]">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Sections</h3>
                        <p class="text-sm text-gray-600 mt-2">Organize student blocks.</p>
                    </div>
                    <div class="text-[#10B981] p-2 bg-[#10B981]/10 rounded-lg group-hover:bg-[#10B981]/20 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-6">System Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Students</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Applications</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['applications'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Enrolled</div>
                        <div class="text-2xl font-bold text-[#10B981]">{{ $stats['enrolled'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Programs</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['programs'] }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>

    <div id="applicationModal"
        class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none transition-opacity duration-200 p-4 modal-backdrop flex items-center justify-center">
        <div
            class="bg-white w-full max-w-2xl rounded-lg shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[90vh] transform scale-95 transition-transform duration-200" id="modalContent">
            <div
                class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Application Details</h3>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:text-[#10B981] transition focus:outline-none text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4">
                    <h4
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Student Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="block text-gray-400 text-xs">Full Name</span><span
                                class="font-bold text-gray-900 uppercase" id="modalName"></span></div>
                        <div><span class="block text-gray-400 text-xs">Email</span><span
                                class="font-medium text-gray-900" id="modalEmail"></span></div>
                        <div><span class="block text-gray-400 text-xs">Course</span><span
                                class="font-bold text-[#10B981]" id="modalCourse"></span></div>
                        <div><span class="block text-gray-400 text-xs">Year</span><span
                                class="font-medium text-gray-900" id="modalYear"></span></div>
                        <div class="col-span-2"><span class="block text-gray-400 text-xs">Status</span><span
                                id="modalStatus" class="font-bold text-gray-900"></span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-white border-t border-gray-200 rounded-b-lg flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-100 border border-gray-200 rounded text-sm font-medium text-gray-600 hover:bg-gray-200 transition">Close</button>
                <a href="{{ route('registrar.applications.index') }}"
                    class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white rounded text-sm font-bold shadow-sm transition">Go
                    to Applications</a>
            </div>
        </div>
    </div>

    <script>
        function openModal(app, user) {
            document.getElementById('modalTitle').innerText = 'Application #' + app.id;
            const last = app.last_name || user?.last_name || '';
            const first = app.first_name || user?.first_name || '';
            const middle = app.middle_name || user?.middle_name ? ' ' + (app.middle_name || user?.middle_name) : '';
            document.getElementById('modalName').innerText = last + ', ' + first + middle;
            document.getElementById('modalEmail').innerText = app.email || user?.email || 'N/A';
            document.getElementById('modalCourse').innerText = app.course_code || 'N/A';
            document.getElementById('modalYear').innerText = app.year_level || 'N/A';
            document.getElementById('modalStatus').innerText = app.status;
            document.getElementById('applicationModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('applicationModal').classList.remove('opacity-0', 'pointer-events-none');
                document.getElementById('modalContent').classList.remove('scale-95');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('applicationModal').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('modalContent').classList.add('scale-95');
            setTimeout(() => {
                document.getElementById('applicationModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }
        window.onclick = function(event) {
            const modal = document.getElementById('applicationModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>

</html>