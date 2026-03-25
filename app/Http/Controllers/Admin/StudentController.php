<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $enrolledUserIds = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray();
        $query = User::where('role', 'student')->whereIn('id', $enrolledUserIds);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

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

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        if (\Illuminate\Support\Facades\Auth::user()->role === 'registrar') {
            return view('registrar.students.edit', compact('student', 'pendingCount'));
        }

        return view('admin.students.edit', compact('student', 'pendingCount'));
    }

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
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $student->first_name  = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name   = $request->last_name;
        $student->name = $request->first_name . ' ' . $request->last_name;
        $student->email = $request->email;

        if ($request->has('status') && $request->status !== null) {
            $student->status = $request->status;
        }

        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }

        $student->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Saved successfully',
                'last_updated' => now()->format('h:i:s A')
            ]);
        }

        $redirectRoute = \Illuminate\Support\Facades\Auth::user()->role === 'registrar' ? 'registrar.students.index' : 'admin.students.index';
        return redirect()->route($redirectRoute)->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        Enrollment::where('user_id', $id)->delete();
        $student->delete();

        return back()->with('success', 'Student deleted successfully.');
    }
}