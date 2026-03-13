<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class Navbar extends Component
{
    public $showDropdown = false;

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $newEnrolleesCount = $pendingCount;
        
        $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.admin.navbar', [
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
        ]);
    }
}