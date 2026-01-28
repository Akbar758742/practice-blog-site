<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostSubmittedForReview extends Notification
{
    use Queueable;

    protected Post $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
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
            'type' => 'post_review',
            'icon' => 'dw dw-file-36',
            'color' => 'primary',
            'title' => 'Post Submitted for Review',
            'message' => "Post '{$this->post->title}' has been submitted for review by {$this->post->user->name}.",
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'author_id' => $this->post->user_id,
            'author_name' => $this->post->user->name,
            'url' => route('admin.posts.index'),
            'severity' => 'INFO',
        ];
    }
}
