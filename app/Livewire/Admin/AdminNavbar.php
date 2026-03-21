<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Enrollment;
use App\Models\Payment;

class AdminNavbar extends Component
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
        try {
            $newEnrolleesCount = Enrollment::where('status', 'Pending')->count();
            
            $notifications = Enrollment::whereIn('status', ['Pending', 'Enrolled'])
                ->with('user')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $newEnrolleesCount = 0;
            $notifications = collect();
        }

        return view('livewire.admin.admin-navbar', [
            'newEnrolleesCount' => $newEnrolleesCount,
            'notifications' => $notifications,
        ]);
    }
}
