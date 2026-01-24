<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('post.view') || $user->hasRole('admin') || $user->hasRole('editor') || $user->hasRole('author');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->hasPermission('post.view') || $user->hasRole('admin') || $user->hasRole('editor') || ($user->hasRole('author') && $post->user_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('post.create') || $user->hasRole('admin') || $user->hasRole('editor') || $user->hasRole('author');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('editor')) {
            return true;
        }

        if ($user->hasRole('author')) {
            return $user->id === $post->user_id && $user->hasPermission('post.edit');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Editor and Author cannot delete according to specs (Delete: ❌ ❌ ✅)
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can publish the post.
     */
    public function publish(User $user, Post $post): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('editor')) {
            return true;
        }

        // Author cannot publish (Publish: ❌ ✅ ✅)
        return false;
    }
}
