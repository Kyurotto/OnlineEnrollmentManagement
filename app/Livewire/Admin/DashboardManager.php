<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class DashboardManager extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $showModal = false;

    public $selectedApp = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewApplication($id)
    {
        $this->selectedApp = Enrollment::with('user')->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedApp = null;
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        // This will trigger a re-render
    }

    public function approveApplication($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Approved']);

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }
        session()->flash('success', "Application #{$id} approved.");
        $this->closeModal();
    }

    public function rejectApplication($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Rejected']);
        session()->flash('success', "Application #{$id} rejected.");
        $this->closeModal();
    }

    public function render()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearName = $activeYear ? $activeYear->year_name : null;

        // 1. Applications Query for Table
        $query = Enrollment::query()->with(['user'])->whereNotIn('status', ['Enrolled', 'Rejected', 'Dropped', 'Withdrawn'])->latest();

        if (! empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $applications = $query->paginate(10);

        $stats = [
            'active_courses' => Course::where('type', 'course')->count(),
            'students' => User::where('role', 'student')
                ->whereHas('application', function ($q) use ($activeYearName) {
                    $q->where('status', 'Enrolled');
                    if ($activeYearName) {
                        $q->where('year_level', 'like', '%'.$activeYearName.'%');
                    }
                })->count(),
            'total_payments' => Payment::count(),
            'applications' => Enrollment::whereNotIn('status', ['Enrolled', 'Rejected', 'Dropped', 'Withdrawn'])
                ->when($activeYearName, function ($q) use ($activeYearName) {
                    $q->where('year_level', 'like', '%'.$activeYearName.'%');
                })->count(),
        ];

        // 3. ROLLING 5 DAYS
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        $weeklyApplications = Enrollment::with(['user', 'course'])
            ->whereHas('user')
            ->whereIn('status', ['Pending', 'Paid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $appsByDate = $weeklyApplications->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

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

        $weekRange = Carbon::now()->format('F d, Y');

        // Admin Dashboard Navbar Data (Duplicate logic for safety if needed, but shared via Provider usually)
        $newEnrolleesCount = Auth::user()->unreadNotifications->count();
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 4. CALCULATE SHS vs COLLEGE ENROLLMENT COUNTS
        $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
        $baseEnrollmentQuery = Enrollment::whereNotIn('status', ['Enrolled', 'Rejected', 'Dropped', 'Withdrawn'])
            ->when($activeYearName, function ($q) use ($activeYearName) {
                $q->where('year_level', 'like', '%'.$activeYearName.'%');
            });

        $shs_count = (clone $baseEnrollmentQuery)->whereIn('course_code', $shsStrands)->count();
        $college_count = (clone $baseEnrollmentQuery)->whereNotIn('course_code', $shsStrands)->count();
        $total_count = (clone $baseEnrollmentQuery)->count();

        // 5. STUDENT CLASSIFICATION STATS (Synced with Registrar Registry)
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
            ->where('latest_enrollments.status', 'Enrolled')
            ->when($activeYearName, function ($q) use ($activeYearName) {
                $q->where('latest_enrollments.year_level', 'like', '%'.$activeYearName.'%');
            });

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

        return view('dashboard', [
            'stats' => $stats,
            'appsByDate' => $appsByDate,
            'weekDates' => $weekDates,
            'weekRange' => $weekRange,
            'applications' => $applications,
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
            'shs_count' => $shs_count,
            'college_count' => $college_count,
            'total_count' => $total_count,
            'registryTotalStudents' => $registryTotalStudents,
            'registryRegularCount' => $registryRegularCount,
            'registryIrregularCount' => $registryIrregularCount,
            'registryNewCount' => $registryNewCount,
            'registryReturningCount' => $registryReturningCount,
        ]);
    }
}
