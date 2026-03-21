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
            background-image: url('/52393.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(6,13,26,0.82) 0%, rgba(13,31,60,0.78) 40%, rgba(26,58,110,0.70) 100%);
            z-index: 0;
            pointer-events: none;
        }
        nav, main, footer {
            position: relative;
            z-index: 1;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #1a3a6e;
            border-radius: 4px;
        }
    </style>
</head>

<body class="text-gray-600 flex flex-col min-h-screen">

    <nav class="sticky top-0 z-20 shadow-lg border-b" style="background: rgba(6,13,26,0.92); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="text-white font-bold p-2 rounded-lg text-sm" style="background: linear-gradient(135deg, #0d1f3c, #1a3a6e); box-shadow: 0 4px 14px rgba(13,31,60,0.6);">
                        AD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-white">Admin Dashboard</h1>
                        <span class="text-xs" style="color: #8ab4d8;">Manage courses, students and payments</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="relative cursor-pointer group mr-4">
                        <div class="relative p-1">
                            <svg class="w-6 h-6 transition shadow-sm" style="color: #8ab4d8;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8ab4d8'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if ($pendingCount > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border border-white"></span>
                                </span>
                            @endif
                        </div>

                        <div class="absolute right-0 top-10 w-80 shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden" style="background: rgba(6,13,26,0.97); backdrop-filter: blur(16px); border: 1px solid rgba(26,58,110,0.5);">
                            <div class="px-4 py-3 border-b flex justify-between items-center" style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                <h3 class="text-sm font-bold uppercase tracking-wide text-white">NOTIFICATIONS</h3>
                                @if ($pendingCount > 0)
                                    <span class="bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ $pendingCount }} New
                                    </span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar p-2 space-y-2" style="background: rgba(6,13,26,0.5);">
                                @forelse($notifications as $notif)
                                    <a href="{{ route('admin.applications.index') }}"
                                        class="block p-3 rounded-lg border transition group" style="background: rgba(13,31,60,0.6); border-color: rgba(26,58,110,0.3);" onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'" onmouseout="this.style.borderColor='rgba(26,58,110,0.3)'">
                                        @if ($notif->status === 'Enrolled')
                                            <p class="text-sm font-bold flex items-center gap-1" style="color: #8ab4d8;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Student Paid ₱{{ number_format($notif->paid_amount ?? 0, 2) }}
                                            </p>
                                            <p class="text-xs mt-1" style="color: #8ab4d8;">
                                                <span class="font-bold text-white uppercase">{{ $notif->user->first_name ?? '' }} {{ $notif->user->last_name ?? '' }}</span>
                                                is now already <span class="font-bold" style="color: #a8d5f5;">PAID</span>.
                                            </p>
                                        @else
                                            <p class="text-sm font-bold text-white">
                                                New Application Received
                                            </p>
                                            <p class="text-xs mt-1" style="color: #8ab4d8;">
                                                <span class="font-medium text-white">{{ $notif->user->first_name ?? '' }} {{ $notif->user->last_name ?? '' }}</span>
                                                applied for <span class="uppercase font-bold" style="color: #a8d5f5;">{{ $notif->course_code ?? 'Course' }}</span>.
                                            </p>
                                        @endif
                                        <p class="text-[10px] mt-2 text-right" style="color: #4a6fa5;">
                                            {{ $notif->updated_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="text-center py-6 text-sm" style="color: #4a6fa5;">No new notifications</div>
                                @endforelse
                            </div>

                            <div class="p-2 border-t text-center" style="background: rgba(13,31,60,0.8); border-color: rgba(26,58,110,0.4);">
                                <a href="{{ route('admin.applications.index') }}"
                                    class="text-xs font-bold" style="color: #8ab4d8;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#8ab4d8'">View All
                                    Applications →</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <div class="text-xs" style="color: #8ab4d8;">Signed in as</div>
                        <div class="text-sm font-bold text-white">Administrator</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="text-white text-sm font-semibold py-2 px-4 rounded-full transition-all" style="background: rgba(220,38,38,0.8); border: 1px solid rgba(220,38,38,0.5);" onmouseover="this.style.background='rgba(220,38,38,1)'" onmouseout="this.style.background='rgba(220,38,38,0.8)'">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6" style="position: relative; z-index: 1;">

        <div class="p-8 rounded-xl shadow-lg border" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
            <h2 class="text-2xl font-bold text-white mb-2">Welcome, Administrator</h2>
            <p class="mb-2" style="color: #8ab4d8;">Use the controls below to manage the system.</p>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg" style="background: rgba(26,58,110,0.3); color: #8ab4d8;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-white">Application Summary This Month</h3>
                </div>
                <div
                    class="px-4 py-1.5 text-sm font-semibold rounded-full shadow-sm" style="background: rgba(26,58,110,0.3); border: 1px solid rgba(26,58,110,0.5); color: #8ab4d8;">
                    {{ $weekRange }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach ($weekDates as $day)
                    <div
                        class="rounded-xl flex flex-col h-[400px]" style="{{ $day['is_today'] ? 'border: 1px solid rgba(26,58,110,0.9); background: rgba(13,31,60,0.5); box-shadow: 0 0 16px rgba(26,58,110,0.3);' : 'border: 1px solid rgba(26,58,110,0.3); background: rgba(6,13,26,0.4);' }}">

                        <div
                            class="text-center py-4 rounded-t-xl" style="{{ $day['is_today'] ? 'border-bottom: 1px solid rgba(26,58,110,0.5); background: rgba(26,58,110,0.25);' : 'border-bottom: 1px solid rgba(26,58,110,0.2);' }}" >
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color: #4a6fa5;">
                                {{ $day['day_name'] }}</p>
                            <p
                                class="text-2xl font-bold mt-1" style="color: {{ $day['is_today'] ? '#a8d5f5' : '#ffffff' }};">
                                {{ $day['day_num'] }}</p>
                        </div>

                        <div class="p-3 flex-1 overflow-y-auto space-y-3 custom-scrollbar">
                            @php $dayApps = $appsByDate->get($day['date_string'], collect()); @endphp

                            @if ($dayApps->isEmpty())
                                <div
                                    class="h-full flex items-center justify-center italic text-[11px] uppercase tracking-wider font-bold" style="color: #1a3a6e;">
                                    NO APPLICATIONS
                                </div>
                            @else
                                @foreach ($dayApps as $app)
                                    @php
                                        $cardStyle = 'border: 1px solid rgba(26,58,110,0.3); background: rgba(13,31,60,0.5);';
                                        $dotColor = 'bg-gray-400';
                                        $textStyle = 'color: #8ab4d8;';
                                        if ($app->status === 'Pending') {
                                            $cardStyle = 'border: 1px solid rgba(217,119,6,0.4); background: rgba(120,53,15,0.2);';
                                            $dotColor = 'bg-amber-400';
                                            $textStyle = 'color: #fbbf24;';
                                        } elseif (in_array($app->status, ['Enrolled', 'Approved'])) {
                                            $cardStyle = 'border: 1px solid rgba(26,58,110,0.6); background: rgba(26,58,110,0.3);';
                                            $dotColor = 'bg-blue-400';
                                            $textStyle = 'color: #a8d5f5;';
                                        }
                                    @endphp

                                    <div
                                        class="p-3 rounded-lg shadow-sm hover:shadow-md transition cursor-default" style="{{ $cardStyle }}">
                                        <p class="text-sm font-bold text-white truncate"
                                            title="{{ $app->user->name ?? 'Unknown Student' }}">
                                            {{ $app->user->name ?? 'Unknown Student' }}
                                        </p>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider" style="{{ $textStyle }}">{{ $app->status }}</span>
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
            <a wire:navigate href="{{ route('admin.courses.index') }}" class="block h-full">
                <div
                    class="p-6 rounded-xl shadow-lg border transition-all cursor-pointer flex justify-between items-start group h-full" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.35);" onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'; this.style.boxShadow='0 8px 24px rgba(13,31,60,0.5)';" onmouseout="this.style.borderColor='rgba(26,58,110,0.35)'; this.style.boxShadow='';">
                    <div>
                        <h3 class="font-bold text-lg text-white transition">Manage
                            Courses</h3>
                        <p class="text-sm mt-2" style="color: #8ab4d8;">Create, edit or remove course offerings.</p>
                    </div>
                    <div style="color: #8ab4d8;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('admin.students.index') }}" class="block h-full">
                <div
                    class="p-6 rounded-xl shadow-lg border transition-all cursor-pointer flex justify-between items-start group h-full" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.35);" onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'; this.style.boxShadow='0 8px 24px rgba(13,31,60,0.5)';" onmouseout="this.style.borderColor='rgba(26,58,110,0.35)'; this.style.boxShadow='';">
                    <div>
                        <h3 class="font-bold text-lg text-white transition">Manage
                            Students</h3>
                        <p class="text-sm mt-2" style="color: #8ab4d8;">View and update student records.</p>
                    </div>
                    <div style="color: #8ab4d8;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('admin.payments.index') }}" class="block h-full">
                <div
                    class="p-6 rounded-xl shadow-lg border transition-all cursor-pointer flex justify-between items-start group h-full" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.35);" onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'; this.style.boxShadow='0 8px 24px rgba(13,31,60,0.5)';" onmouseout="this.style.borderColor='rgba(26,58,110,0.35)'; this.style.boxShadow='';">
                    <div>
                        <h3 class="font-bold text-lg text-white transition">Manage
                            Payments</h3>
                        <p class="text-sm mt-2" style="color: #8ab4d8;">View transactions and resolve issues.</p>
                    </div>
                    <div style="color: #8ab4d8;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>

            <a wire:navigate href="{{ route('admin.applications.index') }}" class="block h-full">
                <div
                    class="p-6 rounded-xl shadow-lg border transition-all cursor-pointer flex justify-between items-start group h-full" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.35);" onmouseover="this.style.borderColor='rgba(26,58,110,0.9)'; this.style.boxShadow='0 8px 24px rgba(13,31,60,0.5)';" onmouseout="this.style.borderColor='rgba(26,58,110,0.35)'; this.style.boxShadow='';">
                    <div>
                        <h3 class="font-bold text-lg text-white transition">Manage
                            Applications</h3>
                        <p class="text-sm mt-2" style="color: #8ab4d8;">Review, accept, or decline applications.</p>
                    </div>
                    <div style="color: #8ab4d8;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 p-8 rounded-xl shadow-lg border" style="background: rgba(6,13,26,0.75); backdrop-filter: blur(12px); border-color: rgba(26,58,110,0.4);">
                <h3 class="font-bold text-lg text-white mb-6">Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div class="p-4 rounded-lg border" style="background: rgba(13,31,60,0.5); border-color: rgba(26,58,110,0.4);">
                        <div class="text-xs mb-1 uppercase tracking-wide font-bold" style="color: #4a6fa5;">Active Courses</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['active_courses'] }}</div>
                    </div>
                    <div class="p-4 rounded-lg border" style="background: rgba(13,31,60,0.5); border-color: rgba(26,58,110,0.4);">
                        <div class="text-xs mb-1 uppercase tracking-wide font-bold" style="color: #4a6fa5;">Total Students</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['students'] }}</div>
                    </div>
                    <div class="p-4 rounded-lg border" style="background: rgba(13,31,60,0.5); border-color: rgba(26,58,110,0.4);">
                        <div class="text-xs mb-1 uppercase tracking-wide font-bold" style="color: #4a6fa5;">Enrolled</div>
                        <div class="text-2xl font-bold" style="color: #a8d5f5;">{{ $stats['enrolled'] ?? 0 }}</div>
                    </div>
                    <div class="p-4 rounded-lg border" style="background: rgba(13,31,60,0.5); border-color: rgba(26,58,110,0.4);">
                        <div class="text-xs mb-1 uppercase tracking-wide font-bold" style="color: #4a6fa5;">Payments</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['total_payments'] }}</div>
                    </div>
                    <div class="p-4 rounded-lg border" style="background: rgba(13,31,60,0.5); border-color: rgba(26,58,110,0.4);">
                        <div class="text-xs mb-1 uppercase tracking-wide font-bold" style="color: #4a6fa5;">Applications</div>
                        <div class="text-2xl font-bold text-white">{{ $stats['applications'] }}</div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="py-6 mt-auto border-t" style="background: rgba(6,13,26,0.85); border-color: rgba(26,58,110,0.4);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm" style="color: #4a6fa5;">
            © 2026 Your Institution — Admin Panel
        </div>
    </footer>
</body>

</html>
