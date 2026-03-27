<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use App\Notifications\StudentPaymentConfirmed;
use Illuminate\Support\Facades\Notification;

class PaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCourse = 'ALL';
    public $statusFilter = 'All statuses';
    
    public $showModal = false;
    public $isEditMode = false;
    public $editingPaymentId = null;

    // Form fields
    public $user_id;
    public $amount;
    public $payment_type = 'Cash';
    public $reference_no;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCourse' => ['except' => 'ALL'],
        'statusFilter' => ['except' => 'All statuses'],
        'showModal' => ['except' => false, 'as' => 'showModal'],
        'editingPaymentId' => ['except' => null, 'as' => 'edit_id'],
    ];

    public function mount()
    {
        if (request()->has('showModal') && request('showModal') === 'true') {
            $this->openCreateModal();
        }
        if (request()->has('edit_id')) {
            $this->openEditModal(request('edit_id'));
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCourse()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterCourse', 'statusFilter']);
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['user_id', 'amount', 'payment_type', 'reference_no', 'isEditMode', 'editingPaymentId']);
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $payment = Payment::findOrFail($id);
        $this->editingPaymentId = $id;
        $this->user_id = $payment->user_id;
        $this->amount = $payment->amount;
        $this->payment_type = $payment->payment_method;
        $this->reference_no = $payment->transaction_id;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['user_id', 'amount', 'payment_type', 'reference_no', 'isEditMode', 'editingPaymentId']);
    }

    public function store()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'nullable|string',
            'payment_type' => 'required|string',
        ]);

        $latestEnrollment = Enrollment::where('user_id', $this->user_id)->latest()->first();

        $payment = Payment::create([
            'user_id' => $this->user_id,
            'application_id' => $latestEnrollment ? $latestEnrollment->id : null,
            'amount' => $this->amount,
            'transaction_id' => $this->reference_no ?? 'CASH-' . time(),
            'status' => 'Paid', 
            'payment_method' => $this->payment_type,
            'payment_date' => now(),
        ]);

        if ($payment->application_id) {
            Enrollment::where('id', $payment->application_id)->update(['status' => 'Paid']);
        }

        $this->notifyRecipients($payment);

        $this->closeModal();
        session()->flash('success', 'Payment of ₱' . number_format($this->amount, 2) . ' processed successfully.');
    }

    public function update()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0', 
            'payment_type' => 'required|string',
            'reference_no' => 'nullable|string',
        ]);

        $payment = Payment::findOrFail($this->editingPaymentId);
        $payment->update([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_type,
            'transaction_id' => $this->reference_no,
        ]);

        $this->closeModal();
        session()->flash('success', 'Payment details updated successfully.');
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['Paid', 'Rejected'])) return;

        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'status' => $status,
            'payment_date' => $status === 'Paid' ? now() : $payment->payment_date
        ]);

        if ($status === 'Paid') {
            if ($payment->application_id) {
                Enrollment::where('id', $payment->application_id)->update(['status' => 'Paid']);
            }
            $this->notifyRecipients($payment);
        }

        session()->flash('success', 'Payment status updated to ' . $status);
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        session()->flash('success', 'Payment record deleted.');
    }

    private function notifyRecipients($payment)
    {
        $staff = User::whereIn('role', ['registrar', 'admin'])->get();
        $student = User::find($payment->user_id);
        
        $recipients = $staff->push($student)->filter();

        if($recipients->count() > 0){
            Notification::send($recipients, new StudentPaymentConfirmed($payment));
        }
    }

    public function render()
    {
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id') 
            ->with(['user', 'application']); 

        if ($this->statusFilter != 'All statuses') {
            $query->where('payments.status', $this->statusFilter);
        }

        if ($this->filterCourse != 'ALL') {
            $filter = $this->filterCourse;
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
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('payments.id', 'like', $searchTerm)
                  ->orWhere('payments.transaction_id', 'like', $searchTerm) 
                  ->orWhereHas('user', function($u) use ($searchTerm) {
                      $u->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        $payments = $query->orderBy('payments.id', 'desc')->paginate(10);
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('livewire.cashier-payment-manager', [
            'payments' => $payments,
            'students' => $students,
        ])->layout('components.layouts.cashier', ['title' => 'Manage Payments']);
    }
}
