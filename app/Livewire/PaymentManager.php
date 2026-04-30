<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use App\Notifications\StudentPaymentConfirmed;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;

class PaymentManager extends Component
{
    use WithPagination;

    protected $listeners = ['assessmentUpdated' => 'refreshStudentData'];

    public $search = '';
    public $filterCourse = 'ALL';
    public $statusFilter = 'All statuses';
    public $level = null;

    public $showModal = false;
    public $isEditMode = false;
    public $editingPaymentId = null;

    // New properties for sidebar layout
    public $activeTab = 'assessment';
    public $isDropPayMode = false;
    public $selectedStudentId = null;
    public $selectedStudent = null;
    public $enrollment = null;
    public $selectedVoucherType = null;
    public $paymentHistory = [];
    public $tuitionFees = 0;
    public $miscellaneousFees = 0;
    public $appliedDiscount = 0;
    public $totalAssessment = 0;
    public $currentBalance = 0;
    public $discountPercentage = 0;
    public $totalPaymentsMade = 0;

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


        if (request()->routeIs('cashier.payments.college')) {
            $this->level = 'college';
        } elseif (request()->routeIs('cashier.payments.shs')) {
            $this->level = 'shs';
        }



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

    public function selectStudent($studentId, $enrollmentId = null)
    {
        $this->selectedStudentId = $studentId;
        $this->selectedStudent = User::findOrFail($studentId);
        $this->appliedDiscount = 0;
        $this->discountPercentage = 0;
        $this->isDropPayMode   = false; // always reset to Payment mode on student change

        // Use provided enrollment ID or get the latest enrollment
        if ($enrollmentId) {
            $this->enrollment = Enrollment::findOrFail($enrollmentId);
        } else {
            $this->enrollment = Enrollment::where('user_id', $studentId)->latest()->first();
        }

        if ($this->enrollment) {
            // Extract and store voucher type for easy access
            $this->selectedVoucherType = $this->enrollment->voucher_type;

            // Get payment assessment details from cache (base fees)
            $level = strtolower($this->enrollment->level ?? 'college');
            $program = $this->enrollment->course_code ?? 'all';
            $yearLevelDigit = preg_match('/\d+/', $this->enrollment->year_level, $matches) ? $matches[0] : filter_var($this->enrollment->year_level, FILTER_SANITIZE_NUMBER_INT);

            $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevelDigit}";
            $assessment = Cache::get($cacheKey);

            if (!$assessment && $yearLevelDigit !== 'all') {
                // Try program-wide default (e.g., ICT All Levels)
                $assessment = Cache::get("payment_assessment_{$level}_{$program}_all");
            }

            if (!$assessment && $program !== 'all') {
                // Try level-wide default (e.g., All Strands Grade 11)
                $assessment = Cache::get("payment_assessment_{$level}_all_{$yearLevelDigit}");
            }

            if (!$assessment) {
                // Fallback to global if specific not found
                $assessment = Cache::get("payment_assessment_{$level}_all_all", [
                    'tuitionFee' => 0,
                    'miscellaneousFees' => 0,
                    'discountPercentage' => 0,
                    'discountAmount' => 0,
                ]);
            }

            $this->tuitionFees = $assessment['tuitionFee'] ?? 0;
            $this->miscellaneousFees = $assessment['miscellaneousFees'] ?? 0;

            // Calculate base assessment
            $subtotal = $this->tuitionFees + $this->miscellaneousFees;

            // Calculate and apply pre-set discounts from assessment configuration
            $configDiscPerc = (float) ($assessment['discountPercentage'] ?? 0);
            $configDiscFixed = (float) ($assessment['discountAmount'] ?? 0);
            $presetDiscount = ($subtotal * ($configDiscPerc / 100)) + $configDiscFixed;

            $this->totalAssessment = $subtotal;

            // Load persisted discount from enrollment or use preset from configuration
            $this->appliedDiscount = (float) ($this->enrollment->cashier_discount ?? 0);

            // If no specific discount is set for this enrollment, use the preset from config
            if ($this->appliedDiscount == 0 && $presetDiscount > 0) {
                $this->appliedDiscount = $presetDiscount;
            }

            // Voucher is indicator only — no automatic discount applied
            // Cashier must manually input discount amount

            // Get payment history
            $this->paymentHistory = Payment::where('user_id', $studentId)->orderBy('created_at', 'desc')->get();
            // Include previous balance carried from prior terms
            $previousBalance = $this->enrollment->previous_balance ?? 0;

            // Calculate balance (assessment - discount + previous balance - paid)
            $totalPaid = Payment::where('user_id', $studentId)->where('status', 'Paid')->sum('amount');
            $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount + $previousBalance) - $totalPaid);
        } else {
            // No enrollment found, reset all fields
            $this->tuitionFees = 0;
            $this->miscellaneousFees = 0;
            $this->totalAssessment = 0;
            $this->appliedDiscount = 0;
            $this->currentBalance = 0;
            $this->paymentHistory = [];
        }

        $this->activeTab = 'assessment';
    }

    public function setPaymentMode()
    {
        $this->isDropPayMode = false;
    }

    public function setDropPayMode()
    {
        $this->isDropPayMode = true;
    }

    public function applyDiscount()
    {
        // Calculate total discount from percentage
        $subtotal = $this->tuitionFees + $this->miscellaneousFees;
        
        // Ensure discountPercentage is a valid number
        $perc = (float) $this->discountPercentage;
        
        if ($perc <= 0) {
            session()->flash('error', 'Please enter a valid discount percentage (e.g., 50 for 50%).');
            return;
        }

        if ($perc > 100) {
             session()->flash('error', 'Discount percentage cannot exceed 100%.');
             return;
        }

        $totalRequestedDiscount = ($subtotal * ($perc / 100));

        if ($totalRequestedDiscount > $this->totalAssessment) {
            session()->flash('error', 'Calculated discount (₱' . number_format($totalRequestedDiscount, 2) . ') cannot exceed the total assessment.');
            return;
        }

        $this->appliedDiscount = $totalRequestedDiscount;

        // Persist discount to enrollment so student can see it
        if ($this->enrollment) {
            $this->enrollment->cashier_discount = $this->appliedDiscount;
            $this->enrollment->save();
        }

        $totalPaid = Payment::where('user_id', $this->selectedStudentId)->where('status', 'Paid')->sum('amount');
        $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount) - $totalPaid);
        
        // Reset input fields
        $this->discountPercentage = 0;
        
        session()->flash('success', 'Discount of ₱' . number_format($this->appliedDiscount, 2) . ' applied successfully.');
    }

    public function removeDiscount()
    {
        $this->appliedDiscount = 0;
        $this->discountPercentage = 0;

        // Remove persisted discount from enrollment
        if ($this->enrollment) {
            $this->enrollment->cashier_discount = 0;
            $this->enrollment->save();
        }

        $totalPaid = Payment::where('user_id', $this->selectedStudentId)->where('status', 'Paid')->sum('amount');
        $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount) - $totalPaid);
        session()->flash('success', 'Discount removed successfully.');
    }

    public function refreshStudentData()
    {
        // Refresh the currently selected student's data when assessment is updated
        if ($this->selectedStudentId) {
            $this->selectStudent($this->selectedStudentId);
        }
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

    public function submitPayment()
    {
        // Validate amount is provided
        if (!$this->amount || $this->amount <= 0) {
            session()->flash('error', 'Please enter a valid amount paid.');
            return;
        }

        if (!$this->selectedStudentId) {
            session()->flash('error', 'Please select a student.');
            return;
        }

        // Create payment record
        $latestEnrollment = Enrollment::where('user_id', $this->selectedStudentId)->latest()->first();

        $payment = Payment::create([
            'user_id'          => $this->selectedStudentId,
            'application_id'   => $latestEnrollment ? $latestEnrollment->id : null,
            'amount'           => $this->amount,
            'transaction_id'   => $this->reference_no ?? 'CASH-' . time(),
            'status'           => 'Paid',
            'payment_method'   => $this->payment_type,
            'payment_date'     => now(),
            'is_drop_payment'  => $this->isDropPayMode,
        ]);

        if ($payment->application_id) {
            Enrollment::where('id', $payment->application_id)->update(['status' => 'Paid']);
        }

        $this->notifyRecipients($payment);

        // Refresh the selected student's data to update balance
        $this->selectStudent($this->selectedStudentId);

        // Reset form
        $this->amount = '';
        $this->reference_no = '';
        $label = $this->isDropPayMode ? 'Drop payment' : 'Payment';
        session()->flash('success', "{$label} of ₱" . number_format($payment->amount, 2) . ' processed successfully.');
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
        $student = User::find($payment->user_id);

        if($student){
            $student->notify(new StudentPaymentConfirmed($payment));
        }
    }

    public function render()
    {


        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->with(['user', 'application'])
            ->where('payments.status', '!=', 'Pending');

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

        $payments = $query->orderBy('payments.id', 'desc')->paginate(15);

        // Fetch all enrolled students based on level (SHS or College)
        $enrollmentQuery = Enrollment::query();
        $branch = 'all';

        if ($this->level === 'shs') {
            // Filter for SHS students
            $branch = 'shs';
            $enrollmentQuery->whereIn('course_code', ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
        } elseif ($this->level === 'college') {
            // Filter for College students (exclude SHS)
            $branch = 'college';
            $enrollmentQuery->whereNotIn('course_code', ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
        }



        // Get the latest enrollment for each student, prioritizing those with vouchers set by registrar
        $allEnrollments = $enrollmentQuery
            ->with(['user', 'payments'])
            ->orderBy('voucher_applied_at', 'desc')  // Prioritize those with vouchers (most recent)
            ->orderBy('updated_at', 'desc')  // Then by last update
            ->get();

        // Keep only the latest enrollment per student
        $enrolledStudents = $allEnrollments
            ->unique('user_id');


        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('livewire.cashier-payment-manager-new', [
            'payments'           => $enrolledStudents,
            'students'           => $students,
            'activeTab'          => $this->activeTab,
            'isDropPayMode'      => $this->isDropPayMode,
            'selectedStudentId'  => $this->selectedStudentId,
            'selectedStudent'    => $this->selectedStudent,
            'enrollment'         => $this->enrollment,
            'selectedVoucherType'=> $this->selectedVoucherType,
            'paymentHistory'     => $this->paymentHistory,
            'tuitionFees'        => $this->tuitionFees,
            'miscellaneousFees'  => $this->miscellaneousFees,
            'appliedDiscount'    => $this->appliedDiscount,
            'totalAssessment'    => $this->totalAssessment,
            'currentBalance'     => $this->currentBalance,
        ])->layout('components.layouts.cashier', ['title' => 'Manage Payments']);
    }
}
