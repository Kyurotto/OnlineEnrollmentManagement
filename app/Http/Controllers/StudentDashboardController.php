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
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Ensure the active semester actually belongs to the active year
        $activeSemester = null;
        if ($activeYear) {
            $activeSemester = Semester::where('is_active', true)
                ->where('academic_year', $activeYear->year_name)
                ->first();
        }

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
        // A student is old if they have ANY historical record other than the one they are currently processing
        $isOldStudent = Enrollment::where('user_id', $user->id)
            ->where('id', '!=', $currentYearEnrollment->id ?? 0)
            ->exists();

        $isStep3Done = false;
        // For Old Students, clearance must be checked against the CURRENT TERM enrollment
        if ($isOldStudent) {
            if ($activeYear && $activeSemester) {
                $currentRecord = Enrollment::where('user_id', $user->id)
                    ->where(function($q) use ($activeYear, $activeSemester) {
                        $q->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                          ->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
                    })
                    ->latest()
                    ->first();
                
                if ($currentRecord) {
                    $isStep3Done = ($currentRecord->credentials_verified == 1);
                }
            }
        }
        
        // Final flag for dashboard button: 
        // New student can always enroll. Old student must finish Step 3 (Clearance).
        $canEnrollNow = !$isOldStudent || $isStep3Done;

        // 11. Fetch Payment Assessment Details (for visibility)
        $assessment = null;
        if ($currentYearEnrollment && $activeYear && $activeSemester) {
            $level = strtolower($currentYearEnrollment->level ?? 'college');
            $program = $currentYearEnrollment->course_code ?? 'all';
            
            // Robust year level extraction (e.g., "1st Year" -> "1")
            $yearLevelDigit = 'all';
            if (preg_match('/(\d+)/', $currentYearEnrollment->year_level, $matches)) {
                $yearLevelDigit = $matches[1];
            }
            
            $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevelDigit}";
            $assessment = \Illuminate\Support\Facades\Cache::get($cacheKey);
            
            if (!$assessment) {
                // Fallback to global
                $assessment = \Illuminate\Support\Facades\Cache::get("payment_assessment_{$level}_all_all", [
                    'tuitionFee' => 0,
                    'miscellaneousFees' => 0,
                    'discountPercentage' => 0,
                    'discountAmount' => 0,
                ]);
            }
            
            // Calculate final total
            $subtotal = ($assessment['tuitionFee'] ?? 0) + ($assessment['miscellaneousFees'] ?? 0);
            $percDisc = $subtotal * (($assessment['discountPercentage'] ?? 0) / 100);
            $totalDisc = $percDisc + ($assessment['discountAmount'] ?? 0);
            $assessment['finalTotal'] = max(0, $subtotal - $totalDisc);
            $assessment['totalDiscount'] = $totalDisc;
        }

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
            'canEnrollNow',
            'isOldStudent',
            'assessment'
        ));
    }
}
