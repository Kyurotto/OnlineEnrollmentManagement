<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Registrar</title>
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
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
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

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-slate-800">Student List</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">NAME</th>
                            <th class="px-6 py-4">EMAIL</th>
                            <th class="px-6 py-4">ROLE</th>
                            <th class="px-6 py-4">STATUS</th>
                            <th class="px-6 py-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">#{{ $student->id }}</td>
                            
                            <td class="px-6 py-4 font-bold text-slate-900 uppercase">
                                {{ $student->last_name }}, {{ $student->first_name }} 
                                @if($student->middle_name) {{ substr($student->middle_name, 0, 1) }}. @endif
                            </td>

                            <td class="px-6 py-4 text-gray-500">{{ $student->email }}</td>

                            <td class="px-6 py-4">
                                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                    {{ $student->role ?? 'STUDENT' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $status = $student->display_status ?? 'Not Enrolled';
                                    $badgeColor = match($status) {
                                        'Approved', 'Enrolled', 'Active' => 'bg-green-100 text-green-800 border-green-200',
                                        'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'Rejected' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('registrar.students.edit', $student->id) }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1 rounded text-xs font-bold transition">Edit</a>
                                    
                                    <form action="{{ route('registrar.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 px-3 py-1 rounded text-xs font-bold transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">
                @if(method_exists($students, 'links'))
                    {{ $students->links() }}
                @endif
            </div>
        </div>
    </main>
</body>
</html>