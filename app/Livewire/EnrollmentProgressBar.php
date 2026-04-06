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

            if ($allDocsUploaded || $hasPromissory) {
                $steps['online_docs'] = 'green'; // Documents uploaded OR promissory note submitted
            } else {
                $steps['online_docs'] = 'grey'; // No documents uploaded
            }

            // 2. Physical Docs Logic
            if ($latestEnrollment->physical_documents_received || $hasPromissory) {
                $steps['physical_docs'] = 'green';
            } else {
                $steps['physical_docs'] = 'grey';
            }

            // 3. Payment Logic (Checks for any 'Paid' status linked to this application)
            $hasPaid = $latestEnrollment->payments()->where('status', 'Paid')->exists();
            $steps['payment'] = $hasPaid ? 'green' : ($latestEnrollment->status == 'Approved' ? 'yellow' : 'grey');

            // 4. Enroll Logic
            $steps['enroll'] = ($latestEnrollment->status == 'Enrolled') ? 'green' : ($hasPaid ? 'yellow' : 'grey');
        }

        return view('livewire.enrollment-progress-bar', [
            'steps' => $steps,
            'latestEnrollment' => $latestEnrollment,
        ]);
    }
}
