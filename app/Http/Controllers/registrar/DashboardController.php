<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch Accurate Data Counts
        $studentsCount = User::where('role', 'student')
                             ->whereIn('id', Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray())
                             ->count();
        
        $totalApplicationsCount = Enrollment::count(); 
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $enrolledCount = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count();
        $programsCount = Course::count();

        // 2. CALCULATE EXTRA STATS
        $allEnrollments = Enrollment::select('year_level')->get();

        $academicYearsCount = $allEnrollments->pluck('year_level')->map(function ($item) {
            $parts = explode('|', $item); 
            return isset($parts[2]) ? trim($parts[2]) : null;
        })->filter()->unique()->count();

        $semestersCount = $allEnrollments->pluck('year_level')->map(function ($item) {
            $parts = explode('|', $item);
            return isset($parts[1]) ? trim($parts[1]) : null;
        })->filter()->unique()->count();

        $sectionsCount = Enrollment::where('status', 'Enrolled')->distinct('year_level')->count('year_level');

        // 3. MAP THE STATS EXACTLY FOR THE HTML VIEW
        $stats = [
            'students'       => $studentsCount,
            'applications'   => $pendingCount, 
            'enrolled'       => $enrolledCount,
            'programs'       => $programsCount,
            'academic_years' => $academicYearsCount > 0 ? $academicYearsCount : 1, 
            'semesters'      => $semestersCount > 0 ? $semestersCount : 1,       
            'sections'       => $sectionsCount,
        ];

        // 4. NOTIFICATIONS (Dropdown)
        $newEnrolleesCount = $pendingCount;

        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // 5. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        $weeklyApplications = Enrollment::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group applications by date (Y-m-d)
        $appsByDate = $weeklyApplications->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        // Generate the 5 days for the view
        $weekDates = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $startDate->copy()->addDays($i);
            $weekDates[] = [
                'date_string' => $date->format('Y-m-d'),
                'day_name'    => $date->format('l'),
                'day_num'     => $date->format('d'),
                'is_today'    => $date->isToday(),
            ];
        }
        
        // Displays the current Month and Year (e.g. "February 2026")
        $weekRange = Carbon::now()->format('F Y');

        return view('registrar.dashboard', compact(
            'stats', 'newEnrolleesCount', 'notifications', 
            'appsByDate', 'weekDates', 'weekRange'
        ));
    }
}