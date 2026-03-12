<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Registrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Light Pagination matching Carbon & Emerald theme */
        .custom-pagination p {
            color: #4B5563 !important;
            font-size: 0.875rem;
        }

        .custom-pagination [role="navigation"] span.relative,
        .custom-pagination [role="navigation"] a.relative {
            background-color: #FFFFFF !important;
            border-color: #E5E7EB !important;
            color: #4B5563 !important;
        }

        .custom-pagination [role="navigation"] span[aria-current="page"]>span {
            background-color: #F9FAFB !important;
            border-color: #10B981 !important;
            color: #10B981 !important;
        }

        .custom-pagination [role="navigation"] a.relative:hover {
            background-color: #F3F4F6 !important;
            color: #111827 !important;
        }

        .custom-pagination [role="navigation"] svg {
            color: #9CA3AF !important;
        }
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
                            <span class="text-xs text-gray-500">Manage Students</span>
                        </div>
                    </div>
                    <div class="flex space-x-6 text-sm font-medium text-gray-500 h-16">
                        <a href="{{ route('registrar.dashboard') }}"
                            class="flex items-center hover:text-[#10B981] transition h-full">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative">
                        <svg class="w-6 h-6 text-gray-400 hover:text-[#10B981] transition cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if (isset($pendingCount) && $pendingCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-rose-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-white">
                <h3 class="text-lg font-bold text-gray-900">Student List</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Last Name</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">First Name</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Program</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Section</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 font-bold tracking-wider text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="bg-white border-b border-gray-100 hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                                    {{ $student->last_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                                    {{ $student->first_name ?? 'N/A' }}</td>

                                <td class="px-6 py-4 text-gray-500 lowercase">{{ $student->email }}</td>

                                <td class="px-6 py-4 font-bold text-[#10B981] text-center uppercase">
                                    {{ $student->program }}</td>
                                <td class="px-6 py-4 font-medium text-gray-500 text-center whitespace-nowrap">
                                    {{ $student->year_display }}</td>


                                <td class="px-6 py-4">
                                    <span
                                        class="bg-[#10B981]/10 text-[#10B981] text-xs font-bold px-3 py-1 rounded-full border border-[#10B981]/20">
                                        {{ $student->status ?? 'Enrolled' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('registrar.students.edit', $student->id) }}"
                                        class="text-[#10B981] hover:text-[#059669] font-bold text-xs uppercase tracking-wider transition-colors">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic">No approved
                                    students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 custom-pagination">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            © 2026 Your Institution — Registrar Panel
        </div>
    </footer>

</body>

</html>