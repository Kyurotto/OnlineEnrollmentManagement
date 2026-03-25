<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
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
        $user = $application->user;
        if ($user) {
            $user->delete();
        }
        $application->delete();
        session()->flash('success', "Application #{$id} and associated account destroyed.");
        $this->closeModal();
    }

    public function render()
    {
        // 1. Applications Query for Table
        $query = Enrollment::query()->with(['user'])->whereNotIn('status', ['Enrolled', 'Rejected'])->latest();

        if (!empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $applications = $query->paginate(10);

        // 2. Gather Overview Statistics
        $stats = [
            'active_courses' => Course::where('type', 'course')->count(),
            'students'       => User::where('role', 'student')
                                    ->whereHas('application', function($q) {
                                        $q->whereIn('status', ['Enrolled', 'Approved']);
                                    })->count(),
            'total_payments' => Payment::count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(),
        ];

        // 3. ROLLING 5 DAYS
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay();

        $weeklyApplications = Enrollment::with(['user', 'course'])
            ->whereHas('user')
            ->whereIn('status', ['Pending', 'Paid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $appsByDate = $weeklyApplications->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

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

        $weekRange = Carbon::now()->format('F d, Y');

        // Admin Dashboard Navbar Data (Duplicate logic for safety if needed, but shared via Provider usually)
        $newEnrolleesCount = Enrollment::where('status', 'Pending')->count();
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'appsByDate' => $appsByDate,
            'weekDates' => $weekDates,
            'weekRange' => $weekRange,
            'applications' => $applications,
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
        ]);
    }
}
