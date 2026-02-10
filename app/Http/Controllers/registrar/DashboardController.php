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
        // 1. Gather Statistics for the Overview Cards
        $stats = [
            'students'       => User::where('role', 'student')->count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(), // Pending Apps
            'enrolled'       => Enrollment::where('status', 'Enrolled')->count(), // Officially Enrolled
            'active_courses' => Course::count(),
        ];

        // 2. Notification Count (for the logic, even if bell is hidden)
        $pendingCount = Enrollment::where('status', 'Pending')->count();

        // 3. Return the specific Registrar View
        return view('registrar.dashboard', compact('stats', 'pendingCount'));
    }
}
