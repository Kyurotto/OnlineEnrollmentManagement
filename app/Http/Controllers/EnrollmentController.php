<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Payment; // Import Payment Model
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        // Prevent duplicate applications if currently active
        if (in_array($user->status, ['Pending', 'Enrolled', 'Approved'])) {
            return redirect()->route('student.dashboard')->with('error', 'You have an active enrollment application.');
        }

        return view('student.enrollment');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (in_array($user->status, ['Pending', 'Enrolled', 'Approved'])) {
            return redirect()->route('student.dashboard');
        }

        $request->validate([
            'course_code' => 'required',
            'year_level'  => 'required',
            'semester'    => 'required',
            'academic_year' => 'required',
            'first_name'  => 'required',
            'last_name'   => 'required',
            'birth_date'  => 'required',
            'age'         => 'required',
            'gender'      => 'required',
            'email'       => 'required',
        ]);

        $course = Course::where('course_code', $request->course_code)->first();
        if (!$course) { return back()->withErrors(['course_code' => 'Invalid Course Code.']); }

        $fullAddress = $request->house_no . ' ' . $request->street . ', ' . $request->barangay . ', ' . $request->city . ', ' . $request->province . ' ' . $request->zip;

        // 1. Save Enrollment Application
        $enrollment = Enrollment::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status'      => 'Pending',
                'course_id'   => $course->id, 
                'course_code' => $request->course_code, 
                'year_level'  => $request->year_level . ' | ' . $request->semester . ' | ' . $request->academic_year,
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
                'father_name'        => $request->father_name,
                'mother_maiden_name' => $request->mother_maiden_name,
                'guardian_name'      => $request->guardian_name,
                'guardian_contact'   => $request->guardian_contact,
            ]
        );

        // 2. AUTO-LIST DOWNPAYMENT (The Connection to Cashier)
        // This code inserts the row into the 'payments' table.
        Payment::firstOrCreate(
            [
                'user_id' => $user->id, 
                'application_id' => $enrollment->id
            ],
            [
                'amount'       => 1000.00,  // Standard Downpayment
                'status'       => 'Pending', // Shows in Cashier's "Pending" list
                'payment_date' => now(),
            ]
        );

        // 3. Update User Status
        $user->status = 'Pending';
        $user->save();

        return redirect()->route('student.dashboard')->with('success', 'Application submitted! Please pay the downpayment.');
    }
}