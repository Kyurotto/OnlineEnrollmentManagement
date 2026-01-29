<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50 text-slate-800">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-teal-700 text-white font-bold p-2 rounded-lg text-sm">SD</div>
                    <div>
                        <h1 class="text-lg font-bold leading-none">Student Dashboard</h1>
                        <span class="text-xs text-gray-500">Your academic hub</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded shadow">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}</div>
        @endif

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold mb-2">Welcome to your student dashboard!</h2>
            <p class="text-gray-600">Use the actions below to manage enrollment, choose courses and pay fees.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('enrollment.create') }}" class="group block h-full">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col justify-between hover:shadow-md transition cursor-pointer relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg group-hover:text-teal-700 transition">Enroll</h3>
                            <p class="text-sm text-gray-500 mt-2">Start a new enrollment or view pending applications.
                            </p>
                        </div>
                        <div class="text-teal-700 p-2 bg-teal-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('payment.index') }}" class="group block h-full">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col justify-between hover:shadow-md transition cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg group-hover:text-yellow-600 transition">Pay Fees</h3>
                            <p class="text-sm text-gray-500 mt-2">View outstanding fees and make secure payments.</p>
                        </div>
                        <div class="text-yellow-600 p-2 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('student.profile') }}" class="group block h-full">
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col justify-between hover:shadow-md transition cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg group-hover:text-purple-600 transition">Profile</h3>
                            <p class="text-sm text-gray-500 mt-2">Update contact info, view enrollment history and
                                documents.</p>
                        </div>
                        <div class="text-purple-600 p-2 bg-purple-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Enrollment Application — Requirements & Steps</h3>
                    <p class="text-sm text-gray-500 mt-1">Before you submit an application, please ensure you meet the
                        eligibility criteria.</p>
                </div>
                <div class="hidden md:block text-sky-500"><svg class="w-12 h-12" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-8">
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">Eligibility</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Be a registered user of this portal</li>
                        <li>Meet program-specific entry requirements (check course catalog)</li>
                        <li>Fees must be paid or a payment plan arranged</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">Required Documents</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Original SHS Report Card/Form 138</li>
                        <li>Original Certificate of Good Moral Character</li>
                        <li>Original PSA Birth Certificate</li>
                        <li>Proof of payment or payment receipt (PDF/JPG)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">File requirements</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">PDF preferred for documents, each file ≤ 5 MB. Use
                        clear scans/photos; filenames should include your username.</p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 mb-2">Deadlines & Notes</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Regular intake deadline: see course selection for term-specific dates</li>
                        <li>Late submissions may be considered with approval</li>
                        <li>Incomplete applications will not be processed</li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('enrollment.create') }}"
                class="inline-block bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 px-6 rounded-lg shadow transition">Start
                Application</a>
        </div>
    </main>
</body>

</html>
