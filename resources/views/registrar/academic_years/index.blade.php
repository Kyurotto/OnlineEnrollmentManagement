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
<body class="bg-[#121212] text-[#A1A1AA]">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-white">Registrar Panel</h1>
                            <span class="text-xs text-[#52525B]">Manage Academic Years</span>
                        </div>
                    <div class="flex space-x-6 text-sm font-medium text-[#A1A1AA] h-16 ml-10">
                        <a href="{{ route('registrar.dashboard') }}" class="flex items-center hover:text-white transition h-full">Dashboard</a>
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1C1C1E] rounded-lg shadow-sm border border-[#27272A] p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Academic Years List</h2>
                <button onclick="openModal()" class="bg-[#10B981] hover:bg-[#059669] text-white text-xs font-bold py-2 px-4 rounded uppercase tracking-wide transition">
                    Add New Academic Year
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#27272A] text-xs text-[#52525B] uppercase tracking-wider">
                            <th class="py-3 px-4 font-medium">ID</th>
                            <th class="py-3 px-4 font-medium">Academic Year</th>
                            <th class="py-3 px-4 font-medium">Status</th>
                            <th class="py-3 px-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($years as $year)
                        <tr class="border-b border-[#27272A] hover:bg-[#27272A]/50 transition group">
                            <td class="py-4 px-4 text-[#52525B]">{{ $year->id }}</td>
                            <td class="py-4 px-4 font-medium text-white">{{ $year->year_name }}</td>
                            <td class="py-4 px-4">
                                @if($year->is_active)
                                    <span class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-2 py-1 rounded-full border border-[#10B981]/20">Active</span>
                                @else
                                    <span class="bg-[#27272A] text-[#52525B] text-xs font-bold px-2 py-1 rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <button 
                                    data-year="{{ json_encode($year) }}"
                                    onclick="editModal(JSON.parse(this.dataset.year))"
                                    class="text-[#10B981] hover:text-[#059669] text-xs font-medium transition mr-3">
                                    Edit
                                </button>
                                
                                <form action="{{ route('registrar.academic-years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Academic Year?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-medium transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-[#52525B] text-sm">No academic years found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $years->links() }}
            </div>
        </div>
    </main>

    <div id="yearModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-[#1C1C1E] rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all border border-[#27272A]">
            <h3 class="text-lg font-bold text-white mb-4" id="modalTitle">Add New Academic Year</h3>
            
            <form id="yearForm" action="{{ route('registrar.academic-years.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#A1A1AA] uppercase mb-1">Academic Year</label>
                    <input type="text" name="year_name" id="year_name" class="w-full bg-[#121212] border border-[#27272A] text-white rounded p-2 text-sm focus:ring-2 focus:ring-[#10B981] outline-none" placeholder="2025 - 2026" required>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-[#10B981] rounded border-[#27272A] bg-[#121212] focus:ring-[#10B981]">
                    <label for="is_active" class="ml-2 text-sm text-[#A1A1AA]">Set as Active Academic Year</label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm text-[#A1A1AA] hover:bg-[#27272A] rounded transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm bg-[#10B981] hover:bg-[#059669] text-white font-bold rounded shadow transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('yearModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'Add New Academic Year';
            document.getElementById('yearForm').action = "{{ route('registrar.academic-years.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('year_name').value = '';
            document.getElementById('is_active').checked = false;
        }

        function editModal(data) {
            document.getElementById('yearModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'Edit Academic Year';
            document.getElementById('yearForm').action = "/registrar/academic-years/" + data.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            document.getElementById('year_name').value = data.year_name;
            document.getElementById('is_active').checked = data.is_active == 1;
        }

        function closeModal() {
            document.getElementById('yearModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('yearModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>