<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Course; // 1. IMPORT COURSE MODEL
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        // 1. VALIDATION
        $request->validate([
            'course_code' => 'required',
            'year_level'  => 'required',
            'semester'    => 'required',
            'academic_year' => 'required',
            'first_name'  => 'required|string',
            'last_name'   => 'required|string',
            'birth_date'  => 'required|date',
            'age'         => 'required|integer',
            'gender'      => 'required',
            'email'       => 'required|email',
        ]);

        // 2. FIND THE COURSE ID (Fix for Error 1364)
        // We look up the course by its code (e.g., 'BSIS') to get its database ID (e.g., 1)
        $course = Course::where('course_code', $request->course_code)->first();

        // Optional: Handle invalid course code
        if (!$course) {
            return back()->withErrors(['course_code' => 'Invalid Course Code selected.']);
        }

        // 3. Combine Address Fields
        $fullAddress = $request->house_no . ' ' . $request->street . ', ' .
                       $request->barangay . ', ' . $request->city . ', ' .
                       $request->province . ' ' . $request->zip;

        // 4. Create the Record
        $enrollment = Enrollment::create([
            'user_id'     => Auth::id(),
            'status'      => 'Pending',

            // FIX: Save the ID found above
            'course_id'   => $course->id, 
            
            // Keep this if your DB also has course_code, otherwise remove it
            'course_code' => $request->course_code, 
            
            'year_level'  => $request->year_level . ' | ' . $request->semester . ' | ' . $request->academic_year,

            // Student Info
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'birth_date'  => $request->birth_date,
            'age'         => $request->age,
            'gender'      => $request->gender,
            'religion'    => $request->religion,
            'birthplace'  => $request->birthplace,
            'email'       => $request->email,
            'contact'     => $request->contact,
            'address_full'=> $fullAddress,

            // Parent/Guardian Info
            'father_name'        => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name'      => $request->guardian_name,
            'guardian_contact'   => $request->guardian_contact,
        ]);

        // 5. Create Payment Record
        Payment::create([
            'user_id'        => Auth::id(),
            'application_id' => $enrollment->id,
            'amount'         => 1000.00,
            'status'         => 'Pending',
            'payment_date'   => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Application submitted! Waiting for Admin approval.');
    }
}