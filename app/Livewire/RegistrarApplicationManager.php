<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;

#[Layout('components.layouts.registrar')]
class RegistrarApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'All statuses';
    public $sortField = 'enrollments.id';
    public $sortDirection = 'desc';
    public $year_level = 'All Years';
    public $level = null;

    public function mount()
    {
        if (request()->routeIs('registrar.applications.college')) {
            $this->level = 'college';
        } elseif (request()->routeIs('registrar.applications.shs')) {
            $this->level = 'shs';
        }
    }

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

    public $selectedId;

    public function selectApplication($id)
    {
        $this->selectedId = $id;
    }

    public function approve($id)
    {
        $application = Enrollment::findOrFail($id);
        
        // Finalize enrollment status
        $application->status = 'Enrolled';
        $application->save();

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }

        session()->flash('success', 'Application status updated to Enrolled (Paid).');
    }

    public function reject($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = 'Rejected';
        $application->save();

        session()->flash('success', 'Application status updated to Rejected.');
    }

    public function togglePhysicalDocuments($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->physical_documents_received = !$application->physical_documents_received;
        $application->save();

        $status = $application->physical_documents_received ? 'marked as received' : 'unmarked';
        session()->flash('success', "Physical documents $status.");
        
        // Emit event to reset Alpine state
        $this->dispatch('modal-reset');
    }

    public function destroy($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        session()->flash('success', 'Application record deleted.');
    }

    public function render()
    {
        $query = Enrollment::query()
            ->select('enrollments.*')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->with(['user']);

        if ($this->level) {
            $query->where('enrollments.level', $this->level);
        }

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

        // Manual Eager Load for 'course' based on course_code
        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        foreach ($applications as $application) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }

            // Simplify year level display (e.g., "1st Year | 2nd Semester" -> "1st Year")
            if (!empty($application->year_level)) {
                $parts = explode('|', $application->year_level);
                $application->year_display = trim($parts[0]);
            } else {
                $application->year_display = 'N/A';
            }
        }

        // 2. Count pending applications for the header badge (respecting the current level)
        $pendingCountQuery = Enrollment::where('status', 'Pending');
        if ($this->level) {
            $pendingCountQuery->where('level', $this->level);
        }
        $pendingCount = $pendingCountQuery->count();

        return view('livewire.registrar-application-manager', [
            'applications' => $applications,
            'pendingCount' => $pendingCount
        ]);
    }
}
