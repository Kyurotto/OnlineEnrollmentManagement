<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class EnrollmentProgressBar extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $latestEnrollment = Enrollment::where('user_id', $user->id)
            ->when($activeYear, function($query) use ($activeYear) {
                return $query->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%');
            })
            ->when($activeSemester, function($query) use ($activeSemester) {
                return $query->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
            })
            ->latest()
            ->first();


        $isEnrollmentForCurrentTerm = false;
        if ($latestEnrollment && $activeYear && $activeSemester) {
            $isEnrollmentForCurrentTerm = (stripos((string)$latestEnrollment->year_level, $activeYear->year_name) !== false && 
                                           stripos((string)$latestEnrollment->year_level, $activeSemester->name) !== false);
        }

        $anyEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();
        $allEnrollmentsCount = Enrollment::where('user_id', $user->id)->count();
        
        // A student is "Old" if they have been previously enrolled (completed enrollment)
        // for a PREVIOUS term, or have archived enrollment records from previous terms,
        // or have multiple enrollments.
        // If they only have 1 enrollment, they are a new student, even if their status is 'Enrolled'.
        $isOldStudent = false;
        if ($allEnrollmentsCount > 1) {
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
                        // If the enrollment is for the current term, it's NOT a previous term
                        return !(stripos((string)$enrollment->year_level, $activeYear->year_name) !== false && 
                                 stripos((string)$enrollment->year_level, $activeSemester->name) !== false);
                    });
                
                $isOldStudent = $previousEnrolled->count() > 0;
            }
        }
        
        $isFullyUploaded = false;
        if ($anyEnrollment) {
            $docFields = $anyEnrollment->getDocumentFields();
            $uploadedCount = 0;
            foreach($docFields as $field => $label) {
                if (!empty($anyEnrollment->$field)) {
                    $uploadedCount++;
                }
            }
            $isFullyUploaded = ($uploadedCount === count($docFields));
        }

        // Always use 6-step bar for old students
        $isOldStudentWithMissingDocs = $isOldStudent;

        $steps = [
            'online_docs'         => 'grey',
            'physical_docs'       => 'grey',
            'registrar_clearance' => 'grey',
            'application'         => 'grey',
            'payment'             => 'grey',
            'enroll'              => 'grey'
        ];

        // Build dynamic steps array for Old Students
        $oldStudentStepsKeys = [];

        if ($isOldStudent) {
            // Determine lifetime document completion based on ANY enrollment they've had
            $isOnlineDocsDone = $isFullyUploaded;
            $isPhysicalDocsDone = ($anyEnrollment && $anyEnrollment->physical_documents_received == 1);
            $isCleared = ($anyEnrollment && $anyEnrollment->credentials_verified == 1);

            // Fixed 6-step sequence for Old Students
            $oldStudentStepsKeys = [
                'online_docs',
                'physical_docs',
                'registrar_clearance',
                'application',
                'payment',
                'enroll'
            ];

            // 1. Online Docs
            if ($isOnlineDocsDone) {
                $steps['online_docs'] = 'green';
            } else {
                $steps['online_docs'] = 'yellow';
            }

            // 2. Physical Docs
            // "if the registrar approve the done hard docs the display of the progress bar is done check indicator and it can also proceed the third steps"
            if ($isPhysicalDocsDone) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = 'yellow';
            }

            // 3. Registrar Clearance
            if ($isCleared) {
                $steps['registrar_clearance'] = 'green';
            } else {
                // If they haven't been cleared yet, it's yellow/pending
                $steps['registrar_clearance'] = 'yellow';
            }

            // 4. Application (Fill up form for new term)
            // They can only fill up the form if they are cleared
            if ($steps['registrar_clearance'] === 'green') {
                if ($isEnrollmentForCurrentTerm && in_array($latestEnrollment->status, ['Pending', 'Approved', 'Paid', 'Enrolled'])) {
                    $steps['application'] = 'green';
                } else {
                    $steps['application'] = 'yellow'; // Ready to fill up
                }
            } else {
                $steps['application'] = 'grey'; // Blocked by clearance
            }

            // 5. Payment
            if ($steps['application'] === 'green') {
                if ($isEnrollmentForCurrentTerm && in_array($latestEnrollment->status, ['Paid', 'Enrolled'])) {
                    $steps['payment'] = 'green';
                } else {
                    $steps['payment'] = 'yellow';
                }
            } else {
                $steps['payment'] = 'grey';
            }

            // 6. Enroll
            if ($steps['payment'] === 'green') {
                if ($isEnrollmentForCurrentTerm && $latestEnrollment->status === 'Enrolled') {
                    $steps['enroll'] = 'green';
                } else {
                    $steps['enroll'] = 'yellow';
                }
            } else {
                $steps['enroll'] = 'grey';
            }
        } 
        // 2. Logic for Previous 5-Step Bar (New Students or Fully Cleared Old Students)
        else {
            // For new students, use $anyEnrollment as fallback if $latestEnrollment is null
            $newStudentEnrollment = $latestEnrollment ?? $anyEnrollment;

            // Step 1: Application
            if ($newStudentEnrollment && in_array($newStudentEnrollment->status, ['Pending', 'Approved', 'Paid', 'Enrolled'])) {
                $steps['application'] = 'green';
            } else {
                $steps['application'] = 'yellow';
            }

            // Step 2: Online Docs
            if ($anyEnrollment) {
                $hasGoodMoral = !empty($anyEnrollment->good_moral_path);
                $hasPrimaryDocs = (!empty($anyEnrollment->form_137_path) && !empty($anyEnrollment->psa_path));
                $hasPromissory = !empty($anyEnrollment->promissory_note_path);

                if ($hasGoodMoral || $hasPrimaryDocs || $hasPromissory) {
                    $steps['online_docs'] = 'green';
                } else if ($steps['application'] === 'green') {
                    $steps['online_docs'] = 'yellow';
                }
            } else {
                $steps['online_docs'] = 'grey';
            }

            // Step 3: Physical Documents (depends on Step 1, NOT Step 2)
            // Student can proceed to physical docs even if online docs are not fully uploaded
            if ($newStudentEnrollment && $newStudentEnrollment->physical_documents_received == 1) {
                $steps['physical_docs'] = 'green';
            } else if ($steps['application'] === 'green') {
                $steps['physical_docs'] = 'yellow';
            } else {
                $steps['physical_docs'] = 'grey';
            }

            // Step 4: Cashier Payment
            if ($newStudentEnrollment && in_array($newStudentEnrollment->status, ['Paid', 'Enrolled'])) {
                $steps['payment'] = 'green';
            } else if ($steps['physical_docs'] === 'green') {
                $steps['payment'] = 'yellow';
            } else {
                $steps['payment'] = 'grey';
            }

            // Step 5: Enrolled
            if ($newStudentEnrollment && $newStudentEnrollment->status === 'Enrolled') {
                $steps['enroll'] = 'green';
            } else if ($steps['payment'] === 'green') {
                $steps['enroll'] = 'yellow';
            } else {
                $steps['enroll'] = 'grey';
            }
        }




        return view('livewire.enrollment-progress-bar', [
            'steps'                       => $steps,
            'latestEnrollment'            => $latestEnrollment,
            'isOldStudentWithMissingDocs' => $isOldStudent, // renamed internally to just isOldStudent flag for blade
            'oldStudentStepsKeys'         => $oldStudentStepsKeys ?? [],
        ]);
    }
}
