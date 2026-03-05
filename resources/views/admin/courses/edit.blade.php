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

                    <div class="flex space-x-6 text-sm font-medium text-gray-600 h-16">
                        <a href="{{ route('dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">
                            Dashboard
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer group">
                        <div class="relative">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white animate-pulse">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </div>

                        @if(isset($pendingCount) && $pendingCount > 0)
                        <div class="absolute right-0 top-10 w-64 bg-white border border-gray-200 shadow-2xl rounded-lg hidden group-hover:block z-50">
                            <div class="p-4">
                                <p class="text-sm font-bold text-gray-900">{{ $pendingCount }} New Application(s)</p>
                                <p class="text-xs text-gray-500 mt-1">Students are waiting for approval.</p>
                                <a href="{{ route('admin.applications.index') }}" class="block mt-3 text-center bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 rounded transition shadow-sm">
                                    View Applications →
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-bold text-gray-900 mr-4 uppercase tracking-tight">Administrator</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Course</h2>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-500">Update course details below.</p>
                        <span id="save-status" class="text-xs font-bold text-[#10B981] uppercase tracking-wider opacity-0 transition-opacity duration-500">Saved</span>
                    </div>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">← Back to List</a>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <form id="course-form" action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Course Code</label>
                        <input type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}"
                            class="autosave-input w-full bg-white border border-gray-300 rounded-md py-3 px-4 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm">
                        <span class="text-rose-500 text-xs font-medium error-msg mt-1 inline-block" id="error-course_code"></span>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Course Name</label>
                        <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}"
                            class="autosave-input w-full bg-white border border-gray-300 rounded-md py-3 px-4 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm">
                        <span class="text-rose-500 text-xs font-medium error-msg mt-1 inline-block" id="error-course_name"></span>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Credits</label>
                        <input type="number" name="credits" value="{{ old('credits', $course->credits) }}"
                            class="autosave-input w-24 bg-white border border-gray-300 rounded-md py-3 px-4 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all text-center font-bold shadow-sm">
                    </div>

                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" rows="3"
                            class="autosave-input w-full bg-white border border-gray-300 rounded-md py-3 px-4 text-sm text-gray-900 focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all uppercase tracking-wide text-xs">Save & Return</button>
                        <a href="{{ route('admin.courses.index') }}" class="w-full bg-white border border-gray-300 shadow-sm text-gray-700 font-bold py-3 px-4 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition text-center uppercase tracking-wide text-xs">Cancel</a>
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
            statusLabel.classList.remove('text-[#10B981]', 'text-rose-500');
            statusLabel.classList.add('text-gray-500', 'opacity-100');
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
                        statusLabel.classList.add('text-rose-500');
                        for (const [key, messages] of Object.entries(data.errors)) {
                            const errorSpan = document.getElementById(`error-${key}`);
                            if (errorSpan) errorSpan.innerText = messages[0];
                        }
                    } else {
                        statusLabel.innerText = 'Saved ' + (data.last_updated || '');
                        statusLabel.classList.remove('text-gray-500', 'text-rose-500');
                        statusLabel.classList.add('text-[#10B981]');
                        setTimeout(() => { statusLabel.classList.remove('opacity-100'); statusLabel.classList.add('opacity-0'); }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusLabel.innerText = 'Connection Error';
                    statusLabel.classList.add('text-rose-500');
                });
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
