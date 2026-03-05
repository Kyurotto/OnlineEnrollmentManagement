<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Applications - Registrar</title>
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
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                        <div>
                            <h1 class="text-lg font-bold leading-none text-gray-900">Registrar Panel</h1>
                            <span class="text-xs text-gray-500">Manage Applications</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-600 h-16">
                        <a href="{{ route('registrar.dashboard') }}"
                            class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
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

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Applications List</h3>
                <span class="bg-white text-[#10B981] border border-[#10B981]/20 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                    {{ $pendingCount }} Pending
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider bg-white">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Course Applied</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-200 bg-white">
                        @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500">#{{ $application->id }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 uppercase whitespace-nowrap">
                                {{ $application->last_name }}, {{ $application->first_name }}
                                {{ $application->middle_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap lowercase">
                                {{ $application->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-[#10B981]">{{ $application->course_code }}</span>
                                <span class="text-gray-500 text-xs ml-1 font-normal">
                                    ({{ $application->year_level }})
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $badgeColor = match(ucfirst($application->status)) {
                                    'Approved' => 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20',
                                    'Enrolled' => 'bg-sky-50 text-sky-600 border border-sky-200',
                                    'Rejected' => 'bg-rose-50 text-rose-600 border border-rose-200',
                                    'Pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                    default => 'bg-gray-100 text-gray-600 border border-gray-200',
                                };
                                $displayText = ucfirst($application->status);
                                if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeColor }} shadow-sm">
                                    {{ $displayText }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" data-application="{{ json_encode($application) }}"
                                        data-user="{{ json_encode($application->user) }}"
                                        onclick="openModal(JSON.parse(this.dataset.application), JSON.parse(this.dataset.user), null)"
                                        class="bg-[#10B981] hover:bg-[#059669] text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm">
                                        View
                                    </button>

                                    <form action="{{ route('registrar.applications.destroy', $application->id) }}"
                                        method="POST" onsubmit="return confirm('Delete this application?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-1 rounded text-xs font-bold transition border border-gray-300 shadow-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                @if(method_exists($applications, 'links'))
                <div class="custom-pagination">
                    {{ $applications->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <div id="applicationModal" class="fixed inset-0 bg-black/40 hidden z-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 shrink-0">
                <h2 class="text-xl font-bold text-gray-900" id="modalTitle">Application Details</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-white">
                <div class="space-y-8">

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-200 pt-4">
                            <div><span class="block text-gray-500 text-xs mb-1">Full Name:</span><span class="font-bold text-gray-900 uppercase" id="modalName"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Email:</span><span class="font-medium text-gray-900" id="modalEmail"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Date of Birth:</span><span class="font-medium text-gray-900" id="modalDob"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Age:</span><span class="font-medium text-gray-900" id="modalAge"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Gender:</span><span class="font-medium text-gray-900 capitalize" id="modalGender"></span></div>
                            <div class="col-span-1 md:col-span-2"><span class="block text-gray-500 text-xs mb-1">Address:</span><span class="font-medium text-gray-900" id="modalAddress"></span></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 shadow-sm">
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                        <div class="space-y-4 text-sm">
                            <p><span class="font-bold text-gray-500 mr-2">Program:</span><span class="text-[#10B981] font-bold uppercase" id="modalCourse"></span></p>
                            <div class="flex gap-4">
                                <span><span class="font-bold text-gray-500">Year:</span> <span id="modalYear" class="text-gray-900"></span></span>
                                <span><span class="font-bold text-gray-500">Status:</span> <span class="text-[#10B981] font-bold uppercase" id="modalStatus"></span></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-gray-200 pt-4">
                            <div><span class="block text-gray-500 text-xs mb-1">Father's Name:</span><span class="font-bold text-gray-900 uppercase" id="modalFather"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Mother's Name:</span><span class="font-bold text-gray-900 uppercase" id="modalMother"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Guardian:</span><span class="font-bold text-gray-900 uppercase" id="modalGuardian"></span></div>
                            <div><span class="block text-gray-500 text-xs mb-1">Contact #:</span><span class="font-medium text-gray-900" id="modalContact"></span></div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3 text-center md:text-left">Submitted Documents</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-200 pt-4" id="modalDocuments">
                            </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-200 flex justify-between items-center shrink-0 rounded-b-xl">
                <div id="actionButtons" class="flex gap-3 hidden">
                    <form id="approveForm" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-[#10B981]/20 transition">Approve</button>
                    </form>
                    <form id="rejectForm" method="POST" onsubmit="return confirm('Reject application?');">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-rose-600/20 transition">Reject</button>
                    </form>
                </div>
                <button onclick="closeModal()" class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm ml-auto">Close</button>
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
        document.getElementById('modalCourse').innerText = courseCode + (courseDesc ? ' - ' + courseDesc : '');

        document.getElementById('modalYear').innerText = app.year_level || 'N/A';

        let statusText = app.status;
        if (statusText === 'Enrolled') { statusText = 'Paid'; }
        document.getElementById('modalStatus').innerText = statusText;

        document.getElementById('modalFather').innerText = app.father_name || 'N/A';
        document.getElementById('modalMother').innerText = app.mother_maiden_name || 'N/A';
        document.getElementById('modalGuardian').innerText = app.guardian_name || 'N/A';
        document.getElementById('modalContact').innerText = app.guardian_contact || 'N/A';

        const docsContainer = document.getElementById('modalDocuments');
        docsContainer.innerHTML = '';

        const documents = [
            { key: 'form_138_path', label: 'Form 138' },
            { key: 'good_moral_path', label: 'Good Moral' },
            { key: 'psa_path', label: 'PSA Birth Cert' },
            { key: 'id_picture_path', label: 'ID Picture' }
        ];

        const storageBase = @json(asset('storage')) + '/';

        documents.forEach(doc => {
            const hasFile = app[doc.key] ? true : false;
            let headerHtml = '';
            let boxHtml = '';

            if (hasFile) {
                const fileUrl = storageBase + app[doc.key];
                const isImage = app[doc.key].match(/\.(jpeg|jpg|png|gif|webp)$/i);

                headerHtml = `
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(16, 185, 129, 0.1); border: 2px solid #10B981; border-radius: 50%; flex-shrink: 0;">
                            <span style="color: #10B981; font-weight: 900; font-size: 14px; line-height: 1;">✓</span>
                        </div>
                        <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #374151;">${doc.label}</span>
                    </div>
                `;

                if (isImage) {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" style="display: block;">
                            <img src="${fileUrl}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; background-color: #f9fafb;">
                        </a>
                    `;
                } else {
                    boxHtml = `
                        <a href="${fileUrl}" target="_blank" style="display: block; text-decoration: none;">
                            <div style="width: 100%; height: 120px; border-radius: 8px; border: 1px solid #e5e7eb; background-color: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #10B981; transition: 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <span style="font-size: 30px;">📄</span>
                                <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; margin-top: 4px;">PDF</span>
                            </div>
                        </a>
                    `;
                }
            } else {
                headerHtml = `
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; background-color: rgba(244, 63, 94, 0.1); border: 2px solid #f43f5e; border-radius: 50%; flex-shrink: 0;">
                            <span style="color: #f43f5e; font-weight: 900; font-size: 14px; line-height: 1;">✗</span>
                        </div>
                        <span style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #f43f5e;">${doc.label}</span>
                    </div>
                `;

                boxHtml = `
                    <div style="width: 100%; height: 120px; border-radius: 8px; background-color: #fff1f2; border: 1px dashed rgba(244, 63, 94, 0.4); display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.8;">
                        <span style="font-size: 24px; color: rgba(244, 63, 94, 0.6);">⚠️</span>
                        <span style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: rgba(244, 63, 94, 0.6); margin-top: 4px;">Missing</span>
                    </div>
                `;
            }

            docsContainer.innerHTML += `<div>${headerHtml}${boxHtml}</div>`;
        });

        const baseUrl = "{{ url('registrar/applications') }}";
        document.getElementById('approveForm').action = `${baseUrl}/${app.id}`;
        document.getElementById('rejectForm').action = `${baseUrl}/${app.id}`;

        if (app.status === 'Pending') {
            document.getElementById('actionButtons').classList.remove('hidden');
        } else {
            document.getElementById('actionButtons').classList.add('hidden');
        }

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
