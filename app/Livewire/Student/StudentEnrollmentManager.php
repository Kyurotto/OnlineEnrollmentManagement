<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Course;

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

        // Save progress to session draft (Exclude file objects which are non-serializable)
        if (!in_array($property, ['form_137', 'good_moral', 'psa', 'id_picture'])) {
            $draft = session()->get('enrollment_draft_' . Auth::id(), []);
            $draft[$property] = $this->$property;
            session()->put('enrollment_draft_' . Auth::id(), $draft);
        }
    }

    public function submitEnrollment()
    {
        $this->validate([
            'course_code' => 'required',
            'year_level' => 'required',
            'semester' => 'required',
            'academic_year' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date',
            'age' => 'required|numeric',
            'gender' => 'required',
            'contact' => 'required',
            'form_137' => 'nullable|file|max:5120',
            'good_moral' => 'nullable|file|max:5120',
            'psa' => 'nullable|file|max:5120',
            'id_picture' => 'nullable|image|max:2048',
        ]);

        $course = Course::where('course_code', $this->course_code)
                        ->where('type', 'program')
                        ->first();

        if (!$course) {
            session()->flash('error', 'Selected program is invalid or not registered in the system.');
            return;
        }

        // Handle File Uploads
        $paths = [];
        foreach (['form_137' => 'form_138_path', 'good_moral' => 'good_moral_path', 'psa' => 'psa_path', 'id_picture' => 'id_picture_path'] as $field => $dbField) {
            if ($this->$field) {
                $paths[$dbField] = $this->$field->store('enrollments/docs', 'public');
            }
        }

        // Unified Year Level String: "Year | Semester | Academic Year"
        $unifiedYearLevel = "{$this->year_level} | {$this->semester} | {$this->academic_year}";

        $enrollment = Enrollment::create(array_merge([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'course_code' => $this->course_code,
            'year_level' => $unifiedYearLevel,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'age' => $this->age,
            'gender' => $this->gender,
            'email' => $this->email,
            'contact' => $this->contact,
            'address_full' => implode(', ', array_filter([$this->house_no, $this->street, $this->barangay, $this->city, $this->province, $this->zip])),
            'father_name' => $this->father_name,
            'mother_maiden_name' => $this->mother_maiden_name,
            'guardian_name' => $this->guardian_name,
            'guardian_contact' => $this->guardian_contact,
            'status' => 'Pending',
        ], $paths));

        // Auto-create 1,000 PHP Downpayment for the Cashier/Student History
        \App\Models\Payment::create([
            'user_id' => Auth::id(),
            'application_id' => $enrollment->id,
            'amount' => 1000,
            'status' => 'Pending',
            'payment_date' => now(),
            'payment_method' => 'Cash',
        ]);

        // Clear draft on successful submission
        session()->forget('enrollment_draft_' . Auth::id());

        session()->flash('success', 'Enrollment application submitted successfully to the Registrar.');
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