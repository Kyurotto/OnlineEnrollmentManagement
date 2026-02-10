<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <div class="flex items-center">
                        <span class="text-sm font-bold text-slate-800 mr-4">Administrator</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Edit Course</h2>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-500">Update course details below.</p>
                        <span id="save-status" class="text-xs font-bold text-gray-400 uppercase tracking-wider opacity-0 transition-opacity duration-500">Saved</span>
                    </div>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-gray-500 hover:text-slate-900 transition">← Back to List</a>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                <form id="course-form" action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                        <input type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}" class="autosave-input w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500 outline-none">
                        <span class="text-red-500 text-xs error-msg" id="error-course_code"></span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                        <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}" class="autosave-input w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500 outline-none">
                        <span class="text-red-500 text-xs error-msg" id="error-course_name"></span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Credits</label>
                        <input type="number" name="credits" value="{{ old('credits', $course->credits) }}" class="autosave-input w-24 border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500 outline-none text-center">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="autosave-input w-full border-gray-300 rounded-md shadow-sm border p-2 text-sm focus:ring-sky-500 focus:border-sky-500 outline-none">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 mt-8">
                        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 px-4 rounded shadow transition">Save & Return</button>
                        <a href="{{ route('admin.courses.index') }}" class="w-full bg-white border border-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded shadow-sm hover:bg-gray-50 transition text-center">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('course-form');
        const inputs = document.querySelectorAll('.autosave-input');
        const statusLabel = document.getElementById('save-status');
        let timeout = null;

        function saveCourse() {
            statusLabel.innerText = 'Saving...';
            statusLabel.classList.remove('text-green-500', 'text-red-500');
            statusLabel.classList.add('text-gray-400', 'opacity-100');
            document.querySelectorAll('.error-msg').forEach(el => el.innerText = '');

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        statusLabel.innerText = 'Error Saving';
                        statusLabel.classList.add('text-red-500');
                        for (const [key, messages] of Object.entries(data.errors)) {
                            const errorSpan = document.getElementById(`error-${key}`);
                            if (errorSpan) errorSpan.innerText = messages[0];
                        }
                    } else {
                        statusLabel.innerText = 'Saved ' + data.last_updated;
                        statusLabel.classList.remove('text-gray-400', 'text-red-500');
                        statusLabel.classList.add('text-green-500');
                        setTimeout(() => { statusLabel.classList.remove('opacity-100'); statusLabel.classList.add('opacity-0'); }, 3000);
                    }
                })
                .catch(error => { console.error('Error:', error); statusLabel.innerText = 'Connection Error'; statusLabel.classList.add('text-red-500'); });
        }

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                statusLabel.innerText = 'Typing...';
                statusLabel.classList.add('opacity-100');
                clearTimeout(timeout);
                timeout = setTimeout(saveCourse, 1000);
            });
        });
    });
    </script>
</body>
</html>
