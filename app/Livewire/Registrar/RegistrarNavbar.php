<?php

namespace App\Livewire\Registrar;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class RegistrarNavbar extends Component
{
    public $showDropdown = false;
    public $currentRoute;

    public function mount()
    {
        $this->currentRoute = request()->route() ? request()->route()->getName() : null;
    }

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

        return view('livewire.registrar.registrar-navbar', [
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
        ]);
    }
}
