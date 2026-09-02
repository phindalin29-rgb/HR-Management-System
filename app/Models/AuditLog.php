<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Session;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'record_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
    ];

    /**
     * Record an audit trail entry. Called from model observers/traits.
     */
    public static function record(
        string $action,
        string $module,
        $recordId,
        array $old = [],
        array $new = [],
        $userId = null,
        $userName = null
    ): void {
        try {
            $user = Auth::user();

            $userId ??= Session::get('user_id') ?? ($user?->user_id ?? null);
            $userName ??= Session::get('name') ?? ($user?->name ?? null);

            if (empty($userId)) {
                return; // no actor -> skip (e.g. during seeding)
            }

            self::create([
                'user_id'     => $userId,
                'user_name'   => $userName,
                'action'      => $action,
                'module'      => $module,
                'record_id'   => $recordId,
                'description' => ucfirst($action) . " {$module} #{$recordId}",
                'old_values'  => !empty($old) ? json_encode($old) : null,
                'new_values'  => !empty($new) ? json_encode($new) : null,
                'ip_address'  => Request::ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('AuditLog failed: ' . $e->getMessage());
        }
    }
}
