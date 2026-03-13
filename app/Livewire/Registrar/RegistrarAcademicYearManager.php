<?php

namespace App\Livewire\Registrar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AcademicYear;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarAcademicYearManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditMode = false;
    public $editingYearId = null;

    public $year_name;
    public $is_active = false;

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['year_name', 'is_active', 'editingYearId']);
        $this->showModal = true;
        $this->isEditMode = false;
    }

    public function editModal($id)
    {
        $this->resetValidation();
        $year = AcademicYear::findOrFail($id);
        $this->editingYearId = $year->id;
        $this->year_name = $year->year_name;
        $this->is_active = (bool)$year->is_active;

        $this->showModal = true;
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['year_name', 'is_active', 'editingYearId', 'isEditMode']);
    }

    public function save()
    {
        $rules = [
            'year_name' => 'required|string|unique:academic_years,year_name',
        ];

        if ($this->isEditMode) {
            $rules['year_name'] = 'required|string|unique:academic_years,year_name,' . $this->editingYearId;
        }

        $this->validate($rules);

        if ($this->is_active) {
            if ($this->isEditMode) {
                AcademicYear::where('id', '!=', $this->editingYearId)->update(['is_active' => false]);
            } else {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }
        }

        if ($this->isEditMode) {
            $year = AcademicYear::findOrFail($this->editingYearId);
            $year->update([
                'year_name' => $this->year_name,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Academic Year updated successfully.');
        } else {
            AcademicYear::create([
                'year_name' => $this->year_name,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Academic Year created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        AcademicYear::findOrFail($id)->delete();
        session()->flash('success', 'Academic Year deleted successfully.');
    }

    public function render()
    {
        $years = AcademicYear::orderBy('is_active', 'desc')
                             ->orderBy('year_name', 'desc')
                             ->paginate(10);

        return view('livewire.registrar.registrar-academic-year-manager', compact('years'));
    }
}
