<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Dashboard</h1>
                        <span class="text-xs text-gray-500">Manage students and enrollments</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs text-gray-400">Signed in as</div>
                        <div class="text-sm font-bold text-slate-800">Registrar</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Welcome, Registrar</h2>
            <p class="text-gray-600">Use the controls below to manage student records and applications.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <a href="{{ route('registrar.students.index') }}" class="block">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-green-700 transition">Manage Students</h3>
                        <p class="text-sm text-gray-500 mt-2">View and update student records and enrollments.</p>
                    </div>
                    <div class="text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('registrar.applications.index') }}" class="block">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer flex justify-between items-start group h-full">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition">Manage Applications</h3>
                        <p class="text-sm text-gray-500 mt-2">Review, accept, or decline user applications.</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Overview</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Total Students</div>
                        <div class="text-3xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'student')->count() }}</div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Total Enrollments</div>
                        <div class="text-3xl font-bold text-slate-800">{{ \App\Models\Enrollment::count() }}</div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Pending Applications</div>
                        <div class="text-3xl font-bold text-amber-600">{{ \App\Models\Enrollment::where('status', 'Pending')->count() }}</div>
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
