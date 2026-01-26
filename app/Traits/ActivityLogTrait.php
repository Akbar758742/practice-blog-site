<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;

trait ActivityLogTrait
{
    /**
     * Log an activity with subject
     */
    protected function logActivity(string $logName, string $description, $subject = null, array $properties = [])
    {
        $activity = activity($logName)
            ->causedBy(auth()->user());

        if ($subject) {
            $activity->performedOn($subject);
        }

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
}
