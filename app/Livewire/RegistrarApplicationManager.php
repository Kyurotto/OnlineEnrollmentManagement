<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;

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

    public function approve($id)
    {
        $application = Enrollment::findOrFail($id);

        // Finalize enrollment status
        $application->status = 'Enrolled';
        $application->save();

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }

        session()->flash('success', 'Application status updated to Enrolled (Paid).');
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

        session()->flash('success', 'Clearance approved for student.');
        $this->dispatch('modal-reset');
    }

    public function grantClearance($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = true;
        $enrollment->last_audited_at = now();
        $enrollment->save();

        session()->flash('success', 'Registrar clearance granted.');
        $this->dispatch('modal-reset');
    }

    public function revokeClearance($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->credentials_verified = false;
        $enrollment->save();

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

    public function render()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $query = Enrollment::query()
            ->select('enrollments.*')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->with(['user']);

        // Only show applications for the current active term
        if ($activeYear && $activeSemester) {
            $query->where('enrollments.year_level', 'LIKE', "%{$activeYear->year_name}%")
                  ->where('enrollments.year_level', 'LIKE', "%{$activeSemester->name}%");
        }

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

        if ($this->status !== 'All statuses') {
            $query->where('enrollments.status', $this->status);
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
        $applications->getCollection()->transform(function ($application) use ($courses) {
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
            return $application;
        });

        // 2. Count pending applications for the header badge (respecting the current level)
        $pendingCountQuery = Enrollment::where('status', 'Pending');
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
            'sections' => $sections
        ]);
    }
}
