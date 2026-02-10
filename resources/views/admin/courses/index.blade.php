<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">

                    <div class="flex items-center gap-3">
                        <div class="bg-black text-white font-bold p-2 rounded-lg text-sm">AD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Admin Panel</h1>
                            <span class="text-xs text-gray-500">Manage Courses</span>
                        </div>
                    </div>

                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">
                            Dashboard
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="relative cursor-pointer group">
                        <div class="relative">
                            <svg class="w-6 h-6 text-gray-500 group-hover:text-gray-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse shadow-sm">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </div>

                        @if(isset($pendingCount) && $pendingCount > 0)
                        <div class="absolute right-0 top-10 w-64 bg-white border border-gray-200 shadow-xl rounded-lg hidden group-hover:block z-50">
                            <div class="p-4">
                                <p class="text-sm font-bold text-slate-800">{{ $pendingCount }} New Application(s)</p>
                                <p class="text-xs text-gray-500 mt-1">Students are waiting for approval.</p>
                                <a href="{{ route('admin.applications.index') }}" class="block mt-3 text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded transition">
                                    View Applications →
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Manage Courses</h2>
            <p class="text-sm text-gray-500">Add, edit or remove course offerings.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
                <h3 class="font-bold text-lg text-slate-800 mb-6">Add Course</h3>
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                        <input type="text" name="course_code" placeholder="e.g. BSIT" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                        <input type="text" name="course_name" placeholder="Full Course Title" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Credits</label>
                        <input type="number" name="credits" value="3" class="w-20 border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500 text-center">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded shadow transition">Add Course</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-slate-800">Existing Courses <span class="text-gray-500 text-sm font-normal">({{ count($courses) }})</span></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($courses as $course)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-slate-800">{{ $course->course_code }}</td>
                                <td class="px-3 py-4 text-sm text-slate-600 uppercase">{{ $course->course_name }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $course->credits }}</td>
                                <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium space-y-2">
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="block w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-2 py-1 rounded text-xs shadow-sm text-center">Edit</a>
                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs shadow-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div class="mt-12 border-t border-gray-200 pt-6 text-center text-sm text-gray-500 pb-6">
        © 2026 Your Institution — Admin Panel
    </div>
</body>
</html>
