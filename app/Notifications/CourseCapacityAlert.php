<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseCapacityAlert extends Notification
{
    use Queueable;

    protected $courseName;
    protected $percentage;

    public function __construct($courseName, $percentage)
    {
        $this->courseName = $courseName;
        $this->percentage = $percentage;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Course Capacity Alert: ' . $this->courseName)
            ->greeting('Hello Admin,')
            ->line("This is an automated alert to inform you that demand for the course <strong>{$this->courseName}</strong> has reached <strong>" . round($this->percentage, 2) . "%</strong> of its total capacity.")
            ->line('You may want to consider opening additional sections to accommodate the demand.')
            ->action('View Course Demand Report', url('/admin/reports/course-demand'))
            ->line('Thank you for your proactive management!');
    }

    public function toArray($notifiable)
    {
        return [
            'course_name' => $this->courseName,
            'percentage' => round($this->percentage, 2),
            'message' => "Demand for {$this->courseName} has reached " . round($this->percentage, 2) . "% of its total capacity.",
        ];
    }
}
