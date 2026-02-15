<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application #{{ $application->id }} - Registrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Overlay effect for the background to make it look like a modal */
        .modal-backdrop { background-color: rgba(0, 0, 0, 0.5); }
    </style>
</head>
<body class="bg-gray-100 text-slate-800 h-screen overflow-hidden flex flex-col">

    <nav class="bg-white border-b border-gray-200 z-10 shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-900 text-white font-bold p-2 rounded-lg text-sm">RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Registrar Panel</h1>
                        <span class="text-xs text-gray-500">Manage Applications</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-1.5 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-grow flex items-center justify-center p-4 modal-backdrop relative">
        
        <div class="bg-white w-full max-w-4xl rounded-lg shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white shrink-0">
                <h2 class="text-xl font-bold text-slate-800">Application #{{ $application->id }}</h2>
                
                <a href="{{ route('registrar.applications.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar">
                
                <div class="space-y-6">

                    <div>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm border-t border-gray-100 pt-3">
                            <div class="col-span-1">
                                <span class="block text-gray-500 text-xs mb-1">Full Name:</span>
                                <span class="font-medium text-slate-900 uppercase">
                                    {{ $application->user?->last_name ?? 'N/A' }}, {{ $application->user?->first_name ?? 'N/A' }} {{ $application->user?->middle_name ?? '' }}
                                </span>
                            </div>
                            <div class="col-span-1">
                                <span class="block text-gray-500 text-xs mb-1">Email:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->email ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-1">
                                <span class="block text-gray-500 text-xs mb-1">Date of Birth:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->birth_date ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-1">
                                <span class="block text-gray-500 text-xs mb-1">Age:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->age ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-1">
                                <span class="block text-gray-500 text-xs mb-1">Gender:</span>
                                <span class="font-medium text-slate-900 capitalize">{{ $application->user?->gender ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-gray-500 text-xs mb-1">Address:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->address_full ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-md p-5">
                        <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Program Details</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="font-bold text-blue-900 mr-1">Program:</span>
                                <span class="text-blue-700 uppercase">
                                    {{ $application->course?->course_code ?? 'NO CODE' }} - {{ $application->course?->course_description ?? 'Course Not Found' }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center text-blue-900 gap-x-2">
                                <span class="font-bold">Year Level:</span>
                                <span class="text-blue-700">{{ $application->year_level ?? '1st Year' }}</span>
                                <span class="text-blue-300">|</span>
                                
                                <span class="font-bold">1st Semester</span> <span class="text-blue-300">|</span>

                                <span class="text-blue-700">{{ date('Y') }}-{{ date('Y')+1 }}</span>
                                
                                <div class="ml-4 flex items-center gap-2">
                                    <span class="font-bold">Status:</span>
                                    @php
                                        $statusClass = match(ucfirst($application->status)) {
                                            'Approved' => 'bg-white border-green-200 text-green-700',
                                            'Rejected' => 'bg-white border-red-200 text-red-700',
                                            default => 'bg-white border-gray-200 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 border rounded text-xs font-bold shadow-sm {{ $statusClass }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm border-t border-gray-100 pt-3">
                            <div>
                                <span class="block text-gray-500 text-xs mb-1">Father's Name:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->father_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 text-xs mb-1">Mother's Name:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->mother_maiden_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 text-xs mb-1">Guardian:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->guardian_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-gray-500 text-xs mb-1">Contact #:</span>
                                <span class="font-medium text-slate-900">{{ $application->user?->guardian_contact ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3 shrink-0">
                
                <a href="{{ route('registrar.applications.index') }}" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded text-sm font-semibold transition">
                    Close
                </a>

                @if($application->status === 'Pending')
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded text-sm font-semibold shadow-sm transition">
                            Approve
                        </button>
                    </form>
                    
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded text-sm font-semibold shadow-sm transition">
                            Reject
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</body>
</html>