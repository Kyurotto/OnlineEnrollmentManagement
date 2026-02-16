<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch Data
        $studentsCount = User::where('role', 'student')->count();
        $pendingCount  = Enrollment::where('status', 'Pending')->count();
        $enrolledCount = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count();
        $programsCount = Course::count();

        // 2. CALCULATE EXTRA STATS (Fixing the Crash)
        // Since 'academic_year' and 'semester' columns don't exist in the database,
        // we extract them from the 'year_level' column which looks like: "Year | Semester | AcadYear"
        
        $allEnrollments = Enrollment::select('year_level')->get();

        $academicYearsCount = $allEnrollments->pluck('year_level')->map(function ($item) {
            // Split string by '|' and get the 3rd part (Academic Year)
            $parts = explode('|', $item); 
            return isset($parts[2]) ? trim($parts[2]) : null;
        })->filter()->unique()->count();

        $semestersCount = $allEnrollments->pluck('year_level')->map(function ($item) {
            // Split string by '|' and get the 2nd part (Semester)
            $parts = explode('|', $item);
            return isset($parts[1]) ? trim($parts[1]) : null;
        })->filter()->unique()->count();

        // Count distinct sections (Year Levels) based on active students
        $sectionsCount = Enrollment::where('status', 'Enrolled')->distinct('year_level')->count('year_level');

        $stats = [
            'students'       => $studentsCount,
            'applications'   => $pendingCount,
            'enrolled'       => $enrolledCount,
            'programs'       => $programsCount,
            'academic_years' => $academicYearsCount > 0 ? $academicYearsCount : 1, // Default to 1 if empty
            'semesters'      => $semestersCount > 0 ? $semestersCount : 1,       // Default to 1 if empty
            'sections'       => $sectionsCount,
        ];

        // 3. NOTIFICATIONS
        $newEnrolleesCount = $pendingCount;

        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        return view('registrar.dashboard', compact('stats', 'newEnrolleesCount', 'notifications'));
    }
}