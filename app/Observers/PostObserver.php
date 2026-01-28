<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\NotificationService;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // Notify editors when a post is created and pending review
        if ($post->status->value === 'pending' || $post->status->value === 'draft') {
            NotificationService::notifyPostSubmittedForReview($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Check if status changed to pending (submitted for review)
        if ($post->isDirty('status') && $post->status->value === 'pending') {
            NotificationService::notifyPostSubmittedForReview($post);
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
