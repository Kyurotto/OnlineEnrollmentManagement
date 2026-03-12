<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use App\Models\Payment;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class ApplicationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // We can use Livewire attributes to set the layout if we make this a full page component.
    // However, since the view itself contains the full HTML document, we can tell Livewire 
    // to render it without wrapping it in an app layout.
    
    public function updateStatus($id, $status)
    {
        $application = Enrollment::findOrFail($id);
        $application->status = $status;
        $application->save();
        
        session()->flash('success', "Application #{$id} status updated to {$status}.");
    }

    public function destroy($id)
    {
        Enrollment::findOrFail($id)->delete();
        session()->flash('success', "Record #{$id} deleted.");
    }

    public function render()
    {
        $query = Enrollment::with(['user'])->latest();

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

        $applications = $query->paginate(10);

        return view('livewire.application-manager', [
            'applications' => $applications,
        ]);
    }
}
