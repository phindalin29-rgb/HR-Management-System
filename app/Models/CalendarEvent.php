<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;

class CalendarEvent extends Model
{
    use HasFactory, LogsAudit;

    protected $auditModule = 'calendar';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'color',
        'type',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
