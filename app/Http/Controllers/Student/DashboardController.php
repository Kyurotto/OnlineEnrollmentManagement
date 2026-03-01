<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Fetch currently ACTIVE Semester and Year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // 2. Check if student has a PENDING application for the CURRENT ACTIVE SEMESTER
        $hasPendingApplication = false;
        
        if ($activeYear) {
            $hasPendingApplication = Enrollment::where('user_id', $user->id)
                ->where('status', 'Pending') // Only hide button if currently waiting for approval
                ->whereRaw("year_level LIKE ?", ["%{$activeYear->year_name}%"])
                ->exists();
        }

        // 3. Get Student Stats
        $myEnrollments = Enrollment::where('user_id', $user->id)->count();
        $latestEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();

        // 5. Get enrollment for CURRENT ACTIVE YEAR (show status only if current year matches)
        $currentYearEnrollment = null;
        if ($activeYear && $latestEnrollment) {
            // Check if latest enrollment is for the current active year
            if (strpos($latestEnrollment->year_level, $activeYear->year_name) !== false) {
                $currentYearEnrollment = $latestEnrollment;
            }
        }

        // 4. Check if student is enrolled in the CURRENTLY ACTIVE academic year (disable if true)
        $isEnrolledInActiveYear = false;
        if ($activeYear) {
            $isEnrolledInActiveYear = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Enrolled', 'Approved', 'Pending'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->exists();
        }

        return view('student.dashboard', compact(
            'activeSemester', 
            'activeYear', 
            'hasPendingApplication',
            'myEnrollments', 
            'latestEnrollment',
            'currentYearEnrollment',
            'isEnrolledInActiveYear'
        ));
    }
}