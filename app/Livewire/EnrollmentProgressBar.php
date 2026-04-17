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
            $level = $latestEnrollment->getLevel();

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
            $onlineComplete   = $allDocsUploaded;
            $physicalComplete = $latestEnrollment->physical_documents_received == 1;
            $hasPaid          = $latestEnrollment->payments()->where('status', 'Paid')->exists();

            // Step 2 — Upload Online Documents
            // yellow = student has not yet uploaded all required documents (PENDING)
            // green  = all required docs uploaded
            // Note: If they have a promissory note, it allows them to proceed to Step 3, but Step 2 remains pending.
            if ($onlineComplete) {
                $steps['online_docs'] = 'green';
            } else {
                $steps['online_docs'] = 'yellow';
            }

            // Step 3 — Pass Physical Documents
            // This is parallel to Step 2, so it always shows pending until registrar confirms
            if ($physicalComplete) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = 'yellow'; // pending until registrar confirms
            }

            // Step 4 — Pay in Cashier
            // STRICT GATE: physical docs must be GREEN before this step can turn yellow/green
            // Note: physical docs being green overrides the need for online docs to be green
            if ($steps['physical_docs'] === 'green') {
                if ($hasPaid) {
                    $steps['payment'] = 'green';
                } else {
                    $steps['payment'] = 'yellow'; // pending until cashier marks as paid
                }
            } else {
                $steps['payment'] = 'grey'; // locked until both online & physical docs are explicitly done
            }

            // Step 5 — Enroll
            // STRICT GATE: payment must be GREEN before this step can turn yellow/green
            if ($steps['payment'] === 'green') {
                if ($latestEnrollment->status === 'Enrolled') {
                    $steps['enroll'] = 'green';
                } else {
                    $steps['enroll'] = 'yellow';
                }
            } else {
                $steps['enroll'] = 'grey'; // locked until payment is complete
            }
        }

        return view('livewire.enrollment-progress-bar', [
            'steps'            => $steps,
            'latestEnrollment' => $latestEnrollment,
        ]);
    }
}
