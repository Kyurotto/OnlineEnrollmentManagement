<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class DashboardManager extends Component
{
    public function render()
    {
        // 1. Gather Overview Statistics
        $stats = [
            'active_courses' => Course::count(),
            
            'students'       => User::where('role', 'student')
                                    ->whereIn('id', Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray())
                                    ->count(),
                                    
            'total_payments' => Payment::count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(),
            'enrolled'       => Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count(),
        ];

        // 2. ROLLING 5 DAYS (Always includes Today as the last card)
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(4)->startOfDay(); // 5 days total including today

        $weeklyApplications = Enrollment::with(['user', 'course'])
            ->whereHas('user')
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

        return view('livewire.admin.dashboard-manager', compact('stats', 'appsByDate', 'weekDates', 'weekRange'));
    }
}
