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
        
        $isFullyUploaded = false;
        $isPartiallyUploaded = false;
        if ($anyEnrollment) {
            $docFields = $anyEnrollment->getDocumentFields();
            $uploadedCount = 0;
            foreach($docFields as $field => $label) {
                if (!empty($anyEnrollment->$field)) {
                    $uploadedCount++;
                }
            }
            $isFullyUploaded = ($uploadedCount === count($docFields));
            $isPartiallyUploaded = ($uploadedCount > 0 && !$isFullyUploaded);
        }

        $isOldStudentWithMissingDocs = $isOldStudent;

        $steps = [
            'online_docs'         => 'grey',
            'physical_docs'       => 'grey',
            'registrar_clearance' => 'grey',
            'application'         => 'grey',
            'payment'             => 'grey',
            'enroll'              => 'grey'
        ];

        $oldStudentStepsKeys = [];

        if ($isOldStudent) {
            // For Old Students, we check if they have a record for the CURRENT term
            // If they don't, we start them fresh at Step 1 of the new cycle
            $currentRecord = $isEnrollmentForCurrentTerm ? $latestEnrollment : null;
            
            $isOnlineDocsDone = false;
            $isPartiallyDone = false;
            if ($anyEnrollment) {
                $docFields = $anyEnrollment->getDocumentFields();
                $uploaded = 0;
                foreach($docFields as $f => $l) { if(!empty($anyEnrollment->$f)) $uploaded++; }
                $isOnlineDocsDone = ($uploaded === count($docFields));
                $isPartiallyDone = ($uploaded > 0);
            }

            $isPhysicalDocsDone = ($currentRecord && $currentRecord->physical_documents_received == 1);
            $isCleared = ($currentRecord && $currentRecord->credentials_verified == 1);

            $oldStudentStepsKeys = [
                'online_docs',
                'physical_docs',
                'registrar_clearance',
                'application',
                'payment',
                'enroll'
            ];

            // 1. Online Docs - Can be "Pending" (ongoing) but doesn't block Step 2/3
            if ($isOnlineDocsDone) {
                $steps['online_docs'] = 'green';
            } else if ($isPartiallyDone) {
                $steps['online_docs'] = 'ongoing'; // Shows as Pending/In-Progress
            } else {
                $steps['online_docs'] = 'yellow';
            }

            // 2. Physical Docs
            if ($isPhysicalDocsDone) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = 'yellow'; // Always ready to receive
            }

            // 3. Registrar Clearance (Step 3) - The gate for Step 4
            if ($isCleared) {
                $steps['registrar_clearance'] = 'green';
            } else {
                // Step 3 is Pending as long as Step 1 is started
                $steps['registrar_clearance'] = ($isPartiallyDone || $isOnlineDocsDone) ? 'yellow' : 'grey';
            }

            // 4. Application (Step 4) - ONLY if Step 3 is GREEN
            if ($steps['registrar_clearance'] === 'green') {
                // Check if they have ALREADY filled out the course selection (FULL form)
                $hasFilledForm = ($currentRecord && !empty($currentRecord->course_code));
                
                if ($hasFilledForm) {
                    $steps['application'] = 'green';
                } else {
                    $steps['application'] = 'yellow'; // Ready to fill up
                }
            } else {
                $steps['application'] = 'grey'; 
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
        else {
            $newStudentEnrollment = $latestEnrollment ?? $anyEnrollment;

            // Step 1: Application
            if ($newStudentEnrollment && in_array($newStudentEnrollment->status, ['Pending', 'Approved', 'Paid', 'Enrolled'])) {
                $steps['application'] = 'green';
            } else {
                $steps['application'] = 'yellow';
            }

            // Step 2: Online Docs
            if ($anyEnrollment) {
                if ($isFullyUploaded) {
                    $steps['online_docs'] = 'green';
                } else if ($isPartiallyUploaded || !empty($anyEnrollment->promissory_note_path)) {
                    $steps['online_docs'] = 'ongoing';
                } else if ($steps['application'] === 'green') {
                    $steps['online_docs'] = 'yellow';
                }
            } else {
                $steps['online_docs'] = 'grey';
            }

            // Step 3: Physical Documents
            if ($newStudentEnrollment && $newStudentEnrollment->physical_documents_received == 1) {
                $steps['physical_docs'] = 'green';
            } else if ($anyEnrollment && !empty($anyEnrollment->promissory_note_path)) {
                $steps['physical_docs'] = 'ongoing';
            } else if ($steps['application'] === 'green') {
                $steps['physical_docs'] = 'yellow';
            } else {
                $steps['physical_docs'] = 'grey';
            }

            // Step 4: Cashier Payment
            if ($newStudentEnrollment && in_array($newStudentEnrollment->status, ['Paid', 'Enrolled'])) {
                $steps['payment'] = 'green';
            } else if ($steps['physical_docs'] === 'green' || $steps['physical_docs'] === 'ongoing') {
                $steps['payment'] = 'yellow';
            } else {
                $steps['payment'] = 'grey';
            }

            // Step 5: Enrolled
            if ($newStudentEnrollment && $newStudentEnrollment->status === 'Enrolled') {
                $steps['enroll'] = 'green';
            } else if ($steps['payment'] === 'green' || $steps['payment'] === 'ongoing') {
                $steps['enroll'] = 'yellow';
            } else {
                $steps['enroll'] = 'grey';
            }
        }

        return view('livewire.enrollment-progress-bar', [
            'steps'                       => $steps,
            'latestEnrollment'            => $latestEnrollment,
            'isOldStudentWithMissingDocs' => $isOldStudent,
            'oldStudentStepsKeys'         => $oldStudentStepsKeys ?? [],
        ]);
    }
}
