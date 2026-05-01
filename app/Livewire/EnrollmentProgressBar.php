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
        
        $isOldStudent = ($allEnrollmentsCount > 1) || 
                        ($allEnrollmentsCount === 1 && $anyEnrollment && 
                            $activeYear && $activeSemester && 
                            (stripos((string)$anyEnrollment->year_level, $activeYear->year_name) === false || 
                             stripos((string)$anyEnrollment->year_level, $activeSemester->name) === false));
        
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
            // If they don't, we look at their absolute latest record (the one from previous term being processed)
            $currentRecord = $isEnrollmentForCurrentTerm ? $latestEnrollment : $anyEnrollment;
            
            // 1. Online Docs - Check across ALL enrollments for returning students
            // We consider it done if they have EVER uploaded all required docs in any record
            $isOnlineDocsDone = false;
            $isPartiallyDone = false;
            
            // Check all records for any uploaded documents
            $allUserEnrollments = Enrollment::where('user_id', $user->id)->get();
            $uploadedFields = [];
            
            foreach ($allUserEnrollments as $rec) {
                foreach ($docFields as $f => $l) {
                    if (!empty($rec->$f)) {
                        $uploadedFields[$f] = true;
                    }
                }
            }
            
            $isOnlineDocsDone = (count($uploadedFields) === count($docFields));
            $isPartiallyDone = (count($uploadedFields) > 0);
            
            // 2. Physical Docs & Clearance
            // These should NOT reset after submitting a new term application.
            // We check if they have EVER been received/verified.
            $isPhysicalDocsDone = $allUserEnrollments->contains('physical_documents_received', 1);
            $isCleared = $allUserEnrollments->contains('credentials_verified', 1);

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
            } else if ($isPartiallyDone) {
                $steps['online_docs'] = 'ongoing';
            } else {
                $steps['online_docs'] = 'yellow';
            }

            // 2. Physical Docs
            if ($isPhysicalDocsDone) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = 'yellow';
            }

            // 3. Registrar Clearance
            if ($isCleared) {
                $steps['registrar_clearance'] = 'green';
            } else {
                // Registrar Clearance is pending (yellow) for old students
                $steps['registrar_clearance'] = 'yellow';
            }

            // 4. Application
            // Requirement: Must be cleared (Step 3 = green) to proceed to Step 4
            // MUST be for the CURRENT term to be considered done
            if ($steps['registrar_clearance'] === 'green') {
                $hasFilledFormForCurrentTerm = ($latestEnrollment && !empty($latestEnrollment->course_code));
                if ($hasFilledFormForCurrentTerm) {
                    $steps['application'] = 'green';
                } else {
                    $steps['application'] = 'yellow';
                }
            } else {
                $steps['application'] = 'grey'; 
            }

            // 5. Payment
            // Requirement: Must have filled form (Step 4 = green)
            if ($steps['application'] === 'green') {
                $isPaid = ($latestEnrollment && $latestEnrollment->status === 'Paid');
                $steps['payment'] = $isPaid ? 'green' : 'yellow';
            } else {
                $steps['payment'] = 'grey';
            }

            // 6. Enroll
            if ($steps['payment'] === 'green') {
                $isEnrolled = ($latestEnrollment && $latestEnrollment->status === 'Enrolled');
                $steps['enroll'] = $isEnrolled ? 'green' : 'yellow';
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
