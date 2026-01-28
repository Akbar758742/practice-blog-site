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
    public $filterSeverity = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'filterLogName' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterSeverity' => ['except' => ''],
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

    public function updatingFilterSeverity()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterUser = '';
        $this->filterLogName = '';
        $this->filterAction = '';
        $this->filterSeverity = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        // Role-based access control for activity logs
        // Admin -> all activities
        // Editor -> content-related activities
        // Author -> only own activities

        $query = Activity::with('causer');

        // Apply role-based filtering
        if ($user->hasRole('admin')) {
            // Admin sees everything - no additional filter
        } elseif ($user->hasRole('editor')) {
            // Editor sees content-related activities (posts, comments, categories, tags, pages)
            $contentLogNames = ['post', 'posts', 'comment', 'comments', 'category', 'categories', 'tag', 'tags', 'page', 'pages'];
            $query->where(function ($q) use ($contentLogNames, $user) {
                $q->whereIn('log_name', $contentLogNames)
                  ->orWhere('causer_id', $user->id);
            });
        } else {
            // Author/Other roles see only their own activities
            $query->where('causer_id', $user->id);
        }

        // Apply search and filters
        $query->when($this->search, function ($q) {
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
            ->when($this->filterSeverity, function ($q) {
                $q->whereJsonContains('properties->severity', $this->filterSeverity);
            })
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->filterDateTo);
            });

        $activities = $query->latest()->paginate($this->perPage);

        // Get unique log names and users for filters (based on user's access)
        $baseQuery = Activity::query();
        if ($user->hasRole('admin')) {
            // Admin sees all
        } elseif ($user->hasRole('editor')) {
            $contentLogNames = ['post', 'posts', 'comment', 'comments', 'category', 'categories', 'tag', 'tags', 'page', 'pages'];
            $baseQuery->where(function ($q) use ($contentLogNames, $user) {
                $q->whereIn('log_name', $contentLogNames)
                  ->orWhere('causer_id', $user->id);
            });
        } else {
            $baseQuery->where('causer_id', $user->id);
        }

        $logNames = (clone $baseQuery)->distinct()->pluck('log_name')->filter()->sort();
        $userIds = (clone $baseQuery)->distinct()->pluck('causer_id');
        $users = User::whereIn('id', $userIds)->get();

        // Get action types from properties
        $actions = ['created', 'updated', 'deleted', 'restored', 'force_deleted', 'status_changed'];

        // Statistics (based on user's access)
        $statsQuery = Activity::query();
        if ($user->hasRole('admin')) {
            // Admin sees all stats
        } elseif ($user->hasRole('editor')) {
            $contentLogNames = ['post', 'posts', 'comment', 'comments', 'category', 'categories', 'tag', 'tags', 'page', 'pages'];
            $statsQuery->where(function ($q) use ($contentLogNames, $user) {
                $q->whereIn('log_name', $contentLogNames)
                  ->orWhere('causer_id', $user->id);
            });
        } else {
            $statsQuery->where('causer_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'today' => (clone $statsQuery)->whereDate('created_at', today())->count(),
            'this_week' => (clone $statsQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => (clone $statsQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'critical' => (clone $statsQuery)->whereJsonContains('properties->severity', 'CRITICAL')->count(),
            'warning' => (clone $statsQuery)->whereJsonContains('properties->severity', 'WARNING')->count(),
        ];

        // Severity options for filter
        $severities = ['INFO', 'WARNING', 'CRITICAL'];

        return view('livewire.admin.activity-log', [
            'activities' => $activities,
            'logNames' => $logNames,
            'users' => $users,
            'actions' => $actions,
            'severities' => $severities,
            'stats' => $stats,
            'userRole' => $user->hasRole('admin') ? 'admin' : ($user->hasRole('editor') ? 'editor' : 'author'),
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
