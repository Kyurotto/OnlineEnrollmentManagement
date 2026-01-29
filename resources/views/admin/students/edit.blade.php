<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-2xl w-full bg-white p-8 rounded-xl shadow-lg border border-gray-100">

            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h2 class="text-2xl font-bold text-slate-900">Edit Student Record</h2>
                <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-gray-500 hover:text-slate-900 transition">← Back to List</a>
            </div>

            <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Change Password (Optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <p class="text-xs text-gray-500 mt-1">Only fill this if you want to reset the student's password.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.students.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-md transition">Update Student</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
