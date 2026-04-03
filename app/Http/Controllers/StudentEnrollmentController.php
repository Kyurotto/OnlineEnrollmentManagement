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
            'father_name' => 'nullable|string|max:255',
            'mother_maiden_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:11',
            'id_picture' => 'nullable|image|max:2048',
        ];

        // Different document validation based on level
        if ($level === 'shs') {
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',  // SF9
                'sf10' => 'nullable|file|max:5120',      // Permanent Record
                'good_moral' => 'nullable|file|max:5120', // Optional for SHS
                'psa' => 'nullable|file|max:5120',        // PSA Birth Certificate
            ]);
        } else {
            // College documents
            $validationRules = array_merge($validationRules, [
                'form_137' => 'nullable|file|max:5120',
                'good_moral' => 'nullable|file|max:5120',
                'psa' => 'nullable|file|max:5120',
            ]);
        }

        $request->validate($validationRules);

        $course = Course::where('course_code', $request->course_code)->first();

        if (!$course) {
            return back()->with('error', 'Selected program is invalid or not registered in the system.')->withInput();
        }

        // Handle File Uploads based on level
        $paths = [];
        $fileMap = $level === 'shs'
            ? ['form_137' => 'form_137_path', 'sf10' => 'sf10_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path']
            : ['form_137' => 'form_137_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path'];

        foreach ($fileMap as $field => $dbField) {
            if ($request->hasFile($field)) {
                $paths[$dbField] = $request->file($field)->store('enrollments/docs', 'public');
            }
        }

        // Unified Year Level String: "Year | Semester | Academic Year"
        $unifiedYearLevel = "{$request->year_level} | {$request->semester} | {$request->academic_year}";

        $enrollment = Enrollment::create(array_merge([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'course_code' => $request->course_code,
            'level' => $level,
            'year_level' => $unifiedYearLevel,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'contact' => $request->contact,
            // Address logic (simplified for validation, or we can fetch individual components if they were in the form)
            'address_full' => implode(', ', array_filter([$request->house_no, $request->street, $request->barangay, $request->city, $request->province, $request->zip])),
            'father_name' => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name' => $request->guardian_name,
            'guardian_contact' => $request->guardian_contact,
            'status' => 'Pending',
        ], $paths));

        // Auto-create 1,000 PHP Downpayment for the Cashier/Student History
        \App\Models\Payment::create([
            'user_id' => Auth::id(),
            'application_id' => $enrollment->id,
            'amount' => 1000,
            'status' => 'Pending',
            'payment_date' => now(),
            'payment_method' => 'Cash',
        ]);

        // Clear draft on successful submission
        session()->forget('enrollment_draft_' . Auth::id());

        // 6. Notify Admins and Registrars
        $staff = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($staff->count() > 0) {
            Notification::send($staff, new NewEnrollmentSubmitted($enrollment));
        }

        return redirect()->route('student.dashboard')->with('success', 'Enrollment application submitted successfully to the Registrar.');
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
}
