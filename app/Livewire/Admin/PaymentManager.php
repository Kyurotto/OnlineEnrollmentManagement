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

class PaymentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_course = 'ALL';
    public $status = 'All statuses';
    public $sortField = 'payments.id';
    public $sortDirection = 'desc';
    public $level = null;

    private const SHS_STRANDS = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];

    public function mount()
    {
        if (request()->routeIs('admin.payments.college')) {
            $this->level = 'college';
        } elseif (request()->routeIs('admin.payments.shs')) {
            $this->level = 'shs';
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filter_course', 'status']);
        $this->resetPage();
    }

    // Reset pagination when filters change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCourse() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public $showModal = false;
    public $isEditMode = false;
    public $selectedPaymentId;

    // Form fields
    public $user_id;
    public $amount;
    public $payment_type = 'Cash';
    public $reference_no;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:1',
        'payment_type' => 'required|string',
        'reference_no' => 'nullable|string',
    ];

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['user_id', 'amount', 'reference_no', 'payment_type', 'isEditMode', 'selectedPaymentId']);
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $payment = Payment::findOrFail($id);
        $this->selectedPaymentId = $id;
        $this->user_id = $payment->user_id;
        $this->amount = $payment->amount;
        $this->payment_type = $payment->payment_method ?? 'Cash';
        $this->reference_no = $payment->transaction_id;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['user_id', 'amount', 'reference_no', 'payment_type', 'isEditMode', 'selectedPaymentId']);
    }

    public function store()
    {
        $this->validate();

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
            Enrollment::where('id', $payment->application_id)->update([
                'status' => 'Paid',
                'updated_at' => now(),
            ]);
        }

        // Notifications
        $student = User::find($this->user_id);
        if($student){
            $student->notify(new StudentPaymentConfirmed($payment));
        }

        $this->closeModal();
        session()->flash('success', 'Payment processed successfully.');
    }

    public function update()
    {
        $this->validate();

        $payment = Payment::findOrFail($this->selectedPaymentId);
        $payment->update([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_type,
            'transaction_id' => $this->reference_no,
        ]);

        $this->closeModal();
        session()->flash('success', 'Payment updated successfully.');
    }

    public function updateStatus($id, $status)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $status]);

        if ($status === 'Paid') {
            if ($payment->application_id) {
                Enrollment::where('id', $payment->application_id)->update([
                    'status' => 'Paid',
                    'updated_at' => now(),
                ]);
            }

            $student = User::find($payment->user_id);
            if($student){
                $student->notify(new StudentPaymentConfirmed($payment));
            }
        }

        session()->flash('success', "Payment status updated to $status.");
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        session()->flash('success', 'Payment record deleted.');
    }

    public function render()
    {
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->with(['user', 'application']);

        // Filter by level (SHS or College)
        if ($this->level === 'shs') {
            $query->whereIn('enrollments.course_code', self::SHS_STRANDS);
        } elseif ($this->level === 'college') {
            $query->whereNotIn('enrollments.course_code', self::SHS_STRANDS);
        }

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
                    $u->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%");
                });
            });
        }

        $payments = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
        $students = User::where('role', 'student')->orderBy('name')->get();

        // Build dynamic program options based on level
        $programOptions = collect();
        if ($this->level === 'shs') {
            $programOptions = \App\Models\Course::where('type', 'shs')->get();
        } elseif ($this->level === 'college') {
            $programOptions = \App\Models\Course::where('type', 'program')->get();
        } else {
            $programOptions = \App\Models\Course::get();
        }

        // Determine the page title
        $pageTitle = match($this->level) {
            'shs' => 'SHS Payment Management',
            'college' => 'College Payment Management',
            default => 'Payment Management',
        };

        return view('livewire.admin.admin-payment-manager', [
            'payments' => $payments,
            'students' => $students,
            'programOptions' => $programOptions,
            'pageTitle' => $pageTitle,
        ])->layout('components.layouts.admin', ['title' => $pageTitle]);
    }
}
