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
<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Semesters</span>
                        </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16 ml-10">
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
        @if(session('success'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Semesters List</h2>
                <button onclick="openModal()" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition">
                    Add New Semester
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3 px-4 font-bold">ID</th>
                            <th class="py-3 px-4 font-bold">Academic Year</th>
                            <th class="py-3 px-4 font-bold">Name</th>
                            <th class="py-3 px-4 font-bold">Start Date</th>
                            <th class="py-3 px-4 font-bold">End Date</th>
                            <th class="py-3 px-4 font-bold">Status</th>
                            <th class="py-3 px-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($semesters as $semester)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                            <td class="py-4 px-4 text-gray-400 font-mono">{{ $semester->id }}</td>
                            <td class="py-4 px-4 font-bold text-gray-900">{{ $semester->academic_year }}</td>
                            <td class="py-4 px-4 text-gray-600 font-medium">{{ $semester->name }}</td>
                            <td class="py-4 px-4 text-gray-500">{{ \Carbon\Carbon::parse($semester->start_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-4 text-gray-500">{{ \Carbon\Carbon::parse($semester->end_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-4">
                                @if($semester->is_active)
                                    <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-3 py-1.5 rounded-full border border-[#10B981]/20">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-400 text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <button 
                                        data-semester="{{ json_encode($semester) }}"
                                        onclick="editModal(JSON.parse(this.dataset.semester))"
                                        class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-widest transition">
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('registrar.semesters.destroy', $semester->id) }}" method="POST" onsubmit="return confirm('Delete this semester?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-400 text-xs font-bold uppercase tracking-widest transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 italic text-sm font-medium">No semesters found in the database.</td>
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

    <div id="semesterModal" class="fixed inset-0 bg-gray-900/50 z-50 items-center justify-center backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 transform transition-all border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6" id="modalTitle">Add New Semester</h3>
            
            <form id="semesterForm" action="{{ route('registrar.semesters.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all" required>
                            <option value="" disabled selected>Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Semester Name</label>
                        <select name="name" id="name" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all">
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all" required>
                    </div>
                </div>

                <div class="mb-8 flex items-center bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <input type="checkbox" name="is_active" id="is_active" class="w-5 h-5 text-[#10B981] rounded border-gray-200 bg-white focus:ring-[#10B981]">
                    <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">Set as Active Semester</label>
                </div>

                <div class="flex justify-end items-center gap-4">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button type="submit" class="px-8 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all">Save Semester</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('semesterModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
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
            const modal = document.getElementById('semesterModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
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
            const modal = document.getElementById('semesterModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('semesterModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>