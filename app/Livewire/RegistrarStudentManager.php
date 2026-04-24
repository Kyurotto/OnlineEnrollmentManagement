<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegistrarStudentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'users.id';
    public $sortDirection = 'desc';
    public $filter = 'all'; // all | regular | irregular

    // Classification modal state
    public $classificationEnrollmentId = null;
    public $classificationReason = '';
    public $classificationIsRegular = true;
    public $showClassificationModal = false;
    public $classificationLevel = 'college'; // 'shs' or 'college'

    protected $queryString = ['search', 'sortField', 'sortDirection', 'filter'];
    private const ALLOWED_FILTERS = ['all', 'regular', 'irregular'];

    public function mount(): void
    {
        $this->filter = $this->normalizeFilter($this->filter);
    }



    public function setFilter($value) {
        $this->filter = $this->normalizeFilter($value);
        $this->resetPage();
    }

    private function normalizeFilter($value): string
    {
        $value = strtolower((string) $value);
        return in_array($value, self::ALLOWED_FILTERS, true) ? $value : 'all';
    }

    public function updatingSearch() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Run the automated audit on all enrolled/approved students.
     */
    public function runAuditAll()
    {
        $enrollments = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->get();
        $updated = 0;

        foreach ($enrollments as $enrollment) {
            if ($enrollment->runStatusAudit()) {
                $enrollment->save();
                $updated++;
            }
        }

        session()->flash('success', "Audit complete. {$updated} student record(s) updated.");
    }

    /**
     * Run audit on a single enrollment.
     */
    public function runAuditSingle(int $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        if ($enrollment->runStatusAudit()) {
            $enrollment->save();
            session()->flash('success', 'Student status audited and updated.');
        } else {
            session()->flash('success', 'No changes — student status is already up to date.');
        }
    }

    /**
     * Open the classification reason modal for a specific enrollment.
     */
    public function openClassificationModal(int $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $this->classificationEnrollmentId = $enrollmentId;
        $this->classificationReason = $enrollment->classification_reason ?? '';
        $this->classificationIsRegular = $enrollment->is_regular !== false;
        $this->classificationLevel = $enrollment->isSHS() ? 'shs' : 'college';
        $this->showClassificationModal = true;
    }

    public function closeClassificationModal()
    {
        $this->showClassificationModal = false;
        $this->classificationEnrollmentId = null;
        $this->classificationReason = '';
        $this->classificationIsRegular = true;
        $this->classificationLevel = 'college';
    }

    /**
     * Save the manually selected classification reason and mark as Irregular,
     * or mark as Regular if no reason is provided.
     */
    public function saveClassification()
    {
        $enrollment = Enrollment::findOrFail($this->classificationEnrollmentId);

        if ($this->classificationIsRegular) {
            $enrollment->is_regular = true;
            $enrollment->classification_reason = null;
            $enrollment->last_audited_at = now();
            $enrollment->save();

            $this->closeClassificationModal();
            session()->flash('success', 'Student classified as Regular.');
            return;
        }

        $this->validate([
            'classificationReason' => 'required|string|in:' . implode(',', array_keys(
                $this->classificationLevel === 'shs'
                    ? Enrollment::SHS_CLASSIFICATION_REASONS
                    : Enrollment::CLASSIFICATION_REASONS
            )),
        ]);

        $enrollment->is_regular = false;
        $enrollment->classification_reason = $this->classificationReason;
        $enrollment->last_audited_at = now();

        // Auto-update student_type to match the reason
        $typeMap = [
            'Transferee Credit Gap'   => 'transferee',
            'Shifter/Bridging'        => 'shifter',
            'Academic Deficiency'     => 'returnee',
            'Financial Underloading'  => 'returnee',
            'Personal/Health Reasons' => 'returnee',
            'Graduating Special Load' => 'returnee',
        ];
        $enrollment->student_type = $typeMap[$this->classificationReason] ?? $enrollment->student_type;

        $enrollment->save();

        $this->closeClassificationModal();
        session()->flash('success', 'Student classified as Irregular: ' . $this->classificationReason);
    }

    /**
     * Manually verify a transferee's credentials and mark them as Regular.
     */
    public function verifyCredentials(int $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->credentials_verified = true;
        $enrollment->is_regular = true;
        $enrollment->classification_reason = null;
        $enrollment->last_audited_at = now();
        $enrollment->save();

        session()->flash('success', 'Credentials verified. Student is now classified as Regular.');
    }

    /**
     * Manually override a student back to Regular status.
     */
    public function markAsRegular(int $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->is_regular = true;
        $enrollment->classification_reason = null;
        $enrollment->last_audited_at = now();
        $enrollment->save();

        session()->flash('success', 'Student manually marked as Regular.');
    }

    public function render()
    {

        // Map field names if needed (already using latest_enrollments in blade)
        $sortField = $this->sortField;
        



        $optionalEnrollmentColumns = [
    'promissory_reason',
    'is_regular',
    'classification_reason',
    'credentials_verified',
    'student_type',
    'physical_documents_received',
];
$availableColumns = collect($optionalEnrollmentColumns)
    ->mapWithKeys(fn($column) => [$column => Schema::hasColumn('enrollments', $column)])
    ->all();
$enrollmentSelect = ['user_id', 'course_code', 'year_level', 'status', 'id'];
foreach ($optionalEnrollmentColumns as $column) {
    $enrollmentSelect[] = $availableColumns[$column]
        ? $column
        : DB::raw("NULL as {$column}");
}
$hasIsRegular = $availableColumns['is_regular'] ?? false;

// Map field names if needed (already using latest_enrollments in blade)
$sortField = $this->sortField;




        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

        $query = User::query()
            ->select('users.*', 'latest_enrollments.course_code', 'latest_enrollments.year_level',
                     'latest_enrollments.id as enrollment_id',
                     'latest_enrollments.is_regular', 'latest_enrollments.classification_reason',
                     'latest_enrollments.credentials_verified', 'latest_enrollments.student_type',
                     'latest_enrollments.physical_documents_received',
                     'courses.course_name')
            ->joinSub(
                Enrollment::select($enrollmentSelect)
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved', 'Paid', 'Pending']);

        // Only show students enrolled in the current active academic year
        if ($activeYear) {
            $query->where('latest_enrollments.year_level', 'like', '%' . $activeYear->year_name . '%');
        }

        // Search logic
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('users.first_name', 'like', "%{$this->search}%")
                  ->orWhere('users.last_name', 'like', "%{$this->search}%")
                  ->orWhere('users.email', 'like', "%{$this->search}%")
                  ->orWhere('latest_enrollments.course_code', 'like', "%{$this->search}%")
                  ->orWhere('latest_enrollments.promissory_reason', 'like', "%{$this->search}%");
            });
        }


        $students = $query->orderBy($sortField, $this->sortDirection)->paginate(10);

        // Filter by classification
