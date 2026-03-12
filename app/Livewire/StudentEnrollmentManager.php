<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\User;

class StudentEnrollmentManager extends Component
{
    use WithFileUploads;

    public $course_code;
    public $year_level;
    public $semester;
    public $academic_year;
    
    // Student Info
    public $last_name;
    public $first_name;
    public $middle_name;
    public $birth_date;
    public $age;
    public $gender;
    public $religion;
    public $birthplace;
    public $email;
    public $contact;
    public $ip_community;

    // Address
    public $house_no;
    public $street;
    public $barangay;
    public $city;
    public $province;
    public $zip;

    // Guardians
    public $father_name;
    public $guardian_name;
    public $mother_maiden_name;
    public $guardian_contact;

    // Files
    public $form_138;
    public $good_moral;
    public $psa;
    public $id_picture;

    public function mount()
    {
        $user = Auth::user();
        $this->last_name = $user->last_name;
        $this->first_name = $user->first_name;
        $this->middle_name = $user->middle_name;
        $this->email = $user->email;

        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Redirect if already enrolled for current year
        if ($activeYear) {
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->whereIn('status', ['Enrolled', 'Approved', 'Pending'])
                ->where('year_level', 'LIKE', '%' . $activeYear->year_name . '%')
                ->exists();
            
            if ($isEnrolled) {
                session()->flash('error', 'You are already enrolled for this academic year.');
                return redirect()->route('student.dashboard');
            }
        }

        if ($activeSemester) {
            $this->semester = $activeSemester->name;
        }
        if ($activeYear) {
            $this->academic_year = $activeYear->year_name;
        }
    }

    public function submitApplication()
    {
        $user = Auth::user();

        $this->validate([
            'course_code' => 'required',
            'year_level'  => 'required',
            'first_name'  => 'required',
            'last_name'   => 'required',
            'birth_date'  => 'required',
            'contact'     => 'required|regex:/^[0-9]{11}$/',
            'form_138'    => 'nullable|max:5120',
            'good_moral'  => 'nullable|max:5120',
            'psa'         => 'nullable|max:5120',
            'id_picture'  => 'nullable|image|max:5120',
        ]);

        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $semesterToSave = $this->semester ?? ($activeSemester ? $activeSemester->name : 'Unknown');
        $academicYearToSave = $this->academic_year ?? ($activeYear ? $activeYear->year_name : 'Unknown');

        $course = Course::where('course_code', $this->course_code)->first();
        if (!$course) {
            $this->addError('course_code', 'Invalid Course Code.');
            return;
        }

        $fullAddress = trim($this->house_no . ' ' . $this->street . ', ' . $this->barangay . ', ' . $this->city . ', ' . $this->province . ' ' . $this->zip);

        $enrollment = Enrollment::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status'      => 'Pending',
                'course_id'   => $course->id, 
                'course_code' => $this->course_code, 
                'year_level'  => $this->year_level . ' | ' . $semesterToSave . ' | ' . $academicYearToSave,
                'first_name'  => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name'   => $this->last_name,
                'birth_date'  => $this->birth_date,
                'age'         => $this->age ?? 0,
                'gender'      => $this->gender ?? 'Not Specified',
                'religion'    => $this->religion,
                'birthplace'  => $this->birthplace,
                'email'       => $this->email,
                'contact'     => $this->contact,
                'address_full'=> $fullAddress,
                'father_name'        => $this->father_name,
                'mother_maiden_name' => $this->mother_maiden_name,
                'guardian_name'      => $this->guardian_name,
                'guardian_contact'   => $this->guardian_contact,
            ]
        );

        if ($this->form_138) {
            $enrollment->form_138_path = $this->form_138->store('documents/form138', 'public');
        }
        if ($this->good_moral) {
            $enrollment->good_moral_path = $this->good_moral->store('documents/good_moral', 'public');
        }
        if ($this->psa) {
            $enrollment->psa_path = $this->psa->store('documents/psa', 'public');
        }
        if ($this->id_picture) {
            $enrollment->id_picture_path = $this->id_picture->store('documents/id_pictures', 'public');
        }
        $enrollment->save();

        Payment::updateOrCreate(
            ['user_id' => $user->id, 'application_id' => $enrollment->id],
            ['amount' => 1000.00, 'status' => 'Pending', 'payment_date' => now()]
        );

        User::where('id', $user->id)->update(['status' => 'Pending']);

        session()->flash('success', 'Application submitted successfully!');
        
        // This JS event will clear the autosave standard before redirecting
        $this->dispatch('application-submitted');

        return redirect()->route('student.dashboard');
    }

    public function render()
    {
        $semesters = Semester::whereNotIn('name', ['1st Semester', '2nd Semester'])->orderBy('id', 'desc')->get();
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        
        $activeSemester = Semester::where('is_active', true)->first();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('livewire.student-enrollment-manager', compact('semesters', 'academicYears', 'activeSemester', 'activeYear'))->layout('components.layouts.student');
    }
}
