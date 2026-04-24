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
            $isOnlineDocsDone = $isFullyUploaded;
            $isPhysicalDocsDone = ($anyEnrollment && $anyEnrollment->physical_documents_received == 1);
            $isCleared = ($anyEnrollment && $anyEnrollment->credentials_verified == 1);

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
            } else if ($anyEnrollment && !empty($anyEnrollment->promissory_note_path)) {
                $steps['online_docs'] = 'ongoing'; // Promissory Note -> Ongoing
            } else {
                $steps['online_docs'] = 'yellow';
            }

            // 2. Physical Docs
            if ($isPhysicalDocsDone) {
                $steps['physical_docs'] = 'green';
            } else if ($anyEnrollment && !empty($anyEnrollment->promissory_note_path)) {
                $steps['physical_docs'] = 'ongoing';
            } else {
                $steps['physical_docs'] = 'yellow';
            }

            // 3. Registrar Clearance
            if ($isCleared) {
                $steps['registrar_clearance'] = 'green';
            } else if ($anyEnrollment && !empty($anyEnrollment->promissory_note_path)) {
                $steps['registrar_clearance'] = 'ongoing';
            } else {
                $steps['registrar_clearance'] = 'yellow';
            }

            // 4. Application
            if ($steps['registrar_clearance'] === 'green' || $steps['registrar_clearance'] === 'ongoing') {
                if ($isEnrollmentForCurrentTerm && in_array($latestEnrollment->status, ['Pending', 'Approved', 'Paid', 'Enrolled'])) {
                    $steps['application'] = 'green';
                } else {
                    $steps['application'] = 'yellow'; 
                }
            } else {
                $steps['application'] = 'grey'; 
            }

            // 5. Payment
            if ($steps['application'] === 'green' || $steps['application'] === 'ongoing') {
                if ($isEnrollmentForCurrentTerm && in_array($latestEnrollment->status, ['Paid', 'Enrolled'])) {
                    $steps['payment'] = 'green';
                } else {
                    $steps['payment'] = 'yellow';
                }
            } else {
                $steps['payment'] = 'grey';
            }

            // 6. Enroll
            if ($steps['payment'] === 'green' || $steps['payment'] === 'ongoing') {
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
                $hasGoodMoral = !empty($anyEnrollment->good_moral_path);
                $hasPrimaryDocs = (!empty($anyEnrollment->form_137_path) && !empty($anyEnrollment->psa_path));
                $hasPromissory = !empty($anyEnrollment->promissory_note_path);

                if ($hasPromissory && !($hasGoodMoral && $hasPrimaryDocs)) {
                    $steps['online_docs'] = 'ongoing'; // Promissory note gives 'Ongoing' yellow check
                } else if ($hasGoodMoral || $hasPrimaryDocs) {
                    $steps['online_docs'] = 'green';
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
