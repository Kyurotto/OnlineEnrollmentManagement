<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CourseManager extends Component
{
    public $courses;
    
    // Form fields
    public $course_id;
    public $course_code;
    public $course_name;
    public $credits = 3;
    public $description;

    public $isEditMode = false;

    protected $rules = [
        'course_code' => 'required|string|max:50',
        'course_name' => 'required|string|max:255',
        'credits'     => 'required|integer|min:1',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $this->courses = Course::all();
    }

    public function store()
    {
        $this->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'credits'     => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Course::create([
            'course_code' => $this->course_code,
            'course_name' => $this->course_name,
            'credits'     => $this->credits,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Course added successfully!');
        $this->resetFields();
        $this->loadCourses();
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $this->course_id = $id;
        $this->course_code = $course->course_code;
        $this->course_name = $course->course_name;
        $this->credits = $course->credits;
        $this->description = $course->description;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $this->course_id,
            'course_name' => 'required|string|max:255',
            'credits'     => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        if ($this->course_id) {
            $course = Course::find($this->course_id);
            $course->update([
                'course_code' => $this->course_code,
                'course_name' => $this->course_name,
                'credits'     => $this->credits,
                'description' => $this->description,
            ]);
            $this->isEditMode = false;
            $this->resetFields();
            $this->loadCourses();
            session()->flash('success', 'Course updated successfully!');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            Course::findOrFail($id)->delete();
            $this->loadCourses();
            session()->flash('success', 'Course deleted successfully!');
        }
    }

    public function cancelEdit()
    {
        $this->isEditMode = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->course_id = null;
        $this->course_code = '';
        $this->course_name = '';
        $this->credits = 3;
        $this->description = '';
    }

    public function render()
    {
        return view('livewire.course-manager');
    }
}
