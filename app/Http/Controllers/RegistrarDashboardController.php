<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegistrarDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 0. Get Active Academic Year
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        // 1. Fetch Accurate Data Counts
        $studentsCountQuery = User::where('role', 'student')
                             ->whereHas('application', function($q) use ($activeYearName) {
                                 $q->where('status', 'Enrolled');
                                 if ($activeYearName) {
                                     $q->where('year_level', 'like', '%' . $activeYearName . '%');
                                 }
                             });
        $studentsCount = $studentsCountQuery->count();

        $totalApplicationsCount = Enrollment::count();
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $enrolledCount = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count();
        $programsCount = Course::where('type', 'program')->count();
        $strandsCount = Course::where('type', 'shs')->count();

        // 2. CALCULATE EXTRA STATS
        $sectionsCount = \App\Models\Section::count();
        $hasIsRegular = Schema::hasColumn('enrollments', 'is_regular');

        $baseStudentClassStats = User::query()
            ->joinSub(
                Enrollment::select(
                    'user_id',
                    'id',
                    'status',
                    'year_level',
                    $hasIsRegular ? 'is_regular' : DB::raw('NULL as is_regular')
                )
                    ->whereIn('id', function ($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id',
                '=',
                'latest_enrollments.user_id'
            )
            ->where('users.role', 'student')
            ->where('latest_enrollments.status', 'Enrolled');

        // No longer strictly filtering by year to maintain registry visibility during transitions
        // if ($activeYearName) {
        //     $baseStudentClassStats->where('latest_enrollments.year_level', 'like', '%' . $activeYearName . '%');
        // }

        $registryTotalStudents = (clone $baseStudentClassStats)->count();
        $registryRegularCount = $hasIsRegular
            ? (clone $baseStudentClassStats)->whereRaw('latest_enrollments.is_regular = 1')->count()
            : 0;
        $registryIrregularCount = $hasIsRegular
            ? (clone $baseStudentClassStats)->whereRaw('latest_enrollments.is_regular = 0')->count()
            : 0;

        // Calculate New vs Returning Students
        $registryReturningCount = (clone $baseStudentClassStats)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('enrollments')
                    ->whereColumn('enrollments.user_id', 'users.id')
                    ->whereColumn('enrollments.id', '<>', 'latest_enrollments.id');
            })
            ->count();

        $registryNewCount = $registryTotalStudents - $registryReturningCount;

        // 3. MAP THE STATS EXACTLY FOR THE HTML VIEW
        $stats = [
            'students'     => $studentsCount,
            'applications' => $pendingCount,
            'programs'     => $programsCount,
            'strands'      => $strandsCount,
            'sections'     => $sectionsCount,
        ];

        // 4. NOTIFICATIONS (Dropdown)
        $newEnrolleesCount = Auth::user()->unreadNotifications->count();

        $notifications = Enrollment::whereIn('status', ['Pending', 'Paid', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // 5. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        // Limit to latest 50 applications to prevent massive rendering overhead
        $weeklyApplications = Enrollment::with('user')
            ->whereIn('status', ['Pending', 'Paid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(50)
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
            'shs_count', 'college_count', 'total_count',
            'registryTotalStudents', 'registryRegularCount', 'registryIrregularCount',
            'registryNewCount', 'registryReturningCount'
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
