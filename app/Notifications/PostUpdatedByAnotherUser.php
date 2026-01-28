<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostUpdatedByAnotherUser extends Notification
{
    use Queueable;

    protected Post $post;
    protected User $updatedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, User $updatedBy)
    {
        $this->post = $post;
        $this->updatedBy = $updatedBy;
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
            'type' => 'post_updated',
            'icon' => 'dw dw-edit-2',
            'color' => 'info',
            'title' => 'Your Post Was Updated',
            'message' => "Your post '{$this->post->title}' was updated by {$this->updatedBy->name}.",
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'updated_by_id' => $this->updatedBy->id,
            'updated_by_name' => $this->updatedBy->name,
            'url' => route('admin.posts.edit', $this->post->id),
            'severity' => 'INFO',
        ];
    }
}
