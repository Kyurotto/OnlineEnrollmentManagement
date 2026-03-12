<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class Navbar extends Component
{
    public function render()
    {
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        foreach($notifications as $notif) {
            if($notif->status === 'Enrolled') {
                $payment = Payment::where('application_id', $notif->id)->first();
                $notif->paid_amount = $payment ? $payment->amount : 0;
            }
        }

        return view('livewire.navbar', [
            'pendingCount' => $pendingCount,
            'notifications' => $notifications,
        ]);
    }
}
