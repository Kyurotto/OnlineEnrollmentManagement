<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarStudentManager extends Component
{
    use WithPagination;

    // Edit Modal properties
    public $showEditModal = false;
    public $editingStudentId;
    public $first_name, $middle_name, $last_name, $email, $status;

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $this->editingStudentId = $student->id;
        $this->first_name = $student->first_name;
        $this->middle_name = $student->middle_name;
        $this->last_name = $student->last_name;
        $this->email = $student->email;
        $this->status = $student->status;
        
        $this->showEditModal = true;
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->reset(['editingStudentId', 'first_name', 'middle_name', 'last_name', 'email', 'status']);
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->editingStudentId)],
            'status'      => ['nullable', 'string'],
        ]);

        $student = User::findOrFail($this->editingStudentId);
        
        $student->update([
            'first_name'  => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name'   => $this->last_name,
            'name'        => $this->first_name . ' ' . $this->last_name,
            'email'       => $this->email,
            'status'      => $this->status ?? $student->status,
        ]);

        session()->flash('success', 'Student updated successfully.');
        $this->closeModal();
    }

    public function render()
    {
        // 1. Fetch official students (Approved/Enrolled only)
        $students = User::where('role', 'student')
                        ->whereIn('id', Enrollment::whereIn('status', ['Enrolled', 'Approved'])->pluck('user_id')->toArray())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10); 

        // 2. Attach Program, Section (Year only), and Account details
        foreach ($students as $student) {
            $enrollment = Enrollment::with('course')
                                    ->where('user_id', $student->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                    
            // Program sync logic
            if ($enrollment && $enrollment->course && !empty($enrollment->course->name)) {
                $student->program = $enrollment->course->name;
            } elseif ($enrollment && !empty($enrollment->course_code)) {
                $student->program = $enrollment->course_code;
            } else {
                $student->program = 'N/A';
            }

            // Section: Extracts only the Year Level (e.g., "1st Year")
            if ($enrollment && !empty($enrollment->year_level)) {
                $parts = explode('|', $enrollment->year_level);
                $student->year_display = trim($parts[0]);
            } else {
                $student->year_display = 'N/A';
            }
            
            // EMAIL column gets the full email
            $student->display_email = $student->email;
            
            // USER ACCOUNT column gets the short username
            $student->display_account = $student->username ?: 'N/A';
        }

        return view('livewire.registrar-student-manager', compact('students'));
    }
}
