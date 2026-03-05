<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">
                        AD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Admin Dashboard</h1>
                        <span class="text-xs text-gray-500">Manage courses, students and payments</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="relative cursor-pointer group mr-4">

                        <div
                            class="absolute right-0 top-10 w-80 bg-white border border-gray-200 shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">NOTIFICATIONS</h3>
                                @if ($pendingCount > 0)
                                    <span
                                        class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-2 py-0.5 rounded-full border border-[#10B981]/20">
                                        {{ $pendingCount }} New
                                    </span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar bg-white p-2 space-y-2">
                                @forelse($notifications as $notif)
                                    <a href="{{ route('admin.applications.index') }}"
                                        class="block bg-gray-50 p-3 rounded-lg border border-gray-200 hover:border-[#10B981] hover:shadow-sm transition group">
                                        @if ($notif->status === 'Enrolled')
                                            <p
                                                class="text-sm font-bold text-[#10B981] group-hover:text-[#059669] flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Student Paid ₱{{ number_format($notif->paid_amount ?? 0, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span
                                                    class="font-bold text-gray-900 uppercase">{{ $notif->user->first_name ?? '' }}
                                                    {{ $notif->user->last_name ?? '' }}</span>
                                                is now already <span class="font-bold text-[#10B981]">PAID</span>.
                                            </p>
                                        @else
                                            <p class="text-sm font-bold text-gray-900 group-hover:text-[#10B981]">
                                                New Application Received
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span
                                                    class="font-medium text-gray-900">{{ $notif->user->first_name ?? '' }}
                                                    {{ $notif->user->last_name ?? '' }}</span>
                                                applied for <span
                                                    class="uppercase font-bold text-[#10B981]">{{ $notif->course_code ?? 'Course' }}</span>.
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-gray-400 mt-2 text-right">
                                            {{ $notif->updated_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="text-center py-6 text-gray-500 text-sm">No new notifications</div>
                                @endforelse
                            </div>

                            <div class="bg-gray-50 p-2 border-t border-gray-200 text-center">
                                <a href="{{ route('admin.applications.index') }}"
                                    class="text-xs font-bold text-[#10B981] hover:text-[#059669]">View All
                                    Applications →</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-500">Signed in as</div>
                        <div class="text-sm font-bold text-gray-900">Administrator</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome, Administrator</h2>
            <p class="text-gray-500 mb-2">Use the controls below to manage the system.</p>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981]/10 p-2 rounded-lg text-[#10B981]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">Application Summary This Month</h3>
                </div>
                <div
                    class="px-4 py-1.5 bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] text-sm font-semibold rounded-full shadow-sm">
                    {{ $weekRange }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach ($weekDates as $day)
                    <div
                        class="border {{ $day['is_today'] ? 'border-[#10B981] bg-[#10B981]/5 shadow-sm' : 'border-gray-200 bg-gray-50' }} rounded-xl flex flex-col h-[400px]">

                        <div
                            class="text-center py-4 border-b {{ $day['is_today'] ? 'border-[#10B981]/30 bg-[#10B981]/10 rounded-t-xl' : 'border-gray-200' }}">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
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
                                        $textColor = 'text-gray-600';
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

                                    <div
                                        class="bg-white p-3 rounded-lg border {{ $borderColor }} shadow-sm hover:shadow-md transition cursor-default">
                                        <p class="text-sm font-bold text-gray-900 truncate"
                                            title="{{ $app->user->name ?? 'Unknown Student' }}">
                                            {{ $app->user->name ?? 'Unknown Student' }}
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('admin.courses.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Courses</h3>
                        <p class="text-sm text-gray-500 mt-2">Create, edit or remove course offerings.</p>
                    </div>
                    <div class="text-[#10B981]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Students</h3>
                        <p class="text-sm text-gray-500 mt-2">View and update student records.</p>
                    </div>
                    <div class="text-[#10B981]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Payments</h3>
                        <p class="text-sm text-gray-500 mt-2">View transactions and resolve issues.</p>
                    </div>
                    <div class="text-[#10B981]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.applications.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition">Manage
                            Applications</h3>
                        <p class="text-sm text-gray-500 mt-2">Review, accept, or decline applications.</p>
                    </div>
                    <div class="text-[#10B981]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-6">Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Active Courses</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['active_courses'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Total Students</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Enrolled</div>
                        <div class="text-2xl font-bold text-[#10B981]">{{ $stats['enrolled'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Payments</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_payments'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Applications</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['applications'] }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Your Institution — Admin Panel
        </div>
    </footer>
</body>

</html>
