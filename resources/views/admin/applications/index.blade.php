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

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #3F3F46;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#121212] text-[#A1A1AA] flex flex-col min-h-screen">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">AD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-[#FFFFFF]">Admin Panel</h1>
                            <span class="text-xs text-[#52525B]">Manage Applications</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-[#A1A1AA] h-16">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <div class="relative cursor-pointer group mr-2">
                        <div class="absolute right-0 top-10 w-80 bg-[#1C1C1E] border border-[#27272A] shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-[#121212] border-b border-[#27272A] flex justify-between items-center">
                                <h3 class="text-sm font-bold text-[#FFFFFF] uppercase tracking-wide">NOTIFICATIONS</h3>
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scrollbar bg-[#121212] p-2 space-y-2">
                                @if (isset($notifications) && count($notifications) > 0)
                                    @foreach ($notifications as $notif)
                                        <div data-application="{{ json_encode($notif) }}"
                                            data-user="{{ json_encode($notif->user) }}"
                                            onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                            class="block bg-[#1C1C1E] p-3 rounded-lg border border-[#27272A] hover:border-[#10B981] hover:shadow-sm transition group cursor-pointer">
                                            @if ($notif->status === 'Enrolled')
                                                <p class="text-sm font-bold text-[#10B981] group-hover:text-[#059669] flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Student Paid ₱{{ number_format($notif->paid_amount ?? 0, 2) }}
                                                </p>
                                                <p class="text-xs text-[#A1A1AA] mt-1">
                                                    <span class="font-bold text-[#FFFFFF] uppercase">{{ $notif->first_name }} {{ $notif->last_name }}</span>
                                                    is now already <span class="font-bold text-[#10B981]">PAID</span>.
                                                </p>
                                            @else
                                                <p class="text-sm font-bold text-[#FFFFFF] group-hover:text-[#10B981]">New Application</p>
                                                <p class="text-xs text-[#A1A1AA] mt-1">
                                                    <span class="font-medium text-[#FFFFFF] uppercase">{{ $notif->first_name }} {{ $notif->last_name }}</span>
                                                    applied for <span class="uppercase font-bold text-[#10B981]">{{ $notif->course_code }}</span>.
                                                </p>
                                            @endif
                                            <p class="text-[10px] text-[#52525B] mt-2 text-right">{{ $notif->updated_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-6 text-[#52525B] text-sm">No notifications</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-[#3F3F46] text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('success'))
            <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1C1C1E] rounded-xl shadow-md border border-[#27272A] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#1C1C1E]">
                <h3 class="text-lg font-bold text-[#FFFFFF]">Applications List</h3>
                @if (isset($pendingCount) && $pendingCount > 0)
                    <span class="bg-[#121212] text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingCount }} Pending</span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-[#A1A1AA]">
                    <thead class="text-xs text-[#52525B] uppercase bg-[#121212]">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Course Applied</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#27272A]">
                        @forelse($applications as $application)
                            <tr class="bg-[#1C1C1E] hover:bg-[#27272A]/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#52525B]">#{{ $application->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-[#FFFFFF] uppercase">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA] lowercase">{{ $application->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                    <span class="font-bold text-[#10B981]">{{ $application->course_code }}</span>
                                    <span class="text-[#52525B] text-xs ml-1">({{ $application->year_level }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA]">
                                    {{ $application->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColor = match (ucfirst($application->status)) {
                                            'Approved' => 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20',
                                            'Enrolled' => 'bg-sky-500/10 text-sky-400 border border-sky-500/20',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                            default => 'bg-[#27272A] text-[#A1A1AA]',
                                        };
                                        $displayText = ucfirst($application->status);
                                        if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-application="{{ json_encode($application) }}"
                                            data-user="{{ json_encode($application->user) }}"
                                            onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                            class="bg-[#10B981] hover:bg-[#059669] text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm">
                                            View
                                        </button>

                                        <form action="{{ route('admin.applications.destroy', $application->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this application?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-[#27272A] hover:bg-[#3F3F46] text-[#FFFFFF] px-3 py-1 rounded text-xs font-bold transition border border-[#3F3F46]">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-[#52525B]">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-[#27272A] bg-[#121212]/30">
                @if (method_exists($applications, 'links'))
                    {{ $applications->links() }}
                @endif
            </div>
        </div>
    </main>

    <div id="applicationModal"
        class="fixed inset-0 bg-black/70 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm p-4">
        <div class="relative top-10 mx-auto p-8 border border-[#27272A] w-full max-w-2xl shadow-2xl rounded-xl bg-[#1C1C1E] transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-[#FFFFFF]" id="modalTitle">Application Details</h3>
                <button onclick="closeModal()"
                    class="text-[#A1A1AA] hover:text-[#FFFFFF] text-2xl font-bold transition focus:outline-none">&times;</button>
            </div>

            <div class="space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar pr-2">
                <div class="bg-[#121212] p-5 rounded-lg border border-[#27272A]">
                    <h4 class="text-xs font-bold text-[#10B981] mb-4 uppercase tracking-widest border-b border-[#27272A] pb-2">Student Information</h4>
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div><span class="block text-[#52525B] text-xs mb-1">Full Name</span> <span id="modalName" class="text-[#FFFFFF] font-bold uppercase"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Email</span> <span id="modalEmail" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Date of Birth</span> <span id="modalDob" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Age</span> <span id="modalAge" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Gender</span><span id="modalGender" class="text-[#FFFFFF] font-medium capitalize"></span></div>
                        <div class="col-span-2"><span class="block text-[#52525B] text-xs mb-1">Address</span> <span id="modalAddress" class="text-[#FFFFFF] font-medium"></span></div>
                    </div>
                </div>

                <div class="bg-[#10B981]/5 p-5 rounded-lg border border-[#10B981]/20">
                    <h4 class="text-xs font-bold text-[#10B981] mb-4 uppercase tracking-widest border-b border-[#10B981]/20 pb-2">Program Details</h4>
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div class="col-span-2">
                            <span class="block text-[#52525B] text-xs mb-1">Program</span>
                            <span id="modalCourse" class="text-[#10B981] uppercase font-bold"></span>
                        </div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Year Level</span> <span id="modalYear" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Status</span> <span id="modalStatus" class="font-bold px-3 py-1 rounded-full text-xs bg-[#121212] border border-[#10B981]/30 text-[#10B981] inline-block mt-1"></span></div>
                    </div>
                </div>

                <div class="bg-[#121212] p-5 rounded-lg border border-[#27272A]">
                    <h4 class="text-xs font-bold text-[#10B981] mb-4 uppercase tracking-widest border-b border-[#27272A] pb-2">Guardian Information</h4>
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div><span class="block text-[#52525B] text-xs mb-1">Father's Name</span> <span id="modalFather" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Mother's Name</span> <span id="modalMother" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Guardian</span> <span id="modalGuardian" class="text-[#FFFFFF] font-medium"></span></div>
                        <div><span class="block text-[#52525B] text-xs mb-1">Contact #</span> <span id="modalContact" class="text-[#FFFFFF] font-medium"></span></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center pt-5 border-t border-[#27272A]">
                <div id="actionButtons" class="flex gap-3 hidden">
                    <form id="approveForm" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="px-6 py-2 bg-[#10B981] text-white rounded-lg hover:bg-[#059669] font-bold text-xs uppercase transition shadow-md shadow-[#10B981]/10">Approve</button>
                    </form>
                    <form id="rejectForm" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="px-6 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 font-bold text-xs uppercase transition shadow-md shadow-rose-600/10">Reject</button>
                    </form>
                </div>
                <button onclick="closeModal()" class="px-6 py-2 bg-[#27272A] text-[#A1A1AA] rounded-lg hover:bg-[#3F3F46] hover:text-white font-bold text-xs uppercase transition border border-[#3F3F46]">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(app, user, course) {
            try {
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

                document.getElementById('modalCourse').innerText = courseCode + (courseDesc ? ' - ' + courseDesc : '');
                document.getElementById('modalYear').innerText = app.year_level || 'N/A';

                let statusText = app.status;
                if (statusText === 'Enrolled') {
                    statusText = 'Paid';
                }
                document.getElementById('modalStatus').innerText = statusText;

                const updateUrl = `{{ url('admin/applications') }}/${app.id}`;
                document.getElementById('approveForm').action = updateUrl;
                document.getElementById('rejectForm').action = updateUrl;

                if (app.status === 'Pending') {
                    document.getElementById('actionButtons').classList.remove('hidden');
                } else {
                    document.getElementById('actionButtons').classList.add('hidden');
                }

                document.getElementById('modalFather').innerText = app.father_name || 'N/A';
                document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
                document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
                document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

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