<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request)
    {
        // 1. VALIDATION: Ensure required fields are not empty
        $request->validate([
            'course_code' => 'required',
            'year_level'  => 'required',
            'first_name'  => 'required|string',
            'last_name'   => 'required|string',
            'birth_date'  => 'required|date',  // Fixes "birth_date cannot be null"
            'age'         => 'required|integer',
            'gender'      => 'required',
            'email'       => 'required|email',
        ]);

        // 2. Combine Address Fields (Since your DB uses 'address_full')
        $fullAddress = $request->house_no . ' ' . $request->street . ', ' .
                       $request->barangay . ', ' . $request->city . ', ' .
                       $request->province;

        // 3. Create the Record (Mapping Form Inputs -> DB Columns)
        Enrollment::create([
            'user_id'     => Auth::id(),
            'status'      => 'Pending', // Triggers the Admin Bell

            // Course Info
            'course_code' => $request->course_code,
            'year_level'  => $request->year_level,

            // Student Info
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'birth_date'  => $request->birth_date, // This must match the form input name="birth_date"
            'age'         => $request->age,
            'gender'      => $request->gender,
            'religion'    => $request->religion,
            'birthplace'  => $request->birthplace,
            'email'       => $request->email,
            'contact'     => $request->contact,

            // Address (Concatenated)
            'address_full'=> $fullAddress,

            // Parent/Guardian Info
            'father_name'        => $request->father_name,
            'mother_maiden_name' => $request->mother_maiden_name,
            'guardian_name'      => $request->guardian_name,
            'guardian_contact'   => $request->guardian_contact,
        ]);

        // 4. Redirect
        return redirect()->route('student.dashboard')->with('success', 'Application submitted! Waiting for Admin approval.');
    }
}
