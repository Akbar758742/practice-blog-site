<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleChanged extends Notification
{
    use Queueable;

    protected User $user;
    protected User $changedBy;
    protected array $oldRoles;
    protected array $newRoles;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, User $changedBy, array $oldRoles, array $newRoles)
    {
        $this->user = $user;
        $this->changedBy = $changedBy;
        $this->oldRoles = $oldRoles;
        $this->newRoles = $newRoles;
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
        $oldRoleNames = implode(', ', $this->oldRoles);
        $newRoleNames = implode(', ', $this->newRoles);

        return [
            'type' => 'role_changed',
            'icon' => 'dw dw-user-12',
            'color' => 'warning',
            'title' => 'User Role Changed',
            'message' => "{$this->changedBy->name} changed {$this->user->name}'s roles from [{$oldRoleNames}] to [{$newRoleNames}].",
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'changed_by_id' => $this->changedBy->id,
            'changed_by_name' => $this->changedBy->name,
            'old_roles' => $this->oldRoles,
            'new_roles' => $this->newRoles,
            'url' => route('admin.dashboard'),
            'severity' => 'WARNING',
        ];
    }
}
