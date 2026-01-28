<?php

namespace App\Notifications;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleAssigned extends Notification
{
    use Queueable;

    protected Role $role;
    protected User $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Role $role, User $assignedBy)
    {
        $this->role = $role;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'role_assigned',
            'icon' => 'dw dw-user-12',
            'color' => 'success',
            'title' => 'Role Assigned',
            'message' => "You have been assigned the '{$this->role->name}' role by {$this->assignedBy->name}.",
            'role_id' => $this->role->id,
            'role_name' => $this->role->name,
            'assigned_by_id' => $this->assignedBy->id,
            'assigned_by_name' => $this->assignedBy->name,
            'url' => route('admin.dashboard'),
            'severity' => 'INFO',
        ];
    }
}
