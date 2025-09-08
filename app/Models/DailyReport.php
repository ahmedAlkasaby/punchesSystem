<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends MainModel
{
    protected $table = 'daily_reports';
    protected $fillable = [
        'employee_id',
        'date',
        'first_in',
        'last_out',
        'total_seconds',
        'total_hours',
        'day_status',
        'exception_type',
        'is_late',
        'late_seconds',
        'is_early_leave',
        'is_under_hours',
        'has_missing_in',
        'has_missing_out',
        'flagged_in',
        'flagged_out',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
