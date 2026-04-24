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

        if ($activeYear && $activeSemester) {
            $hasPendingApplication = Enrollment::where('user_id', $user->id)
                ->where('status', 'Pending')
                ->where(function($q) use ($activeYear, $activeSemester) {
                    $q->where(function($sub) use ($activeYear, $activeSemester) {
                        $sub->where('semester_name', $activeSemester->name)
                            ->where('academic_year_name', $activeYear->year_name);
                    })->orWhere(function($sub) use ($activeYear, $activeSemester) {
                        $sub->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                            ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
                    });
                })
                ->exists();
        }

        // 3. Get Student Stats
        $myEnrollments = Enrollment::where('user_id', $user->id)->count();
        $latestEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();

        // 4. Get enrollment for CURRENT ACTIVE YEAR
        $currentYearEnrollment = null;
        if ($activeYear && $activeSemester && $latestEnrollment) {
            if (($latestEnrollment->academic_year_name === $activeYear->year_name && $latestEnrollment->semester_name === $activeSemester->name) ||
                (stripos($latestEnrollment->year_level, $activeYear->year_name) !== false && 
                 stripos($latestEnrollment->year_level, $activeSemester->name) !== false)) {
                $currentYearEnrollment = $latestEnrollment;
            }
        }

        // 5. Check if student is enrolled in the CURRENTLY ACTIVE academic year
        $isEnrolledInActiveYear = false;
        if ($activeYear && $activeSemester) {
            $isEnrolledInActiveYear = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Enrolled', 'Approved', 'Pending', 'Paid'])
                ->where(function($q) use ($activeYear, $activeSemester) {
                    $q->where(function($sub) use ($activeYear, $activeSemester) {
                        $sub->where('semester_name', $activeSemester->name)
                            ->where('academic_year_name', $activeYear->year_name);
                    })->orWhere(function($sub) use ($activeYear, $activeSemester) {
                        $sub->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                            ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
                    });
                })
                ->exists();
        }


        // 7. Check if an enrollment record already exists for this user in the active year, or if they have unresolved applications
        $existingEnrollment = null;
        $hasSubmitted = false;
        if ($activeYear && $activeSemester) {
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where(function($query) use ($activeYear, $activeSemester) {
                    $query->where(function($q) use ($activeYear, $activeSemester) {
                        $q->whereIn('status', ['Pending', 'Approved', 'Paid', 'Enrolled', 'Rejected'])
                          ->where(function($sub) use ($activeYear, $activeSemester) {
                              $sub->where(function($sub2) use ($activeYear, $activeSemester) {
                                  $sub2->where('semester_name', $activeSemester->name)
                                       ->where('academic_year_name', $activeYear->year_name);
                              })->orWhere(function($sub2) use ($activeYear, $activeSemester) {
                                  $sub2->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                                       ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
                              });
                          });
                    })->orWhereIn('status', ['Pending', 'Approved', 'Paid']); // Block if they have ANY active processes
                })
                ->first();
            $hasSubmitted = $existingEnrollment !== null;
        }

        // 8. Logic for Progress Bar Steps (to be used for button visibility)
        $isOldStudent = false;
        if ($myEnrollments > 1) {
            $isOldStudent = true;
        } else {
            $hasArchivedEnrollment = Enrollment::where('user_id', $user->id)
                ->whereNotNull('archived_at')
                ->exists();
            if ($hasArchivedEnrollment) {
                $isOldStudent = true;
            } else {
                // Check if they have an Enrolled status from a PREVIOUS term
                $previousEnrolled = Enrollment::where('user_id', $user->id)
                    ->where('status', 'Enrolled')
                    ->get()
                    ->filter(function($enrollment) use ($activeYear, $activeSemester) {
                        if (!$activeYear || !$activeSemester) return true;
                        return !(stripos((string)$enrollment->year_level, $activeYear->year_name) !== false && 
                                 stripos((string)$enrollment->year_level, $activeSemester->name) !== false);
                    });
                $isOldStudent = $previousEnrolled->count() > 0;
            }
        }
        $isStep1Done = false;
        $isStep2Done = false;
        $isStep3Done = false;

        if ($isOldStudent && $latestEnrollment) {
            // Step 1: Online Docs
            $docFields = $latestEnrollment->getDocumentFields();
            $uploadedCount = 0;
            foreach ($docFields as $field => $label) {
                if (!empty($latestEnrollment->$field)) {
                    $uploadedCount++;
                }
            }
            $isStep1Done = ($uploadedCount === count($docFields));

            // Step 2: Physical Docs
            $isStep2Done = ($latestEnrollment->physical_documents_received == 1);

            // Step 3: Registrar Clearance
            $isStep3Done = ($latestEnrollment->credentials_verified == 1);
        }
        
        // Final flag for dashboard button: 
        // New student can always enroll. Old student must finish Step 3 (Clearance).
        $canEnrollNow = !$isOldStudent || $isStep3Done;

        return view('dashboard', compact(
            'activeSemester',
            'activeYear',
            'hasPendingApplication',
            'myEnrollments',
            'latestEnrollment',
            'currentYearEnrollment',
            'isEnrolledInActiveYear',
            'hasSubmitted',
            'existingEnrollment',
            'canEnrollNow'
        ));
    }
}
