<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
    </style>
</head>

<body class="bg-gray-50 text-gray-600 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Programs</span>
                        </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-600 h-16 ml-10">
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>


                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

        @if(session('success'))
        <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded relative mb-6 shadow-sm">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Programs List</h2>
                <button onclick="openModal()" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold mt-4 py-2 px-4 rounded uppercase tracking-wide transition shadow-sm">
                    Add New Program
                </button>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs text-gray-600 uppercase tracking-wider bg-gray-50">
                            <th class="py-3 px-4 font-bold">ID</th>
                            <th class="py-3 px-4 font-bold">Program Name</th>
                            <th class="py-3 px-4 font-bold">Description</th>
                            <th class="py-3 px-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm bg-white">
                        @forelse($programs as $program)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition group">
                            <td class="py-4 px-4 text-gray-500 font-mono">#{{ $program->id }}</td>
                            <td class="py-4 px-4 font-bold text-gray-900">{{ $program->course_name }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $program->description }}</td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex justify-end gap-4">
                                    <button data-program="{{ json_encode($program) }}"
                                        onclick="editModal(JSON.parse(this.dataset.program))"
                                        class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-widest transition">
                                        Edit
                                    </button>

                                    <form action="{{ route('registrar.programs.destroy', $program->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this program?');"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-500 hover:text-rose-600 text-xs font-bold uppercase tracking-widest transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-500 italic text-sm">No programs found in the database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($programs, 'links'))
            <div class="mt-6 border-t border-gray-200 pt-4">
                {{ $programs->links() }}
            </div>
            @endif
        </div>
    </main>

    <div id="programModal"
        class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 border border-gray-200 transform transition-all">
            <h3 class="text-xl font-bold text-gray-900 mb-6" id="modalTitle">Add New Program</h3>

            <form id="programForm" action="{{ route('registrar.programs.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Program Name</label>
                    <input type="text" name="course_name" id="course_name"
                           class="w-full bg-white text-gray-900 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm"
                           placeholder="e.g. BS Information Systems" required>
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full bg-white text-gray-900 border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400 shadow-sm"
                        placeholder="Brief description of the program"></textarea>
                </div>

                <div class="flex justify-end items-center gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 rounded shadow-sm transition">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all">
                        Save Program
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>

    <script>
    function openModal() {
        document.getElementById('programModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add New Program';
        document.getElementById('programForm').action = "{{ route('registrar.programs.store') }}";
        document.getElementById('methodField').innerHTML = ''; // Clear PUT method

        document.getElementById('course_name').value = '';
        document.getElementById('description').value = '';
    }

    function editModal(program) {
        document.getElementById('programModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Program';
        document.getElementById('programForm').action = "/registrar/programs/" + program.id;
        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('course_name').value = program.course_name;
        document.getElementById('description').value = program.description || '';
    }

    function closeModal() {
        document.getElementById('programModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('programModal');
        if (event.target == modal) closeModal();
    }
    </script>
</body>
</html>
