<div>
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Enrollment Application</h1>
                <div class="flex items-center gap-2">
                    <p class="text-xs text-gray-500">Submit an application, upload documents and record payments.</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('student.dashboard') }}" wire:navigate class="text-sm text-gray-500 hover:text-[#10B981] transition font-medium">← Back to Dashboard</a>
                @if(request()->routeIs('student.dashboard'))
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 px-4 rounded shadow transition-colors">Logout</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-12">
        <form wire:submit="submitEnrollment" class="space-y-6">

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Course Selection</h2>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select a course to apply for</label>
                <div class="border border-gray-300 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-gray-50">
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-100 rounded transition-colors">
                        <input type="radio" wire:model="course_code" name="course_code" value="ACT" class="text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                        <span class="text-sm text-gray-600"><span class="font-bold text-gray-900">ACT</span> — ASSOCIATE IN COMPUTER TECH</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-100 rounded transition-colors">
                        <input type="radio" wire:model="course_code" name="course_code" value="BSIS" class="text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                        <span class="text-sm text-gray-600"><span class="font-bold text-gray-900">BSIS</span> — BS INFORMATION SYSTEMS</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-100 rounded transition-colors">
                        <input type="radio" wire:model="course_code" name="course_code" value="BTVTED" class="text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                        <span class="text-sm text-gray-600"><span class="font-bold text-gray-900">BTVTED</span> — BTV Teacher Education</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-100 rounded transition-colors">
                        <input type="radio" wire:model="course_code" name="course_code" value="DHRT" class="text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                        <span class="text-sm text-gray-600"><span class="font-bold text-gray-900">DHRT</span> — HOTEL & RESTAURANT TECH</span>
                    </label>
                    <label class="flex items-start space-x-3 cursor-pointer p-2 hover:bg-gray-100 rounded transition-colors">
                        <input type="radio" wire:model="course_code" name="course_code" value="DIT" class="text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                        <span class="text-sm text-gray-600"><span class="font-bold text-gray-900">DIT</span> — DIPLOMA INFO TECH</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Year Level</label>
                        <select wire:model="year_level" name="year_level" class="w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none">
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Semester</label>
                        <select wire:model="semester" name="semester" class="autosave w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none border-dashed">
                            <option value="">Select Semester</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            @foreach($semesters as $s)
                                <option value="{{ $s->name }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Active Year</label>
                        <select wire:model="academic_year" name="academic_year" class="autosave w-full bg-white text-gray-900 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-[#10B981] focus:border-[#10B981] outline-none border-dashed">
                            <option value="">Select Year</option>
                            @foreach($academicYears as $y)
                                <option value="{{ $y->year_name }}">{{ $y->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Student Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" wire:model="last_name" name="last_name" placeholder="Last Name"
                        class="bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium" required>

                    <input type="text" wire:model="first_name" name="first_name" placeholder="First Name"
                        class="bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium" required>

                    <input type="text" wire:model="middle_name" name="middle_name" placeholder="Middle Name"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="date" wire:model="birth_date" name="birth_date"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-600 placeholder-gray-400" required>

                    <input type="number" wire:model="age" name="age" placeholder="Age"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium" required>

                    <div class="flex items-center space-x-4 pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="gender" name="gender" value="male" class="autosave text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                            <span class="text-gray-700 font-medium text-sm">Male</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="gender" name="gender" value="female" class="autosave text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                            <span class="text-gray-700 font-medium text-sm">Female</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <input type="text" wire:model="religion" name="religion" placeholder="Religion"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                    <input type="text" wire:model="birthplace" name="birthplace" placeholder="Birthplace"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="email" wire:model="email" name="email"
                        placeholder="Email Address"
                        readonly
                        class="border-b border-gray-200 py-2 w-full focus:border-gray-200 outline-none text-gray-500 cursor-not-allowed bg-gray-50 px-2 rounded-t" required>

                    <input type="text" wire:model="contact" placeholder="Contact Number (e.g. 09123456789)"
                        maxlength="11"
                        class="bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium" required>
                </div>

                <div class="mt-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Belonging to any Indigenous Peoples (IP) Community?</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="ip_community" name="ip_community" value="yes" class="autosave text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                            <span class="text-gray-700 font-medium text-sm">Yes</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="ip_community" name="ip_community" value="no" class="autosave text-[#10B981] focus:ring-[#10B981] bg-white border-gray-300 mr-2">
                            <span class="text-gray-700 font-medium text-sm">No</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Student Address Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" wire:model="house_no" name="house_no" placeholder="House No."
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                    <input type="text" wire:model="street" name="street" placeholder="Sitio / Street"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                    <input type="text" wire:model="barangay" name="barangay" placeholder="Barangay"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <input type="text" wire:model="city" name="city" placeholder="Municipality / City"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                    <input type="text" wire:model="province" name="province" placeholder="Province"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                    <input type="text" wire:model="zip" name="zip" placeholder="ZIP / Postal Code"
                        class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Parent / Guardian Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Father's Name</label>
                            <input type="text" wire:model="father_name" name="father_name" placeholder="Father's Full Name"
                                class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Legal Guardian's Name</label>
                            <input type="text" wire:model="guardian_name" name="guardian_name" placeholder="Legal Guardian"
                                class="autosave bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mother's Maiden Name</label>
                            <input type="text" wire:model="mother_maiden_name" placeholder="Mother's Maiden Name"
                                class="bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Guardian Contact Number</label>
                            <input type="text" wire:model="guardian_contact" placeholder="Contact Number (e.g. 09123456789)"
                                maxlength="11"
                                class="bg-transparent border-b border-gray-300 py-2 w-full focus:border-[#10B981] outline-none text-gray-900 placeholder-gray-400 font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Document Requirements</h2>
                <p class="text-sm text-gray-500 mb-6">Please take a clear photo or upload a PDF of the following documents.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-gray-900">Form 137 (Report Card)</h3>
                            <p class="text-xs text-gray-500">Original copy with principal's signature.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-gray-600 leading-tight">
                                    @if($form_138)
                                        <span class="font-bold text-gray-900">{{ $form_138->getClientOriginalName() }}</span>
                                    @else
                                        <span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo
                                    @endif
                                </p>
                            </div>
                            <input type="file" wire:model="form_138" class="hidden" accept="image/*,application/pdf" />
                        </label>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-gray-900">Certificate of Good Moral</h3>
                            <p class="text-xs text-gray-500">Issued by your previous school.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-gray-600 leading-tight">
                                    @if($good_moral)
                                        <span class="font-bold text-gray-900">{{ $good_moral->getClientOriginalName() }}</span>
                                    @else
                                        <span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo
                                    @endif
                                </p>
                            </div>
                            <input type="file" wire:model="good_moral" class="hidden" accept="image/*,application/pdf" />
                        </label>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-gray-900">PSA Birth Certificate</h3>
                            <p class="text-xs text-gray-500">Clear copy of the original PSA document.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-gray-600 leading-tight">
                                    @if($psa)
                                        <span class="font-bold text-gray-900">{{ $psa->getClientOriginalName() }}</span>
                                    @else
                                        <span class="font-semibold text-[#10B981]">Tap to upload</span> or take a photo
                                    @endif
                                </p>
                            </div>
                            <input type="file" wire:model="psa" class="hidden" accept="image/*,application/pdf" />
                        </label>
                    </div>

                    <div class="bg-[#10B981]/5 p-5 rounded-xl border border-[#10B981]/20">
                        <div class="mb-3">
                            <h3 class="text-sm font-bold text-[#10B981]">2x2 ID Picture</h3>
                            <p class="text-xs text-gray-600">White background, formal attire.</p>
                        </div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-[#10B981]/40 border-dashed rounded-lg cursor-pointer bg-white hover:bg-[#10B981]/5 transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <svg class="w-8 h-8 mb-3 text-[#10B981] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"></path></svg>
                                <p class="mb-2 text-sm text-gray-600 leading-tight">
                                    @if($id_picture)
                                        <span class="font-bold text-gray-900">{{ $id_picture->getClientOriginalName() }}</span>
                                    @else
                                        <span class="font-semibold text-[#10B981]">Take a Selfie</span> or upload photo
                                    @endif
                                </p>
                            </div>
                            <input type="file" wire:model="id_picture" class="hidden" accept="image/*" capture="user" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded shadow-sm w-full" role="alert">
                    <p class="font-bold">Application could not be submitted:</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="bg-[#10B981] hover:bg-[#059669] text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-[#10B981]">
                    <span wire:loading.remove wire:target="submitApplication">Submit Enrollment</span>
                    <span wire:loading.remove wire:target="submitEnrollment">Submit Enrollment</span>
                    <span wire:loading wire:target="submitEnrollment">Submitting...</span>
                </button>
            </div>

        </form>
    </main>
</div>
