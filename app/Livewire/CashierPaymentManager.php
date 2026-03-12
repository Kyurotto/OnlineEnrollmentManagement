<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use App\Notifications\StudentPaymentConfirmed;
use Illuminate\Support\Facades\Notification;

class CashierPaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_course = 'ALL';
    public $status = 'All statuses';

    // Modal state
    public $showModal = false;
    public $isEditMode = false;

    // Form fields
    public $paymentId;
    public $user_id = '';
    public $amount = 500;
    public $payment_type = 'Cash';
    public $reference_no = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCourse() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->isEditMode = false;
        $this->paymentId = null;
        $this->user_id = '';
        $this->amount = 500;
        $this->payment_type = 'Cash';
        $this->reference_no = '';
        $this->showModal = true;
    }

    public function editPayment($id)
    {
        $this->resetValidation();
        $payment = Payment::findOrFail($id);
        
        $this->isEditMode = true;
        $this->paymentId = $payment->id;
        $this->user_id = $payment->user_id;
        $this->amount = $payment->amount;
        $this->payment_type = $payment->payment_method ?? 'Cash';
        $this->reference_no = $payment->transaction_id;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function savePayment()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'nullable|string',
            'payment_type' => 'required|string',
        ]);

        if ($this->isEditMode) {
            $payment = Payment::findOrFail($this->paymentId);
            $payment->update([
                'user_id' => $this->user_id,
                'amount' => $this->amount,
                'payment_method' => $this->payment_type,
                'transaction_id' => $this->reference_no,
            ]);
            session()->flash('success', 'Payment details updated successfully.');
        } else {
            $latestEnrollment = Enrollment::where('user_id', $this->user_id)->latest()->first();

            $payment = Payment::create([
                'user_id' => $this->user_id,
                'application_id' => $latestEnrollment ? $latestEnrollment->id : null,
                'amount' => $this->amount,
                'transaction_id' => $this->reference_no ?? 'CASH-' . time(),
                'status' => 'Paid', 
                'payment_method' => $this->payment_type,
            ]);

            if ($payment->application_id) {
                Enrollment::where('id', $payment->application_id)->update(['status' => 'Enrolled']);
            }

            // Notification
            $recipients = User::whereIn('role', ['registrar', 'admin'])->get();
            if($recipients->count() > 0){
                Notification::send($recipients, new StudentPaymentConfirmed($payment));
            }

            session()->flash('success', 'Payment of ₱' . number_format($this->amount, 2) . ' processed successfully.');
        }

        $this->closeModal();
    }

    public function markAsPaid($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'Paid']);

        if ($payment->application_id) {
            Enrollment::where('id', $payment->application_id)->update(['status' => 'Enrolled']);
        }

        $recipients = User::whereIn('role', ['registrar', 'admin'])->get();
        if($recipients->count() > 0){
            Notification::send($recipients, new StudentPaymentConfirmed($payment));
        }

        session()->flash('success', 'Payment status updated to Paid');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter_course = 'ALL';
        $this->status = 'All statuses';
        $this->resetPage();
    }

    public function render()
    {
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id') 
            ->with(['user', 'application']); 

        if ($this->status != 'All statuses') {
            $query->where('payments.status', $this->status);
        }

        if ($this->filter_course != 'ALL') {
            $filter = $this->filter_course;
            if (str_contains($filter, '-')) {
                $parts = explode('-', $filter);
                if(count($parts) >= 2) {
                    $courseCode = $parts[0];
                    $yearDigit = $parts[1];
                    $suffix = match($yearDigit) { '1' => 'st', '2' => 'nd', '3' => 'rd', default => 'th' };
                    $yearString = $yearDigit . $suffix . ' Year'; 
                    $query->where('enrollments.course_code', $courseCode)
                          ->where('enrollments.year_level', 'like', $yearString . '%');
                }
            } else {
                $query->where('enrollments.course_code', $filter);
            }
        }

        if ($this->search != '') {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                  ->orWhere('payments.transaction_id', 'like', "%{$search}%") 
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('payments.id', 'desc')->paginate(10);
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('livewire.cashier-payment-manager', compact('payments', 'students'))->layout('components.layouts.cashier');
    }
}
