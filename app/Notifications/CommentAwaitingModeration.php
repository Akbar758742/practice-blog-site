<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAwaitingModeration extends Notification
{
    use Queueable;

    protected Comment $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
            'type' => 'comment_moderation',
            'icon' => 'dw dw-chat-11',
            'color' => 'warning',
            'title' => 'Comment Awaiting Moderation',
            'message' => "New comment on '{$this->comment->post->title}' by {$this->comment->user->name} requires moderation.",
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post->title,
            'commenter_id' => $this->comment->user_id,
            'commenter_name' => $this->comment->user->name,
            'url' => route('admin.comments'),
            'severity' => 'INFO',
        ];
    }
}
