<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body { font-family: 'Inter', sans-serif; }
    @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
    .saved-toast { animation: fadeOut 2s ease-out 1s forwards; }
    </style>
</head>

<body class="bg-[#121212] text-[#A1A1AA] pb-12">

    <div class="bg-[#1C1C1E] shadow-sm border-b border-[#27272A] sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold text-[#FFFFFF]">Enrollment Application</h1>
                <div class="flex items-center gap-2">
                    <p class="text-xs text-[#52525B]">Submit an application, upload documents and record payments.</p>
                    <span id="autosave-status" class="text-xs font-bold text-[#10B981] hidden uppercase tracking-wider bg-[#10B981]/10 border border-[#10B981]/20 px-2 py-0.5 rounded-full">
                        Draft Saved
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('student.dashboard') }}" class="text-sm text-[#A1A1AA] hover:text-[#10B981] transition">← Back to Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 hover:bg-[#3F3F46] text-[#FFFFFF] text-sm font-medium py-2 px-4 rounded shadow transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form id="enrollment-form" action="{{ route('student.enrollment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h2 class="text-lg font-bold text-[#FFFFFF] mb-4">Course Selection</h2>
                <label class="block text-sm font-medium text-[#A1A1AA] mb-2">Select a course to apply for</label>
                <div class="border border-[#3F3F46] rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-[#27272A] rounded transition-colors">
                        <input type="radio" name="course_code" value="ACT" class="autosave mt-1 h-4 w-4 text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46]">
                        <span class="text-sm text-[#A1A1AA]"><span class="font-bold text-[#FFFFFF]">ACT</span> — ASSOCIATE IN COMPUTER TECH</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-[#27272A] rounded transition-colors">
                        <input type="radio" name="course_code" value="BSIS" class="autosave mt-1 h-4 w-4 text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46]">
                        <span class="text-sm text-[#A1A1AA]"><span class="font-bold text-[#FFFFFF]">BSIS</span> — BS INFORMATION SYSTEMS</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-[#27272A] rounded transition-colors">
                        <input type="radio" name="course_code" value="BTVTED" class="autosave mt-1 h-4 w-4 text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46]">
                        <span class="text-sm text-[#A1A1AA]"><span class="font-bold text-[#FFFFFF]">BTVTED</span> — BTV Teacher Education</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-[#27272A] rounded transition-colors">
                        <input type="radio" name="course_code" value="DHRT" class="autosave mt-1 h-4 w-4 text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46]">
                        <span class="text-sm text-[#A1A1AA]"><span class="font-bold text-[#FFFFFF]">DHRT</span> — HOTEL & RESTAURANT TECH</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-[#27272A] rounded transition-colors">
                        <input type="radio" name="course_code" value="DIT" class="autosave mt-1 h-4 w-4 text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46]">
                        <span class="text-sm text-[#A1A1AA]"><span class="font-bold text-[#FFFFFF]">DIT</span> — DIPLOMA INFO TECH</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                    <div>
                        <label class="block text-sm font-medium text-[#A1A1AA] mb-1">Year Level</label>
                        <select name="year_level" class="autosave w-full bg-[#121212] text-[#FFFFFF] border-[#3F3F46] rounded-md shadow-sm border py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none">
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#A1A1AA] mb-1">Semester</label>
                        <select name="semester" class="autosave w-full bg-[#121212] text-[#FFFFFF] border-[#3F3F46] rounded-md shadow-sm border py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->name }}" @if($activeSemester && $semester->id === $activeSemester->id) selected @endif>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#A1A1AA] mb-1">Active Year</label>
                        <select name="academic_year" class="autosave w-full bg-[#121212] text-[#FFFFFF] border-[#3F3F46] rounded-md shadow-sm border py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none">
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->year_name }}" @if($activeYear && $year->id === $activeYear->id) selected @endif>
                                    {{ $year->year_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h2 class="text-lg font-bold text-[#FFFFFF] mb-6">Student Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="last_name" placeholder="Last Name"
                        value="{{ old('last_name', Auth::user()->last_name ?? '') }}"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]" required>

                    <input type="text" name="first_name" placeholder="First Name"
                        value="{{ old('first_name', Auth::user()->first_name ?? '') }}"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]" required>

                    <input type="text" name="middle_name" placeholder="Middle Name"
                        value="{{ old('middle_name', Auth::user()->middle_name ?? '') }}"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="date" name="birth_date"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#A1A1AA] placeholder-[#52525B]" required>

                    <input type="number" name="age" placeholder="Age"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]" required>

                    <div class="flex items-center space-x-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="male" class="autosave text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46] mr-2"> 
                            <span class="text-[#A1A1AA]">Male</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="female" class="autosave text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46] mr-2"> 
                            <span class="text-[#A1A1AA]">Female</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <input type="text" name="religion" placeholder="Religion"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                    <input type="text" name="birthplace" placeholder="Birthplace"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="email" name="email"
                        value="{{ old('email', Auth::user()->email ?? '') }}"
                        placeholder="Email Address"
                        readonly
                        class="border-b border-[#27272A] py-2 w-full focus:border-[#10B981] outline-none text-[#52525B] cursor-not-allowed bg-transparent" required>

                    <input type="text" name="contact" placeholder="Contact Number (e.g. 09123456789)"
                        maxlength="11"
                        pattern="[0-9]{11}"
                        title="Please enter exactly 11 digits"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]" required>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-[#A1A1AA] mb-2">Belonging to any Indigenous Peoples (IP) Community?</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="ip_community" value="yes" class="autosave text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46] mr-2"> 
                            <span class="text-[#A1A1AA]">Yes</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="ip_community" value="no" class="autosave text-[#10B981] focus:ring-[#10B981] bg-[#121212] border-[#3F3F46] mr-2"> 
                            <span class="text-[#A1A1AA]">No</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h2 class="text-lg font-bold text-[#FFFFFF] mb-6">Student Address Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="house_no" placeholder="House No."
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                    <input type="text" name="street" placeholder="Sitio / Street"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                    <input type="text" name="barangay" placeholder="Barangay"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" name="city" placeholder="Municipality / City"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                    <input type="text" name="province" placeholder="Province"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                    <input type="text" name="zip" placeholder="ZIP / Postal Code"
                        class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                </div>
            </div>

            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h2 class="text-lg font-bold text-[#FFFFFF] mb-6">Parent / Guardian Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Father's Name</label>
                            <input type="text" name="father_name" placeholder="Father's Full Name"
                                class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Legal Guardian's Name</label>
                            <input type="text" name="guardian_name" placeholder="Legal Guardian"
                                class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Mother's Maiden Name</label>
                            <input type="text" name="mother_maiden_name" placeholder="Mother's Maiden Name"
                                class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#A1A1AA] mb-1">Guardian Contact Number</label>
                            
                            <input type="text" name="guardian_contact" placeholder="Contact Number (e.g. 09123456789)"
                                maxlength="11"
                                pattern="[0-9]{11}"
                                title="Please enter exactly 11 digits"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="autosave bg-transparent border-b border-[#3F3F46] py-2 w-full focus:border-[#10B981] outline-none text-[#FFFFFF] placeholder-[#52525B]">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#1C1C1E] p-8 rounded-xl shadow-md border border-[#27272A]">
                <h2 class="text-lg font-bold text-[#FFFFFF] mb-2">Document Requirements</h2>
                <p class="text-sm text-[#A1A1AA] mb-6">Please take a clear photo or upload a PDF of the following documents.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-[#121212] p-5 rounded-xl border border-[#27272A]">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-[#FFFFFF]">Form 137 (Report Card)</h3>
                            <p class="text-xs text-[#52525B]">Original copy with principal's signature.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#3F3F46] border-dashed rounded-lg cursor-pointer bg-[#1C1C1E] hover:bg-[#27272A]/50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-[#A1A1AA] leading-tight"><span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo</p>
                                <p class="text-xs text-[#52525B] truncate max-w-[200px]" id="file-name-137">PNG, JPG or PDF</p>
                            </div>
                            <input type="file" name="form_137" class="hidden" accept="image/*,application/pdf" onchange="updateFileName(this, 'file-name-137')" />
                        </label>
                    </div>

                    <div class="bg-[#121212] p-5 rounded-xl border border-[#27272A]">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-[#FFFFFF]">Certificate of Good Moral</h3>
                            <p class="text-xs text-[#52525B]">Issued by your previous school.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#3F3F46] border-dashed rounded-lg cursor-pointer bg-[#1C1C1E] hover:bg-[#27272A]/50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-[#A1A1AA] leading-tight"><span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo</p>
                                <p class="text-xs text-[#52525B] truncate max-w-[200px]" id="file-name-moral">PNG, JPG or PDF</p>
                            </div>
                            <input type="file" name="good_moral" class="hidden" accept="image/*,application/pdf" onchange="updateFileName(this, 'file-name-moral')" />
                        </label>
                    </div>

                    <div class="bg-[#121212] p-5 rounded-xl border border-[#27272A]">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-[#FFFFFF]">PSA Birth Certificate</h3>
                            <p class="text-xs text-[#52525B]">Clear copy of the original PSA document.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#3F3F46] border-dashed rounded-lg cursor-pointer bg-[#1C1C1E] hover:bg-[#27272A]/50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-[#A1A1AA] leading-tight"><span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo</p>
                                <p class="text-xs text-[#52525B] truncate max-w-[200px]" id="file-name-psa">PNG, JPG or PDF</p>
                            </div>
                            <input type="file" name="psa" class="hidden" accept="image/*,application/pdf" onchange="updateFileName(this, 'file-name-psa')" />
                        </label>
                    </div>

                    <div class="bg-[#10B981]/5 p-5 rounded-xl border border-[#10B981]/20">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-[#10B981]">2x2 ID Picture</h3>
                            <p class="text-xs text-[#A1A1AA]">White background, formal attire.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#10B981]/40 border-dashed rounded-lg cursor-pointer bg-[#1C1C1E] hover:bg-[#10B981]/10 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-[#A1A1AA] leading-tight"><span class="font-semibold text-[#10B981]">Take a Selfie</span> or upload photo</p>
                                <p class="text-xs text-[#52525B] truncate max-w-[200px]" id="file-name-pic">PNG or JPG only</p>
                            </div>
                            <input type="file" name="id_picture" class="hidden" accept="image/*" capture="user" onchange="updateFileName(this, 'file-name-pic')" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                @if ($errors->any())
                <div class="mb-6 bg-rose-500/10 border-l-4 border-rose-500 text-rose-400 p-4 rounded shadow-sm w-full" role="alert">
                    <p class="font-bold text-[#FFFFFF]">Application could not be submitted:</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="flex justify-end pt-4 border-t border-[#27272A]">
                <button type="submit" id="submit-btn" 
                    class="bg-[#10B981] hover:bg-[#059669] text-[#FFFFFF] font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#121212] focus:ring-[#10B981]">
                    Submit Enrollment
                </button>
            </div>
            
        </form>
    </main>

    <script>
    function updateFileName(input, textId) {
        const textElement = document.getElementById(textId);
        if (input.files && input.files.length > 0) {
            textElement.classList.remove('text-[#52525B]');
            textElement.classList.add('text-[#FFFFFF]', 'font-bold');
            textElement.innerText = input.files[0].name;
        } else {
            textElement.classList.add('text-[#52525B]');
            textElement.classList.remove('text-[#FFFFFF]', 'font-bold');
            if(textId === 'file-name-pic') {
                textElement.innerText = "PNG or JPG only";
            } else {
                textElement.innerText = "PNG, JPG or PDF";
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const STORAGE_KEY = 'enrollment_draft_final';
        const inputs = document.querySelectorAll('.autosave');
        const statusLabel = document.getElementById('autosave-status');
        const form = document.getElementById('enrollment-form');

        const savedData = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');

        inputs.forEach(input => {
            if (savedData[input.name]) {
                if (input.type === 'radio') {
                    if (input.value === savedData[input.name]) {
                        input.checked = true;
                    }
                } else {
                    input.value = savedData[input.name];
                }
            }
            input.addEventListener('input', function() {
                saveToLocalStorage(this);
            });
        });

        function saveToLocalStorage(input) {
            const currentData = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
            currentData[input.name] = input.value;
            localStorage.setItem(STORAGE_KEY, JSON.stringify(currentData));
            statusLabel.classList.remove('hidden');
            statusLabel.classList.remove('saved-toast');
            void statusLabel.offsetWidth;
            statusLabel.classList.add('saved-toast');
        }

        form.addEventListener('submit', function() {
            localStorage.removeItem(STORAGE_KEY);
        });
    });
    </script>

</body>
</html>