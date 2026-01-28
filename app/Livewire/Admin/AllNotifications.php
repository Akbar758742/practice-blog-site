<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AllNotifications extends Component
{
    use WithPagination;

    public $filterType = '';
    public $filterSeverity = '';
    public $filterStatus = '';
    public $perPage = 20;

    protected $queryString = [
        'filterType' => ['except' => ''],
        'filterSeverity' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterSeverity()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterSeverity = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('refreshNotifications');
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('refreshNotifications');
    }

    public function deleteNotification($notificationId)
    {
        $user = Auth::user();
        $user->notifications()->where('id', $notificationId)->delete();
        $this->dispatch('refreshNotifications');
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        $this->dispatch('refreshNotifications');
    }

    public function getSeverityBadgeClass($severity)
    {
        return match($severity) {
            'CRITICAL' => 'badge-danger',
            'WARNING' => 'badge-warning',
            'INFO' => 'badge-info',
            default => 'badge-secondary',
        };
    }

    public function getColorClass($color)
    {
        return match($color) {
            'danger' => 'bg-danger',
            'warning' => 'bg-warning',
            'success' => 'bg-success',
            'info' => 'bg-info',
            'primary' => 'bg-primary',
            default => 'bg-secondary',
        };
    }

    public function render()
    {
        $user = Auth::user();

        $query = $user->notifications()
            ->when($this->filterType, function ($q) {
                $q->where('data->type', $this->filterType);
            })
            ->when($this->filterSeverity, function ($q) {
                $q->where('data->severity', $this->filterSeverity);
            })
            ->when($this->filterStatus === 'read', function ($q) {
                $q->whereNotNull('read_at');
            })
            ->when($this->filterStatus === 'unread', function ($q) {
                $q->whereNull('read_at');
            });

        $notifications = $query->latest()->paginate($this->perPage);

        // Get unique types for filter
        $types = $user->notifications()
            ->get()
            ->pluck('data.type')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'critical' => $user->notifications()->where('data->severity', 'CRITICAL')->count(),
            'warning' => $user->notifications()->where('data->severity', 'WARNING')->count(),
        ];

        return view('livewire.admin.all-notifications', [
            'notifications' => $notifications,
            'types' => $types,
            'stats' => $stats,
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'All Notifications']);
    }
}
