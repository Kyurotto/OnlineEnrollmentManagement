<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Semester;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarSemesterManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $editingSemesterId = null;

    public $academic_year;
    public $name = 'First Semester';
    public $start_date;
    public $end_date;
    public $is_active = false;

    public function mount()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->academic_year = $activeYear->year_name;
        }
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'start_date', 'end_date', 'is_active', 'editingSemesterId']);
        
        $activeYear = AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            $this->academic_year = $activeYear->year_name;
        }

        $this->name = 'First Semester';
        $this->showModal = true;
        $this->isEditMode = false;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $semester = Semester::findOrFail($id);
        $this->editingSemesterId = $semester->id;
        $this->academic_year = $semester->academic_year;
        $this->name = $semester->name;
        $this->start_date = Carbon::parse($semester->start_date)->format('Y-m-d');
        $this->end_date = Carbon::parse($semester->end_date)->format('Y-m-d');
        $this->is_active = (bool)$semester->is_active;

        $this->showModal = true;
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['academic_year', 'name', 'start_date', 'end_date', 'is_active', 'editingSemesterId', 'isEditMode']);
    }

    public function save()
    {
        $this->validate([
            'academic_year' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($this->is_active) {
            $this->activateSemesterAndYear($this->academic_year, $this->editingSemesterId);
        }

        if ($this->isEditMode) {
            $semester = Semester::findOrFail($this->editingSemesterId);
            $semester->update([
                'academic_year' => $this->academic_year,
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Semester updated successfully.');
        } else {
            Semester::create([
                'academic_year' => $this->academic_year,
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Semester created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Semester::findOrFail($id)->delete();
        session()->flash('success', 'Semester deleted successfully.');
    }

    private function activateSemesterAndYear($academicYearName, $excludeSemesterId = null)
    {
        Semester::query()->update(['is_active' => false]);
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('year_name', $academicYearName)->update(['is_active' => true]);
    }

    public function render()
    {
        $semesters = Semester::orderBy('is_active', 'desc')
                             ->orderBy('id', 'desc')
                             ->paginate(10);
                             
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();

        return view('livewire.registrar-semester-manager', compact('semesters', 'academicYears'));
    }
}
