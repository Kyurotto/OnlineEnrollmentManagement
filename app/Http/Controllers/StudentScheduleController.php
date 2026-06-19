<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class StudentScheduleController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $schedules = Schedule::whereIn('enrollment_id', $user->enrollments()->pluck('id')->toArray())
            ->with(['employee', 'enrollment'])
            ->get();
            
        return view('student.schedules.index', compact('schedules'));
    }
}
