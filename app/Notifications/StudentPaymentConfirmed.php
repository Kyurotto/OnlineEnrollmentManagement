<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StudentPaymentConfirmed extends Notification
{
    use Queueable;

    public $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database']; // Stores in 'notifications' table
    }

    public function toArray($notifiable)
    {
        $amount = number_format($this->payment->amount, 2);
        
        // For Student
        if ($notifiable->role === 'student') {
            $message = "Your payment of ₱{$amount} has been confirmed. You are now officially enrolled!";
        } else {
            // For Registrar/Admin
            $message = $this->payment->user->name . " has paid ₱{$amount}.";
        }

        return [
            'message' => $message,
            'payment_id' => $this->payment->id,
            'student_id' => $this->payment->user_id,
            'time' => now(),
        ];
    }
}