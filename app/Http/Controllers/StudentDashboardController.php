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

<<<<<<< HEAD
        // 6. Calculate Progress Steps for the Progress Bar
        $steps = [
            'application' => $latestEnrollment ? 'green' : 'grey',
            'online_docs' => 'grey',
            'physical_docs' => 'grey',
            'payment' => 'grey',
            'enroll' => 'grey'
        ];

        if ($latestEnrollment) {
            // 1. Online Docs Logic - Check if ALL required documents are uploaded
            $level = $latestEnrollment->level;
            $allDocsUploaded = true;

            if ($level === 'shs') {
                $requiredDocs = ['form_137_path', 'sf10_path', 'good_moral_path', 'psa_path', 'id_picture_path'];
            } else {
                $requiredDocs = ['form_137_path', 'good_moral_path', 'psa_path', 'id_picture_path'];
            }

            foreach ($requiredDocs as $doc) {
                if (empty($latestEnrollment->$doc)) {
                    $allDocsUploaded = false;
                    break;
                }
            }

            $hasPromissory = !empty($latestEnrollment->promissory_note_path);

            if ($allDocsUploaded) {
                $steps['online_docs'] = 'green'; // All documents uploaded
            } elseif ($hasPromissory) {
                $steps['online_docs'] = 'yellow'; // Missing docs but promissory note submitted
            } else {
                $steps['online_docs'] = 'grey'; // No documents uploaded
            }

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
            $steps['enroll'] = ($latestEnrollment->status == 'Enrolled') ? 'green' : ($hasPaid ? 'yellow' : 'grey');
        }

        // 7. Check if an enrollment record already exists for this user in the active year
=======
        // 6. Check if an enrollment record already exists for this user in the active year
>>>>>>> origin/main
        $existingEnrollment = null;
        $hasSubmitted = false;
        if ($activeYear) {
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Pending', 'Approved', 'Enrolled', 'Rejected'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->first();
            $hasSubmitted = $existingEnrollment !== null;
        }

        return view('dashboard', compact(
            'activeSemester',
            'activeYear',
            'hasPendingApplication',
            'myEnrollments',
            'latestEnrollment',
            'currentYearEnrollment',
            'isEnrolledInActiveYear',
<<<<<<< HEAD
            'steps',
=======
>>>>>>> origin/main
            'hasSubmitted',
            'existingEnrollment'
        ));
    }
}
