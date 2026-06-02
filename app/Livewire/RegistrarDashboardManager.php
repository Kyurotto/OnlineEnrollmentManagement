<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.registrar')]
class RegistrarDashboardManager extends Component
{
    public function render()
    {
        // 0. Get Active Academic Year
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        // 1. Fetch Accurate Data Counts
        $studentsCountQuery = User::query()->where('role', '=', 'student', 'and')
            ->whereHas('application', function ($q) use ($activeYearName) {
                $q->where('status', '=', 'Enrolled', 'and');
                if ($activeYearName) {
                    $q->where('year_level', 'like', '%'.$activeYearName.'%', 'and');
                }
            });
        $studentsCount = $studentsCountQuery->count();

        $totalApplicationsCount = Enrollment::query()->count('*');
        $activeApplicationsCount = Enrollment::query()->whereNotIn('status', ['Enrolled', 'Rejected'])->hasUploadsOrVerified()->count('*');
        $enrolledCount = Enrollment::query()->whereIn('status', ['Enrolled', 'Approved'])->count('*');
        $programsCount = Course::query()->where('type', '=', 'program')->count('*');
        $strandsCount = Course::query()->where('type', '=', 'shs')->count('*');

        // 2. CALCULATE EXTRA STATS
        $sectionsCount = Section::query()->count('*');
        $hasIsRegular = Schema::hasColumn('enrollments', 'is_regular');

        $baseStudentClassStats = User::query()
            ->joinSub(
                Enrollment::query()->select([
                    'user_id',
                    'id',
                    'status',
                    'year_level',
                    $hasIsRegular ? 'is_regular' : DB::raw('NULL as is_regular'),
                ])
                    ->whereIn('id', function ($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id',
                '=',
                'latest_enrollments.user_id',
                'inner'
            )
            ->where('users.role', '=', 'student')
            ->where('latest_enrollments.status', '=', 'Enrolled');

        $registryTotalStudents = (clone $baseStudentClassStats)->count('*');
        $registryRegularCount = $hasIsRegular
            ? (clone $baseStudentClassStats)->whereRaw('latest_enrollments.is_regular = 1')->count('*')
            : 0;
        $registryIrregularCount = $hasIsRegular
            ? (clone $baseStudentClassStats)->whereRaw('latest_enrollments.is_regular = 0')->count('*')
            : 0;

        // Calculate New vs Returning Students
        $registryReturningCount = (clone $baseStudentClassStats)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('enrollments')
                    ->whereColumn('enrollments.user_id', 'users.id')
                    ->whereColumn('enrollments.id', '<>', 'latest_enrollments.id');
            })
            ->count('*');

        $registryNewCount = $registryTotalStudents - $registryReturningCount;

        // 3. MAP THE STATS EXACTLY FOR THE HTML VIEW
        $stats = [
            'students' => $studentsCount,
            'applications' => $activeApplicationsCount,
            'programs' => $programsCount,
            'strands' => $strandsCount,
            'sections' => $sectionsCount,
        ];

        // 4. NOTIFICATIONS (Dropdown)
        $newEnrolleesCount = Auth::user() ? Auth::user()->unreadNotifications->count() : 0;

        $notifications = Enrollment::query()->whereIn('status', ['Pending', 'Paid', 'Enrolled'], 'and', false)
            ->hasUploadsOrVerified()
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 5. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        // Limit to latest 50 applications to prevent massive rendering overhead
        $weeklyApplications = Enrollment::query()->with('user')
            ->whereIn('status', ['Pending', 'Paid'], 'and', false)
            ->hasUploadsOrVerified()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(50)
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

        // Modal Handling is usually done via traditional routes in the controller,
        // but we pass selectedApp as null unless handled via Livewire state later.
        $selectedApp = null;
        if (request()->has('app_id')) {
            $selectedApp = Enrollment::query()->with('user')->find(request()->app_id);
        }

        // 6. CALCULATE SHS vs COLLEGE ENROLLMENT COUNTS
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $shs_count = Enrollment::query()->whereIn('course_code', $shsStrands, 'and', false)->count('*');
        $college_count = Enrollment::query()->whereNotIn('course_code', $shsStrands, 'and', false)->count('*');
        $total_count = Enrollment::query()->count('*');

        return view('registrar.dashboard', compact(
            'stats', 'newEnrolleesCount', 'notifications',
            'appsByDate', 'weekDates', 'weekRange', 'selectedApp',
            'shs_count', 'college_count', 'total_count',
            'registryTotalStudents', 'registryRegularCount', 'registryIrregularCount',
            'registryNewCount', 'registryReturningCount'
        ));
    }
}
