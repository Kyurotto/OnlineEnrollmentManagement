<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\AcademicYear;

class StudentEnrollmentManager extends Component
{
    use WithFileUploads;

    public $course_code;
    public $year_level;
    public $semester;
    public $academic_year;

    public $last_name, $first_name, $middle_name, $birth_date, $age, $gender;
    public $religion, $birthplace, $email, $contact, $ip_community;
    public $house_no, $street, $barangay, $city, $province, $zip;
    public $father_name, $guardian_name, $mother_maiden_name, $guardian_contact;

    public $form_137, $good_moral, $psa, $id_picture;

    public function mount()
    {
        $user = Auth::user();
        if($user) {
            $this->email = $user->email;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->middle_name = $user->middle_name;
        }

        // Restore draft from session
        $draft = session()->get('enrollment_draft_' . Auth::id(), []);
        foreach ($draft as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function updated($property)
    {
        // Simple input filtering for contact numbers (digits only)
        if ($property === 'contact' || $property === 'guardian_contact') {
            $this->$property = preg_replace('/[^0-9]/', '', $this->$property);
        }

        // Save progress to session draft
        $draft = session()->get('enrollment_draft_' . Auth::id(), []);
        $draft[$property] = $this->$property;
        session()->put('enrollment_draft_' . Auth::id(), $draft);
    }

    public function submitEnrollment()
    {
        $this->validate([
            'course_code' => 'required',
            'year_level' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact' => 'required',
        ]);

        // Clear draft on successful submission
        session()->forget('enrollment_draft_' . Auth::id());

        // Place your enrollment creation and file upload logic here

        session()->flash('success', 'Enrollment application submitted successfully.');
        return redirect()->route('student.dashboard');
    }

    public function render()
    {
        return view('livewire.student.student-enrollment-manager', [
            'semesters' => Semester::all(),
            'activeSemester' => Semester::where('is_active', true)->first(),
            'academicYears' => AcademicYear::all(),
            'activeYear' => AcademicYear::where('is_active', true)->first(),

        ])->layout('components.layouts.student');
    }
}