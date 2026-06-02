<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin', ['title' => 'Activity Logs'])]
class ActivityLogManager extends Component
{
    use WithPagination;

    public $search = '';
    public $actionFilter = 'All actions';
    public $sortField = 'activity_logs.id';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'actionFilter' => ['except' => 'All actions'],
        'sortField' => ['except' => 'activity_logs.id'],
        'sortDirection' => ['except' => 'desc']
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingActionFilter() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function render()
    {
        $query = ActivityLog::query();

        if (str_contains($this->sortField, 'users.')) {
            $query->join('users', 'activity_logs.user_id', '=', 'users.id')
                  ->select('activity_logs.*');
        } else {
            $query->select('activity_logs.*');
        }

        $query->with('user');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($sub) {
                    $sub->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                })
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhere('action', 'like', "%{$this->search}%");
            });
        }

        if ($this->actionFilter !== 'All actions') {
            $query->where('action', $this->actionFilter);
        }

        $logs = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);

        $actionTypes = [
            'All actions',
            'payment_approved',
            'payment_rejected',
            'application_approved',
            'application_rejected',
            'clearance_approved',
            'clearance_revoked'
        ];

        return view('livewire.admin.activity-log-manager', [
            'logs' => $logs,
            'actionTypes' => $actionTypes
        ]);
    }
}