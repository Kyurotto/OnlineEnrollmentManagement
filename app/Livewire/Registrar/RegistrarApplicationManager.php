<?php

namespace App\Livewire\Registrar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use App\Models\Course;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.registrar')]
class RegistrarApplicationManager extends Component
{
    use WithPagination;
    
    public $showModal = false;
    public $selectedApp = null;
    
    public function viewApplication($id)
    {
        $this->selectedApp = Enrollment::with('user')->findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedApp = null;
    }

    public function approve($id)
    {
        $application = Enrollment::with('user')->findOrFail($id);
        $application->update(['status' => 'Approved']);
        
        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }

        session()->flash('success', 'Application status updated to Approved.');
    }

    public function reject($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Rejected']);
        
        session()->flash('success', 'Application status updated to Rejected.');
    }

    public function delete($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->delete();

        session()->flash('success', 'Application record deleted.');
    }

    public function render()
    {
        $applications = Enrollment::with(['user'])
            ->latest()
            ->paginate(10);

        $courseCodes = $applications->pluck('course_code')->unique();
        $courses = Course::whereIn('course_code', $courseCodes)->get()->keyBy('course_code');

        foreach ($applications as $application) {
            if (isset($courses[$application->course_code])) {
                $application->setRelation('course', $courses[$application->course_code]);
            }
        }

        $pendingCount = Enrollment::where('status', 'Pending')->count();

        return view('livewire.registrar.registrar-application-manager', compact('applications', 'pendingCount'));
    }
}
