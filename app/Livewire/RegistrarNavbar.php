<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class RegistrarNavbar extends Component
{
    public function render()
    {
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $newEnrolleesCount = $pendingCount;
        
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.registrar-navbar', [
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
        ]);
    }
}
