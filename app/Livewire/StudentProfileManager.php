<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Payment;

class StudentProfileManager extends Component
{
    // Profile Fields
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;

    // Password fields
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = User::find(Auth::id());

        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
        ]);

        $user->first_name = $this->first_name;
        $user->middle_name = $this->middle_name;
        $user->last_name = $this->last_name;
        $user->save();

        session()->flash('profile-updated', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());
        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password-updated', 'Password successfully updated.');
    }

    public function render()
    {
        $user = Auth::user();
        
        // Fetch recent payments for activity feed
        $payments = Payment::where('user_id', $user->id)
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function ($record) {
                        return [
                            'amount' => $record->amount,
                            'date' => $record->created_at->format('M d, Y h:i A'),
                            'status' => $record->status,
                        ];
                    });

        return view('livewire.student-profile-manager', compact('payments'))->layout('components.layouts.student');
    }
}
