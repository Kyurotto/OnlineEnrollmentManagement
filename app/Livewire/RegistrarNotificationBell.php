<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class RegistrarNotificationBell extends Component
{
    public $showDropdown = false;

    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function loadNotifications()
    {
        // Triggered by frontend
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        $this->dispatch('refreshNotifications');
    }

    public function markAndNavigate($id, $url)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($url);
    }

    public function render()
    {
        $notifications = Auth::user() ? Auth::user()->unreadNotifications()->take(5)->get() : collect();

        $unreadCount = Auth::user() ? Auth::user()->unreadNotifications->count() : 0;

        return view('livewire.registrar-notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
