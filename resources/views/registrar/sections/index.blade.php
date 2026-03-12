<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sections</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-600">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                        <span class="text-xs text-gray-500">Manage Sections</span>
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Sections List</h2>
                <button onclick="openModal()" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition">
                    Add New Section
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3 px-4 font-medium">ID</th>
                            <th class="py-3 px-4 font-medium">Academic Year</th>
                            <th class="py-3 px-4 font-medium">Section</th>
                            <th class="py-3 px-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($sections as $section)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                            <td class="py-4 px-4 text-gray-400">{{ $section->id }}</td>
                            <td class="py-4 px-4 font-medium text-gray-600">{{ $section->academic_year }}</td>
                            
                            <td class="py-4 px-4 text-gray-900 font-bold uppercase transition-colors group-hover:text-[#10B981]">
                                {{ $section->section_name }}
                            </td>

                            <td class="py-4 px-4 text-right">
                                <button 
                                    data-section="{{ json_encode($section) }}"
                                    onclick="editModal(JSON.parse(this.dataset.section))"
                                    class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-widest transition mr-3">
                                    Edit
                                </button>
                                
                                <form action="{{ route('registrar.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Delete this Section?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-400 text-xs font-bold uppercase tracking-widest transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 italic text-sm font-medium">No sections found in the database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sections->links() }}
            </div>
        </div>
    </main>

    <div id="sectionModal" class="fixed inset-0 bg-gray-900/50 z-50 items-center justify-center backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Add New Section</h3>
            
            <form id="sectionForm" action="{{ route('registrar.sections.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Academic Year</label>
                    <select name="academic_year" id="academic_year" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all" required>
                        <option value="" disabled selected>Select Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->year_name }}">{{ $year->year_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Program</label>
                    <select name="course_id" id="course_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all" required>
                        <option value="" disabled selected>Select Program</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ preg_replace('/[0-9]+/', '', $course->course_code) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section Name</label>
                    <input type="text" name="section_name" id="section_name" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all uppercase placeholder-gray-400" placeholder="e.g. BSIS 1A" required>
                    <p class="text-[10px] text-gray-400 mt-2 font-medium">Enter the full section name (e.g., BSIS 1A).</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all">Save Section</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('sectionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Add New Section';
            document.getElementById('sectionForm').action = "{{ route('registrar.sections.store') }}";
            document.getElementById('methodField').innerHTML = '';
            
            document.getElementById('academic_year').value = '';
            document.getElementById('course_id').value = '';
            document.getElementById('section_name').value = '';
        }

        function editModal(data) {
            const modal = document.getElementById('sectionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Edit Section';
            document.getElementById('sectionForm').action = "/registrar/sections/" + data.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('academic_year').value = data.academic_year;
            document.getElementById('course_id').value = data.course_id;
            document.getElementById('section_name').value = data.section_name;
        }

        function closeModal() {
            const modal = document.getElementById('sectionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('sectionModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>