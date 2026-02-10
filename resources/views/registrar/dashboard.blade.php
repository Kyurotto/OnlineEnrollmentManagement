<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dashboard</title>
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
                    <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Panel</h1>
                        <span class="text-xs text-gray-500">Manage Students & Applications</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-400">Signed in as</div>
                        <div class="text-sm font-bold text-slate-800">Registrar</div>
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
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Welcome, Registrar</h2>
            <p class="text-gray-600">Review applications and manage student records.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">

            <a href="{{ route('registrar.students.index') }}" class="block">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-purple-700 transition">Manage
                            Students</h3>
                        <p class="text-sm text-gray-500 mt-2">View and update student records.</p>
                    </div>
                    <div class="text-purple-600">
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
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition">Manage
                            Applications</h3>
                        <p class="text-sm text-gray-500 mt-2">Review, accept, or decline applications.</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
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
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Total Students</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['students'] }}</div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Pending Apps</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['applications'] }}</div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Enrolled</div>
                        <div class="text-2xl font-bold text-green-600">{{ $stats['enrolled'] }}</div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-bold">Active Courses</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $stats['active_courses'] }}</div>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>
</body>

</html>
