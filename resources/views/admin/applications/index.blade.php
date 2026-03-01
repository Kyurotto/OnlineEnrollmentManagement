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
                        <div
                            class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">
                            AD</div>
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
                        <div
                            class="absolute right-0 top-10 w-80 bg-[#1C1C1E] border border-[#27272A] shadow-2xl rounded-xl hidden group-hover:block z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 bg-[#121212] border-b border-[#27272A] flex justify-between items-center">
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
                                                <p
                                                    class="text-sm font-bold text-[#10B981] group-hover:text-[#059669] flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Student Paid ₱{{ number_format($notif->paid_amount ?? 0, 2) }}
                                                </p>
                                                <p class="text-xs text-[#A1A1AA] mt-1">
                                                    <span
                                                        class="font-bold text-[#FFFFFF] uppercase">{{ $notif->first_name }}
                                                        {{ $notif->last_name }}</span>
                                                    is now already <span class="font-bold text-[#10B981]">PAID</span>.
                                                </p>
                                            @else
                                                <p class="text-sm font-bold text-[#FFFFFF] group-hover:text-[#10B981]">
                                                    New Application</p>
                                                <p class="text-xs text-[#A1A1AA] mt-1">
                                                    <span
                                                        class="font-medium text-[#FFFFFF] uppercase">{{ $notif->first_name }}
                                                        {{ $notif->last_name }}</span>
                                                    applied for <span
                                                        class="uppercase font-bold text-[#10B981]">{{ $notif->course_code }}</span>.
                                                </p>
                                            @endif
                                            <p class="text-[10px] text-[#52525B] mt-2 text-right">
                                                {{ $notif->updated_at->diffForHumans() }}</p>
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
                        <button
                            class="bg-red-500 hover:bg-[#3F3F46] text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('success'))
            <div
                class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded relative mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#1C1C1E] rounded-xl shadow-md border border-[#27272A] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#1C1C1E]">
                <h3 class="text-lg font-bold text-[#FFFFFF]">Applications List</h3>
                @if (isset($pendingCount) && $pendingCount > 0)
                    <span
                        class="bg-[#121212] text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold">{{ $pendingCount }}
                        Pending</span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-[#A1A1AA]">
                    <thead class="text-xs text-[#52525B] uppercase bg-[#121212]">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Program</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#27272A]">
                        @forelse($applications as $application)
                            <tr class="bg-[#1C1C1E] hover:bg-[#27272A]/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#52525B]">#{{ $application->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-[#FFFFFF] uppercase">
                                    {{ $application->first_name }} {{ $application->last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA] lowercase">
                                    {{ $application->email }}</td>
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
                                        if ($displayText === 'Enrolled') {
                                            $displayText = 'Paid';
                                        }
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


                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-[#52525B]">No applications found.
                                </td>
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
        class="fixed inset-0 bg-black/80 hidden z-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
        <div
            class="bg-[#1C1C1E] w-full max-w-4xl rounded-xl shadow-2xl border border-[#27272A] overflow-hidden flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#1C1C1E] shrink-0">
                <h2 class="text-xl font-bold text-[#FFFFFF]" id="modalTitle">Application Details</h2>
                <button onclick="closeModal()"
                    class="text-[#A1A1AA] hover:text-[#FFFFFF] transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-[#1C1C1E]">
                <div class="space-y-8">

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information
                        </h3>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-[#27272A] pt-4">
                            <div><span class="block text-[#52525B] text-xs mb-1">Full Name:</span><span
                                    class="font-bold text-[#FFFFFF] uppercase" id="modalName"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Email:</span><span
                                    class="font-medium text-[#FFFFFF]" id="modalEmail"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Date of Birth:</span><span
                                    class="font-medium text-[#FFFFFF]" id="modalDob"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Age:</span><span
                                    class="font-medium text-[#FFFFFF]" id="modalAge"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Gender:</span><span
                                    class="font-medium text-[#FFFFFF] capitalize" id="modalGender"></span></div>
                            <div class="col-span-1 md:col-span-2"><span
                                    class="block text-[#52525B] text-xs mb-1">Address:</span><span
                                    class="font-medium text-[#FFFFFF]" id="modalAddress"></span></div>
                        </div>
                    </div>

                    <div class="bg-[#121212] border border-[#27272A] rounded-lg p-5">
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                        <div class="space-y-4 text-sm">
                            <p><span class="font-bold text-[#A1A1AA] mr-2">Program:</span><span
                                    class="text-[#10B981] font-bold uppercase" id="modalCourse"></span></p>
                            <div class="flex gap-4">
                                <span><span class="font-bold text-[#A1A1AA]">Year:</span> <span
                                        id="modalYear"></span></span>
                                <span><span class="font-bold text-[#A1A1AA]">Status:</span> <span
                                        class="text-[#10B981] font-bold uppercase" id="modalStatus"></span></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Guardian Information
                        </h3>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-[#27272A] pt-4">
                            <div><span class="block text-[#52525B] text-xs mb-1">Father's Name:</span><span
                                    class="font-bold text-[#FFFFFF] uppercase" id="modalFather"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Mother's Name:</span><span
                                    class="font-bold text-[#FFFFFF] uppercase" id="modalMother"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Guardian:</span><span
                                    class="font-bold text-[#FFFFFF] uppercase" id="modalGuardian"></span></div>
                            <div><span class="block text-[#52525B] text-xs mb-1">Contact #:</span><span
                                    class="font-medium text-[#FFFFFF]" id="modalContact"></span></div>
                        </div>
                    </div>

                    <div>
                        <h3
                            class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3 text-center md:text-left">
                            Submitted Documents</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-[#27272A] pt-4"
                            id="modalDocuments">
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-[#121212] px-6 py-5 border-t border-[#27272A] flex justify-between items-center shrink-0">
                <div id="actionButtons" class="flex gap-3 hidden">
                    <form id="approveForm" method="POST">
                        @csrf @method('PATCH')
                    </form>
                    <form id="rejectForm" method="POST">
                        @csrf @method('PATCH')
                    </form>
                </div>
                <button onclick="closeModal()"
                    class="px-6 py-2 bg-[#27272A] hover:bg-[#3F3F46] text-[#FFFFFF] rounded-lg text-sm font-semibold transition ml-auto">Close</button>
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

                // ==========================================
                // DOCUMENT INJECTION LOGIC (WITH SIGNS)
                // ==========================================
                const docsContainer = document.getElementById('modalDocuments');
                docsContainer.innerHTML = '';

                const documents = [{
                        key: 'form_138_path',
                        label: 'Form 138'
                    },
                    {
                        key: 'good_moral_path',
                        label: 'Good Moral'
                    },
                    {
                        key: 'psa_path',
                        label: 'PSA Birth Cert'
                    },
                    {
                        key: 'id_picture_path',
                        label: 'ID Picture'
                    }
                ];

                const storageBase = @json(asset('storage')) + '/';

                documents.forEach(doc => {
                    const hasFile = app[doc.key] ? true : false;
                    let headerHtml = '';
                    let boxHtml = '';

                    if (hasFile) {
                        const fileUrl = storageBase + app[doc.key];
                        const isImage = app[doc.key].match(/\.(jpeg|jpg|png|gif|webp)$/i);

                        // EMERALD CHECK SIGN
                        headerHtml = `
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(16, 185, 129, 0.2); border: 2px solid #10B981; border-radius: 50%; flex-shrink: 0;">
                                    <span style="color: #10B981; font-weight: 900; font-size: 14px; line-height: 1;">✓</span>
                                </div>
                                <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #FFFFFF;">${doc.label}</span>
                            </div>
                        `;

                        if (isImage) {
                            boxHtml = `
                                <a href="${fileUrl}" target="_blank" style="display: block;">
                                    <img src="${fileUrl}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #27272A; background-color: #121212;">
                                </a>
                            `;
                        } else {
                            boxHtml = `
                                <a href="${fileUrl}" target="_blank" style="display: block; text-decoration: none;">
                                    <div style="width: 100%; height: 120px; border-radius: 8px; border: 1px solid #27272A; background-color: #1C1C1E; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #10B981; transition: 0.2s;">
                                        <span style="font-size: 30px;">📄</span>
                                        <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; margin-top: 4px;">PDF</span>
                                    </div>
                                </a>
                            `;
                        }
                    } else {
                        // ROSE X SIGN
                        headerHtml = `
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(244, 63, 94, 0.2); border: 2px solid #f43f5e; border-radius: 50%; flex-shrink: 0;">
                                    <span style="color: #f43f5e; font-weight: 900; font-size: 14px; line-height: 1;">✗</span>
                                </div>
                                <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #f43f5e;">${doc.label}</span>
                            </div>
                        `;

                        boxHtml = `
                            <div style="width: 100%; height: 120px; border-radius: 8px; background-color: #121212; border: 1px dashed rgba(244, 63, 94, 0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                <span style="font-size: 24px; color: rgba(244, 63, 94, 0.6);">⚠️</span>
                                <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: rgba(244, 63, 94, 0.6); margin-top: 4px;">Missing</span>
                            </div>
                        `;
                    }

                    docsContainer.innerHTML += `<div>${headerHtml}${boxHtml}</div>`;
                });

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
