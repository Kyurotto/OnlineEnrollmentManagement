<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 pb-12">

    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold text-slate-800">Enrollment Application</h1>
                <p class="text-xs text-gray-500">Submit an application, upload documents and record payments.</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('student.dashboard') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 transition">← Back to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded shadow">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('enrollment.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Course Selection</h2>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select a course to apply for</label>
                <div class="border border-gray-200 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded"><input
                            type="radio" name="course_code" value="ACT" class="mt-1 h-4 w-4 text-teal-600"><span
                            class="text-sm text-gray-700"><span class="font-bold">ACT</span> — ASSOCIATE IN COMPUTER
                            TECH</span></label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded"><input
                            type="radio" name="course_code" value="BSIS" class="mt-1 h-4 w-4 text-teal-600"><span
                            class="text-sm text-gray-700"><span class="font-bold">BSIS</span> — BS INFORMATION
                            SYSTEMS</span></label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded"><input
                            type="radio" name="course_code" value="BTVTED" class="mt-1 h-4 w-4 text-teal-600"><span
                            class="text-sm text-gray-700"><span class="font-bold">BTVTED</span> — BTV Teacher
                            Education</span></label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded"><input
                            type="radio" name="course_code" value="DHRT" class="mt-1 h-4 w-4 text-teal-600"><span
                            class="text-sm text-gray-700"><span class="font-bold">DHRT</span> — HOTEL & RESTAURANT
                            TECH</span></label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded"><input
                            type="radio" name="course_code" value="DIT" class="mt-1 h-4 w-4 text-teal-600"><span
                            class="text-sm text-gray-700"><span class="font-bold">DIT</span> — DIPLOMA INFO
                            TECH</span></label>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                    <select name="year_level" class="w-full border-gray-300 rounded-md shadow-sm border py-2 px-3">
                        <option>Select Year Level</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Student Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="first_name" placeholder="First Name" value="John"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="middle_name" placeholder="Middle Name" value="Laurence E"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="last_name" placeholder="Last Name" value="Novicio"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="date" name="birth_date"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="number" name="age" placeholder="Age"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <div class="flex items-center space-x-4"><label><input type="radio" name="gender" value="male"
                                class="text-teal-600"> Male</label><label><input type="radio" name="gender"
                                value="female" class="text-teal-600"> Female</label></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <input type="text" name="religion" placeholder="Religion"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="birthplace" placeholder="Birthplace"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="email" name="email" value="novicio@example.com"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="contact" placeholder="Contact Number"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Belonging to any Indigenous Peoples (IP)
                        Community?</label>
                    <div class="flex space-x-4"><label><input type="radio" name="ip_community" value="yes"
                                class="text-teal-600"> Yes</label><label><input type="radio" name="ip_community"
                                value="no" class="text-teal-600"> No</label></div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Student Address Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="house_no" placeholder="House No."
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="street" placeholder="Sitio / Street"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="barangay" placeholder="Barangay"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="city" placeholder="Municipality / City"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="province" placeholder="Province"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                    <input type="text" name="country" placeholder="Country"
                        class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none">
                </div>
                <div class="mb-2"><input type="text" name="zip" placeholder="ZIP / Postal Code"
                        class="border-b border-gray-300 py-2 w-1/3 focus:border-teal-500 outline-none"></div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Parent / Guardian Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-6">
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Father's Name</label><input
                                type="text" name="father_name" placeholder="Father's Full Name"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Legal Guardian's
                                Name</label><input type="text" name="guardian_name" placeholder="Legal Guardian"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Primary Parent / Guardian
                                Name</label><input type="text" name="primary_contact_name"
                                placeholder="Name (Primary Contact)"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Primary Parent
                                Contact</label><input type="text" name="primary_contact_number"
                                placeholder="(000) 000-0000"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                    </div>
                    <div class="space-y-6">
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Mother's Maiden
                                Name</label><input type="text" name="mother_maiden_name"
                                placeholder="Mother's Maiden Name"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Contact Number</label><input
                                type="text" name="guardian_contact" placeholder="Contact Number for Parent/Guardian"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div><label class="block text-xs font-semibold text-gray-500 mb-1">Relation to
                                Student</label><input type="text" name="relation" placeholder="e.g. Mother, Father"
                                class="border-b border-gray-300 py-2 w-full focus:border-teal-500 outline-none"></div>
                        <div class="pt-6 flex gap-4"><label
                                class="flex items-center space-x-2 text-sm text-gray-600"><input type="checkbox"
                                    name="consent"><span>Consent given</span></label><label
                                class="flex items-center space-x-2 text-sm text-gray-600"><input type="checkbox"
                                    name="lives_with"><span>Lives with student</span></label></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end"><button type="submit"
                    class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">Submit
                    Enrollment</button></div>
        </form>
    </main>
</body>

</html>