if ($this->filter === 'regular' && $hasIsRegular) {
    $query->whereRaw('latest_enrollments.is_regular = 1');
} elseif ($this->filter === 'irregular' && $hasIsRegular) {
    $query->whereRaw('latest_enrollments.is_regular = 0');
}

$students = $query->orderBy($sortField, $this->sortDirection)->paginate(10);



        // Stats


        $baseStats = User::query()
            ->joinSub(
                Enrollment::select(
                    'user_id',
                    'id',
                    'status',
                    'year_level',
                    $hasIsRegular ? 'is_regular' : DB::raw('NULL as is_regular')
                )
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved', 'Paid', 'Pending']);

        if ($activeYear) {
            $baseStats->where('latest_enrollments.year_level', 'like', '%' . $activeYear->year_name . '%');
        }

        $totalStudents   = (clone $baseStats)->count();
        $regularCount    = $hasIsRegular ? (clone $baseStats)->whereRaw('latest_enrollments.is_regular = 1')->count() : 0;
        $irregularCount  = $hasIsRegular ? (clone $baseStats)->whereRaw('latest_enrollments.is_regular = 0')->count() : 0;

        foreach ($students as $student) {
            $student->program = $student->course_code ?: 'N/A';

            if (!empty($student->year_level)) {
                $parts = explode('|', $student->year_level);
                $student->year_display = trim($parts[0]);
            } else {
                $student->year_display = 'N/A';
            }

            
            // Level: Determine if SHS or College based on course code
            $shsStrands = ['STEM', 'HUMMS', 'HUMSS', 'GAS', 'ABM', 'HE', 'ICT'];
            $student->level = in_array($student->course_code, $shsStrands) ? 'SHS' : 'COLLEGE';

            $student->display_email = $student->email;


            $student->display_email   = $student->email;

            $student->display_account = $student->username ?: 'N/A';

            // Compute warning flags inline
            $student->has_warning = (
                ($student->student_type === 'Transferee' && !$student->credentials_verified) ||
                !$student->physical_documents_received
            );
        }

        $pendingCount = Enrollment::where('status', 'Pending')->count();

        return view('livewire.registrar-student-manager', [
            'students'                  => $students,
            'pendingCount'              => $pendingCount,
            'classificationReasons'     => Enrollment::CLASSIFICATION_REASONS,
            'shsClassificationReasons'  => Enrollment::SHS_CLASSIFICATION_REASONS,
            'totalStudents'             => $totalStudents,
            'regularCount'              => $regularCount,
            'irregularCount'            => $irregularCount,
        ])->layout('components.layouts.registrar', ['title' => 'Student Registry']);
    }
}
