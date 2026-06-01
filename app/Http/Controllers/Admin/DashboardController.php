<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 0. Get Active Academic Year
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        // 1. Gather Overview Statistics
        $studentsCountQuery = User::where('role', 'student')
            ->whereHas('application', function ($q) use ($activeYearName) {
                $q->where('status', 'Enrolled');
                if ($activeYearName) {
                    $q->where('year_level', 'like', '%'.$activeYearName.'%');
                }
            });
        $studentsCount = $studentsCountQuery->count();

        $stats = [
            'students' => $studentsCount,
            'total_payments' => Payment::count(),
            'applications' => Enrollment::where('status', 'Pending')
                ->when($activeYearName, function ($q) use ($activeYearName) {
                    $q->where('year_level', 'like', '%'.$activeYearName.'%');
                })->count(),
            'enrolled' => $studentsCount,
        ];

        // 2. Get the Count for the Notification Badge (Only Pending)
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // 3. Get the Actual Records (Latest 5 for the dropdown list)
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->whereHas('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        foreach ($notifications as $notif) {
            if ($notif->status === 'Enrolled') {
                $payment = Payment::where('application_id', $notif->id)->first();
                $notif->paid_amount = $payment ? $payment->amount : 0;
            }
        }

        // 4. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay(); // 5 days total including today

        $weeklyApplications = Enrollment::with(['user', 'course'])
            ->whereHas('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group applications by date (Y-m-d)
        $appsByDate = $weeklyApplications->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        // Generate the 5 days for the view
        $weekDates = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $startDate->copy()->addDays($i);
            $weekDates[] = [
                'date_string' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'day_num' => $date->format('d'),
                'is_today' => $date->isToday(),
            ];
        }

        // Displays the current Month and Year (e.g. "February 2026")
        $weekRange = Carbon::now()->format('F Y');

        // Calculate SHS vs College enrollment counts
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $baseQuery = Enrollment::whereIn('status', ['Enrolled', 'Approved', 'Paid', 'Pending']);

        $shs_count = (clone $baseQuery)->whereIn('course_code', $shsStrands)->count();
        $college_count = (clone $baseQuery)->whereNotIn('course_code', $shsStrands)->count();
        $total_count = (clone $baseQuery)->count();

        return view('admin.dashboard', compact('stats', 'pendingCount', 'notifications', 'appsByDate', 'weekDates', 'weekRange', 'shs_count', 'college_count', 'total_count'));
    }

    /**
     * Get pending enrollment counts by level (SHS vs College)
     */
    public function getPendingCounts()
    {
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        $baseQuery = Enrollment::when($activeYearName, function ($q) use ($activeYearName) {
            $q->where('year_level', 'like', '%'.$activeYearName.'%');
        });

        return response()->json([
            'total_pending' => (clone $baseQuery)->where('status', 'Pending')->count(),
            'shs_pending' => (clone $baseQuery)->whereIn('course_code', $shsStrands)
                ->where('status', 'Pending')
                ->count(),
            'college_pending' => (clone $baseQuery)->whereNotIn('course_code', $shsStrands)
                ->where('status', 'Pending')
                ->count(),
            'shs_approved' => (clone $baseQuery)->whereIn('course_code', $shsStrands)
                ->where('status', 'Approved')
                ->count(),
            'college_approved' => (clone $baseQuery)->whereNotIn('course_code', $shsStrands)
                ->where('status', 'Approved')
                ->count(),
            'shs_total' => (clone $baseQuery)->whereIn('course_code', $shsStrands)->count(),
            'college_total' => (clone $baseQuery)->whereNotIn('course_code', $shsStrands)->count(),
        ]);
    }

    /**
     * Get automatic track statistics based on course code mapping
     */
    public function getTrackStats()
    {
        $acadStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM'];
        $tvlStrands = ['HE', 'ICT'];
        $allShs = array_merge($acadStrands, $tvlStrands);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        $baseQuery = Enrollment::when($activeYearName, function ($q) use ($activeYearName) {
            $q->where('year_level', 'like', '%'.$activeYearName.'%');
        });

        return [
            'acad_count' => (clone $baseQuery)->whereIn('course_code', $acadStrands)->count(),
            'tvl_count' => (clone $baseQuery)->whereIn('course_code', $tvlStrands)->count(),
            'college_count' => (clone $baseQuery)->whereNotIn('course_code', $allShs)->count(),
            'shs_total' => (clone $baseQuery)->whereIn('course_code', $allShs)->count(),
        ];
    }
}
