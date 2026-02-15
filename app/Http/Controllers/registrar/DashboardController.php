<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Overview Stats
        $stats = [
            'students'       => User::where('role', 'student')->count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(),
            'enrolled'       => Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count(),
            'active_courses' => Course::count(),
        ];

        // 2. NOTIFICATION LOGIC (UPDATED)
        // Count Pending (New Applications) for the red badge
        $newEnrolleesCount = Enrollment::where('status', 'Pending')->count();

        // Fetch Notifications: Include BOTH 'Pending' (New) and 'Enrolled' (Paid)
        // We order by 'updated_at' so the most recent action (application or payment) appears top.
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                            ->with('user')
                            ->orderBy('updated_at', 'desc') // Show latest changes first
                            ->take(5)
                            ->get();

        return view('registrar.dashboard', compact('stats', 'newEnrolleesCount', 'notifications'));
    }
}