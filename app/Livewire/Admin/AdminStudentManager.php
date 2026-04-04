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

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

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
            ->select('users.*', 'latest_enrollments.course_code', 'latest_enrollments.year_level', 'courses.course_name')
            ->joinSub(
                Enrollment::select('user_id', 'course_code', 'year_level', 'status', 'promissory_reason')
                    ->whereIn('id', function($q) {
                        $q->selectRaw('MAX(id)')->from('enrollments')->groupBy('user_id');
                    }),
                'latest_enrollments',
                'users.id', '=', 'latest_enrollments.user_id'
            )
            ->leftJoin('courses', 'latest_enrollments.course_code', '=', 'courses.course_code')
            ->where('users.role', 'student')
            ->whereIn('latest_enrollments.status', ['Enrolled', 'Approved']);

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

        return view('livewire.admin.admin-student-manager', [
            'students' => $students
        ])->layout('components.layouts.admin', ['title' => 'Student Population Registry']);
    }
}
