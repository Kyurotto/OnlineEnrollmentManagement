<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where(function($query) {
                        $query->where('role', 'student')->orWhereNull('role');
                    })
                    ->whereNotIn('role', ['admin', 'registrar', 'cashier'])
                    ->latest()
                    ->paginate(10);

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

        if (auth()->user()->role === 'registrar') {
            return view('registrar.students.edit', compact('student', 'pendingCount'));
        }

        return view('admin.students.edit', compact('student', 'pendingCount'));
    }

    // *** UPDATED UPDATE FUNCTION ***
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        // 1. Validate (removed username validation since you removed the field)
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

        // 2. Assign New Values
        $student->first_name  = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name   = $request->last_name;

        // 3. Sync the 'name' column (Important for some default Laravel features)
        // This ensures "John Doe" is saved to 'name' if you change 'first_name'
        $student->name = $request->first_name . ' ' . $request->last_name;

        $student->email = $request->email;

        if ($request->has('status') && $request->status !== null) {
            $student->status = $request->status;
        }

        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }

        // 4. Save
        $student->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Saved successfully',
                'last_updated' => now()->format('h:i:s A')
            ]);
        }

        $redirectRoute = auth()->user()->role === 'registrar' ? 'registrar.students.index' : 'admin.students.index';
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
