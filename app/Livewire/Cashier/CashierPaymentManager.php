<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;

class CashierPaymentManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $activeDropdown = null;

    public $payment_id;
    public $payment_user_id;
    public $payment_amount = 500;
    public $payment_type = 'Cash';
    public $payment_reference_no = '';

    public $search = '';
    public $filter_course = 'ALL';
    public $status = '';

    public function resetFilters()
    {
        $this->reset(['search', 'filter_course', 'status']);
    }

    public function toggleDropdown($id)
    {
        $this->activeDropdown = $this->activeDropdown === $id ? null : $id;
    }

    public function closeDropdowns()
    {
        $this->activeDropdown = null;
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->isEditMode = false;
        $this->payment_id = null;
        $this->payment_user_id = '';
        $this->payment_amount = 500;
        $this->payment_type = 'Cash';
        $this->payment_reference_no = '';
        $this->showModal = true;
        $this->closeDropdowns();
    }

    public function editPayment($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        $payment = Payment::find($id);
        $this->payment_id = $payment->id;
        $this->payment_user_id = $payment->user_id;
        $this->payment_amount = $payment->amount;
        $this->payment_type = $payment->payment_method ?? 'Cash';
        $this->payment_reference_no = $payment->transaction_id;
        $this->showModal = true;
        $this->closeDropdowns();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function savePayment()
    {
        $this->validate([
            'payment_user_id' => 'required',
            'payment_amount' => 'required|numeric',
        ]);

        if ($this->isEditMode) {
            Payment::find($this->payment_id)->update([
                'user_id' => $this->payment_user_id,
                'amount' => $this->payment_amount,
                'payment_method' => $this->payment_type,
                'transaction_id' => $this->payment_reference_no,
            ]);
            session()->flash('success', 'Payment updated successfully.');
        } else {
            $payment = Payment::create([
                'user_id' => $this->payment_user_id,
                'amount' => $this->payment_amount,
                'payment_method' => $this->payment_type,
                'transaction_id' => $this->payment_reference_no,
                'status' => 'Paid',
                'payment_date' => now(), // Explicitly set the collection date
            ]);

            // Auto-update enrollment status if applicable
            $enrollment = \App\Models\Enrollment::where('user_id', $this->payment_user_id)->latest()->first();
            if ($enrollment) {
                $payment->update(['application_id' => $enrollment->id]);
                $enrollment->update(['status' => 'Enrolled']);
            }

            // Notify Registrars
            $registrars = User::where('role', 'registrar')->get();
            if ($registrars->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($registrars, new \App\Notifications\StudentPaymentConfirmed($payment));
            }

            session()->flash('success', 'Payment processed successfully.');
        }

        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $payment = Payment::with('user')->find($id);
        if ($payment) {
            $payment->update([
                'status' => 'Paid',
                'payment_date' => now()
            ]);

            // Auto-update enrollment status to officialize
            $enrollment = \App\Models\Enrollment::where('user_id', $payment->user_id)->latest()->first();
            if ($enrollment) {
                $payment->update(['application_id' => $enrollment->id]);
                $enrollment->update(['status' => 'Enrolled']);
            }

            // Notify Registrars
            $registrars = User::where('role', 'registrar')->get();
            if ($registrars->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($registrars, new \App\Notifications\StudentPaymentConfirmed($payment));
            }

            session()->flash('success', 'Payment marked as Paid.');
        }
        $this->closeDropdowns();
    }

    public function rejectPayment($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $payment->update([
                'status' => 'Rejected',
                'payment_date' => now()
            ]);

            // Return enrollment to Pending queue
            $enrollment = \App\Models\Enrollment::where('user_id', $payment->user_id)->latest()->first();
            if ($enrollment) {
                $enrollment->update(['status' => 'Pending']);
            }

            session()->flash('success', 'Payment rejected. Enrollment returned to Pending queue.');
        }
        $this->closeDropdowns();
    }

    public function render()
    {
        $query = Payment::with(['user', 'application.course'])
            ->whereHas('application', function ($q) {
                // Show to cashier as long as it's not rejected/deleted (Pending, Approved, or Enrolled)
                $q->whereIn('status', ['Pending', 'Approved', 'Enrolled']);
            })
            ->latest();

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('transaction_id', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Note: Course filtering depends on the application relationship
        if ($this->filter_course !== 'ALL') {
             // Basic matching for course blocks or codes
             $query->whereHas('application', function($q) {
                 $q->where('course_code', $this->filter_course)
                   ->orWhere('year_level', 'like', '%' . $this->filter_course . '%');
             });
        }

        return view('livewire.cashier.cashier-payment-manager', [
            'payments' => $query->paginate(10),
            'students' => User::where('role', 'student')->get(),
        ])->layout('components.layouts.cashier');
    }
}
