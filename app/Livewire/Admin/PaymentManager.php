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
    // Reset pagination when filters change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCourse() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

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
