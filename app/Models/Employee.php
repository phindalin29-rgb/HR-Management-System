<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;

class Employee extends Model
{
    use HasFactory, LogsAudit;
    protected $table = 'employees'; // Specify the table name if it's not pluralized
    
    protected $fillable = [ // Using Models
        'employee_id',
    ];
}
