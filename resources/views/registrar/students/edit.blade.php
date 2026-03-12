<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Registrar</title>
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
                        <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Students</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <svg class="w-6 h-6 text-gray-400 hover:text-[#10B981] transition cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                            {{ $pendingCount }}
                        </span>
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

    <div class="min-h-screen flex items-center justify-center p-6 -mt-10">
        <div class="max-w-3xl w-full bg-white p-10 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Edit Student Record</h2>
                <a href="{{ route('registrar.students.index') }}" class="text-sm text-gray-500 hover:text-[#10B981] transition flex items-center gap-1"><span>←</span> Back to List</a>
            </div>

            <form action="{{ route('registrar.students.update', $student->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full bg-white text-gray-900 border border-gray-200 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="w-full bg-white text-gray-900 border border-gray-200 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full bg-white text-gray-900 border border-gray-200 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none uppercase placeholder-gray-400">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full bg-white text-gray-900 border border-gray-200 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none placeholder-gray-400">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full bg-white text-gray-900 border border-gray-200 rounded p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none">
                        <option value="Not Enrolled" {{ $student->status == 'Not Enrolled' ? 'selected' : '' }}>Not Enrolled</option>
                        <option value="Enrolled" {{ $student->status == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('registrar.students.index') }}" class="px-6 py-2.5 border border-gray-200 bg-gray-50 rounded text-gray-500 font-medium hover:bg-gray-100 hover:text-gray-900 transition text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded shadow-md shadow-[#10B981]/20 transition text-sm">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>