<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;

class AdminApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'All statuses';
    public $sortField = 'enrollments.id';
    public $sortDirection = 'desc';
    public $year_level = 'All Years';
    public $selectedId;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'All statuses'],
        'year_level' => ['except' => 'All Years'],
        'sortField' => ['except' => 'enrollments.id'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingYearLevel() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function selectApplication($id)
    {
        $this->selectedId = $id;
    }

    public function approve($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = 'Approved';
        $application->save();

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }

        session()->flash('success', 'Application status updated to Approved.');
    }

    public function reject($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = 'Rejected';
        $application->save();

        session()->flash('success', 'Application status updated to Rejected.');
    }

    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        session()->flash('success', 'Application record deleted successfully.');
    }

    public function render()
    {
        $query = Enrollment::with('user')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->select('enrollments.*');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($sub) {
                    $sub->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                })
                ->orWhere('enrollments.course_code', 'like', "%{$this->search}%")
                ->orWhere('enrollments.promissory_reason', 'like', "%{$this->search}%");
            });
        }

        if ($this->status !== 'All statuses') {
            $query->where('enrollments.status', $this->status);
        }

        if ($this->year_level !== 'All Years') {
            $query->where('enrollments.year_level', 'like', "{$this->year_level}%");
        }

        $applications = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        foreach ($applications as $application) {
            // Simplify year level display (e.g., "1st Year | 2nd Semester" -> "1st Year")
            if (!empty($application->year_level)) {
                $parts = explode('|', $application->year_level);
                $application->year_display = trim($parts[0]);
            } else {
                $application->year_display = 'N/A';
            }
        }

        return view('livewire.admin.admin-application-manager', [
            'applications' => $applications
        ])->layout('components.layouts.admin', ['title' => 'Applications']);
    }
}
