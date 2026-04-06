<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Payment;
use App\Models\Enrollment;

class StudentProfileManager extends Component
{
    // Context for conditional rendering
    public $context = 'profile';

    // Enrollment edit request state
    public $enrollmentEditRequestStatus = 'None';

    // Profile Fields
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;

    // Password fields
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount($context = 'profile')
    {
        $this->context = $context;
        $user = Auth::user();
        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;

        // Load enrollment edit request status for the enrollment-actions context
        $enrollment = Enrollment::where('user_id', Auth::id())->first();
        $this->enrollmentEditRequestStatus = $enrollment?->edit_request_status ?? 'None';
    }

    public function requestEdit()
    {
        $enrollment = Enrollment::where('user_id', Auth::id())->first();

        if (!$enrollment) {
            session()->flash('edit-request-error', 'No active enrollment record found.');
            return;
        }

        if ($this->enrollmentEditRequestStatus === 'Pending') {
            return;
        }

        $enrollment->edit_request_status = 'Pending';
        $enrollment->save();

        // Notify Admins and Registrars
        $staff = User::whereIn('role', ['admin', 'registrar'])->get();
        if ($staff->count() > 0) {
            Notification::send($staff, new \App\Notifications\EditEnrollmentRequested($enrollment));
        }

        $this->enrollmentEditRequestStatus = 'Pending';
        session()->flash('edit-requested', 'Edit request sent to registrar. Please wait for approval.');
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
