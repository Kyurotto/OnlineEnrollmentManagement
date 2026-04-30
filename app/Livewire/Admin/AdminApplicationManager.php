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

        // ALWAYS exclude archived records from the main applications list
        $query->whereNull('enrollments.archived_at');

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
        $applications->getCollection()->transform(function ($application) use ($activeYear) {
            // Simplify year level display (e.g., "1st Year | 2nd Semester" -> "1st Year")
            if (!empty($application->year_level)) {
                $parts = explode('|', $application->year_level);
                $application->year_display = trim($parts[0]);
            } else {
                $application->year_display = 'N/A';
            }

            // Calculate Student Classification (New vs Returning)
            $isReturning = Enrollment::where('user_id', $application->user_id)
                ->where('id', '<', $application->id)
                ->exists();
            $application->classification = $application->student_type 
                ?? ($isReturning ? 'Returning' : 'New');

            // AUTO-INHERIT CLEARANCE: If this returning student was already cleared in a previous/archived record,
            // inherit that clearance to their current record so the APPROVE CLEARANCE button hides
            if ($isReturning && !$application->credentials_verified) {
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
            // If the application is not for the current active year, mark as Pending
            if ($activeYear && stripos((string)$application->year_level, $activeYear->year_name) === false) {
                $application->status = 'Pending';
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
