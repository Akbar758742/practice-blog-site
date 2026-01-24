<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('comment.view') || $user->hasRole('admin') || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comment.view') || $user->hasRole('admin') || $user->hasRole('editor') || $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Usually authenticated users can comment, but moderation might be needed.
        // Assuming all auth users can comment on allowed posts.
        return true;
    }

    /**
     * Determine whether the user can update the model (Edit own comment? or Moderate?)
     * Steps say: Approve / Reject (Moderate).
     */
    public function update(User $user, Comment $comment): bool
    {
        // Edit own comment?
        if ($user->id === $comment->user_id)
            return true;

        // Moderate? use specific permission or 'moderate' method?
        // Using 'update' for moderation might be confusing if we want to allow editing text vs changing status.
        return $user->hasPermission('comment.moderate') || $user->hasRole('admin') || $user->hasRole('editor');
    }

    public function moderate(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comment.moderate') || $user->hasRole('admin') || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comment.delete') || $user->hasRole('admin') || ($user->id === $comment->user_id);
    }
}
