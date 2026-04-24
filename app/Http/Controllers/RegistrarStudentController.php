<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;

class RegistrarStudentController extends Controller
{
    /**
     * Display a listing of students (Registrar View).
     */
    public function index()
{
    // 1. Fetch official students (Approved/Enrolled only)
    $students = User::where('role', 'student')
                    ->whereIn('id', Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10); 

    // 2. Attach Program, Section (Year only), and Account details
    foreach ($students as $student) {
        $enrollment = Enrollment::with('course')
                                ->where('user_id', $student->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                                
        // Program sync logic
        if ($enrollment && $enrollment->course && !empty($enrollment->course->name)) {
            $student->program = $enrollment->course->name;
        } elseif ($enrollment && !empty($enrollment->course_code)) {
            $student->program = $enrollment->course_code;
        } else {
            $student->program = 'N/A';
        }

        // Section: Extracts only the Year Level (e.g., "1st Year")
        if ($enrollment && !empty($enrollment->year_level)) {
            $parts = explode('|', $enrollment->year_level);
            $student->year_display = trim($parts[0]);
        } else {
            $student->year_display = 'N/A';
        }
        
        // MATCHING YOUR LATEST SCREENSHOT:
        // EMAIL column gets the full email
        $student->display_email = $student->email;
        
        // USER ACCOUNT column gets the short username
        $student->display_account = $student->username ?: 'N/A';
    }

    $pendingCount = Enrollment::where('status', 'Pending')->count();

    if (request()->routeIs('registrar.*')) {
        return view('registrar.students.index', compact('students', 'pendingCount'));
    }

    return view('admin.students.index', compact('students', 'pendingCount'));
}

    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        $student = User::findOrFail($id);
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // Return the REGISTRAR view
        return view('registrar.students.edit', compact('student', 'pendingCount'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name'             => ['required', 'string', 'max:255'],
            'middle_name'            => ['nullable', 'string', 'max:255'],
            'last_name'              => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'max:255', Rule::unique('users')->ignore($student->id)],
            'status'                 => ['nullable', 'string'],
            'student_type'           => ['nullable', 'string', 'in:new,transferee,shifter,returnee'],
            'is_regular'             => ['nullable', 'in:0,1,'],
            'classification_reason'  => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user fields
        $student->first_name  = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name   = $request->last_name;
        $student->name        = $request->first_name . ' ' . $request->last_name;
        $student->email       = $request->email;

        if ($request->has('status') && $request->status !== null) {
            $student->status = $request->status;
        }

        $student->save();

        // Update enrollment classification fields if present
        $enrollment = \App\Models\Enrollment::where('user_id', $id)
            ->whereIn('status', ['Enrolled', 'Approved'])
            ->latest()
            ->first();

        if ($enrollment) {
            if ($request->filled('student_type')) {
                $enrollment->student_type = $request->student_type;
            }

            if ($request->has('is_regular') && $request->is_regular !== '') {
                $isRegular = (bool) $request->is_regular;
                $enrollment->is_regular = $isRegular;

                // Require classification reason when marking Irregular
                if (!$isRegular) {
                    if (!$request->filled('classification_reason')) {
                        return back()->withErrors(['classification_reason' => 'A classification reason is required when marking a student as Irregular.'])->withInput();
                    }
                    $enrollment->classification_reason = $request->classification_reason;
                } else {
                    // Clearing reason when marking Regular
                    $enrollment->classification_reason = null;
                }
            } elseif ($request->has('is_regular') && $request->is_regular === '') {
                $enrollment->is_regular = null;
                $enrollment->classification_reason = null;
            }

            $enrollment->last_audited_at = now();
            $enrollment->save();
        }

        return redirect()->route('registrar.students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        Enrollment::where('user_id', $id)->delete(); // Clean up enrollments
        $student->delete();

        return back()->with('success', 'Student record deleted.');
    }

}