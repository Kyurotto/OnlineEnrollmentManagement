<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewEnrollmentSubmitted extends Notification
{
    use Queueable;

    public $enrollment;

    public function __construct($enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function via($notifiable)
    {
        return ['database']; // Stores in 'notifications' table
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->enrollment->first_name . ' ' . $this->enrollment->last_name . ' has submitted an enrollment application.',
            'enrollment_id' => $this->enrollment->id,
            'student_id' => $this->enrollment->user_id,
            'level' => $this->enrollment->level,
            'time' => now(),
        ];
    }
}
