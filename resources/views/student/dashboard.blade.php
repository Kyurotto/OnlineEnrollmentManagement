<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-600">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-[#10B981] text-white font-bold p-2 rounded-lg text-sm shadow-md shadow-[#10B981]/20">SD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-gray-900">Student Dashboard</h1>
                        <span class="text-xs text-gray-500">Your academic hub</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 px-4 rounded shadow transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative font-medium shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        @php 
            // Use enrollment for CURRENT academic year only
            $currentStatus = $currentYearEnrollment ? $currentYearEnrollment->status : 'Not Enrolled';
            
            // Check if student can enroll in the CURRENT ACTIVE semester
            $currentAcademicYear = $activeYear;
            if ($currentAcademicYear && $currentYearEnrollment) {
                // If enrollment is for current academic year, student cannot enroll again
                $isEnrollmentForCurrentYear = strpos($currentYearEnrollment->year_level, $currentAcademicYear->year_name) !== false;
                $canEnroll = !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved']) || !$isEnrollmentForCurrentYear;
            } else {
                $canEnroll = !in_array($currentStatus, ['Pending', 'Enrolled', 'Approved']);
            }
        @endphp

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Welcome, {{ Auth::user()->first_name }}!</h2>
                <p class="text-gray-500 text-sm mt-1">Here is your current enrollment status.</p>
            </div>
            
            <div>
                @if($currentStatus === 'Enrolled' || $currentStatus === 'Approved')
                    <span class="bg-green-50 text-green-700 px-4 py-2 rounded-lg font-bold text-sm border border-green-100 inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        ENROLLED
                    </span>
                @elseif($currentStatus === 'Pending')
                    <span class="bg-amber-50 text-amber-600 px-4 py-2 rounded-lg font-bold text-sm border border-amber-100 inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        PENDING
                    </span>
                @elseif($currentStatus === 'Rejected')
                    <span class="bg-red-50 text-red-600 px-4 py-2 rounded-lg font-bold text-sm border border-red-100 inline-flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        REJECTED
                    </span>
                @else
                    <span class="bg-gray-100 text-gray-500 px-4 py-2 rounded-lg font-bold text-sm border border-gray-200">
                        NOT ENROLLED
                    </span>
                @endif
            </div>
        </div>

        @if($activeSemester && $activeYear && $canEnroll)
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r-lg p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="text-green-600 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="flex-grow">
                    <h3 class="text-gray-900 font-bold text-lg mb-1">Enrollment is Now Open!</h3>
                    <p class="text-gray-600 text-sm mb-3">
                        A new semester is active. You can now submit a new enrollment application.
                    </p>
                    <p class="text-gray-500 font-medium mb-3 text-xs">
                        <strong class="text-gray-900">Active Semester:</strong> {{ $activeSemester->name }} <span class="mx-1 text-gray-300">|</span> <strong class="text-gray-900">Academic Year:</strong> {{ $activeYear->year_name }}
                    </p>
                </div>
            </div>
        </div>
        @elseif(!$activeSemester || !$activeYear)
        <div class="bg-white border-l-4 border-gray-200 rounded-r-lg p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="text-gray-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-gray-900 font-bold text-lg mb-1">Enrollment Currently Closed</h3>
                    <p class="text-gray-600 text-sm">Please wait for the next enrollment period announcement from the registrar.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($canEnroll && !$isEnrolledInActiveYear)
            <a href="{{ route('student.enrollment.create') }}" class="group block h-full">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col justify-between hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition-colors">Enroll</h3>
                            <p class="text-sm text-gray-500 mt-2">Start a new enrollment or view pending applications.</p>
                        </div>
                        <div class="text-[#10B981] p-2 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            @else
            <div class="group block h-full cursor-not-allowed opacity-60">
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-400">Enrollment Submitted</h3>
                            <p class="text-sm text-gray-400 mt-2">You have an active application or are already enrolled.</p>
                        </div>
                        <div class="text-gray-300 p-2 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <a href="{{ route('student.payment') }}" class="group block h-full">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col justify-between hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition-colors">Pay Fees</h3>
                            <p class="text-sm text-gray-500 mt-2">View outstanding fees and make secure payments.</p>
                        </div>
                        <div class="text-[#10B981] p-2 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.profile') }}" class="group block h-full">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col justify-between hover:border-[#10B981] hover:shadow-md transition-all cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 group-hover:text-[#10B981] transition-colors">Profile</h3>
                            <p class="text-sm text-gray-500 mt-2">Update contact info, view enrollment history.</p>
                        </div>
                        <div class="text-[#10B981] p-2 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Enrollment Application — Requirements & Steps</h3>
                    <p class="text-sm text-gray-500 mt-1">Before you submit an application, please ensure you meet the eligibility criteria.</p>
                </div>
                <div class="hidden md:block text-gray-200">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-8">
                <div>
                    <h4 class="font-bold text-gray-900 mb-2">Eligibility</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Be a registered user of this portal</li>
                        <li>Meet program-specific entry requirements (check course catalog)</li>
                        <li>Fees must be paid or a payment plan arranged</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-2">Required Documents</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Original SHS Report Card/Form 138</li>
                        <li>Original Certificate of Good Moral Character</li>
                        <li>Original PSA Birth Certificate</li>
                        <li>Proof of payment or payment receipt (PDF/JPG)</li>
                    </ul>
                </div>
            </div>

            @if($canEnroll && !$isEnrolledInActiveYear)
            <a href="{{ route('student.enrollment.create') }}" class="inline-block bg-[#10B981] hover:bg-[#059669] text-white font-bold py-3 px-6 rounded-lg shadow-md shadow-[#10B981]/20 transition-all">Start Application</a>
            @else
            <button disabled class="inline-block bg-gray-100 text-gray-400 border border-gray-200 font-bold py-3 px-6 rounded-lg shadow-sm cursor-not-allowed">Application Submitted</button>
            @endif
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            © Enrollment Management System — Student Portal
        </div>
    </footer>
</body>
</html>