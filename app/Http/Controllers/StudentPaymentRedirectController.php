<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class StudentPaymentRedirectController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        // Get student's latest enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('year_level', '!=', null)
            ->latest()
            ->first();

        if ($enrollment) {
            $yearLevel = strtolower($enrollment->year_level);

            // Determine level based on year_level
            if (strpos($yearLevel, 'college') !== false ||
                strpos($yearLevel, '3rd year') !== false ||
                strpos($yearLevel, '4th year') !== false) {
                return redirect()->route('student.payment.college');
            }
        }

        // Default to SHS
        return redirect()->route('student.payment.shs');
    }
}
