<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewEnrollmentSubmitted;

class StudentEnrollmentController extends Controller
{
    public function create(Request $request)
    {
        $level = $request->query('level');

        // If no level provided, show the selection screen
        if (!$level) {
            return view('student.enrollment_choice');
        }

        // Validate level parameter
        if (!in_array($level, ['shs', 'college'])) {
            return redirect()->route('student.enrollment.create')->with('error', 'Invalid enrollment level selected.');
        }

        $user = Auth::user();

        // Fetch programs and strands from the database
        $programs = Course::where('type', 'program')->orderBy('course_code', 'asc')->get();
        $strands = Course::where('type', 'shs')->orderBy('course_code', 'asc')->get();

        // Initial data for the form
        $data = [
            'level' => $level,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'middle_name' => $user->middle_name,
            'programs' => $programs,
            'strands' => $strands,
        ];

        // Restore draft from session
        $draft = session()->get('enrollment_draft_' . Auth::id(), []);
        $data = array_merge($data, $draft);

        $semesters = Semester::all();
        $activeSemester = Semester::where('is_active', true)->first();
        $academicYears = AcademicYear::all();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('student.enrollment', array_merge($data, [
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
        ]));
    }

    public function store(Request $request)
    {
        $level = $request->input('level');

        // Validate level parameter
        if (!in_array($level, ['shs', 'college'])) {
            return back()->with('error', 'Invalid enrollment level.')->withInput();
        }

        // Build validation rules based on enrollment level
        $validationRules = [
            'level' => 'required|in:shs,college',
            'course_code' => 'required',
            'year_level' => 'required',
            'semester' => 'required',
            'academic_year' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required',
            'contact' => 'required',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:12|unique:enrollments,lrn',
            'facebook_account' => 'nullable|string|max:255',
            'religion_church' => 'nullable|string|max:255',
            'junior_high_school' => 'nullable|string|max:255',
            'health_concerns' => 'nullable|string|max:1000',
            'prk_blk_lot_vill' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'mother_maiden_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:11',
        ];

        $request->validate($validationRules);

        $course = Course::where('course_code', $request->course_code)->first();

        if (!$course) {
            return back()->with('error', 'Selected program is invalid or not registered in the system.')->withInput();
        }

        // Unified Year Level String: "Year | Semester | Academic Year"
        $unifiedYearLevel = "{$request->year_level} | {$request->semester} | {$request->academic_year}";

        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'course_code' => $request->course_code,
            'level' => $level,
            'year_level' => $unifiedYearLevel,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'extension' => $request->extension,
            'lrn' => $request->lrn,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'contact' => $request->contact,
            'facebook_account' => $request->facebook_account,
            'religion_church' => $request->religion_church,
            'junior_high_school' => $request->junior_high_school,
            'health_concerns' => $request->health_concerns,
            'prk_blk_lot_vill' => $request->prk_blk_lot_vill,
            // Address logic (simplified for validation, or we can fetch individual components if they were in the form)
            'address_full' => implode(', ', array_filter([$request->prk_blk_lot_vill, $request->barangay, $request->city, $request->province, $request->zip])),
            'father_name' => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
            'status' => 'Pending',
        ]);

        // Clear draft on successful submission
        session()->forget('enrollment_draft_' . Auth::id());

        // 5. Notify Admins and Registrars
        $staff = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($staff->count() > 0) {
            Notification::send($staff, new NewEnrollmentSubmitted($enrollment));
        }

        return redirect()->route('student.enrollment.upload')->with('success', 'Information saved. Please upload your documents to proceed.');
    }

    public function upload()
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment) {
            return redirect()->route('student.enrollment.create')->with('info', 'Please submit your enrollment application first.');
        }

        return view('student.enrollment_upload', compact('enrollment'));
    }

    public function storeUpload(Request $request)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment) {
            return redirect()->route('student.enrollment.create')->with('error', 'No active enrollment record found.');
        }

        $level = $enrollment->level;

        $validationRules = [
            'id_picture' => 'nullable|image|max:2048',
            'promissory_note' => 'nullable|file|mimes:doc,docx,pdf|max:5120',
            'promissory_reason' => 'nullable|string|max:1000',
        ];

        if ($level === 'shs') {
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',
                'sf10' => 'nullable|file|max:5120',
                'good_moral' => 'nullable|file|max:5120',
                'psa' => 'nullable|file|max:5120',
            ]);
        } else {
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',
                'good_moral' => 'nullable|file|max:5120',
                'psa' => 'nullable|file|max:5120',
            ]);
        }

        $request->validate($validationRules);

        $fileMap = $level === 'shs'
            ? ['form_137' => 'form_137_path', 'sf10' => 'sf10_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path']
            : ['form_137' => 'form_137_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path'];

        $updatedData = [];
        foreach ($fileMap as $field => $dbField) {
            if ($request->hasFile($field)) {
                $updatedData[$dbField] = $request->file($field)->store('enrollments/docs', 'public');
            }
        }

        // Handle Promissory Note
        if ($request->hasFile('promissory_note')) {
            $updatedData['promissory_note_path'] = $request->file('promissory_note')->store('enrollments/promissory', 'public');
        }

        if ($request->has('promissory_reason')) {
            $updatedData['promissory_reason'] = $request->promissory_reason;
        }

        if (!empty($updatedData)) {
            $enrollment->update($updatedData);
        }

        return back()->with('success', 'Information and documents updated successfully.');
    }

    /**
     * Display the review view for an enrollment application
     */
    public function review()
    {
        $user = Auth::user();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Get the existing enrollment for the current active year
        $enrollment = null;
        if ($activeYear) {
            $enrollment = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Pending', 'Approved', 'Enrolled', 'Rejected'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->first();
        }

        // If no enrollment found, redirect to dashboard
        if (!$enrollment) {
            return redirect()->route('student.dashboard')->with('error', 'No enrollment application found for review.');
        }

        return view('student.enrollment_review', compact('enrollment'));
    }

    public function edit()
    {
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment) {
            return redirect()->route('student.dashboard')->with('error', 'No enrollment found.');
        }

        if (in_array($enrollment->status, ['Enrolled', 'Paid'])) {
            return redirect()->route('student.enrollment.review')->with('error', 'You cannot edit an application that has already been finalized/paid.');
        }

        $programs = Course::where('type', 'program')->orderBy('course_code', 'asc')->get();
        $strands = Course::where('type', 'shs')->orderBy('course_code', 'asc')->get();

        $semesters = Semester::all();
        $activeSemester = Semester::where('is_active', true)->first();
        $academicYears = AcademicYear::all();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $data = $enrollment->toArray();
        $data['programs'] = $programs;
        $data['strands'] = $strands;

        return view('student.enrollment_edit', array_merge($data, [
            'enrollment' => $enrollment,
            'semesters' => $semesters,
            'activeSemester' => $activeSemester,
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
        ]));
    }

    public function update(Request $request)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment || $enrollment->edit_request_status !== 'Approved') {
             return redirect()->route('student.enrollment.review')->with('error', 'Unauthorized edit attempt.');
        }

        $level = $request->input('level', $enrollment->level);

        $validationRules = [
            'level' => 'required|in:shs,college',
            'course_code' => 'required',
            'year_level' => 'required',
            'semester' => 'required',
            'academic_year' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required',
            'contact' => 'required',
            'middle_name' => 'nullable|string|max:255',
            'extension' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:12|unique:enrollments,lrn,' . Auth::id() . ',user_id',
            'facebook_account' => 'nullable|string|max:255',
            'religion_church' => 'nullable|string|max:255',
            'junior_high_school' => 'nullable|string|max:255',
            'health_concerns' => 'nullable|string|max:1000',
            'prk_blk_lot_vill' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'mother_maiden_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:11',
        ];

        $request->validate($validationRules);

        $course = Course::where('course_code', $request->course_code)->first();

        if (!$course) {
            return back()->with('error', 'Selected program is invalid or not registered in the system.')->withInput();
        }

        $unifiedYearLevel = "{$request->year_level} | {$request->semester} | {$request->academic_year}";

        $address = $request->address_full;
        if(empty($address)){
             $address = implode(', ', array_filter([$request->prk_blk_lot_vill, $request->barangay, $request->city, $request->province, $request->zip]));
        }

        $enrollment->update([
            'course_id' => $course->id,
            'course_code' => $request->course_code,
            'level' => $level,
            'year_level' => $unifiedYearLevel,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'extension' => $request->extension,
            'lrn' => $request->lrn,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'contact' => $request->contact,
            'facebook_account' => $request->facebook_account,
            'religion_church' => $request->religion_church,
            'junior_high_school' => $request->junior_high_school,
            'health_concerns' => $request->health_concerns,
            'prk_blk_lot_vill' => $request->prk_blk_lot_vill,
            'address_full' => $address ?: $enrollment->address_full,
            'father_name' => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
        ]);

        return redirect()->route('student.enrollment.review')->with('success', 'Your application has been successfully updated and is pending review.');
    }

    /**
     * Request edit access for enrollment application
     */
    public function requestEdit(Request $request)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment) {
            return back()->with('error', 'No enrollment found.');
        }

        if (in_array($enrollment->status, ['Enrolled', 'Paid'])) {
            return back()->with('error', 'You cannot edit a finalized enrollment.');
        }

        if ($enrollment->edit_request_status === 'Pending') {
            return back()->with('info', 'Your edit request is already pending approval.');
        }

        $enrollment->update([
            'edit_request_status' => 'Pending',
            'edit_requested_at' => now(),
        ]);

        return back()->with('success', 'Edit request submitted. Please wait for registrar approval.');
    }
}
