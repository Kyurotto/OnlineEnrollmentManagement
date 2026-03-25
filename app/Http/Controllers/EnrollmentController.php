<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Payment; 
use App\Models\User;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Notification;
use App\Notifications\NewEnrollmentSubmitted;

class EnrollmentController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Check for existing enrollment in the current active year
        if ($activeYear) {
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Enrolled', 'Approved', 'Pending'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->exists();
            
            if ($isEnrolled) {
                return redirect()->route('student.dashboard')->with('error', 'You are already enrolled for this academic year.');
            }
        }

        // Filter dropdowns
        $semesters = Semester::whereNotIn('name', ['1st Semester', '2nd Semester'])->orderBy('id', 'desc')->get();
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        $courses = Course::all();

        return view('student.enrollment', compact('activeSemester', 'activeYear', 'semesters', 'academicYears', 'courses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. RELAXED VALIDATION: Removed strict checks on semester/year if they are auto-filled
        $request->validate([
            'course_code' => 'required',
            'year_level'  => 'required',
            // 'semester' => 'required', // Removed to prevent error if disabled in form
            // 'academic_year' => 'required', // Removed to prevent error if disabled in form
            'first_name'  => 'required',
            'last_name'   => 'required',
            'birth_date'  => 'required',
            'email'       => 'required',
        ]);

        // 2. FALLBACK VALUES: Use Active Year/Semester if form didn't send them
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $semesterToSave = $request->semester ?? ($activeSemester ? $activeSemester->name : 'Unknown');
        $academicYearToSave = $request->academic_year ?? ($activeYear ? $activeYear->year_name : 'Unknown');

        // 3. GET COURSE ID
        $course = Course::where('course_code', $request->course_code)->first();
        if (!$course) { return back()->withErrors(['course_code' => 'Invalid Course Code.']); }

        $fullAddress = $request->house_no . ' ' . $request->street . ', ' . $request->barangay . ', ' . $request->city . ', ' . $request->province . ' ' . $request->zip;

        // 4. SAVE ENROLLMENT
        $enrollment = Enrollment::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status'      => 'Pending',
                'course_id'   => $course->id, 
                'course_code' => $request->course_code, 
                'year_level'  => $request->year_level . ' | ' . $semesterToSave . ' | ' . $academicYearToSave,
                'first_name'  => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name'   => $request->last_name,
                'birth_date'  => $request->birth_date,
                'age'         => $request->age ?? 0, // Default to 0 if empty
                'gender'      => $request->gender ?? 'Not Specified',
                'religion'    => $request->religion,
                'birthplace'  => $request->birthplace,
                'email'       => $request->email,
                'contact'     => $request->contact,
                'address_full'=> $fullAddress,
                'father_name'        => $request->father_name,
                'mother_maiden_name' => $request->mother_maiden_name,
                'guardian_name'      => $request->guardian_name,
                'guardian_contact'   => $request->guardian_contact,
            ]
        );

        // 5. CREATE PAYMENT RECORD
        Payment::updateOrCreate(
            ['user_id' => $user->id, 'application_id' => $enrollment->id],
            ['amount' => 1000.00, 'status' => 'Pending', 'payment_date' => now()]
        );

        // 6. UPDATE USER STATUS
        User::where('id', $user->id)->update(['status' => 'Pending']);

        // 7. NOTIFY ADMINS AND REGISTRARS
        $staff = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($staff->count() > 0) {
            Notification::send($staff, new NewEnrollmentSubmitted($enrollment));
        }

        return redirect()->route('student.dashboard')->with('success', 'Application submitted successfully!');
    }
}