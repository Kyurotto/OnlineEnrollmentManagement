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

    protected $queryString = ['search', 'sortField', 'sortDirection', 'filter'];
    private const ALLOWED_FILTERS = ['all', 'regular', 'irregular'];

    public function mount(): void
    {
        $this->filter = $this->normalizeFilter($this->filter);
    }

    private function debugLog(string $hypothesisId, string $message, array $data = [], string $runId = 'pre-fix'): void
    {
        $payload = [
            'sessionId' => 'c6b285',
            'runId' => $runId,
            'hypothesisId' => $hypothesisId,
            'location' => 'app/Livewire/RegistrarStudentManager.php',
            'message' => $message,
            'data' => $data,
            'timestamp' => (int) round(microtime(true) * 1000),
        ];

        @file_put_contents(base_path('debug-c6b285.log'), json_encode($payload) . PHP_EOL, FILE_APPEND);
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
        $this->classificationIsRegular = $enrollment->is_regular !== false; // true if regular or null
        $this->showClassificationModal = true;
    }

    public function closeClassificationModal()
    {
        $this->showClassificationModal = false;
        $this->classificationEnrollmentId = null;
        $this->classificationReason = '';
        $this->classificationIsRegular = true;
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
            'classificationReason' => 'required|string|in:' . implode(',', array_keys(Enrollment::CLASSIFICATION_REASONS)),
        ]);

        $enrollment->is_regular = false;
        $enrollment->classification_reason = $this->classificationReason;
        $enrollment->last_audited_at = now();
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
        // #region agent log
        $this->debugLog('H1', 'RegistrarStudentManager::render entered', [
            'filter' => $this->filter,
            'sortField' => $this->sortField,
        ]);
        // #endregion

        $hasIsRegular = Schema::hasColumn('enrollments', 'is_regular');

        $optionalEnrollmentColumns = [
            'promissory_reason',
            'is_regular',
            'classification_reason',
            'credentials_verified',
            'student_type',
            'physical_documents_received',
        ];
        $enrollmentSelect = ['user_id', 'course_code', 'year_level', 'status', 'id'];
        foreach ($optionalEnrollmentColumns as $column) {
            $enrollmentSelect[] = Schema::hasColumn('enrollments', $column)
                ? $column
                : DB::raw("NULL as {$column}");
        }

        // #region agent log
        $this->debugLog('H5', 'Schema-safe enrollment select prepared', [
            'has_is_regular' => $hasIsRegular,
            'enrollment_select_count' => count($enrollmentSelect),
        ], 'post-fix');
        // #endregion

        // #region agent log
        $this->debugLog('H2', 'Enrollment optional column presence', [
            'is_regular' => Schema::hasColumn('enrollments', 'is_regular'),
            'classification_reason' => Schema::hasColumn('enrollments', 'classification_reason'),
            'credentials_verified' => Schema::hasColumn('enrollments', 'credentials_verified'),
            'student_type' => Schema::hasColumn('enrollments', 'student_type'),
            'physical_documents_received' => Schema::hasColumn('enrollments', 'physical_documents_received'),
            'promissory_reason' => Schema::hasColumn('enrollments', 'promissory_reason'),
        ]);
        // #endregion

        // #region agent log
        $this->debugLog('H3', 'Hardcoded render subquery columns include optional fields', [
            'subquery_columns' => ['promissory_reason', 'is_regular', 'classification_reason', 'credentials_verified', 'student_type', 'physical_documents_received'],
        ]);
        // #endregion

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
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved']);

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

        // Filter by classification
        if ($this->filter === 'regular' && $hasIsRegular) {
            $query->whereRaw('latest_enrollments.is_regular = 1');
        } elseif ($this->filter === 'irregular' && $hasIsRegular) {
            $query->whereRaw('latest_enrollments.is_regular = 0');
        }

        $students = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Stats
        // #region agent log
        $this->debugLog('H4', 'Stats subquery hardcodes is_regular', [
            'stats_columns' => ['user_id', 'id', 'is_regular', 'status'],
        ]);
        // #endregion

        $baseStats = User::query()
            ->joinSub(
                Enrollment::select(
                    'user_id',
                    'id',
                    'status',
                    $hasIsRegular ? 'is_regular' : DB::raw('NULL as is_regular')
                )
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved']);

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
            'students'              => $students,
            'pendingCount'          => $pendingCount,
            'classificationReasons' => Enrollment::CLASSIFICATION_REASONS,
            'totalStudents'         => $totalStudents,
            'regularCount'          => $regularCount,
            'irregularCount'        => $irregularCount,
        ])->layout('components.layouts.registrar', ['title' => 'Student Registry']);
    }
}
