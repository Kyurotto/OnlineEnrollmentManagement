<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public $showModal = false;
    public $selectedApp = null;

    // Load application details for the modal
    public function viewApplication($id)
    {
        $this->selectedApp = Enrollment::with('user')->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedApp = null;
    }

    public function approveApplication($id)
    {
        $application = Enrollment::findOrFail($id);
        $application->update(['status' => 'Approved']);

        if ($application->user) {
            $application->user->update(['status' => 'Enrolled']);
        }
        session()->flash('success', "Application #{$id} approved.");
        $this->closeModal();
    }

    public function rejectApplication($id)
    {
        $application = Enrollment::findOrFail($id);
        
        // Purge user and application per request
        $user = $application->user;
        
        if ($user) {
            $user->delete();
        }
        
        $application->delete();

        session()->flash('success', "Application #{$id} and associated account destroyed.");
        $this->closeModal();
    }

    public function render()
    {
        $query = Enrollment::query()->with(['user'])->whereNotIn('status', ['Enrolled', 'Rejected'])->latest();

        if (!empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.application-manager', [
            'applications' => $query->paginate(10),
        ]);
    }
}
