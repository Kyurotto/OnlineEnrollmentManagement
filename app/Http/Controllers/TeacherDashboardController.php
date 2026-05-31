<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    /**
     * Teacher Dashboard — overview stats.
     */
    public function index()
    {
        // Active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Count enrolled students (current active term)
        $enrolledCount = Enrollment::where('status', 'Enrolled')->count();

        // SHS vs College breakdown
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $shsCount = Enrollment::where('status', 'Enrolled')
            ->whereIn('course_code', $shsStrands)
            ->count();
        $collegeCount = Enrollment::where('status', 'Enrolled')
            ->whereNotIn('course_code', $shsStrands)
            ->count();

        // Programs & Sections count
        $programsCount = Course::where('type', 'program')->count();
        $strandsCount = Course::where('type', 'shs')->count();
        $sectionsCount = Section::count();

        // Recent enrollments (latest 10)
        $recentEnrollments = Enrollment::with('user', 'course')
            ->where('status', 'Enrolled')
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact(
            'activeYear',
            'enrolledCount',
            'shsCount',
            'collegeCount',
            'programsCount',
            'strandsCount',
            'sectionsCount',
            'recentEnrollments'
        ));
    }

    /**
     * Student Registry — paginated, searchable list.
     */
    public function students(Request $request)
    {
        $search = $request->input('search', '');

        $students = User::where('role', 'student')
            ->whereHas('application', function ($q) {
                $q->where('status', 'Enrolled');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->with('application')
            ->orderBy('last_name')
            ->paginate(15)
            ->withQueryString();

        return view('teacher.students', compact('students', 'search'));
    }

    /**
     * Sections — read-only list with course info.
     */
    public function sections()
    {
        $sections = Section::with('course')
            ->orderBy('academic_year', 'desc')
            ->orderBy('section_name')
            ->paginate(15);

        return view('teacher.sections', compact('sections'));
    }

    /**
     * Teacher Profile — display own employee info.
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        return view('teacher.profile', compact('user', 'employee'));
    }
}
