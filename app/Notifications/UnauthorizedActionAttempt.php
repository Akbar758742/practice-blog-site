<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnauthorizedActionAttempt extends Notification
{
    use Queueable;

    protected User $attemptedBy;
    protected string $action;
    protected string $resource;
    protected ?string $resourceId;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $attemptedBy, string $action, string $resource, ?string $resourceId = null)
    {
        $this->attemptedBy = $attemptedBy;
        $this->action = $action;
        $this->resource = $resource;
        $this->resourceId = $resourceId;
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
        $resourceInfo = $this->resourceId ? "{$this->resource} (ID: {$this->resourceId})" : $this->resource;

        return [
            'type' => 'unauthorized_action',
            'icon' => 'dw dw-warning',
            'color' => 'danger',
            'title' => 'Unauthorized Action Attempt',
            'message' => "{$this->attemptedBy->name} attempted to {$this->action} on {$resourceInfo} without permission.",
            'attempted_by_id' => $this->attemptedBy->id,
            'attempted_by_name' => $this->attemptedBy->name,
            'action' => $this->action,
            'resource' => $this->resource,
            'resource_id' => $this->resourceId,
            'url' => route('admin.activity-log'),
            'severity' => 'WARNING',
        ];
    }
}
