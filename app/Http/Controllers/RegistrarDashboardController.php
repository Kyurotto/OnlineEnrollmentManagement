<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Carbon\Carbon;

class RegistrarDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch Accurate Data Counts
        $studentsCount = User::where('role', 'student')
                             ->whereHas('application', function($q) {
                                 $q->whereIn('status', ['Enrolled', 'Approved']);
                             })
                             ->count();

        $totalApplicationsCount = Enrollment::count();
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $enrolledCount = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count();
        $programsCount = Course::where('type', 'program')->count();

        // 2. CALCULATE EXTRA STATS
        $sectionsCount = \App\Models\Section::count();

        // 3. MAP THE STATS EXACTLY FOR THE HTML VIEW
        $stats = [
            'students'     => $studentsCount,
            'applications' => $pendingCount,
            'programs'     => $programsCount,
            'sections'     => $sectionsCount,
        ];

        // 4. NOTIFICATIONS (Dropdown)
        $newEnrolleesCount = $pendingCount;

        $notifications = Enrollment::whereIn('status', ['Pending', 'Paid', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // 5. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        $weeklyApplications = Enrollment::with('user')
            ->whereIn('status', ['Pending', 'Paid'])
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

        // Modal Handling
        $selectedApp = null;
        if ($request->has('app_id')) {
            $selectedApp = Enrollment::with('user')->find($request->app_id);
        }

        // 6. CALCULATE SHS vs COLLEGE ENROLLMENT COUNTS
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $shs_count = Enrollment::whereIn('course_code', $shsStrands)->count();
        $college_count = Enrollment::whereNotIn('course_code', $shsStrands)->count();
        $total_count = Enrollment::count();

        return view('dashboard', compact(
            'stats', 'newEnrolleesCount', 'notifications',
            'appsByDate', 'weekDates', 'weekRange', 'selectedApp',
            'shs_count', 'college_count', 'total_count'
        ));
    }

    public function approve($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Approved']);
        $application->user->update(['status' => 'Enrolled']);

        return redirect()->route('registrar.dashboard')->with('success', 'Application approved successfully.');
    }

    public function reject($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Rejected']);

        return redirect()->route('registrar.dashboard')->with('success', 'Application rejected.');
    }
}
