<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment; // Needed to delete related data
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // Display the list of students
    public function index()
    {
        // 1. Fetch Students (Filtering out admins/registrars/cashiers)
        $students = User::where(function($query) {
                        $query->where('role', 'student')
                              ->orWhereNull('role');
                    })
                    ->whereNotIn('role', ['admin', 'registrar', 'cashier'])
                    ->latest()
                    ->get()
                    ->map(function($user) {
                        // Check enrollment status
                        $application = Enrollment::where('user_id', $user->id)->latest()->first();

                        $status = 'Not Enrolled';
                        if ($application) {
                            if ($application->status === 'Approved') $status = 'Enrolled';
                            elseif ($application->status === 'Pending') $status = 'Pending';
                            elseif ($application->status === 'Rejected') $status = 'Rejected';
                        }

                        return [
                            'id' => $user->id,
                            'username' => $user->username ?? $user->name,
                            'full_name' => $user->first_name . ' ' . $user->last_name,
                            // Fallback display name
                            'display_name' => ($user->first_name) ? $user->first_name . ' ' . $user->last_name : $user->name,
                            'email' => $user->email,
                            'status' => $status,
                            'role' => $user->role ?? 'student',
                            'created_at' => $user->created_at->format('Y-m-d H:i:s')
                        ];
                    });

        return view('admin.students.index', compact('students'));
    }

    // SHOW EDIT FORM
    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // HANDLE UPDATE
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // Ensure email is unique but ignore the current student's own email
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($student->id)],
            'password' => ['nullable', 'min:8'], // Password is optional
        ]);

        // Update fields
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name = $request->last_name;

        // SYNC: Update the main 'name' column to match
        $student->name = $request->first_name . ' ' . $request->last_name;

        $student->email = $request->email;

        // Only update password if they typed a new one
        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }

        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    // HANDLE DELETE
    public function destroy($id)
    {
        $student = User::findOrFail($id);

        // Clean up: Delete their enrollments first to avoid database errors
        Enrollment::where('user_id', $id)->delete();

        // Delete the user
        $student->delete();

        return back()->with('success', 'Student deleted successfully.');
    }
}
