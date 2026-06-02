<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\ActivityLog;
use App\Notifications\StudentPaymentConfirmed;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class PaymentManager extends Component
{
    use WithPagination;

    protected $listeners = ['assessmentUpdated' => 'refreshStudentData'];

    public $search = '';
    public $filter_course = 'ALL';
    public $status = 'All statuses';
    public $level = null;

    public $showModal = false;
    public $isEditMode = false;
    public $editingPaymentId = null;

    // New properties for sidebar layout
    public $activeTab = 'assessment';
    public $selectedStudentId = null;
    public $isDropPayMode = false;
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
    public $previousBalance = 0;

    // Itemized fee breakdown (for display only — does NOT affect payment logic)
    public $registrationFee = 0;
    public $guidanceFee = 0;
    public $trainingMaterials = 0;
    public $handbook = 0;
    public $mailingFee = 0;
    public $medicalDental = 0;
    public $studentIdFee = 0;
    public $socioCultural = 0;
    public $insurance = 0;
    public $schoolPublication = 0;
    public $studentDevelopment = 0;
    public $libraryFee = 0;
    public $energyFee = 0;
    public $physicalFacilities = 0;
    public $researchInnovation = 0;
    public $internetFee = 0;
    public $audioVisual = 0;
    public $itDevelopment = 0;
    public $laboratoryFee = 0;

    // Override State and Form properties
    public $isEditingAssessment = false;
    public $editRegistrationFee = 0;
    public $editGuidanceFee = 0;
    public $editTrainingMaterials = 0;
    public $editHandbook = 0;
    public $editMailingFee = 0;
    public $editMedicalDental = 0;
    public $editStudentIdFee = 0;
    public $editSocioCultural = 0;
    public $editInsurance = 0;
    public $editSchoolPublication = 0;
    public $editStudentDevelopment = 0;
    public $editLibraryFee = 0;
    public $editEnergyFee = 0;
    public $editPhysicalFacilities = 0;
    public $editResearchInnovation = 0;
    public $editInternetFee = 0;
    public $editAudioVisual = 0;
    public $editItDevelopment = 0;
    public $editLaboratoryFee = 0;
    public $editTuitionFee = 0;

    // Form fields
    public $user_id;
    public $amount;
    public $payment_type = 'Cash';
    public $reference_no;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_course' => ['except' => 'ALL'],
        'status' => ['except' => 'All statuses'],
        'showModal' => ['except' => false, 'as' => 'showModal'],
        'editingPaymentId' => ['except' => null, 'as' => 'edit_id'],
    ];

    public function mount()
    {
        if (request()->routeIs('admin.payments.college')) {
            $this->level = 'college';
        } elseif (request()->routeIs('admin.payments.shs')) {
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

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filter_course', 'status']);
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
        $this->payment_type = $payment->payment_method ?? 'Cash';
        $this->reference_no = $payment->transaction_id;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['user_id', 'amount', 'payment_type', 'reference_no', 'isEditMode', 'editingPaymentId']);
    }

    // FIX: Added the Historical Balance Calculator to Admin
    private function calculateHistoricalPreviousBalance($studentId, $currentEnrollmentId)
    {
        $pastEnrollments = Enrollment::where('user_id', $studentId)
            ->where('id', '<', $currentEnrollmentId)
            ->get();

        $totalHistoricalAssessment = 0;
        $totalHistoricalDiscount = 0;

        foreach ($pastEnrollments as $pastEnv) {
            $savedDbAssessment = (float) ($pastEnv->total_assessment ?? 0);
            if ($savedDbAssessment == 0) {
                $savedDbAssessment = (float) ($pastEnv->tuition_fee ?? 0) + (float) ($pastEnv->miscellaneous_fee ?? 0);
            }

            if ($savedDbAssessment > 0) {
                $pastSubtotal = $savedDbAssessment;
                $appDisc = (float) ($pastEnv->cashier_discount ?? 0);
            } else {
                $level = strtolower($pastEnv->level ?? 'college');
                $program = $pastEnv->course_code ?? 'all';
                $yearLevelDigit = preg_match('/\d+/', (string) $pastEnv->year_level, $matches)
                    ? $matches[0]
                    : filter_var((string) $pastEnv->year_level, FILTER_SANITIZE_NUMBER_INT);

                $overrideKey = "student_assessment_override_{$studentId}";
                $assessment = Cache::get($overrideKey);
                if (!$assessment) {
                    $cacheKey = "payment_assessment_{$level}_{$program}_{$yearLevelDigit}";
                    $assessment = Cache::get($cacheKey)
                        ?? Cache::get("payment_assessment_{$level}_{$program}_all")
                        ?? Cache::get("payment_assessment_{$level}_all_{$yearLevelDigit}")
                        ?? ['tuitionFee' => 0, 'miscellaneousFees' => 0, 'discountPercentage' => 0, 'discountAmount' => 0];
                }

                $pastSubtotal = ((float)($assessment['tuitionFee'] ?? 0)) + ((float)($assessment['miscellaneousFees'] ?? 0));

                $configDiscPerc = (float) ($assessment['discountPercentage'] ?? 0);
                $configDiscFixed = (float) ($assessment['discountAmount'] ?? 0);
                $presetDiscount = ($pastSubtotal * ($configDiscPerc / 100)) + $configDiscFixed;

                $appDisc = (float) ($pastEnv->cashier_discount ?? 0);
                if ($appDisc == 0 && $presetDiscount > 0) {
                    $appDisc = $presetDiscount;
                }
            }

            $totalHistoricalAssessment += $pastSubtotal;
            $totalHistoricalDiscount += $appDisc;
            $totalHistoricalAssessment += (float) ($pastEnv->previous_balance ?? 0);
        }

        $totalHistoricalPaid = Payment::where('user_id', $studentId)
            ->where('application_id', '<', $currentEnrollmentId)
            ->where('status', 'Paid')
            ->sum('amount');

        $calculatedBalance = ($totalHistoricalAssessment - $totalHistoricalDiscount) - $totalHistoricalPaid;

        return max(0, $calculatedBalance);
    }

    public function selectStudent($studentId, $enrollmentId = null)
    {
        $this->selectedStudentId = $studentId;
        $this->selectedStudent = User::findOrFail($studentId);

        // Use provided enrollment ID or get the latest enrollment
        if ($enrollmentId) {
            $this->enrollment = Enrollment::findOrFail($enrollmentId);
        } else {
            $this->enrollment = Enrollment::where('user_id', $studentId)->latest()->first();
        }

        if ($this->enrollment) {
            // Extract and store voucher type for easy access
            $this->selectedVoucherType = $this->enrollment->voucher_type;
            $this->isDropPayMode = false;

            // Get payment assessment details from cache (base fees)
            $level = strtolower($this->enrollment->level ?? 'college');
            $program = $this->enrollment->course_code ?? 'all';
            $yearLevelDigit = preg_match('/\d+/', $this->enrollment->year_level, $matches) ? $matches[0] : filter_var($this->enrollment->year_level, FILTER_SANITIZE_NUMBER_INT);

            $overrideKey = "student_assessment_override_{$studentId}";
            $assessment = Cache::get($overrideKey);
            if (!$assessment) {
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
            }

            $this->tuitionFees = $assessment['tuitionFee'] ?? 0;
            $this->miscellaneousFees = $assessment['miscellaneousFees'] ?? 0;

            // Populate itemized fee breakdown for display
            $this->registrationFee = $assessment['registrationFee'] ?? 0;
            $this->guidanceFee = $assessment['guidanceFee'] ?? 0;
            $this->trainingMaterials = $assessment['trainingMaterials'] ?? 0;
            $this->handbook = $assessment['handbook'] ?? 0;
            $this->mailingFee = $assessment['mailingFee'] ?? 0;
            $this->medicalDental = $assessment['medicalDental'] ?? 0;
            $this->studentIdFee = $assessment['studentId'] ?? 0;
            $this->socioCultural = $assessment['socioCultural'] ?? 0;
            $this->insurance = $assessment['insurance'] ?? 0;
            $this->schoolPublication = $assessment['schoolPublication'] ?? 0;
            $this->studentDevelopment = $assessment['studentDevelopment'] ?? 0;
            $this->libraryFee = $assessment['libraryFee'] ?? 0;
            $this->energyFee = $assessment['energyFee'] ?? 0;
            $this->physicalFacilities = $assessment['physicalFacilities'] ?? 0;
            $this->researchInnovation = $assessment['researchInnovation'] ?? 0;
            $this->internetFee = $assessment['internetFee'] ?? 0;
            $this->audioVisual = $assessment['audioVisual'] ?? 0;
            $this->itDevelopment = $assessment['itDevelopment'] ?? 0;
            $this->laboratoryFee = $assessment['laboratoryFee'] ?? 0;

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

            // Get payment history for current enrollment
            $this->paymentHistory = Payment::where('user_id', $studentId)
                ->where('application_id', $this->enrollment->id)
                ->orderBy('created_at', 'desc')->get();

            // Include previous balance carried from prior terms
            $this->previousBalance = $this->enrollment->previous_balance ?? 0;

            // FIX: Run Historical Calculation for Admin
            if (empty($this->previousBalance) || $this->previousBalance == 0) {
                $cachedPreviousBalance = Cache::get("student_previous_balance_{$studentId}");
                if (!is_null($cachedPreviousBalance)) {
                    $this->previousBalance = (float) $cachedPreviousBalance;
                } else {
                    $this->previousBalance = $this->calculateHistoricalPreviousBalance($studentId, $this->enrollment->id);
                }
            }

            // Calculate balance (assessment - discount + previous balance - paid for current enrollment)
            $totalPaid = Payment::where('user_id', $studentId)
                ->where('application_id', $this->enrollment->id)
                ->where('status', 'Paid')->sum('amount');
            $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount + $this->previousBalance) - $totalPaid);
        } else {
            // No enrollment found, reset all fields
            $this->tuitionFees = 0;
            $this->miscellaneousFees = 0;
            $this->totalAssessment = 0;
            $this->appliedDiscount = 0;
            $this->currentBalance = 0;
            $this->paymentHistory = [];

            // Reset itemized fee breakdown
            $this->registrationFee = 0;
            $this->guidanceFee = 0;
            $this->trainingMaterials = 0;
            $this->handbook = 0;
            $this->mailingFee = 0;
            $this->medicalDental = 0;
            $this->studentIdFee = 0;
            $this->socioCultural = 0;
            $this->insurance = 0;
            $this->schoolPublication = 0;
            $this->studentDevelopment = 0;
            $this->libraryFee = 0;
            $this->energyFee = 0;
            $this->physicalFacilities = 0;
            $this->researchInnovation = 0;
            $this->internetFee = 0;
            $this->audioVisual = 0;
            $this->itDevelopment = 0;
            $this->laboratoryFee = 0;
        }

        $this->activeTab = 'assessment';
    }

    public function editAssessment()
    {
        $this->editRegistrationFee = (float) $this->registrationFee;
        $this->editGuidanceFee = (float) $this->guidanceFee;
        $this->editTrainingMaterials = (float) $this->trainingMaterials;
        $this->editHandbook = (float) $this->handbook;
        $this->editMailingFee = (float) $this->mailingFee;
        $this->editMedicalDental = (float) $this->medicalDental;
        $this->editStudentIdFee = (float) $this->studentIdFee;
        $this->editSocioCultural = (float) $this->socioCultural;
        $this->editInsurance = (float) $this->insurance;
        $this->editSchoolPublication = (float) $this->schoolPublication;
        $this->editStudentDevelopment = (float) $this->studentDevelopment;
        $this->editLibraryFee = (float) $this->libraryFee;
        $this->editEnergyFee = (float) $this->energyFee;
        $this->editPhysicalFacilities = (float) $this->physicalFacilities;
        $this->editResearchInnovation = (float) $this->researchInnovation;
        $this->editInternetFee = (float) $this->internetFee;
        $this->editAudioVisual = (float) $this->audioVisual;
        $this->editItDevelopment = (float) $this->itDevelopment;
        $this->editLaboratoryFee = (float) $this->laboratoryFee;
        $this->editTuitionFee = (float) $this->tuitionFees;

        $this->isEditingAssessment = true;
    }

    public function cancelEditAssessment()
    {
        $this->isEditingAssessment = false;
    }

    public function saveAssessmentOverride()
    {
        if (!$this->selectedStudentId) {
            return;
        }

        // Validate override inputs are numeric and non-negative
        $inputs = [
            'editRegistrationFee', 'editGuidanceFee', 'editTrainingMaterials', 'editHandbook',
            'editMailingFee', 'editMedicalDental', 'editStudentIdFee', 'editSocioCultural',
            'editInsurance', 'editSchoolPublication', 'editStudentDevelopment', 'editLibraryFee',
            'editEnergyFee', 'editPhysicalFacilities', 'editResearchInnovation', 'editInternetFee',
            'editAudioVisual', 'editItDevelopment', 'editLaboratoryFee', 'editTuitionFee'
        ];

        foreach ($inputs as $input) {
            $this->$input = (float) $this->$input;
            if ($this->$input < 0) {
                session()->flash('error', 'All fee inputs must be non-negative values.');
                return;
            }
        }

        $miscSum = $this->editGuidanceFee + $this->editTrainingMaterials + $this->editHandbook +
                   $this->editMailingFee + $this->editMedicalDental + $this->editStudentIdFee +
                   $this->editSocioCultural + $this->editInsurance + $this->editSchoolPublication +
                   $this->editStudentDevelopment + $this->editLibraryFee + $this->editEnergyFee +
                   $this->editPhysicalFacilities + $this->editResearchInnovation + $this->editInternetFee +
                   $this->editAudioVisual + $this->editItDevelopment;

        $assessment = [
            'registrationFee' => $this->editRegistrationFee,
            'guidanceFee' => $this->editGuidanceFee,
            'trainingMaterials' => $this->editTrainingMaterials,
            'handbook' => $this->editHandbook,
            'mailingFee' => $this->editMailingFee,
            'medicalDental' => $this->editMedicalDental,
            'studentId' => $this->editStudentIdFee,
            'socioCultural' => $this->editSocioCultural,
            'insurance' => $this->editInsurance,
            'schoolPublication' => $this->editSchoolPublication,
            'studentDevelopment' => $this->editStudentDevelopment,
            'libraryFee' => $this->editLibraryFee,
            'energyFee' => $this->editEnergyFee,
            'physicalFacilities' => $this->editPhysicalFacilities,
            'researchInnovation' => $this->editResearchInnovation,
            'internetFee' => $this->editInternetFee,
            'audioVisual' => $this->editAudioVisual,
            'itDevelopment' => $this->editItDevelopment,
            'laboratoryFee' => $this->editLaboratoryFee,
            'tuitionFee' => $this->editTuitionFee,
            // Calculate sum for backwards compatibility
            'miscellaneousFees' => $miscSum + $this->editRegistrationFee + $this->editLaboratoryFee,
        ];

        $overrideKey = "student_assessment_override_{$this->selectedStudentId}";
        Cache::put($overrideKey, $assessment, now()->addYears(1));

        $this->isEditingAssessment = false;
        $this->selectStudent($this->selectedStudentId);

        session()->flash('success', 'Custom student assessment saved successfully!');
    }

    public function resetAssessmentToDefault()
    {
        if (!$this->selectedStudentId) {
            return;
        }

        $overrideKey = "student_assessment_override_{$this->selectedStudentId}";
        Cache::forget($overrideKey);

        $this->isEditingAssessment = false;
        $this->selectStudent($this->selectedStudentId);

        session()->flash('success', 'Assessment reverted to program defaults.');
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
        $totalRequestedDiscount = ($subtotal * ($this->discountPercentage / 100));

        if ($totalRequestedDiscount <= 0) {
            session()->flash('error', 'Please enter a valid discount percentage.');
            return;
        }

        if ($totalRequestedDiscount > $this->totalAssessment) {
            session()->flash('error', 'Discount cannot exceed the total assessment.');
            return;
        }

        $this->appliedDiscount = $totalRequestedDiscount;

        // Persist discount to enrollment
        if ($this->enrollment) {
            $this->enrollment->cashier_discount = $this->appliedDiscount;
            $this->enrollment->save();
        }

        $this->previousBalance = $this->enrollment ? ($this->enrollment->previous_balance ?? 0) : 0;

        // FIX: Historical Calculation added to applyDiscount
        if (empty($this->previousBalance) || $this->previousBalance == 0) {
            $cachedPreviousBalance = Cache::get("student_previous_balance_{$this->selectedStudentId}");
            if (!is_null($cachedPreviousBalance)) {
                $this->previousBalance = (float) $cachedPreviousBalance;
            } else if ($this->enrollment) {
                $this->previousBalance = $this->calculateHistoricalPreviousBalance($this->selectedStudentId, $this->enrollment->id);
            }
        }

        $totalPaid = Payment::where('user_id', $this->selectedStudentId)
            ->when($this->enrollment, fn($q) => $q->where('application_id', $this->enrollment->id))
            ->where('status', 'Paid')->sum('amount');
        $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount + $this->previousBalance) - $totalPaid);

        // Reset input fields
        $this->discountPercentage = 0;

        session()->flash('success', 'Discount applied successfully.');
    }

    public function removeDiscount()
    {
        $this->appliedDiscount = 0;
        $this->discountPercentage = 0;

        // Remove from enrollment
        if ($this->enrollment) {
            $this->enrollment->cashier_discount = 0;
            $this->enrollment->save();
        }

        $this->previousBalance = $this->enrollment ? ($this->enrollment->previous_balance ?? 0) : 0;

        // FIX: Historical Calculation added to removeDiscount
        if (empty($this->previousBalance) || $this->previousBalance == 0) {
            $cachedPreviousBalance = Cache::get("student_previous_balance_{$this->selectedStudentId}");
            if (!is_null($cachedPreviousBalance)) {
                $this->previousBalance = (float) $cachedPreviousBalance;
            } else if ($this->enrollment) {
                $this->previousBalance = $this->calculateHistoricalPreviousBalance($this->selectedStudentId, $this->enrollment->id);
            }
        }

        $totalPaid = Payment::where('user_id', $this->selectedStudentId)
            ->when($this->enrollment, fn($q) => $q->where('application_id', $this->enrollment->id))
            ->where('status', 'Paid')->sum('amount');
        $this->currentBalance = max(0, ($this->totalAssessment - $this->appliedDiscount + $this->previousBalance) - $totalPaid);
        session()->flash('success', 'Discount removed successfully.');
    }

    public function refreshStudentData()
    {
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
        if (!$this->amount || $this->amount <= 0) {
            session()->flash('error', 'Please enter a valid amount paid.');
            return;
        }

        if (!$this->selectedStudentId) {
            session()->flash('error', 'Please select a student.');
            return;
        }

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
        $this->selectStudent($this->selectedStudentId);

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

        // Log the payment approval/rejection
        $adminUser = auth()->user();
        if ($adminUser) {
            $action = $status === 'Paid' ? 'payment_approved' : 'payment_rejected';
            $student = User::find($payment->user_id);
            $description = $status === 'Paid'
                ? 'Approved payment of ₱' . number_format($payment->amount, 2) . ' for ' . ($student ? $student->first_name . ' ' . $student->last_name : 'Unknown Student')
                : 'Rejected payment of ₱' . number_format($payment->amount, 2) . ' for ' . ($student ? $student->first_name . ' ' . $student->last_name : 'Unknown Student');

            ActivityLog::create([
                'user_id' => $adminUser->id,
                'action' => $action,
                'target_type' => 'Payment',
                'target_id' => $payment->id,
                'description' => $description,
            ]);
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
        if ($this->level === 'shs' || $this->level === 'college') {
            // Cashier-style view for SHS and College
            $enrollmentQuery = Enrollment::query();

            if ($this->level === 'shs') {
                $enrollmentQuery->whereIn('course_code', ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
            } else {
                $enrollmentQuery->whereNotIn('course_code', ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT']);
            }

            if ($this->search != '') {
                $searchTerm = '%' . $this->search . '%';
                $enrollmentQuery->whereHas('user', function($u) use ($searchTerm) {
                    $u->where('name', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('last_name', 'like', $searchTerm)
                      ->orWhere('first_name', 'like', $searchTerm);
                });
            }

            $enrolledStudents = $enrollmentQuery
                ->with(['user', 'payments'])
                ->orderBy('voucher_applied_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->unique('user_id')
                ->values()
                ->sortBy(function($enrollment) {
                    return strtolower($enrollment->user->last_name . ' ' . $enrollment->user->first_name);
                })
                ->values();

            $students = User::where('role', 'student')->orderBy('name')->get();

            return view('livewire.admin.admin-cashier-payment-manager', [
                'payments' => $enrolledStudents,
                'students' => $students,
                'pageTitle' => 'Manage Payments',
                'activeTab' => $this->activeTab,
                'selectedStudentId' => $this->selectedStudentId,
                'selectedStudent' => $this->selectedStudent,
                'enrollment' => $this->enrollment,
                'selectedVoucherType' => $this->selectedVoucherType,
                'paymentHistory' => $this->paymentHistory,
                'tuitionFees' => $this->tuitionFees,
                'miscellaneousFees' => $this->miscellaneousFees,
                'appliedDiscount' => $this->appliedDiscount,
                'totalAssessment' => $this->totalAssessment,
                'currentBalance' => $this->currentBalance,
                // Itemized fee breakdown
                'registrationFee'    => $this->registrationFee,
                'guidanceFee'        => $this->guidanceFee,
                'trainingMaterials'  => $this->trainingMaterials,
                'handbook'           => $this->handbook,
                'mailingFee'         => $this->mailingFee,
                'medicalDental'      => $this->medicalDental,
                'studentIdFee'       => $this->studentIdFee,
                'socioCultural'      => $this->socioCultural,
                'insurance'          => $this->insurance,
                'schoolPublication'  => $this->schoolPublication,
                'studentDevelopment' => $this->studentDevelopment,
                'libraryFee'         => $this->libraryFee,
                'energyFee'          => $this->energyFee,
                'physicalFacilities' => $this->physicalFacilities,
                'researchInnovation' => $this->researchInnovation,
                'internetFee'        => $this->internetFee,
                'audioVisual'        => $this->audioVisual,
                'itDevelopment'      => $this->itDevelopment,
                'laboratoryFee'      => $this->laboratoryFee,
            ])->layout('components.layouts.admin', ['title' => 'Manage Payments']);
        }

        // Default table view for Admin
        $query = Payment::select('payments.*')
            ->leftJoin('enrollments', 'payments.application_id', '=', 'enrollments.id')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->with(['user', 'application']);

        if ($this->status != 'All statuses') {
            $query->where('payments.status', $this->status);
        }

        if ($this->filter_course != 'ALL') {
            $query->where('enrollments.course_code', $this->filter_course);
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
        $students = User::where('role', 'student')->orderBy('name')->get();
        $programOptions = \App\Models\Course::get();

        return view('livewire.admin.admin-payment-manager', [
            'payments' => $payments,
            'students' => $students,
            'programOptions' => $programOptions,
            'pageTitle' => 'Payment Management',
        ])->layout('components.layouts.admin', ['title' => 'Payment Management']);
    }
}
