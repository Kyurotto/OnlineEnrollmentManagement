<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\AcademicYear;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Fetch currently ACTIVE Semester and Year
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // 2. Check if student has a PENDING application for the CURRENT ACTIVE SEMESTER
        $hasPendingApplication = false;
        
        if ($activeYear) {
            $hasPendingApplication = Enrollment::where('user_id', $user->id)
                ->where('status', 'Pending')
                ->whereRaw("year_level LIKE ?", ["%{$activeYear->year_name}%"])
                ->exists();
        }

        // 3. Get Student Stats
        $myEnrollments = Enrollment::where('user_id', $user->id)->count();
        $latestEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();

        // 4. Get enrollment for CURRENT ACTIVE YEAR
        $currentYearEnrollment = null;
        if ($activeYear && $latestEnrollment) {
            if (strpos($latestEnrollment->year_level, $activeYear->year_name) !== false) {
                $currentYearEnrollment = $latestEnrollment;
            }
        }

        // 5. Check if student is enrolled in the CURRENTLY ACTIVE academic year
        $isEnrolledInActiveYear = false;
        if ($activeYear) {
            $isEnrolledInActiveYear = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Enrolled', 'Approved', 'Pending'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->exists();
        }

        // 6. Calculate Progress Steps for the Progress Bar
        $steps = [
            'application' => $latestEnrollment ? 'green' : 'grey',
            'online_docs' => 'grey',
            'physical_docs' => 'grey',
            'payment' => 'grey',
            'enroll' => 'grey'
        ];

        if ($latestEnrollment) {
            // 1. Online Docs Logic
            $hasPromissory = !empty($latestEnrollment->promissory_note_path);
            $steps['online_docs'] = $hasPromissory ? 'yellow' : 'green';

            // 2. Physical Docs Logic
            if ($latestEnrollment->physical_documents_received) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = $hasPromissory ? 'yellow' : 'grey';
            }

            // 3. Payment Logic (Checks for any 'Paid' status linked to this application)
            $hasPaid = $latestEnrollment->payments()->where('status', 'Paid')->exists();
            $steps['payment'] = $hasPaid ? 'green' : ($latestEnrollment->status == 'Approved' ? 'yellow' : 'grey');

            // 4. Enroll Logic
            $steps['enroll'] = ($latestEnrollment->status == 'Enrolled') ? 'green' : 'grey';
        }

        return view('dashboard', compact(
            'activeSemester', 
            'activeYear', 
            'hasPendingApplication',
            'myEnrollments', 
            'latestEnrollment',
            'currentYearEnrollment',
            'isEnrolledInActiveYear',
            'steps'
        ));
    }
}
