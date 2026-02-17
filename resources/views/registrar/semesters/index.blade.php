<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Semesters</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Semesters</span>
                        </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16 ml-10">
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
                    </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Semesters List</h2>
                <button onclick="openModal()" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition">
                    Add New Semester
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                            <th class="py-3 px-2 font-medium">ID</th>
                            <th class="py-3 px-2 font-medium">Academic Year</th>
                            <th class="py-3 px-2 font-medium">Name</th>
                            <th class="py-3 px-2 font-medium">Start Date</th>
                            <th class="py-3 px-2 font-medium">End Date</th>
                            <th class="py-3 px-2 font-medium">Status</th>
                            <th class="py-3 px-2 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($semesters as $semester)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                            <td class="py-4 px-2 text-gray-500">{{ $semester->id }}</td>
                            <td class="py-4 px-2 font-medium text-slate-700">{{ $semester->academic_year }}</td>
                            <td class="py-4 px-2 text-slate-600">{{ $semester->name }}</td>
                            <td class="py-4 px-2 text-gray-500">{{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-2 text-gray-500">{{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-2">
                                @if($semester->is_active)
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-2 text-right">
                                <button 
                                    data-semester="{{ json_encode($semester) }}"
                                    onclick="editModal(JSON.parse(this.dataset.semester))"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium transition mr-3">
                                    Edit
                                </button>
                                
                                <form action="{{ route('registrar.semesters.destroy', $semester->id) }}" method="POST" onsubmit="return confirm('Delete this semester?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400 text-sm">No semesters found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $semesters->links() }}
            </div>
        </div>
    </main>

    <div id="semesterModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 transform transition-all">
            <h3 class="text-lg font-bold text-slate-800 mb-4" id="modalTitle">Add New Semester</h3>
            
            <form id="semesterForm" action="{{ route('registrar.semesters.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="" disabled selected>Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Semester Name</label>
                        <select name="name" id="name" class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Set as Active Semester</label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow transition">Save Semester</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('semesterModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'Add New Semester';
            document.getElementById('semesterForm').action = "{{ route('registrar.semesters.store') }}";
            document.getElementById('methodField').innerHTML = '';
            
            document.getElementById('academic_year').value = '';
            document.getElementById('name').value = 'First Semester';
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('is_active').checked = false;
        }

        function editModal(data) {
            document.getElementById('semesterModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'Edit Semester';
            document.getElementById('semesterForm').action = "/registrar/semesters/" + data.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('academic_year').value = data.academic_year;
            document.getElementById('name').value = data.name;
            // Format date for input type="date"
            document.getElementById('start_date').value = data.start_date.split('T')[0].split(' ')[0];
            document.getElementById('end_date').value = data.end_date.split('T')[0].split(' ')[0];
            document.getElementById('is_active').checked = data.is_active == 1;
        }

        function closeModal() {
            document.getElementById('semesterModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('semesterModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>