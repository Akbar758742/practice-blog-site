<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Role;
use App\Notifications\PostSubmittedForReview;
use App\Notifications\PostUpdatedByAnotherUser;
use App\Notifications\RoleAssigned;
use App\Notifications\RoleChanged;
use App\Notifications\CommentAwaitingModeration;
use App\Notifications\UnauthorizedActionAttempt;
use App\Notifications\BulkDeleteAttempt;
use App\Notifications\LoginFailureAlert;

class NotificationService
{
    /**
     * Notify editors when a post is submitted for review
     */
    public static function notifyPostSubmittedForReview(Post $post): void
    {
        // Notify all editors and admins
        $usersToNotify = User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'editor']);
        })->where('id', '!=', $post->user_id)->get();

        foreach ($usersToNotify as $user) {
            $user->notify(new PostSubmittedForReview($post));
        }
    }

    /**
     * Notify author when their post is updated by another user
     */
    public static function notifyPostUpdatedByAnother(Post $post, User $updatedBy): void
    {
        // Only notify if the editor is different from the author
        if ($post->user_id !== $updatedBy->id) {
            $post->user->notify(new PostUpdatedByAnotherUser($post, $updatedBy));
        }
    }

    /**
     * Notify user when a role is assigned to them
     */
    public static function notifyRoleAssigned(User $user, Role $role, User $assignedBy): void
    {
        $user->notify(new RoleAssigned($role, $assignedBy));
    }

    /**
     * Notify admins when a user's roles are changed
     */
    public static function notifyRoleChanged(User $targetUser, User $changedBy, array $oldRoles, array $newRoles): void
    {
        // Notify all admins except the one who made the change
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->where('id', '!=', $changedBy->id)->get();

        foreach ($admins as $admin) {
            $admin->notify(new RoleChanged($targetUser, $changedBy, $oldRoles, $newRoles));
        }

        // Notify the affected user about new roles
        $addedRoles = array_diff($newRoles, $oldRoles);
        foreach ($addedRoles as $roleName) {
            $role = Role::where('name', $roleName)->orWhere('slug', $roleName)->first();
            if ($role) {
                $targetUser->notify(new RoleAssigned($role, $changedBy));
            }
        }
    }

    /**
     * Notify moderators when a comment needs moderation
     */
    public static function notifyCommentAwaitingModeration(Comment $comment): void
    {
        // Notify editors and admins
        $moderators = User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'editor']);
        })->get();

        foreach ($moderators as $moderator) {
            $moderator->notify(new CommentAwaitingModeration($comment));
        }
    }

    /**
     * Notify admins of unauthorized action attempt
     */
    public static function notifyUnauthorizedAttempt(User $attemptedBy, string $action, string $resource, ?string $resourceId = null): void
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new UnauthorizedActionAttempt($attemptedBy, $action, $resource, $resourceId));
        }
    }

    /**
     * Notify admins of bulk delete action
     */
    public static function notifyBulkDelete(User $deletedBy, string $resource, int $count, array $itemIds = []): void
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->where('id', '!=', $deletedBy->id)->get();

        foreach ($admins as $admin) {
            $admin->notify(new BulkDeleteAttempt($deletedBy, $resource, $count, $itemIds));
        }
    }

    /**
     * Notify admins of login failure (after multiple attempts)
     */
    public static function notifyLoginFailure(string $loginId, string $ipAddress, int $failedAttempts): void
    {
        if ($failedAttempts < 3) {
            return; // Only notify after 3 attempts
        }

        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new LoginFailureAlert($loginId, $ipAddress, $failedAttempts));
        }
    }

    /**
     * Get users by role
     */
    public static function getUsersByRole(string $role): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereHas('roles', function ($q) use ($role) {
            $q->where('slug', $role);
        })->get();
    }

    /**
     * Get users by multiple roles
     */
    public static function getUsersByRoles(array $roles): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('slug', $roles);
        })->get();
    }
}
