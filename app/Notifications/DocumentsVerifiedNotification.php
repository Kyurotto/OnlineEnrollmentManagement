<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentsVerifiedNotification extends Notification
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
            'message' => 'Your documents have been verified. Your application is now moving to the next stage of enrollment.',
            'action' => 'view_enrollment',
        ];
    }
}
