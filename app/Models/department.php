<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;

class department extends Model
{
    use HasFactory, LogsAudit;

    protected $auditModule = 'departments';

    protected $fillable = [
        'department',
    ];
}
