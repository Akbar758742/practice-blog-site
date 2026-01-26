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
        // Authenticated users can comment on allowed posts.
        return true;
    }

    /**
     * Determine whether the user can update/moderate the model.
     * Only moderators (admin/editor) can change comment status.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Only moderators can update comments (change status, etc.)
        return $user->hasPermission('comment.moderate') || $user->hasRole('admin') || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can moderate comments (approve/reject/spam).
     */
    public function moderate(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comment.moderate') || $user->hasRole('admin') || $user->hasRole('editor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Admin can delete any, users can delete their own
        return $user->hasPermission('comment.delete') || $user->hasRole('admin') || ($user->id === $comment->user_id);
    }

    /**
     * Determine whether the user can restore the model (soft delete).
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->hasRole('admin');
    }
}
