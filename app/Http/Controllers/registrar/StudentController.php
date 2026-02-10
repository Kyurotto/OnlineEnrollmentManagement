<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students (Registrar View).
     */
    public function index()
    {
        // Fetch Students (Exclude Admin/Staff)
        $students = User::where(function($query) {
                            $query->where('role', 'student')->orWhereNull('role');
                        })
                        ->whereNotIn('role', ['admin', 'registrar', 'cashier'])
                        ->latest()
                        ->paginate(10);

        // Notification Count
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // Return the REGISTRAR view
        return view('registrar.students.index', compact('students', 'pendingCount'));
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
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users')->ignore($student->id)],
            'status'      => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update Fields
        $student->first_name  = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name   = $request->last_name;
        $student->name        = $request->first_name . ' ' . $request->last_name; // Sync 'name'
        $student->email       = $request->email;

        // Registrar can manually fix status
        if ($request->has('status') && $request->status !== null) {
            $student->status = $request->status;
        }

        $student->save();

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
