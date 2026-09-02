<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;

class Holiday extends Model
{
    use HasFactory, LogsAudit;

    protected $auditModule = 'calendar';

    protected $fillable = [
        'name_holiday',
        'date_holiday',
    ];
}
