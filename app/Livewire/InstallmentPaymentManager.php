<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Services\InstallmentCalculator;

class InstallmentPaymentManager extends Component
{
    public $enrollmentId;
    public $studentId;
    
    // Downpayment settings
    public $downpaymentPercentage = 25; // 25% of total assessment as downpayment
    public $installmentType = 'equal'; // 'equal' or 'weighted'
    
    // Calculated values
    public $totalAssessment = 0;
    public $downpaymentAmount = 0;
    public $installments = [];
    public $dueDates = [];
    
    // Payment tracking
    public $paymentHistory = [];
    public $paidInstallments = [];

    public function mount($enrollmentId = null, $studentId = null)
    {
        if ($enrollmentId) {
            $this->enrollmentId = $enrollmentId;
            $this->loadEnrollmentData();
        }
        
        if ($studentId) {
            $this->studentId = $studentId;
        }
    }

    public function loadEnrollmentData()
    {
        $enrollment = Enrollment::find($this->enrollmentId);
        
        if (!$enrollment) {
            return;
        }

        // Calculate total assessment (tuition + misc fees)
        // Adjust this based on your actual assessment calculation logic
        $this->totalAssessment = $enrollment->tuition_fee + ($enrollment->miscellaneous_fee ?? 0);
        
        $this->calculateInstallments();
        $this->loadPaymentHistory();
    }

    public function calculateInstallments()
    {
        // Calculate downpayment amount
        $this->downpaymentAmount = InstallmentCalculator::calculateDownpaymentFromAssessment(
            $this->totalAssessment,
            $this->downpaymentPercentage
        );

        // Break down into three installments
        $this->installments = InstallmentCalculator::calculateInstallments(
            $this->downpaymentAmount,
            $this->installmentType
        );

        // Get due dates
        $this->dueDates = InstallmentCalculator::getInstallmentDueDates();
    }

    public function updateDownpaymentPercentage($value)
    {
        $this->downpaymentPercentage = (int)$value;
        $this->calculateInstallments();
    }

    public function updateInstallmentType($type)
    {
        $this->installmentType = $type;
        $this->calculateInstallments();
    }

    public function loadPaymentHistory()
    {
        if (!$this->enrollmentId) {
            return;
        }

        $this->paymentHistory = Payment::where('application_id', $this->enrollmentId)
            ->where('is_installment', true)
            ->orderByRaw("FIELD(installment_type, 'Prelim', 'Midterm', 'Final')")
            ->get()
            ->toArray();

        $this->paidInstallments = collect($this->paymentHistory)
            ->pluck('installment_type')
            ->toArray();
    }

    /**
     * Record a payment for a specific installment
     */
    public function recordInstallmentPayment($installmentType, $amount, $paymentMethod = 'Cash', $referenceNo = '')
    {
        if (!$this->enrollmentId || !$this->studentId) {
            session()->flash('error', 'Enrollment and Student information is required.');
            return;
        }

        // Validate the payment amount matches the installment
        if (abs($amount - $this->installments[$installmentType]) > 0.01) {
            session()->flash('error', "Payment amount does not match the {$installmentType} installment.");
            return;
        }

        // Check if installment is already paid
        if (in_array($installmentType, $this->paidInstallments)) {
            session()->flash('error', "The {$installmentType} installment has already been paid.");
            return;
        }

        // Create payment record
        Payment::create([
            'application_id' => $this->enrollmentId,
            'user_id' => $this->studentId,
            'amount' => $amount,
            'installment_type' => $installmentType,
            'down_payment_total' => $this->downpaymentAmount,
            'is_installment' => true,
            'status' => 'Paid',
            'payment_method' => $paymentMethod,
            'transaction_id' => $referenceNo ?: 'INST-' . strtoupper($installmentType) . '-' . time(),
            'payment_date' => now(),
        ]);

        $this->loadPaymentHistory();
        session()->flash('success', "{$installmentType} installment of ₱" . number_format($amount, 2) . " recorded successfully.");
    }

    /**
     * Check if all installments are paid
     */
    public function isFullyPaid()
    {
        return count($this->paidInstallments) === 3;
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaid()
    {
        return collect($this->paymentHistory)
            ->sum('amount');
    }

    /**
     * Get remaining balance
     */
    public function getRemainingBalance()
    {
        return $this->downpaymentAmount - $this->getTotalPaid();
    }

    public function render()
    {
        return view('livewire.installment-payment-manager', [
            'enrollmentId' => $this->enrollmentId,
            'totalAssessment' => $this->totalAssessment,
            'downpaymentAmount' => $this->downpaymentAmount,
            'installments' => $this->installments,
            'dueDates' => $this->dueDates,
            'paymentHistory' => $this->paymentHistory,
            'paidInstallments' => $this->paidInstallments,
            'remainingBalance' => $this->getRemainingBalance(),
        ]);
    }
}
