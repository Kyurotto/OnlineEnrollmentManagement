<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Payment;
use App\Models\Enrollment;

#[Layout('components.layouts.student')]
class StudentPaymentManager extends Component
{
    public $level; // 'shs' or 'college'
    public $studentLevel; // Display name
    public $tuitionFee = 0;
    public $miscellaneousFees = 0;
    public $totalAssessment = 0;
    public $totalPaymentsMade = 0;
    public $voucherType = null; // 'free_tuition', 'discounted', or null
    public $hasEnrollment = false; // Track if student has an enrollment

    // Level comparison properties
    public $shsFees = ['tuitionFee' => 0, 'miscellaneousFees' => 0];
    public $collegeFees = ['tuitionFee' => 0, 'miscellaneousFees' => 0];

    public function mount()
    {
        $user = Auth::user();

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        // Auto-detect from student's latest enrollment for the current term
        $latestEnrollment = Enrollment::where('user_id', $user->id)
            ->where('year_level', '!=', null)
            ->when($activeYear, function($query) use ($activeYear) {
                return $query->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%');
            })
            ->when($activeSemester, function($query) use ($activeSemester) {
                return $query->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
            })
            ->latest()
            ->first();

        if ($latestEnrollment) {
            $this->hasEnrollment = true;

            // Determine level based on course_code (SHS strands vs College courses)
            $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
            $courseCode = strtoupper($latestEnrollment->course_code);

            if (in_array($courseCode, $shsStrands)) {
                $this->level = 'shs';
            } else {
                $this->level = 'college';
            }

            // Load voucher status from enrollment
            $this->voucherType = $latestEnrollment->voucher_type;
            $this->studentLevel = ucfirst($this->level);
        } else {
            $this->hasEnrollment = false;
            $this->level = 'shs'; // Default for cache loading
            $this->voucherType = null;
            $this->studentLevel = 'Pending Enrollment'; // Show pending instead of level
        }
        $this->loadAssessment();
        $this->loadBothLevelsFees();
    }

    public function loadAssessment()
    {
        // Load fees from cache using the same key as the cashier sets
        $cacheKey = 'payment_assessment_' . $this->level;
        $assessment = Cache::get($cacheKey, [
            'tuitionFee' => 0,
            'miscellaneousFees' => 0,
        ]);

        $this->tuitionFee = (float)($assessment['tuitionFee'] ?? 0);
        $this->miscellaneousFees = (float)($assessment['miscellaneousFees'] ?? 0);
        $this->totalAssessment = $this->tuitionFee + $this->miscellaneousFees;

        // Calculate total payments made by student for the current enrollment
        $user = Auth::user();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        
        $currentEnrollment = Enrollment::where('user_id', $user->id)
            ->when($activeYear, function($query) use ($activeYear) {
                return $query->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%');
            })
            ->when($activeSemester, function($query) use ($activeSemester) {
                return $query->where('year_level', 'LIKE', '%' . $activeSemester->name . '%');
            })
            ->latest()
            ->first();

        $this->totalPaymentsMade = (float)Payment::where('user_id', $user->id)
            ->where('status', 'Paid')
            ->when($currentEnrollment, function($query) use ($currentEnrollment) {
                return $query->where('application_id', $currentEnrollment->id);
            })
            ->sum('amount');
    }

    public function loadBothLevelsFees()
    {
        // Load SHS level fees
        $shsCache = Cache::get('payment_assessment_shs', [
            'tuitionFee' => 0,
            'miscellaneousFees' => 0,
        ]);
        $this->shsFees = [
            'tuitionFee' => (float)($shsCache['tuitionFee'] ?? 0),
            'miscellaneousFees' => (float)($shsCache['miscellaneousFees'] ?? 0),
        ];

        // Load College level fees
        $collegeCache = Cache::get('payment_assessment_college', [
            'tuitionFee' => 0,
            'miscellaneousFees' => 0,
        ]);
        $this->collegeFees = [
            'tuitionFee' => (float)($collegeCache['tuitionFee'] ?? 0),
            'miscellaneousFees' => (float)($collegeCache['miscellaneousFees'] ?? 0),
        ];
    }

    public function render()
    {
        $user = Auth::user();

        // Get student's enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('year_level', '!=', null)
            ->latest()
            ->first();

        // Fetch payment history for the student
        $paymentRecords = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform payment data
        $payments = $paymentRecords->map(function ($record) {
            return [
                'id' => $record->id,
                'amount' => $record->amount,
                'txn_id' => 'TXN-' . str_pad($record->id, 6, '0', STR_PAD_LEFT),
                'date' => $record->created_at->format('M d, Y'),
                'time' => $record->created_at->format('g:i A'),
                'status' => $record->status,
                'reference' => $record->transaction_id,
            ];
        });

        return view('livewire.student-payment-manager', [
            'level' => $this->level,
            'studentLevel' => $this->studentLevel,
            'enrollment' => $enrollment,
            'tuitionFee' => $this->tuitionFee,
            'miscellaneousFees' => $this->miscellaneousFees,
            'totalAssessment' => $this->totalAssessment,
            'totalPaymentsMade' => $this->totalPaymentsMade,
            'voucherType' => $this->voucherType,
            'payments' => $payments,
            'hasEnrollment' => $this->hasEnrollment,
            'shsFees' => $this->shsFees,
            'collegeFees' => $this->collegeFees,
        ]);
    }
}

