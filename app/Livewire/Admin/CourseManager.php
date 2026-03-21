<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CourseManager extends Component
{
    use WithPagination;

    // Form fields
    public $course_id;
    public $course_code;
    public $course_name;
    public $credits = 3;
    public $description;

    public $isEditMode = false;

    public function save()
    {
        if ($this->isEditMode) {
            $this->update();
        } else {
            $this->store();
        }
    }

    protected $rules = [
        'course_code' => 'required|string|max:50',
        'course_name' => 'required|string|max:255',
        'credits'     => 'required|integer|min:1',
        'description' => 'nullable|string',
    ];

    public function store()
    {
        $this->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'credits'     => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        try {
            Course::create([
                'course_code' => $this->course_code,
                'course_name' => $this->course_name,
                'credits'     => $this->credits,
                'description' => $this->description,
                'type'        => 'course',
            ]);

            session()->flash('success', 'Course added successfully!');
            $this->resetFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Critical Error: ' . $e->getMessage());
        }
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

        try {
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
                session()->flash('success', 'Course updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if ($id) {
            Course::findOrFail($id)->delete();
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
        return view('livewire.admin.course-manager', [
            'courses' => Course::where('type', 'course')->latest()->paginate(10)
        ]);
    }
}
