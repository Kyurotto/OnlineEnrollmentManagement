<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin', ['title' => 'Applications'])]
class AdminApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'All statuses';
    public $sortField = 'enrollments.id';
    public $sortDirection = 'desc';
    public $year_level = 'All Years';
    public $course_filter = 'All Programs';
    public $section_filter = 'All Sections';
    public $level = 'All Levels';
    public $selectedId;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'All statuses'],
        'year_level' => ['except' => 'All Years'],
        'course_filter' => ['except' => 'All Programs'],
        'section_filter' => ['except' => 'All Sections'],
        'level' => ['except' => 'All Levels'],
        'sortField' => ['except' => 'enrollments.id'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingYearLevel() { $this->resetPage(); }
    public function updatingCourseFilter() { $this->resetPage(); $this->section_filter = 'All Sections'; }
    public function updatingSectionFilter() { $this->resetPage(); }
    public function updatingLevel() { $this->resetPage(); $this->course_filter = 'All Programs'; }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

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

    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        session()->flash('success', 'Application record deleted successfully.');
    }

    public function render()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $query = Enrollment::with('user')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->select('enrollments.*');

        // Only show applications for the current active term
        if ($activeYear && $activeSemester) {
            $query->where('enrollments.year_level', 'LIKE', "%{$activeYear->year_name}%")
                  ->where('enrollments.year_level', 'LIKE', "%{$activeSemester->name}%");
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

        if ($this->level !== 'All Levels') {
            $query->where('enrollments.level', strtolower($this->level));
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

        // Simplify year level display for each item
        $applications->getCollection()->transform(function ($application) {
            // Simplify year level display (e.g., "1st Year | 2nd Semester" -> "1st Year")
            if (!empty($application->year_level)) {
                $parts = explode('|', $application->year_level);
                $application->year_display = trim($parts[0]);
            } else {
                $application->year_display = 'N/A';
            }
            return $application;
        });

        // Fetch College Programs and SHS Strands separately for grouping
        $collegePrograms = \App\Models\Course::where('type', 'program')->get();
        $shsStrands = \App\Models\Course::where('type', 'shs')->get();

        // Fetch Sections for the selected course
        $sections = collect();
        if ($this->course_filter !== 'All Programs') {
            $course = \App\Models\Course::where('course_code', $this->course_filter)->first();
            if ($course) {
                $sections = \App\Models\Section::where('course_id', $course->id)->get();
            }
        }

        return view('livewire.admin.admin-application-manager', [
            'applications' => $applications,
            'collegePrograms' => $collegePrograms,
            'shsStrands' => $shsStrands,
            'sections' => $sections
        ]);
    }
}
