<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BulkDeleteAttempt extends Notification
{
    use Queueable;

    protected User $deletedBy;
    protected string $resource;
    protected int $count;
    protected array $itemIds;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $deletedBy, string $resource, int $count, array $itemIds = [])
    {
        $this->deletedBy = $deletedBy;
        $this->resource = $resource;
        $this->count = $count;
        $this->itemIds = $itemIds;
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
            'type' => 'bulk_delete',
            'icon' => 'dw dw-trash',
            'color' => 'danger',
            'title' => 'Bulk Delete Performed',
            'message' => "{$this->deletedBy->name} deleted {$this->count} {$this->resource} items.",
            'deleted_by_id' => $this->deletedBy->id,
            'deleted_by_name' => $this->deletedBy->name,
            'resource' => $this->resource,
            'count' => $this->count,
            'item_ids' => $this->itemIds,
            'url' => route('admin.activity-log'),
            'severity' => $this->count >= 10 ? 'CRITICAL' : 'WARNING',
        ];
    }
}
