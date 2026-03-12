<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Academic Years</title>
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
                            <span class="text-xs text-gray-500">Manage Academic Years</span>
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
                <h2 class="text-xl font-bold text-gray-900 mb-4">Academic Years List</h2>
                <button onclick="openModal()" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2.5 px-6 rounded-lg uppercase tracking-widest shadow-md shadow-[#10B981]/20 transition-all">
                    Add New Academic Year
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3.5 px-4 font-bold">ID</th>
                            <th class="py-3.5 px-4 font-bold">Academic Year</th>
                            <th class="py-3.5 px-4 font-bold">Status</th>
                            <th class="py-3.5 px-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($years as $year)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                            <td class="py-4 px-4 text-gray-400 font-mono">#{{ $year->id }}</td>
                            <td class="py-4 px-4 font-bold text-gray-900 uppercase transition-colors group-hover:text-[#10B981]">{{ $year->year_name }}</td>
                            <td class="py-4 px-4">
                                @if($year->is_active)
                                    <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-3 py-1.5 rounded-full border border-[#10B981]/20">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-400 text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <button
                                        data-year="{{ json_encode($year) }}"
                                        onclick="editModal(JSON.parse(this.dataset.year))"
                                        class="text-[#10B981] hover:text-[#059669] text-xs font-bold uppercase tracking-widest transition">
                                        Edit
                                    </button>

                                    <form action="{{ route('registrar.academic_years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Academic Year?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-400 text-xs font-bold uppercase tracking-widest transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400 italic text-sm">No academic years found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($years, 'links'))
            <div class="mt-6 border-t border-gray-100 pt-4">
                {{ $years->links() }}
            </div>
            @endif
        </div>
    </main>

    <div id="yearModal" class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center backdrop-blur-sm p-4 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 transform transition-all border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="modalTitle">Add New Academic Year</h3>

            <form id="yearForm" action="{{ route('registrar.academic_years.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Academic Year</label>
                    <input type="text" name="year_name" id="year_name" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 text-sm focus:ring-1 focus:ring-[#10B981] focus:border-[#10B981] outline-none transition-all placeholder-gray-400" placeholder="e.g. 2025 - 2026" required>
                </div>

                <div class="mb-8 flex items-center bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <input type="checkbox" name="is_active" id="is_active" class="w-5 h-5 text-[#10B981] rounded border-gray-200 bg-white focus:ring-[#10B981]">
                    <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">Set as Active Academic Year</label>
                </div>

                <div class="flex justify-end items-center gap-4">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-900 transition">Cancel</button>
                    <button type="submit" class="px-8 py-2.5 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded-lg shadow-md shadow-[#10B981]/10 transition-all">Save Year</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('yearModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Add New Academic Year';
            document.getElementById('yearForm').action = "{{ route('registrar.academic_years.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('year_name').value = '';
            document.getElementById('is_active').checked = false;
        }

        function editModal(data) {
            const modal = document.getElementById('yearModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalTitle').innerText = 'Edit Academic Year';
            document.getElementById('yearForm').action = "/registrar/academic-years/" + data.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('year_name').value = data.year_name;
            document.getElementById('is_active').checked = data.is_active == 1;
        }

        function closeModal() {
            const modal = document.getElementById('yearModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('yearModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>
