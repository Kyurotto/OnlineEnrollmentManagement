<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Enrollment;

class AdminStudentManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'users.id';
    public $sortDirection = 'desc';
    public $course_filter = 'All Programs';
    public $section_filter = 'All Sections';
    public $level = 'All Levels';
    public $year_level = 'All Years';

    protected $queryString = ['search', 'sortField', 'sortDirection', 'course_filter', 'section_filter', 'level', 'year_level'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCourseFilter() { $this->resetPage(); $this->section_filter = 'All Sections'; }
    public function updatingSectionFilter() { $this->resetPage(); }
    public function updatingLevel() { $this->resetPage(); $this->course_filter = 'All Programs'; }
    public function updatingYearLevel() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = User::query()
            ->select('users.*', 'latest_enrollments.course_code', 'latest_enrollments.year_level', 'latest_enrollments.level', 'courses.course_name')
            ->joinSub(
                Enrollment::select('user_id', 'course_code', 'year_level', 'level', 'status', 'promissory_reason')
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved']);

        if ($this->course_filter !== 'All Programs') {
            $query->where('latest_enrollments.course_code', $this->course_filter);
        }

        if ($this->level !== 'All Levels') {
            $query->where('latest_enrollments.level', strtolower($this->level));
        }

        if ($this->year_level !== 'All Years') {
            $query->where('latest_enrollments.year_level', 'like', "{$this->year_level}%");
        }

        if ($this->section_filter !== 'All Sections') {
            // Find the numeric year from section name (e.g. "1A" -> "1")
            preg_match('/\d+/', $this->section_filter, $matches);
            if (!empty($matches)) {
                $yearNum = $matches[0];
                $query->where('latest_enrollments.year_level', 'like', "{$yearNum}%");
            }
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('users.first_name', 'like', "%{$this->search}%")
                  ->orWhere('users.last_name', 'like', "%{$this->search}%")
                  ->orWhere('users.email', 'like', "%{$this->search}%")
                  ->orWhere('users.username', 'like', "%{$this->search}%")
                  ->orWhere('latest_enrollments.course_code', 'like', "%{$this->search}%")
                  ->orWhere('latest_enrollments.promissory_reason', 'like', "%{$this->search}%");
            });
        }

        $students = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        foreach ($students as $student) {
            // Program sync: Displays only the Course Code (e.g., BSIS)
            $student->program = $student->course_code ?: 'N/A';

            // Section: Displays the Year Level (e.g., "1st Year")
            if (!empty($student->year_level)) {
                $parts = explode('|', $student->year_level);
                $student->year_display = trim($parts[0]);
            } else {
                $student->year_display = 'N/A';
            }
        }

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

        return view('livewire.admin.admin-student-manager', [
            'students' => $students,
            'collegePrograms' => $collegePrograms,
            'shsStrands' => $shsStrands,
            'sections' => $sections
        ])->layout('components.layouts.admin', ['title' => 'Student Population Registry']);
    }
}
