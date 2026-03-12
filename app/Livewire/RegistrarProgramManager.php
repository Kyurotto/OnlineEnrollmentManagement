<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;
use App\Models\Section;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarProgramManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $editingProgramId = null;

    public $course_name;
    public $description;

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['course_name', 'description', 'editingProgramId']);
        $this->showModal = true;
        $this->isEditMode = false;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $program = Course::findOrFail($id);
        $this->editingProgramId = $program->id;
        $this->course_name = $program->course_name;
        $this->description = $program->description;

        $this->showModal = true;
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['course_name', 'description', 'editingProgramId', 'isEditMode']);
    }

    public function save()
    {
        $rules = [
            'course_name' => 'required|string|unique:courses,course_name',
            'description' => 'nullable|string',
        ];

        if ($this->isEditMode) {
            $rules['course_name'] = 'required|string|unique:courses,course_name,' . $this->editingProgramId;
        }

        $this->validate($rules);

        if ($this->isEditMode) {
            $program = Course::findOrFail($this->editingProgramId);
            $program->update([
                'course_name' => $this->course_name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Program updated successfully.');
        } else {
            $generatedCode = strtoupper(substr($this->course_name, 0, 4)) . rand(10, 99);
            Course::create([
                'course_name' => $this->course_name,
                'course_code' => $generatedCode,
                'description' => $this->description ?? '',
            ]);
            session()->flash('success', 'Program added successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        try {
            $course = Course::findOrFail($id);
            Section::where('course_id', $id)->delete();
            $course->delete();
            session()->flash('success', 'Program deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete program. It contains active enrollments or records.');
        }
    }

    public function render()
    {
        $programs = Course::orderBy('id', 'desc')->paginate(10);
        return view('livewire.registrar-program-manager', compact('programs'));
    }
}
