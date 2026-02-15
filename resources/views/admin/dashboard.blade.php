<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-slate-900 text-white font-bold p-2 rounded-lg text-sm">AD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Admin Dashboard</h1>
                        <span class="text-xs text-gray-500">Manage courses, students and payments</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="relative cursor-pointer group mr-4">
                        <div class="relative">
                            <svg class="w-7 h-7 text-gray-500 group-hover:text-slate-800 transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if($pendingCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse">
                                {{ $pendingCount }}
                            </span>
                            @endif
                        </div>

                        <div
                            class="absolute right-0 top-10 w-80 bg-white border border-gray-200 shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">NOTIFICATIONS</h3>
                                @if($pendingCount > 0)
                                <span
                                    class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}
                                    New</span>
                                @endif
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar bg-gray-50 p-2 space-y-2">
                                @forelse($notifications as $notif)
                                <a href="{{ route('admin.applications.index') }}"
                                    class="block bg-white p-3 rounded-lg border border-gray-100 hover:border-indigo-200 hover:shadow-sm transition group">
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600">
                                        New Application Received
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <span class="font-medium text-slate-700">{{ $notif->first_name }}
                                            {{ $notif->last_name }}</span>
                                        applied for <span class="uppercase">{{ $notif->course_code }}</span>.
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-2 text-right">
                                        {{ $notif->created_at->diffForHumans() }}</p>
                                </a>
                                @empty
                                <div class="text-center py-6 text-gray-400 text-sm">No new notifications</div>
                                @endforelse
                            </div>

                            <div class="bg-white p-2 border-t border-gray-100 text-center">
                                <a href="{{ route('admin.applications.index') }}"
                                    class="text-xs font-bold text-indigo-600 hover:text-indigo-800">View All
                                    Applications →</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-400">Signed in as</div>
                        <div class="text-sm font-bold text-slate-800">Administrator</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Welcome, Administrator</h2>
            <p class="text-gray-600">Use the controls below to manage the system.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('admin.courses.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-700 transition">Manage Courses
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">Create, edit or remove course offerings.</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-green-700 transition">Manage
                            Students</h3>
                        <p class="text-sm text-gray-500 mt-2">View and update student records.</p>
                    </div>
                    <div class="text-green-600">
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
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-yellow-600 transition">Manage
                            Payments</h3>
                        <p class="text-sm text-gray-500 mt-2">View transactions and resolve issues.</p>
                    </div>
                    <div class="text-yellow-600">
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
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition">Manage
                            Applications</h3>
                        <p class="text-sm text-gray-500 mt-2">Review, accept, or decline applications.</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Active Courses</div>
                        <div class="text-2xl font-bold  text-slate-800">{{ $stats['active_courses'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Total Students</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['students'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Enrolled</div>
                        <div class="text-2xl font-bold text-green-600">{{ $stats['enrolled'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Total Payments</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['total_payments'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Applications</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['applications'] }}</div>
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