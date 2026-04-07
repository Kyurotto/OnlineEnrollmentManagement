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
        $latestEnrollment = Enrollment::where('user_id', $user->id)->latest()->first();

        $steps = [
            'application'   => $latestEnrollment ? 'green' : 'grey',
            'online_docs'   => 'grey',
            'physical_docs' => 'grey',
            'payment'       => 'grey',
            'enroll'        => 'grey'
        ];

        if ($latestEnrollment) {
            // Build the required document list based on enrollment level
            $level = $latestEnrollment->level;

            if ($level === 'shs') {
                $requiredDocs = ['form_137_path', 'sf10_path', 'good_moral_path', 'psa_path', 'id_picture_path'];
            } else {
                $requiredDocs = ['form_137_path', 'good_moral_path', 'psa_path', 'id_picture_path'];
            }

            $allDocsUploaded = true;
            foreach ($requiredDocs as $doc) {
                if (empty($latestEnrollment->$doc)) {
                    $allDocsUploaded = false;
                    break;
                }
            }

            $hasPromissory = !empty($latestEnrollment->promissory_note_path);

            // ------------------------------------------------------------------
            // Sequential gate flags
            // Each flag must be true before the NEXT step can become yellow/green
            // ------------------------------------------------------------------
            $onlineComplete   = $allDocsUploaded || $hasPromissory;
            $physicalComplete = $latestEnrollment->physical_documents_received || $hasPromissory;
            $hasPaid          = $latestEnrollment->payments()->where('status', 'Paid')->exists();

            // Step 2 — Upload Online Documents
            // yellow = student has not yet uploaded all required documents (PENDING)
            // green  = all required docs uploaded OR promissory note submitted
            $steps['online_docs'] = $onlineComplete ? 'green' : 'yellow';

            // Step 3 — Pass Physical Documents
            // grey   = online docs NOT yet complete → step is locked
            // yellow = online docs done, waiting for registrar to confirm hard docs (PENDING)
            // green  = registrar clicked "Done Hard Docs" OR promissory bypasses this step
            if ($physicalComplete) {
                $steps['physical_docs'] = 'green';
            } elseif ($onlineComplete) {
                $steps['physical_docs'] = 'yellow';
            } else {
                $steps['physical_docs'] = 'grey'; // locked — upload online docs first
            }

            // Step 4 — Pay in Cashier
            // STRICT GATE: physical docs must be green before this step can activate
            // grey   = physical docs not yet confirmed → step is locked
            // yellow = physical docs confirmed AND status is Approved → go pay at cashier
            // green  = cashier has marked the payment as Paid
            if ($hasPaid) {
                $steps['payment'] = 'green';
            } elseif ($physicalComplete && $latestEnrollment->status === 'Approved') {
                $steps['payment'] = 'yellow';
            } else {
                $steps['payment'] = 'grey'; // locked — complete physical docs & get approved first
            }

            // Step 5 — Enroll
            // STRICT GATE: payment must be green before this step can activate
            // grey   = payment not yet completed → step is locked
            // yellow = payment done, waiting for registrar to finalize enrollment
            // green  = enrollent finalized by registrar
            if ($latestEnrollment->status === 'Enrolled') {
                $steps['enroll'] = 'green';
            } elseif ($hasPaid) {
                $steps['enroll'] = 'yellow';
            } else {
                $steps['enroll'] = 'grey'; // locked — complete payment first
            }
        }

        return view('livewire.enrollment-progress-bar', [
            'steps'            => $steps,
            'latestEnrollment' => $latestEnrollment,
        ]);
    }
}
