<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Applications - Registrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Scrollbar for notification dropdown */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 4px;
    }
    </style>
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
                            <span class="text-xs text-gray-500">Manage Applications</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('registrar.dashboard') }}"
                            class="flex items-center hover:text-slate-900 transition h-full">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
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
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Applications List</h3>
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingCount }}
                    Pending</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Course Applied</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">#{{ $application->id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900 uppercase whitespace-nowrap">
                                {{ $application->last_name }}, {{ $application->first_name }}
                                {{ $application->middle_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $application->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900">{{ $application->course_code }}</span>
                                <span class="text-gray-400 text-xs ml-1 font-normal">
                                    ({{ $application->year_level }})
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $badgeColor = match(ucfirst($application->status)) {
                                'Approved' => 'bg-green-100 text-green-700',
                                'Rejected' => 'bg-red-100 text-red-700',
                                'Pending' => 'bg-yellow-100 text-yellow-700',
                                };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeColor }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold transition">
                                        View
                                    </button>

                                    <form action="{{ route('registrar.applications.destroy', $application->id) }}"
                                        method="POST" onsubmit="return confirm('Delete this application?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-slate-800 hover:bg-slate-900 text-white px-3 py-1.5 rounded text-xs font-bold transition shadow-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">No applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200">
                @if(method_exists($applications, 'links'))
                {{ $applications->links() }}
                @endif
            </div>
        </div>
    </main>

    <div id="applicationModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto h-full w-full flex items-center justify-center backdrop-blur-sm">
        <div
            class="relative mx-auto p-0 border w-full max-w-2xl shadow-2xl rounded-lg bg-white transform transition-all">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Application Details</h3>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:text-red-500 transition focus:outline-none text-2xl">&times;</button>
            </div>
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Student Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="block text-gray-500 text-xs">Full Name</span><span
                                class="font-bold text-slate-900 uppercase" id="modalName"></span></div>
                        <div><span class="block text-gray-500 text-xs">Email</span><span
                                class="font-medium text-slate-900" id="modalEmail"></span></div>
                        <div><span class="block text-gray-500 text-xs">Date of Birth</span><span
                                class="font-medium text-slate-900" id="modalDob"></span></div>
                        <div><span class="block text-gray-500 text-xs">Age</span><span
                                class="font-medium text-slate-900" id="modalAge"></span></div>
                        <div><span class="block text-gray-500 text-xs">Gender</span><span
                                class="font-medium text-slate-900 capitalize" id="modalGender"></span></div>
                        <div class="col-span-2"><span class="block text-gray-500 text-xs">Address</span><span
                                class="font-medium text-slate-900" id="modalAddress"></span></div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">
                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Program Details</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-bold text-blue-900">Program:</span><span
                                class="text-blue-800 uppercase ml-1" id="modalCourse"></span></div>
                        <div class="flex items-center gap-3">
                            <div><span class="font-bold text-blue-900">Year Level:</span><span class="text-blue-800"
                                    id="modalYear"></span></div>
                            <span class="text-blue-300">|</span>
                            <div><span class="font-bold text-blue-900">Status:</span><span id="modalStatus"
                                    class="ml-1 px-2 py-0.5 rounded text-xs font-bold bg-white border border-blue-200 text-blue-800"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Guardian Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="block text-gray-500 text-xs">Father's Name</span><span
                                class="font-medium text-slate-900" id="modalFather"></span></div>
                        <div><span class="block text-gray-500 text-xs">Mother's Name</span><span
                                class="font-medium text-slate-900" id="modalMother"></span></div>
                        <div><span class="block text-gray-500 text-xs">Guardian</span><span
                                class="font-medium text-slate-900" id="modalGuardian"></span></div>
                        <div><span class="block text-gray-500 text-xs">Contact #</span><span
                                class="font-medium text-slate-900" id="modalContact"></span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-white border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Close</button>
                <form id="approveForm" method="POST">@csrf @method('PATCH')<input type="hidden" name="status"
                        value="Approved"><button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-bold shadow-sm transition">Approve</button>
                </form>
                <form id="rejectForm" method="POST" onsubmit="return confirm('Reject application?');">@csrf
                    @method('PATCH')<input type="hidden" name="status" value="Rejected"><button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded text-sm font-bold shadow-sm transition">Reject</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(app, user, course) {
        document.getElementById('modalTitle').innerText = 'Application #' + app.id;
        const middle = app.middle_name ? ' ' + app.middle_name : '';
        const fullName = (app.last_name || '') + ', ' + (app.first_name || '') + middle;
        document.getElementById('modalName').innerText = fullName;
        document.getElementById('modalEmail').innerText = app.email || 'N/A';
        document.getElementById('modalDob').innerText = app.birth_date || 'N/A';
        document.getElementById('modalAge').innerText = app.age || 'N/A';
        document.getElementById('modalGender').innerText = app.gender || 'N/A';
        document.getElementById('modalAddress').innerText = app.address_full || 'N/A';

        let courseCode = app.course_code || 'N/A';
        let courseDesc = '';
        if (courseCode === 'BSIS') courseDesc = 'Bachelor of Science in Information Systems';
        if (courseCode === 'ACT') courseDesc = 'Associate in Computer Technology';
        if (courseCode === 'BSA') courseDesc = 'Bachelor of Science in Accountancy';
        if (courseCode === 'BAB') courseDesc = 'Bachelor of Arts in Broadcasting';
        if (courseCode === 'BSSW') courseDesc = 'Bachelor of Science in Social Work';
        if (courseCode === 'AB English') courseDesc = 'Bachelor of Arts in English Language';
        document.getElementById('modalCourse').innerText = courseCode +  (courseDesc ? ' - ' + courseDesc : '');

        document.getElementById('modalYear').innerText = app.year_level || 'N/A';
        document.getElementById('modalStatus').innerText = app.status;
        document.getElementById('modalFather').innerText = app.father_name || 'N/A';
        document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
        document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
        document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

        const baseUrl = "{{ url('registrar/applications') }}";
        document.getElementById('approveForm').action = `${baseUrl}/${app.id}`;
        document.getElementById('rejectForm').action = `${baseUrl}/${app.id}`;
        document.getElementById('applicationModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('applicationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('applicationModal');
        if (event.target == modal) closeModal();
    }
    </script>
</body>

</html>