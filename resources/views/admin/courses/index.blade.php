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
<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">

                    <div class="flex items-center gap-3">
                        <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">AD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-gray-900">Admin Panel</h1>
                            <span class="text-xs text-gray-500">Manage Courses</span>
                        </div>
                    </div>

                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">
                            Dashboard
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer group">
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <div class="absolute right-0 top-10 w-64 bg-white border border-gray-200 shadow-2xl rounded-lg hidden group-hover:block z-50">
                            <div class="p-4">
                                <p class="text-sm font-bold text-gray-900">{{ $pendingCount }} New Application(s)</p>
                                <p class="text-xs text-gray-500 mt-1">Students are waiting for approval.</p>
                                <a href="{{ route('admin.applications.index') }}" class="block mt-3 text-center bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 rounded transition shadow-md shadow-[#10B981]/10">
                                    View Applications →
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Manage Courses</h2>
            <p class="text-sm text-gray-500">Add, edit or remove course offerings.</p>
        </div>

        @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 font-medium shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
                <h3 class="font-bold text-lg text-gray-900 mb-6">Add Course</h3>
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Course Code</label>
                        <input type="text" name="course_code" placeholder="e.g. BSIT" class="w-full bg-gray-50 border-gray-200 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Course Name</label>
                        <input type="text" name="course_name" placeholder="Full Course Title" class="w-full bg-gray-50 border-gray-200 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Credits</label>
                        <input type="number" name="credits" value="3" class="w-20 bg-gray-50 border-gray-200 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all text-center">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-md py-2 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-300"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-bold py-2.5 px-4 rounded shadow-md shadow-[#10B981]/10 transition-all uppercase tracking-wide text-xs">Add Course</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-900">Existing Courses <span class="text-gray-400 text-sm font-normal">({{ count($courses) }})</span></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Credits</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($courses as $course)
                            <tr class="hover:bg-[#27272A]/30 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-[#10B981]">{{ $course->course_code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900 uppercase">{{ $course->course_name }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $course->credits }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="bg-transparent border border-gray-200 text-gray-500 hover:text-gray-900 hover:border-[#10B981] px-3 py-1 rounded text-[10px] uppercase font-bold transition-all">Edit</a>
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white px-3 py-1 rounded text-[10px] uppercase font-bold transition-all">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            © 2026 Your Institution — Admin Panel
        </div>
    </footer>
</body>
</html>