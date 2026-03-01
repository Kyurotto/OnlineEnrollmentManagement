<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-slate-800 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white font-bold p-2 rounded-lg text-sm">ST</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none text-slate-900">Student Portal</h1>
                        <span class="text-xs text-gray-500">Welcome, {{ Auth::user()->name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-sm font-medium text-red-600 hover:text-red-800 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-6">

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Welcome, {{ strtoupper(Auth::user()->name) }}!</h2>
            <p class="text-gray-600 mb-4">Here is your current enrollment status.</p>

            @if($activeSemester)
                <div class="mt-6 p-5 bg-blue-50 border-l-4 border-blue-600 rounded-r-lg shadow-sm">
                    <h3 class="text-blue-900 font-bold text-lg mb-1">Enrollment is Open</h3>
                    
                    <p class="text-blue-800 text-sm">
                        Open for new semesters and ready for new application form for enrollment.
                    </p>
                    <p class="text-xs text-blue-600 mt-1">
                        (Active: {{ $activeSemester->name }} {{ isset($activeYear) ? '| ' . $activeYear->year_name : '' }})
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('student.enrollment.create') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Go to Application Form
                        </a>
                        @if($hasPendingApplication)
                            <div class="mt-2 inline-flex items-center text-yellow-700 font-bold text-xs bg-yellow-50 px-2 py-1 rounded border border-yellow-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Note: You have a pending application status.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-gray-100 border-l-4 border-gray-400 rounded-r-lg">
                    <p class="text-gray-600 font-medium">Enrollment is currently closed. Please wait for the next announcement.</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">My Applications</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-800">{{ $myEnrollments }}</span>
                    <span class="text-sm text-gray-500">submitted</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Latest Status</h3>
                @if($latestEnrollment)
                    <div class="mt-2">
                        <span class="text-2xl font-bold 
                            @if($latestEnrollment->status == 'Enrolled') text-green-600 
                            @elseif($latestEnrollment->status == 'Rejected') text-red-600 
                            @else text-yellow-600 @endif">
                            {{ $latestEnrollment->status }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">For {{ $latestEnrollment->course_code ?? 'Course' }}</p>
                    </div>
                @else
                    <div class="mt-2 text-gray-400 italic text-sm">No enrollment history yet.</div>
                @endif
            </div>
        </div>

    </main>

    

</body>
</html>