<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.registrar')]
class RegistrarApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'All statuses';
    public $sortField = 'enrollments.id';
    public $sortDirection = 'desc';
    public $year_level = 'All Years';
    public $course_filter = 'All Programs';
    public $section_filter = 'All Sections';
    public $level = null;

    public function mount()
    {
        if (request()->routeIs('registrar.applications.college')) {
            $this->level = 'college';
        } elseif (request()->routeIs('registrar.applications.shs')) {
            $this->level = 'shs';
        }
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'All statuses'],
        'year_level' => ['except' => 'All Years'],
        'course_filter' => ['except' => 'All Programs'],
        'section_filter' => ['except' => 'All Sections'],
        'sortField' => ['except' => 'enrollments.id'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingYearLevel() { $this->resetPage(); }
    public function updatingCourseFilter() { $this->resetPage(); $this->section_filter = 'All Sections'; }
    public function updatingSectionFilter() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public $selectedId;

    public function selectApplication($id)
    {
        $this->selectedId = $id;
    }

    public function approve($id, $classType = null, $classReason = null)
    {
        $application = Enrollment::findOrFail($id);

        // Apply classification if provided
        if ($classType === 'regular') {
            $application->is_regular = true;
            $application->classification_reason = null;
        } elseif ($classType === 'irregular') {
            $application->is_regular = false;
            $application->classification_reason = $classReason;
        }

        // Finalize enrollment status
        $application->status = 'Enrolled';
        $application->save();

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }

        session()->flash('success', 'Application status updated to Enrolled and archived.');
    }

    public function reject($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = 'Rejected';
        $application->save();

        session()->flash('success', 'Application status updated to Rejected.');
    }

    public function togglePhysicalDocuments($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->physical_documents_received = !$application->physical_documents_received;
        $application->save();

        $status = $application->physical_documents_received ? 'marked as received' : 'unmarked';
        session()->flash('success', "Physical documents $status.");

        // Emit event to reset Alpine state
        $this->dispatch('modal-reset');
    }

    public function verifyCredentials($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = true;
        $enrollment->is_regular = true;
        $enrollment->classification_reason = null;
        $enrollment->last_audited_at = now();
        $enrollment->save();

        // Log the clearance approval
        $adminUser = auth()->user();
        if ($adminUser) {
            $studentName = $enrollment->user ? $enrollment->user->first_name . ' ' . $enrollment->user->last_name : 'Unknown Student';
            ActivityLog::create([
                'user_id' => $adminUser->id,
                'action' => 'clearance_approved',
                'target_type' => 'Enrollment',
                'target_id' => $enrollment->id,
                'description' => 'Approved clearance for ' . $studentName . ' (' . $enrollment->course_code . ')',
            ]);
        }

        session()->flash('success', 'Clearance approved for student.');
        $this->dispatch('modal-reset');
    }

    public function grantClearance($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = true;
        $enrollment->last_audited_at = now();
        $enrollment->save();

        // Log the clearance approval
        $adminUser = auth()->user();
        if ($adminUser) {
            $studentName = $enrollment->user ? $enrollment->user->first_name . ' ' . $enrollment->user->last_name : 'Unknown Student';
            ActivityLog::create([
                'user_id' => $adminUser->id,
                'action' => 'clearance_approved',
                'target_type' => 'Enrollment',
                'target_id' => $enrollment->id,
                'description' => 'Granted registrar clearance for ' . $studentName . ' (' . $enrollment->course_code . ')',
            ]);
        }

        session()->flash('success', 'Registrar clearance granted.');
        $this->dispatch('modal-reset');
    }

    public function revokeClearance($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = false;
        $enrollment->save();

        // Log the clearance revocation
        $adminUser = auth()->user();
        if ($adminUser) {
            $studentName = $enrollment->user ? $enrollment->user->first_name . ' ' . $enrollment->user->last_name : 'Unknown Student';
            ActivityLog::create([
                'user_id' => $adminUser->id,
                'action' => 'clearance_revoked',
                'target_type' => 'Enrollment',
                'target_id' => $enrollment->id,
                'description' => 'Revoked clearance for ' . $studentName . ' (' . $enrollment->course_code . ')',
            ]);
        }

        session()->flash('success', 'Registrar clearance revoked.');
        $this->dispatch('modal-reset');
    }

    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        session()->flash('success', 'Application record deleted.');
    }

    public function applyVoucher($id, $voucherType)
    {
        $application = Enrollment::findOrFail($id);
        $application->voucher_type = $voucherType;
        $application->voucher_applied_at = now();
        $application->save();

        $voucherLabel = $voucherType === 'free_tuition' ? 'Free Tuition' : 'Discounted';
        session()->flash('success', "Voucher ($voucherLabel) applied successfully.");

        $this->dispatch('modal-reset');
    }

    public function removeVoucher($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->voucher_type = null;
        $application->voucher_applied_at = null;
        $application->save();

        session()->flash('success', 'Voucher removed successfully.');

        $this->dispatch('modal-reset');
    }

    public function setClassification($id, $type, $reason = null)
    {
        $enrollment = Enrollment::findOrFail($id);

        if ($type === 'regular') {
            $enrollment->is_regular = true;
            $enrollment->classification_reason = null;
        } elseif ($type === 'irregular') {
            $enrollment->is_regular = false;
            $enrollment->classification_reason = $reason;
        }

        $enrollment->last_audited_at = now();
        $enrollment->save();

        $label = $type === 'regular' ? 'Regular' : 'Irregular';
        session()->flash('success', "Student classification set to {$label}.");

        $this->dispatch('modal-reset');
    }

    public function render()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $query = Enrollment::query()
            ->select('enrollments.*')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->hasUploadsOrVerified()
            ->with(['user']);

        if ($this->level) {
            $query->where('enrollments.level', $this->level);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($sub) {
                    $sub->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                })
                ->orWhere('enrollments.course_code', 'like', "%{$this->search}%")
                ->orWhere('enrollments.promissory_reason', 'like', "%{$this->search}%");
            });
        }

        // Include archived records only when they still need clearance
        $query->where(function ($q) {
            $q->whereNull('enrollments.archived_at')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('enrollments.archived_at')
                      ->where('enrollments.credentials_verified', false);
              });
        });

        if ($this->status !== 'All statuses') {
            $query->where('enrollments.status', $this->status);
        } else {
            // ALSO hide currently active term 'Enrolled' students
            $query->where(function ($q) use ($activeYear, $activeSemester) {
                        $q->where('enrollments.status', '!=', 'Enrolled');

                        if ($activeYear && $activeSemester) {
                            $q->orWhere(function($sub) use ($activeYear, $activeSemester) {
                                $sub->where('enrollments.status', 'Enrolled')
                                    ->where(function($termQuery) use ($activeYear, $activeSemester) {
                                        $termQuery->where('enrollments.year_level', 'NOT LIKE', "%{$activeYear->year_name}%")
                                                  ->orWhere('enrollments.year_level', 'NOT LIKE', "%{$activeSemester->name}%");
                                    });
                            });
                        }
                  });
        }

        if ($this->year_level !== 'All Years') {
            $query->where('enrollments.year_level', 'like', "{$this->year_level}%");
        }

        if ($this->course_filter !== 'All Programs') {
            $query->where('enrollments.course_code', $this->course_filter);
        }

        if ($this->section_filter !== 'All Sections') {
            // Find the numeric year from section name (e.g. "1A" -> "1")
            preg_match('/\d+/', $this->section_filter, $matches);
            if (!empty($matches)) {
                $yearNum = $matches[0];
                $query->where('enrollments.year_level', 'like', "{$yearNum}%");
            }
        }

        $applications = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Manual Eager Load for 'course' based on course_code
        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        // Transform each item in the collection
        $applications->getCollection()->transform(function ($application) use ($courses, $activeYear, $activeSemester) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }

            // Simplify year level display (e.g., "1st Year | 2nd Semester" -> "1st Year")
            if (!empty($application->year_level)) {
                $parts = explode('|', $application->year_level);
                $application->year_display = trim($parts[0]);
            } else {
                $application->year_display = 'N/A';
            }

            // Calculate Student Classification (New vs Returning)
            // A student is returning if they have an older record OR if this record is from a previous term
            $hasPreviousRecord = Enrollment::where('user_id', $application->user_id)
                ->where('id', '<', $application->id)
                ->exists();

            $isOldTermRecord = $activeYear && $activeSemester
                ? ($application->academic_year_name !== $activeYear->year_name || $application->semester_name !== $activeSemester->name)
                : false;

            $isReturning = $hasPreviousRecord || $isOldTermRecord;

            $application->classification = $application->student_type
                ?? ($isReturning ? 'Returning' : 'New');

            // AUTO-INHERIT CLEARANCE: If this returning student was already cleared in a previous/archived record,
            // inherit that clearance to their current record so the APPROVE CLEARANCE button hides
            if ($isReturning && !$isOldTermRecord && !$application->credentials_verified) {
                $wasCleared = Enrollment::where('user_id', $application->user_id)
                    ->where('id', '!=', $application->id)
                    ->where('credentials_verified', true)
                    ->exists();

                if ($wasCleared) {
                    // Use direct DB update to avoid persisting dynamic attributes (year_display, classification)
                    \Illuminate\Support\Facades\DB::table('enrollments')
                        ->where('id', $application->id)
                        ->update([
                            'credentials_verified' => true,
                            'physical_documents_received' => true,
                            'updated_at' => now(),
                        ]);
                    $application->credentials_verified = true;
                    $application->physical_documents_received = true;
                }
            }

            // Status Override for Term Transitions
            $isCurrentTerm = $activeYear && $activeSemester &&
                $application->academic_year_name === $activeYear->year_name &&
                $application->semester_name === $activeSemester->name;

            if (!$isCurrentTerm && $activeYear && $activeSemester) {
                $application->status = 'Pending';
            }

            return $application;
        });

        // 2. Count pending applications for the header badge (respecting the current level)
        $pendingCountQuery = Enrollment::where('status', 'Pending')->hasUploadsOrVerified();
        if ($this->level) {
            $pendingCountQuery->where('level', $this->level);
        }
        $pendingCount = $pendingCountQuery->count();

        // 3. Fetch courses based on level
        $coursesQuery = Course::query();
        if ($this->level === 'shs') {
            $coursesQuery->where('type', 'shs');
        } elseif ($this->level === 'college') {
            $coursesQuery->where('type', 'program');
        }
        $availableCourses = $coursesQuery->get();

        // Fetch Sections for the selected course
        $sections = collect();
        if ($this->course_filter !== 'All Programs') {
            $course = Course::where('course_code', $this->course_filter)->first();
            if ($course) {
                $sections = \App\Models\Section::where('course_id', $course->id)->get();
            }
        }

        return view('livewire.registrar-application-manager', [
            'applications' => $applications,
            'pendingCount' => $pendingCount,
            'courses' => $availableCourses,
            'sections' => $sections,
            'activeYear' => $activeYear,
            'activeSemester' => $activeSemester
        ]);
    }
}
