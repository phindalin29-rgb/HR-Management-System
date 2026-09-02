<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;

/**
 * Attach to any Eloquent model to automatically record create/update/delete
 * activity into the audit_logs table. Usage: `use LogsAudit;` inside the model.
 *
 * Optional properties:
 *   protected $auditModule = 'calendar';   // module shown in the audit log
 *   protected $auditExcept = ['password'];  // attributes hidden from the log
 */
trait LogsAudit
{
    public static function bootLogsAudit()
    {
        static::created(function ($model) {
            AuditLog::record('created', $model->auditModule(), $model->getKey(), [], $model->auditValues($model->getAttributes()));
        });

        static::updated(function ($model) {
            AuditLog::record('updated', $model->auditModule(), $model->getKey(),
                $model->auditValues($model->getOriginal()),
                $model->auditValues($model->getChanges())
            );
        });

        static::deleted(function ($model) {
            AuditLog::record('deleted', $model->auditModule(), $model->getKey(), $model->auditValues($model->getAttributes()), []);
        });
    }

    public function auditModule(): string
    {
        return property_exists($this, 'auditModule') ? $this->auditModule : class_basename($this);
    }

    public function auditValues(array $attributes): array
    {
        $except = property_exists($this, 'auditExcept')
            ? $this->auditExcept
            : ['password', 'remember_token', 'qr_token', 'biometric_id'];

        return collect($attributes)->except($except)->toArray();
    }
}
