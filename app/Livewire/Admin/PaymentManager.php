<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StudentPaymentConfirmed;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class PaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_course = 'ALL';
    public $status = 'All statuses';
    public $showModal = false;
    public $activeDropdown = null;

    // Form fields for Edit/Create Modal
    public $payment_user_id;
    public $payment_amount;
    public $payment_type = 'Cash';
    public $payment_reference_no;

    public $isEditMode = false;

    // Reset pagination when filters change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCourse() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function toggleDropdown($id)
    {
        $this->activeDropdown = ($this->activeDropdown === $id) ? null : $id;
    }

    public function closeDropdowns()
    {
        $this->activeDropdown = null;
    }

    public function openCreateModal()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function savePayment()
    {
        if ($this->isEditMode) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function store()
    {
        $this->validate([
            'payment_user_id' => 'required|exists:users,id',
            'payment_amount' => 'required|numeric|min:1',
            'payment_reference_no' => 'nullable|string',
            'payment_type' => 'required|string',
        ]);

        $latestEnrollment = Enrollment::where('user_id', $this->payment_user_id)->latest()->first();

        $payment = Payment::create([
            'user_id' => $this->payment_user_id,
            'application_id' => $latestEnrollment ? $latestEnrollment->id : null,
            'amount' => $this->payment_amount,
            'transaction_id' => $this->payment_reference_no ?? 'CASH-' . time(),
            'status' => 'Paid', 
            'payment_method' => $this->payment_type,
        ]);

        if ($payment->application_id) {
            Enrollment::where('id', $payment->application_id)->update([
                'status' => 'Enrolled',
                'updated_at' => now(),
            ]);
        }

        $registrars = User::where('role', 'registrar')->get();
        if($registrars->count() > 0){
            Notification::send($registrars, new StudentPaymentConfirmed($payment));
        }

        $this->showModal = false;
        session()->flash('success', 'Payment processed successfully.');
        $this->resetFields();
    }

    public function editPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $this->payment_id = $id;
        $this->payment_user_id = $payment->user_id;
        $this->payment_amount = $payment->amount;
        $this->payment_type = $payment->payment_method;
        $this->payment_reference_no = $payment->transaction_id;
        $this->isEditMode = true;
        $this->showModal = true;
        $this->activeDropdown = null;
    }

    public function update()
    {
        $payment = Payment::findOrFail($this->payment_id);

        $this->validate([
            'payment_amount' => 'required|numeric|min:0', 
            'payment_type' => 'required|string',
            'payment_reference_no' => 'nullable|string',
            'payment_user_id' => 'required|exists:users,id',
        ]);

        $payment->update([
            'user_id' => $this->payment_user_id,
            'amount' => $this->payment_amount,
            'payment_method' => $this->payment_type,
            'transaction_id' => $this->payment_reference_no,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Payment details updated successfully.');
        $this->resetFields();
    }

    public function updateStatus($id, $newStatus)
    {
        $payment = Payment::findOrFail($id);
        
        $payment->update(['status' => $newStatus]);

        if ($newStatus === 'Paid') {
            if ($payment->application_id) {
                Enrollment::where('id', $payment->application_id)->update([
                    'status' => 'Enrolled',
                    'updated_at' => now(),
                ]);
            }

            $registrars = User::where('role', 'registrar')->get();
            if($registrars->count() > 0){
                Notification::send($registrars, new StudentPaymentConfirmed($payment));
            }
        }

        $this->activeDropdown = null;
        session()->flash('success', 'Payment status updated to ' . $newStatus);
    }

    public function markAsPaid($id)
    {
        $this->updateStatus($id, 'Paid');
    }

    public function prepareCreate()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    private function resetFields()
    {
        $this->payment_id = null;
        $this->payment_user_id = '';
        $this->payment_amount = '';
        $this->payment_type = 'Cash';
        $this->payment_reference_no = '';
        $this->isEditMode = false;
        $this->showModal = false;
        $this->activeDropdown = null;
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

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                ->orWhere('payments.transaction_id', 'like', "%{$search}%") 
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('users.first_name', 'like', "%{$search}%")
                        ->orWhere('users.last_name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%");
                });
            });
        }

        $payments = $query->orderBy('payments.id', 'desc')->paginate(10);
        $students = User::where('role', 'student')->orderBy('first_name')->get();

        return view('admin.payments.index', [
            'payments' => $payments,
            'students' => $students
        ]);
    }
}
