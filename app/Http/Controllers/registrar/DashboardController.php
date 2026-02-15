<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;

// FIX: Class name must be DashboardController, NOT ApplicationController
class DashboardController extends Controller
{
    public function index()
    {
        // 1. Gather Overview Statistics
        $stats = [
            'students'       => User::where('role', 'student')->count(),
            'applications'   => Enrollment::where('status', 'Pending')->count(),
            'enrolled'       => Enrollment::whereIn('status', ['Enrolled', 'Approved'])->count(),
            'active_courses' => Course::count(),
        ];

        // 2. Notification Count (for the Red Badge)
        $newEnrolleesCount = Enrollment::where('status', 'Enrolled')->count();

        // 3. Notification List (Fetch the actual records for the dropdown)
        $notifications = Enrollment::where('status', 'Enrolled')
                            ->with('user')
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get();

        // 4. Return View
        return view('registrar.dashboard', compact('stats', 'newEnrolleesCount', 'notifications'));
    }
}