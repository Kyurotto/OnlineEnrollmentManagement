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
            'form_138'    => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'good_moral'  => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'psa'         => 'nullable|mimes:jpeg,png,jpg,pdf|max:5120',
            'id_picture'  => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
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

        // Handle File Uploads
        if ($request->hasFile('form_138')) {
            $enrollment->form_138_path = $request->file('form_138')->store('documents/form138', 'public');
        }
        if ($request->hasFile('good_moral')) {
            $enrollment->good_moral_path = $request->file('good_moral')->store('documents/good_moral', 'public');
        }
        if ($request->hasFile('psa')) {
            $enrollment->psa_path = $request->file('psa')->store('documents/psa', 'public');
        }
        if ($request->hasFile('id_picture')) {
            $enrollment->id_picture_path = $request->file('id_picture')->store('documents/id_pictures', 'public');
        }
        $enrollment->save();

        // 5. CREATE PAYMENT RECORD
        Payment::updateOrCreate(
            ['user_id' => $user->id, 'application_id' => $enrollment->id],
            ['amount' => 1000.00, 'status' => 'Pending', 'payment_date' => now()]
        );

        // 6. UPDATE USER STATUS
        User::where('id', $user->id)->update(['status' => 'Pending']);

        return redirect()->route('student.dashboard')->with('success', 'Application submitted successfully!');
    }
}