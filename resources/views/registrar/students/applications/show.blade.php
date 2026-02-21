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
        .modal-backdrop { background-color: rgba(0, 0, 0, 0.8); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #3F3F46; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#121212] text-[#A1A1AA] h-screen overflow-hidden flex flex-col">

    <nav class="bg-[#1C1C1E] border-b border-[#27272A] z-10 shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">RD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-[#FFFFFF]">Registrar Panel</h1>
                        <span class="text-xs text-[#A1A1AA]">Manage Applications</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <svg class="w-6 h-6 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($pendingCount) && $pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full border-2 border-[#1C1C1E]">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-grow flex items-center justify-center p-4 modal-backdrop relative">
        
        <div class="bg-[#1C1C1E] w-full max-w-4xl rounded-xl shadow-2xl border border-[#27272A] overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-[#27272A] flex justify-between items-center bg-[#1C1C1E] shrink-0">
                <h2 class="text-xl font-bold text-[#FFFFFF]">Application #{{ $application->id }}</h2>
                
                <a href="{{ route('registrar.applications.index') }}" class="text-[#A1A1AA] hover:text-[#FFFFFF] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar bg-[#1C1C1E]">
                
                <div class="space-y-8">

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Student Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-[#27272A] pt-4">
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Full Name:</span>
                                <span class="font-bold text-[#FFFFFF] uppercase">
                                    {{ $application->user?->last_name ?? 'N/A' }}, {{ $application->user?->first_name ?? 'N/A' }} {{ $application->user?->middle_name ?? '' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Email:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->email ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Date of Birth:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->birth_date ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Age:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->age ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Gender:</span>
                                <span class="font-medium text-[#FFFFFF] capitalize">{{ $application->user?->gender ?? 'N/A' }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-[#52525B] text-xs mb-1">Address:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->address_full ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#121212] border border-[#27272A] rounded-lg p-5">
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-4">Program Details</h3>
                        
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="font-bold text-[#A1A1AA] mr-2">Program:</span>
                                <span class="text-[#10B981] font-bold uppercase">
                                    {{ $application->course?->course_code ?? 'NO CODE' }} - {{ $application->course?->course_description ?? 'Course Not Found' }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center text-[#FFFFFF] gap-x-4 gap-y-2">
                                <div><span class="font-bold text-[#A1A1AA] mr-2">Year Level:</span>{{ $application->year_level ?? '1st Year' }}</div>
                                <span class="text-[#27272A]">|</span>
                                <div><span class="font-bold text-[#A1A1AA] mr-2">Semester:</span>1st Semester</div>
                                <span class="text-[#27272A]">|</span>
                                <div><span class="font-bold text-[#A1A1AA] mr-2">Academic Year:</span>{{ date('Y') }}-{{ date('Y')+1 }}</div>
                                
                                <div class="ml-auto flex items-center gap-3">
                                    <span class="font-bold text-[#A1A1AA]">Status:</span>
                                    @php
                                        $badgeColor = match(ucfirst($application->status)) {
                                            'Approved' => 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/30',
                                            'Enrolled' => 'bg-sky-500/10 text-sky-400 border border-sky-500/30',
                                            'Rejected' => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
                                            'Pending' => 'bg-amber-500/10 text-amber-400 border border-amber-500/30',
                                            default => 'bg-[#27272A] text-[#A1A1AA]',
                                        };
                                        $displayText = ucfirst($application->status);
                                        if ($displayText === 'Enrolled') { $displayText = 'Paid'; }
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $badgeColor }}">
                                        {{ $displayText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-[#10B981] uppercase tracking-wider mb-3">Guardian Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm border-t border-[#27272A] pt-4">
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Father's Name:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->father_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Mother's Name:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->mother_maiden_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Guardian:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->guardian_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-[#52525B] text-xs mb-1">Contact #:</span>
                                <span class="font-medium text-[#FFFFFF]">{{ $application->user?->guardian_contact ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-[#121212] px-6 py-5 border-t border-[#27272A] flex justify-end items-center gap-3 shrink-0">
                
                <a href="{{ route('registrar.applications.index') }}" class="px-6 py-2 bg-[#27272A] hover:bg-[#3F3F46] text-[#FFFFFF] rounded-lg text-sm font-semibold transition-colors border border-[#3F3F46]">
                    Close
                </a>

                @if($application->status === 'Pending')
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="bg-[#10B981] hover:bg-[#059669] text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md shadow-[#10B981]/20 transition-all">
                            Approve Application
                        </button>
                    </form>
                    
                    <form action="{{ route('registrar.applications.update', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this application?');">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md transition-colors">
                            Reject
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</body>
</html>