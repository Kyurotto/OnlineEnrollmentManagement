<?php

namespace App\Livewire\Registrar;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class RegistrarNavbar extends Component
{
    public $showDropdown = false;
    public $showMobileMenu = false;
    public $currentRoute;

    public function mount()
    {
        $this->currentRoute = request()->route() ? request()->route()->getName() : null;
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function toggleMobileMenu()
    {
        $this->showMobileMenu = !$this->showMobileMenu;
    }
    public function render()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if (!$user) return view('livewire.registrar.registrar-navbar');

        $pendingCount = Enrollment::where('status', 'Pending')->count();
        $unreadNotifCount = $user->unreadNotifications()->count();
        $displayCount = $pendingCount + $unreadNotifCount;
        
        // 1. Get real DB notifications (from 'notifications' table)
        $dbNotifications = $user->unreadNotifications()->latest()->take(5)->get();

        // 2. Get enrollment-based alerts (existing logic)
        $enrollmentAlerts = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.registrar.registrar-navbar', [
            'newEnrolleesCount' => $displayCount,
            'dbNotifications' => $dbNotifications,
            'enrollmentAlerts' => $enrollmentAlerts,
        ]);
    }
}
