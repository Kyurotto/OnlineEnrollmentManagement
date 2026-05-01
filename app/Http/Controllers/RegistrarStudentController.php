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
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved', 'Paid', 'Pending']);

        if ($level === 'shs') {
            $query->whereIn('latest_enrollments.course_code', $shsStrands);
        } elseif ($level === 'college') {
            $query->whereNotIn('latest_enrollments.course_code', $shsStrands);
        }

        if ($activeYear) {
            $query->where('latest_enrollments.year_level', 'like', '%' . $activeYear->year_name . '%');
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

        return view('registrar.students.index', compact('students', 'pendingCount', 'search', 'filter', 'sortField', 'sortDirection', 'totalStudents', 'level'));
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
            ->whereIn('status', ['Enrolled', 'Approved', 'Paid', 'Pending'])
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