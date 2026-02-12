<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-900 text-white font-bold p-2 rounded-lg text-sm">AD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-slate-900">Admin Panel</h1>
                            <span class="text-xs text-gray-500">Manage Applications</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('admin.dashboard') }}"
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Applications List</h3>
                @if(isset($pendingCount))
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingCount }}
                    Pending</span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Student Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Course Applied</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $application->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 uppercase">
                                {{ $application->first_name }} {{ $application->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-bold text-slate-700">{{ $application->course_code }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $application->year_level }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $badgeColor = match(ucfirst($application->status)) {
                                'Approved' => 'bg-green-100 text-green-800',
                                'Rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-yellow-100 text-yellow-800',
                                };
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button type="button" {{-- 1. Store data safely in data-attributes --}}
                                        data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        {{-- 2. Read the data in the onclick --}}
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold transition">
                                        View
                                    </button>

                                    <form action="{{ route('admin.applications.destroy', $application->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this application?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-gray-800 hover:bg-gray-900 text-white px-3 py-1 rounded text-xs font-bold transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">No applications found.</td>
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
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Application Details</h3>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
            </div>

            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h4
                        class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wide border-b border-gray-200 pb-2">
                        Student Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="font-semibold text-gray-600">Full Name:</span> <span id="modalName"
                                class="text-gray-900 font-bold uppercase"></span></div>
                        <div><span class="font-semibold text-gray-600">Email:</span> <span id="modalEmail"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Date of Birth:</span> <span id="modalDob"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Age:</span> <span id="modalAge"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Gender:</span><span id="modalGender"
                                class="text-gray-900 capitalize"></span></div>
                        <div class="col-span-2"><span class="font-semibold text-gray-600">Address:</span> <span
                                id="modalAddress" class="text-gray-900"></span></div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h4
                        class="text-sm font-bold text-blue-800 mb-3 uppercase tracking-wide border-b border-blue-200 pb-2">
                        Program Details</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="col-span-2">
                            <span class="font-semibold text-blue-900">Program:</span>
                            <span id="modalCourse" class="text-blue-800 uppercase font-bold"></span>
                        </div>
                        <div><span class="font-semibold text-blue-900">Year Level:</span> <span id="modalYear"
                                class="text-blue-800"></span></div>
                        <div><span class="font-semibold text-blue-900">Status:</span> <span id="modalStatus"
                                class="font-bold px-2 py-0.5 rounded text-xs bg-white border border-blue-200 ml-1"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <h4
                        class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wide border-b border-gray-200 pb-2">
                        Guardian Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="font-semibold text-gray-600">Father's Name:</span> <span id="modalFather"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Mother's Name:</span> <span id="modalMother"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Guardian:</span> <span id="modalGuardian"
                                class="text-gray-900"></span></div>
                        <div><span class="font-semibold text-gray-600">Contact #:</span> <span id="modalContact"
                                class="text-gray-900"></span></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 font-medium transition">Close</button>
                <form id="approveForm" method="POST">
                    @csrf @method('PATCH')
                </form>
                <form id="rejectForm" method="POST" onsubmit="return confirm('Reject application?');">
                    @csrf @method('PATCH')
                </form>
            </div>
        </div>
    </div>

    <script>
    function openModal(app, user, course) {
        try {
            document.getElementById('modalTitle').innerText = 'Application #' + app.id;

            // FIXED: Using 'app' (enrollment) data instead of 'user' to avoid N/A
            const middle = app.middle_name ? ' ' + app.middle_name : '';
            const fullName = (app.last_name || '') + ', ' + (app.first_name || '') + middle;
            document.getElementById('modalName').innerText = fullName;

            // Enrollment fields
            document.getElementById('modalEmail').innerText = app.email || 'N/A';
            document.getElementById('modalDob').innerText = app.birth_date || 'N/A';
            document.getElementById('modalAge').innerText = app.age || 'N/A';
            document.getElementById('modalGender').innerText = app.gender || 'N/A';
            document.getElementById('modalAddress').innerText = app.address_full || 'N/A';

            // Course Info (Map Descriptions Manually)
            let courseCode = app.course_code || 'N/A';
            let courseDesc = '';

            // Map known codes to descriptions
            if (courseCode === 'BSIS') courseDesc = 'Bachelor of Science in Information Systems';
            if (courseCode === 'ACT') courseDesc = 'Associate in Computer Technology';
            if (courseCode === 'BSA') courseDesc = 'Bachelor of Science in Accountancy';
            if (courseCode === 'BAB') courseDesc = 'Bachelor of Arts in Broadcasting';
            if (courseCode === 'BSSW') courseDesc = 'Bachelor of Science in Social Work';
            if (courseCode === 'AB English') courseDesc = 'Bachelor of Arts in English Language';

            document.getElementById('modalCourse').innerText = courseCode + (courseDesc ? ' - ' + courseDesc : '');

            document.getElementById('modalYear').innerText = app.year_level || 'N/A';
            document.getElementById('modalStatus').innerText = app.status;

            // Guardian Info
            document.getElementById('modalFather').innerText = app.father_name || 'N/A';
            document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
            document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
            document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

            // Update URLs
            const baseUrl = "{{ url('admin/applications') }}";
            document.getElementById('approveForm').action = `${baseUrl}/${app.id}`;
            document.getElementById('rejectForm').action = `${baseUrl}/${app.id}`;

            document.getElementById('applicationModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

        } catch (error) {
            console.error('Error parsing application data:', error);
            alert('Error loading details. Please check the console.');
        }
    }

    function closeModal() {
        document.getElementById('applicationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('applicationModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    </script>
</body>

</html>