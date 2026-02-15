<?php

namespace App\Http\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch the enrollment record for the logged-in student
        $enrollment = Enrollment::where('user_id', $user->id)->first();
        
        // Fetch student's payments
        $payments = Payment::where('user_id', $user->id)->latest()->get();

        return view('student.dashboard', compact('enrollment', 'payments'));
    }
}