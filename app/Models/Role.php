<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;

class Role extends Model
{
    use HasFactory, LogsAudit;

    protected $auditModule = 'settings';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'modules',
        'is_system',
    ];

    protected $casts = [
        'modules' => 'array',
        'is_system' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /** Whether this role is permitted to access the given module. */
    public function hasModule(string $module): bool
    {
        $modules = $this->modules ?? [];

        return in_array($module, $modules, true);
    }
}
