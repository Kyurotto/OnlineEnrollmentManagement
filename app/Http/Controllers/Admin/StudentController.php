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
        $search = $request->get('search', '');
        $filter = $request->get('filter', 'all');
        $level = $request->get('level', 'all');
        $sortField = $request->get('sortField', 'users.id');
        $sortDirection = $request->get('sortDirection', 'desc');

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

        $enrollmentSelect = ['user_id', 'course_code', 'year_level', 'status', 'id', 'is_regular', 'classification_reason', 'credentials_verified', 'student_type', 'physical_documents_received'];

        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];

        $query = User::query()
            ->select('users.*', 'latest_enrollments.course_code', 'latest_enrollments.year_level',
                     'latest_enrollments.id as enrollment_id',
                     'latest_enrollments.is_regular', 'latest_enrollments.classification_reason',
                     'latest_enrollments.credentials_verified', 'latest_enrollments.student_type',
                     'latest_enrollments.physical_documents_received',
                     'courses.course_name')
            ->joinSub(
                Enrollment::select($enrollmentSelect)
                    ->whereNull('archived_at')
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->whereNull('archived_at')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->where('latest_enrollments.status', 'Enrolled');

        if ($level === 'shs') {
            $query->whereIn('latest_enrollments.course_code', $shsStrands);
        } elseif ($level === 'college') {
            $query->whereNotIn('latest_enrollments.course_code', $shsStrands);
        }

        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        // Show only enrolled students for the active term
        if ($activeYear) {
            $query->where('latest_enrollments.year_level', 'like', '%' . $activeYear->year_name . '%');
        }
        if ($activeSemester) {
            $query->where('latest_enrollments.year_level', 'like', '%' . $activeSemester->name . '%');
        }


        if ($filter === 'regular') {
            $query->where('latest_enrollments.is_regular', true);
        } elseif ($filter === 'irregular') {
            $query->where('latest_enrollments.is_regular', false);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.first_name', 'like', "%{$search}%")
                  ->orWhere('users.last_name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('latest_enrollments.course_code', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy($sortField, $sortDirection)->paginate(10);

        foreach ($students as $student) {
            $student->program = $student->course_code ?: 'N/A';


            if (!empty($student->year_level)) {
                $parts = explode('|', $student->year_level);
                $student->year_display = trim($parts[0]);
            } else {
                $student->year_display = 'N/A';
            }
            $student->display_email = $student->email;
            $student->display_account = $student->username ?: 'N/A';
        }

        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $totalStudents = (clone $query)->count();

        return view('admin.students.index', compact('students', 'pendingCount', 'search', 'filter', 'sortField', 'sortDirection', 'totalStudents', 'level'));
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
