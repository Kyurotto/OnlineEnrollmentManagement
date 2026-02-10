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
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Students</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center p-6 -mt-10">
        <div class="max-w-3xl w-full bg-white p-10 rounded-lg shadow-lg border border-gray-200">
            <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-slate-900">Edit Student Record</h2>
                <a href="{{ route('registrar.students.index') }}" class="text-sm text-gray-500 hover:text-slate-900 transition flex items-center gap-1"><span>←</span> Back to List</a>
            </div>

            <form action="{{ route('registrar.students.update', $student->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="w-full border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none uppercase">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded p-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="Not Enrolled" {{ $student->status == 'Not Enrolled' ? 'selected' : '' }}>Not Enrolled</option>
                        <option value="Enrolled" {{ $student->status == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                        <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                    </select>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('registrar.students.index') }}" class="px-6 py-2.5 border border-gray-300 rounded text-gray-700 font-medium hover:bg-gray-50 transition text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow-sm transition text-sm">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
