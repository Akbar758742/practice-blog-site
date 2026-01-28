<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->data['type'] ?? 'general',
                        'icon' => $notification->data['icon'] ?? 'dw dw-notification',
                        'color' => $notification->data['color'] ?? 'primary',
                        'title' => $notification->data['title'] ?? 'Notification',
                        'message' => $notification->data['message'] ?? '',
                        'url' => $notification->data['url'] ?? '#',
                        'severity' => $notification->data['severity'] ?? 'INFO',
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->diffForHumans(),
                    ];
                })
                ->toArray();

            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        $this->loadNotifications();
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

    public function render()
    {
        return view('livewire.admin.notifications');
    }
}
