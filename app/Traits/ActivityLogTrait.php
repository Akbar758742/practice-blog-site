<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;

trait ActivityLogTrait
{
    /**
     * Severity levels for activity logs
     */
    const SEVERITY_INFO = 'INFO';
    const SEVERITY_WARNING = 'WARNING';
    const SEVERITY_CRITICAL = 'CRITICAL';

    /**
     * Log an activity with subject and severity
     */
    protected function logActivity(string $logName, string $description, $subject = null, array $properties = [], string $severity = self::SEVERITY_INFO)
    {
        $activity = activity($logName)
            ->causedBy(auth()->user());

        if ($subject) {
            $activity->performedOn($subject);
        }

        // Add severity and additional metadata
        $properties['severity'] = $severity;
        $properties['ip_address'] = request()->ip();
        $properties['user_agent'] = request()->userAgent();
        $properties['timestamp'] = now()->toIso8601String();

        if (!empty($properties)) {
            $activity->withProperties($properties);
        }

        $activity->log($description);
    }

    /**
     * Log create operation
     */
    protected function logCreated(string $logName, $model, string $identifier = null)
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $this->logActivity(
            $logName,
            "{$modelName} created: {$identifier}",
            $model,
            ['action' => 'created', 'attributes' => $model->toArray()]
        );
    }

    /**
     * Log update operation with changes
     */
    protected function logUpdated(string $logName, $model, array $oldValues = [], string $identifier = null)
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $changes = [];
        foreach ($model->getChanges() as $key => $newValue) {
            if (isset($oldValues[$key]) && $oldValues[$key] !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValues[$key],
                    'new' => $newValue
                ];
            }
        }

        $this->logActivity(
            $logName,
            "{$modelName} updated: {$identifier}",
            $model,
            ['action' => 'updated', 'changes' => $changes]
        );
    }

    /**
     * Log delete operation
     */
    protected function logDeleted(string $logName, $model, string $identifier = null, array $extra = [])
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $properties = array_merge(
            ['action' => 'deleted', 'deleted_data' => $model->toArray()],
            $extra
        );

        $this->logActivity(
            $logName,
            "{$modelName} deleted: {$identifier}",
            $model,
            $properties
        );
    }

    /**
     * Log restore operation (for soft deletes)
     */
    protected function logRestored(string $logName, $model, string $identifier = null)
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $this->logActivity(
            $logName,
            "{$modelName} restored: {$identifier}",
            $model,
            ['action' => 'restored']
        );
    }

    /**
     * Log permanent delete operation
     */
    protected function logForceDeleted(string $logName, $model, string $identifier = null)
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $this->logActivity(
            $logName,
            "{$modelName} permanently deleted: {$identifier}",
            $model,
            ['action' => 'force_deleted', 'deleted_data' => $model->toArray()]
        );
    }

    /**
     * Log status change operation
     */
    protected function logStatusChanged(string $logName, $model, string $field, $oldValue, $newValue, string $identifier = null)
    {
        $modelName = class_basename($model);
        $identifier = $identifier ?? $model->id;

        $this->logActivity(
            $logName,
            "{$modelName} {$field} changed: {$identifier}",
            $model,
            [
                'action' => 'status_changed',
                'field' => $field,
                'old_value' => $oldValue,
                'new_value' => $newValue
            ]
        );
    }

    /**
     * Log bulk delete operation (WARNING/CRITICAL based on count)
     */
    protected function logBulkDelete(string $logName, string $resourceType, int $count, array $itemIds = [])
    {
        $severity = $count >= 10 ? self::SEVERITY_CRITICAL : self::SEVERITY_WARNING;

        $this->logActivity(
            $logName,
            "Bulk delete: {$count} {$resourceType} items deleted",
            null,
            [
                'action' => 'bulk_delete',
                'resource_type' => $resourceType,
                'count' => $count,
                'item_ids' => $itemIds,
                'flag' => 'BULK_DELETE'
            ],
            $severity
        );

        // Notify admins of bulk delete
        $this->notifyAdminsOfSecurityEvent('bulk_delete', $resourceType, $count, $itemIds);
    }

    /**
     * Log unauthorized action attempt (WARNING)
     */
    protected function logUnauthorizedAttempt(string $action, string $resource, ?string $resourceId = null)
    {
        $user = auth()->user();
        $resourceInfo = $resourceId ? "{$resource} (ID: {$resourceId})" : $resource;

        $this->logActivity(
            'security',
            "Unauthorized attempt: {$action} on {$resourceInfo}",
            null,
            [
                'action' => 'unauthorized_attempt',
                'attempted_action' => $action,
                'resource' => $resource,
                'resource_id' => $resourceId,
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'flag' => 'PERMISSION_DENIED'
            ],
            self::SEVERITY_WARNING
        );

        // Notify admins
        if ($user) {
            $this->notifyAdminsOfUnauthorizedAttempt($user, $action, $resource, $resourceId);
        }
    }

    /**
     * Log role change (WARNING)
     */
    protected function logRoleChange(User $targetUser, array $oldRoles, array $newRoles)
    {
        $changedBy = auth()->user();

        $this->logActivity(
            'security',
            "Role changed for user: {$targetUser->name}",
            $targetUser,
            [
                'action' => 'role_changed',
                'user_id' => $targetUser->id,
                'user_name' => $targetUser->name,
                'old_roles' => $oldRoles,
                'new_roles' => $newRoles,
                'changed_by_id' => $changedBy?->id,
                'changed_by_name' => $changedBy?->name,
                'flag' => 'ROLE_CHANGE'
            ],
            self::SEVERITY_WARNING
        );

        // Notify admins and the affected user
        $this->notifyOfRoleChange($targetUser, $changedBy, $oldRoles, $newRoles);
    }

    /**
     * Log login failure (WARNING/CRITICAL based on attempts)
     */
    protected function logLoginFailure(string $loginId, int $failedAttempts = 1)
    {
        $severity = $failedAttempts >= 5 ? self::SEVERITY_CRITICAL : ($failedAttempts >= 3 ? self::SEVERITY_WARNING : self::SEVERITY_INFO);

        activity('security')
            ->withProperties([
                'action' => 'login_failure',
                'login_id' => $loginId,
                'failed_attempts' => $failedAttempts,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'severity' => $severity,
                'flag' => 'LOGIN_FAILURE',
                'timestamp' => now()->toIso8601String()
            ])
            ->log("Login failed for: {$loginId} (Attempt #{$failedAttempts})");

        // Notify admins if critical
        if ($failedAttempts >= 3) {
            $this->notifyAdminsOfLoginFailure($loginId, request()->ip(), $failedAttempts);
        }
    }

    /**
     * Log successful login (INFO)
     */
    protected function logLoginSuccess(User $user)
    {
        $this->logActivity(
            'auth',
            "User logged in: {$user->name}",
            $user,
            [
                'action' => 'login_success',
                'user_id' => $user->id,
                'user_name' => $user->name
            ],
            self::SEVERITY_INFO
        );
    }

    /**
     * Log logout (INFO)
     */
    protected function logLogout(User $user)
    {
        $this->logActivity(
            'auth',
            "User logged out: {$user->name}",
            $user,
            [
                'action' => 'logout',
                'user_id' => $user->id,
                'user_name' => $user->name
            ],
            self::SEVERITY_INFO
        );
    }

    /**
     * Log permission denied (WARNING)
     */
    protected function logPermissionDenied(string $permission, ?string $resource = null)
    {
        $user = auth()->user();

        $this->logActivity(
            'security',
            "Permission denied: {$permission}" . ($resource ? " on {$resource}" : ""),
            null,
            [
                'action' => 'permission_denied',
                'permission' => $permission,
                'resource' => $resource,
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'flag' => 'PERMISSION_DENIED'
            ],
            self::SEVERITY_WARNING
        );
    }

    /**
     * Notify admins of security event
     */
    private function notifyAdminsOfSecurityEvent(string $eventType, string $resource, int $count, array $itemIds)
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        $deletedBy = auth()->user();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\BulkDeleteAttempt($deletedBy, $resource, $count, $itemIds));
        }
    }

    /**
     * Notify admins of unauthorized attempt
     */
    private function notifyAdminsOfUnauthorizedAttempt(User $attemptedBy, string $action, string $resource, ?string $resourceId)
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\UnauthorizedActionAttempt($attemptedBy, $action, $resource, $resourceId));
        }
    }

    /**
     * Notify of role change
     */
    private function notifyOfRoleChange(User $targetUser, User $changedBy, array $oldRoles, array $newRoles)
    {
        // Notify all admins
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->where('id', '!=', $changedBy->id)->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\RoleChanged($targetUser, $changedBy, $oldRoles, $newRoles));
        }

        // Also notify the affected user if they have new roles assigned
        if (!empty($newRoles)) {
            foreach ($newRoles as $roleName) {
                $role = \App\Models\Role::where('name', $roleName)->orWhere('slug', $roleName)->first();
                if ($role && !in_array($roleName, $oldRoles)) {
                    $targetUser->notify(new \App\Notifications\RoleAssigned($role, $changedBy));
                }
            }
        }
    }

    /**
     * Notify admins of login failure
     */
    private function notifyAdminsOfLoginFailure(string $loginId, string $ipAddress, int $failedAttempts)
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\LoginFailureAlert($loginId, $ipAddress, $failedAttempts));
        }
    }
}
