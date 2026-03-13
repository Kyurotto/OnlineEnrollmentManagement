<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Enrollment;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class StudentManager extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $enrolledUserIds = Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray();

        $query = User::where('role', 'student')->whereIn('id', $enrolledUserIds);

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        foreach ($students as $student) {
            $enrollment = Enrollment::with('course')
                                    ->where('user_id', $student->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                    
            if ($enrollment && $enrollment->course && !empty($enrollment->course->name)) {
                $student->program = $enrollment->course->name;
            } elseif ($enrollment && !empty($enrollment->course_code)) {
                $student->program = $enrollment->course_code;
            } else {
                $student->program = 'N/A';
            }

            if ($enrollment && !empty($enrollment->year_level)) {
                $parts = explode('|', $enrollment->year_level);
                $student->year_display = trim($parts[0]);
            } else {
                $student->year_display = 'N/A';
            }
            
            $student->display_email = $student->email;
            $student->display_account = $student->username ?: 'N/A';
        }

        return view('livewire.admin.student-manager', [
            'students' => $students
        ]);
    }
}
