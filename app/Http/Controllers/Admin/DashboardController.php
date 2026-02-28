<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
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

        // 2. Get the Count for the Notification Badge (Only Pending)
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // 3. Get the Actual Records (Latest 5 for the dropdown list)
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                        ->with('user')
                        ->whereHas('user')
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get();

        foreach($notifications as $notif) {
            if($notif->status === 'Enrolled') {
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

        return view('admin.dashboard', compact('stats', 'pendingCount', 'notifications', 'appsByDate', 'weekDates', 'weekRange'));
    }
}