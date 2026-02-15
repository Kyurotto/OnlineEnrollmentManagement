<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Gather Overview Statistics
        $stats = [
            'active_courses' => Course::count(),
            'students'       => User::where('role', 'student')->count(),
            'total_payments' => Payment::count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(),
            'enrolled'       => Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count(),
        ];

        // 1. Get the Count
        $pendingCount = \App\Models\Enrollment::where('status', 'Pending')->count();

        // 2. Get the Actual Records (Latest 5 for the dropdown list)
        $notifications = \App\Models\Enrollment::where('status', 'Pending')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact('stats', 'pendingCount', 'notifications'));
    }
}