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
    public $cashierDiscount = 0;
    public $totalAssessment = 0;
    public $totalPaymentsMade = 0;
    public $voucherType = null;
    public $hasEnrollment = false;
    public $previousBalance = 0;

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

        $latestEnrollment = $currentEnrollment ?? Enrollment::where('user_id', $user->id)->latest()->first();
        
        $program = 'all';
        $yearLevelNum = 'all';

        if ($latestEnrollment) {
            $program = $latestEnrollment->course_code;
            // Extract the first number from year_level (e.g., "1st Year" -> 1, "11" -> 11)
            if (preg_match('/\d+/', $latestEnrollment->year_level, $matches)) {
                $yearLevelNum = $matches[0];
            }
        }

        $overrideKey = "student_assessment_override_{$user->id}";
        $assessment = Cache::get($overrideKey);
        if (!$assessment) {
            // Try specific cache key: payment_assessment_{level}_{program}_{yearLevel}
            $cacheKey = "payment_assessment_{$this->level}_{$program}_{$yearLevelNum}";
            $assessment = Cache::get($cacheKey);

            if (!$assessment) {
                // Fallback: Global for level
                $assessment = Cache::get("payment_assessment_{$this->level}_all_all", [
                    'tuitionFee' => 0,
                    'miscellaneousFees' => 0,
                    'discountPercentage' => 0,
                    'discountAmount' => 0,
                ]);
            }
        }

        $this->tuitionFee = (float)($assessment['tuitionFee'] ?? 0);
        $this->miscellaneousFees = (float)($assessment['miscellaneousFees'] ?? 0);
        
        // Calculate pre-set discounts from config
        $subtotal = $this->tuitionFee + $this->miscellaneousFees;
        $configDiscPerc = (float) ($assessment['discountPercentage'] ?? 0);
        $configDiscFixed = (float) ($assessment['discountAmount'] ?? 0);
        $presetDiscount = ($subtotal * ($configDiscPerc / 100)) + $configDiscFixed;

        // Load cashier-applied discount from enrollment
        $this->cashierDiscount = (float) ($latestEnrollment->cashier_discount ?? 0);
        
        // Use preset if no specific cashier discount is set
        if ($this->cashierDiscount == 0 && $presetDiscount > 0) {
            $this->cashierDiscount = $presetDiscount;
        }

        // Add any existing previous_balance from prior terms
        $this->previousBalance = (float) ($latestEnrollment->previous_balance ?? 0);
        if ($this->previousBalance == 0) {
            $cachedPreviousBalance = Cache::get("student_previous_balance_{$user->id}");
            if (!is_null($cachedPreviousBalance)) {
                $this->previousBalance = (float) $cachedPreviousBalance;
            }
        }

        $this->totalAssessment = max(0, ($subtotal + $this->previousBalance) - $this->cashierDiscount);

        // Calculate total payments made by student for current enrollment only
        $this->totalPaymentsMade = (float)Payment::where('user_id', $user->id)
            ->where('status', 'Paid')
            ->when($latestEnrollment, function($query) use ($latestEnrollment) {
                return $query->where('application_id', $latestEnrollment->id);
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

        // Fetch payment history for the student (only confirmed/paid)
        $paymentRecords = Payment::where('user_id', $user->id)
            ->where('status', 'Paid')
            ->when($enrollment, function($query) use ($enrollment) {
                return $query->where('application_id', $enrollment->id);
            })
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

        return view('student.student-payment-manager', [
            'level' => $this->level,
            'studentLevel' => $this->studentLevel,
            'enrollment' => $enrollment,
            'tuitionFee' => $this->tuitionFee,
            'miscellaneousFees' => $this->miscellaneousFees,
            'cashierDiscount' => $this->cashierDiscount,
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

