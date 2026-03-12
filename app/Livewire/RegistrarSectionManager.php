<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Section;
use App\Models\Course;
use App\Models\AcademicYear;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarSectionManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $editingSectionId = null;

    public $academic_year;
    public $course_id;
    public $section_name;

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['academic_year', 'course_id', 'section_name', 'editingSectionId']);
        $this->showModal = true;
        $this->isEditMode = false;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $section = Section::findOrFail($id);
        $this->editingSectionId = $section->id;
        $this->academic_year = $section->academic_year;
        $this->course_id = $section->course_id;
        $this->section_name = $section->section_name;

        $this->showModal = true;
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['academic_year', 'course_id', 'section_name', 'editingSectionId', 'isEditMode']);
    }

    public function save()
    {
        $this->validate([
            'academic_year' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'section_name' => 'required|string|max:10',
        ]);

        if ($this->isEditMode) {
            $section = Section::findOrFail($this->editingSectionId);
            $section->update([
                'academic_year' => $this->academic_year,
                'course_id' => $this->course_id,
                'section_name' => strtoupper($this->section_name),
            ]);
            session()->flash('success', 'Section updated successfully.');
        } else {
            Section::create([
                'academic_year' => $this->academic_year,
                'course_id' => $this->course_id,
                'section_name' => strtoupper($this->section_name),
            ]);
            session()->flash('success', 'Section created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Section::findOrFail($id)->delete();
        session()->flash('success', 'Section deleted successfully.');
    }

    public function render()
    {
        $sections = Section::with('course')
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        
        $courses = Course::all(); 
        
        $academicYears = AcademicYear::where('is_active', true)->get();
        if($academicYears->isEmpty()) {
            $academicYears = AcademicYear::all();
        }

        return view('livewire.registrar-section-manager', compact('sections', 'courses', 'academicYears'));
    }
}
