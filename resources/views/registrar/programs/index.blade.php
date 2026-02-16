<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Programs</span>
                        </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16 ml-10">
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
                    </div>
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
                <h2 class="text-xl font-bold text-slate-800 mb-4">Programs List</h2>
                <button onclick="openModal()"
                    class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition">
                    Add New Program
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                            <th class="py-3 font-medium">ID</th>
                            <th class="py-3 font-medium">Program Name</th>
                            <th class="py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($programs as $program)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                            <td class="py-4 text-gray-500">{{ $program->id }}</td>
                            <td class="py-4 font-bold text-slate-700">{{ $program->course_code }}</td>
                            <td class="py-4 text-right flex justify-end gap-3">
                                <button data-program="{{ json_encode($program) }}"
                                    onclick="editModal(JSON.parse(this.dataset.program))"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">
                                    Edit
                                </button>

                                <form action="{{ route('registrar.programs.destroy', $program->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this program?');"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 text-xs font-medium transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400 text-sm">No programs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $programs->links() }}
            </div>
        </div>
    </main>

    <div id="programModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all">
            <h3 class="text-lg font-bold text-slate-800 mb-4" id="modalTitle">Add New Program</h3>

            <form id="programForm" action="{{ route('registrar.programs.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Program Code / Name</label>
                    <input type="text" name="course_code" id="course_code"
                        class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none uppercase"
                        placeholder="e.g. BSIS" required>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description (Optional)</label>
                    <input type="text" name="description" id="description"
                        class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="e.g. Bachelor of Science in Information Systems">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold rounded shadow transition">Save
                        Program</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openModal() {
        // Reset for "Add" mode
        document.getElementById('programModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add New Program';
        document.getElementById('programForm').action = "{{ route('registrar.programs.store') }}";
        document.getElementById('methodField').innerHTML = ''; // Clear PUT method
        document.getElementById('course_code').value = '';
        document.getElementById('description').value = '';
    }

    function editModal(program) {
        // Setup for "Edit" mode
        document.getElementById('programModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Program';
        // Dynamically set action URL: /registrar/programs/{id}
        document.getElementById('programForm').action = "/registrar/programs/" + program.id;
        // Inject PUT method for Laravel update
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('course_code').value = program.course_code;
        document.getElementById('description').value = program.description || '';
    }

    function closeModal() {
        document.getElementById('programModal').classList.add('hidden');
    }

    // Close on background click
    window.onclick = function(event) {
        const modal = document.getElementById('programModal');
        if (event.target == modal) closeModal();
    }
    </script>
</body>

</html>