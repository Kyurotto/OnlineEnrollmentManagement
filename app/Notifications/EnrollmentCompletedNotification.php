<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EnrollmentCompletedNotification extends Notification
{
    use Queueable;

    protected $enrollmentId;

    public function __construct($enrollmentId)
    {
        $this->enrollmentId = $enrollmentId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'enrollment_id' => $this->enrollmentId,
            'message' => 'Congratulations! Your enrollment process is now complete, and you are officially registered for the term.',
            'action' => 'view_dashboard',
        ];
    }
}
