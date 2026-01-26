<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLog extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUser = '';
    public $filterLogName = '';
    public $filterAction = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'filterLogName' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterDateFrom' => ['except' => ''],
        'filterDateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function updatingFilterLogName()
    {
        $this->resetPage();
    }

    public function updatingFilterAction()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterUser = '';
        $this->filterLogName = '';
        $this->filterAction = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        // Only admins can view activity logs
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $query = Activity::with('causer')
            ->when($this->search, function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterUser, function ($q) {
                $q->where('causer_id', $this->filterUser);
            })
            ->when($this->filterLogName, function ($q) {
                $q->where('log_name', $this->filterLogName);
            })
            ->when($this->filterAction, function ($q) {
                $q->whereJsonContains('properties->action', $this->filterAction);
            })
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->filterDateTo);
            });

        $activities = $query->latest()->paginate($this->perPage);

        // Get unique log names and users for filters
        $logNames = Activity::distinct()->pluck('log_name')->filter()->sort();
        $users = User::whereIn('id', Activity::distinct()->pluck('causer_id'))->get();

        // Get action types from properties
        $actions = ['created', 'updated', 'deleted', 'restored', 'force_deleted', 'status_changed'];

        // Statistics
        $stats = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Activity::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        return view('livewire.admin.activity-log', [
            'activities' => $activities,
            'logNames' => $logNames,
            'users' => $users,
            'actions' => $actions,
            'stats' => $stats,
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'Activity Log']);
    }

    /**
     * Get formatted properties for display
     */
    public function getFormattedProperties($properties)
    {
        if (!$properties) {
            return [];
        }

        $data = is_array($properties) ? $properties : $properties->toArray();

        // Format changes for display
        if (isset($data['changes']) && is_array($data['changes'])) {
            $formatted = [];
            foreach ($data['changes'] as $field => $change) {
                if (is_array($change) && isset($change['old']) && isset($change['new'])) {
                    $formatted[] = "{$field}: {$change['old']} → {$change['new']}";
                }
            }
            $data['formatted_changes'] = $formatted;
        }

        return $data;
    }

    /**
     * Get action badge color
     */
    public function getActionColor($action)
    {
        return match($action) {
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'warning',
            'restored' => 'info',
            'force_deleted' => 'danger',
            'status_changed' => 'secondary',
            default => 'light',
        };
    }
}
