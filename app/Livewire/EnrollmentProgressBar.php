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
            // grey   = online docs NOT yet complete (unless promissory note is uploaded)
            // yellow = online docs done (or promissory), waiting for registrar to confirm hard docs (PENDING)
            // green  = registrar clicked "Done Hard Docs"
            if ($physicalComplete) {
                $steps['physical_docs'] = 'green';
            } elseif ($onlineComplete || $hasPromissory) {
                $steps['physical_docs'] = 'yellow';
            } else {
                $steps['physical_docs'] = 'grey'; // locked — upload online docs first
            }

            // Step 4 — Pay in Cashier
            // STRICT GATE: physical docs must be green before this step can activate (or bypassed via promissory)
            // grey   = physical docs not yet confirmed and no promissory note → step is locked
            // yellow = physical docs confirmed (or promissory) AND status is Approved → go pay at cashier
            // green  = cashier has marked the payment as Paid
            if ($hasPaid) {
                $steps['payment'] = 'green';
            } elseif (($physicalComplete || $hasPromissory) && $latestEnrollment->status === 'Approved') {
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
