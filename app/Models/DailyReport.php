<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends MainModel
{
    protected $table = 'daily_reports';
    protected $fillable = [
        'employee_id', 'date', 'first_in', 'last_out', 'total_seconds', 'total_hours', 'day_status', 'exception_id', 'exception_type',
        'is_late', 'late_seconds', 'is_early_leave', 'is_under_hours', 'has_missing_in', 'has_missing_out', 'flagged_in', 'flagged_out', 'notes', 'computed_at',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function exception()
    {
        return $this->belongsTo(Exception::class, 'exception_id','id');
    }

     public function getLateFormattedAttribute()
    {
        if (!$this->late_seconds) {
            return null;
        }
    
        $hours = floor($this->late_seconds / 3600);
        $minutes = floor(($this->late_seconds % 3600) / 60);
    
        if ($hours > 0) {
            return $hours . ' ' . __('site.hour') . ($minutes > 0 ? ' ' . $minutes . ' ' . __('site.minute') : '');
        }
    
        return $minutes . ' ' . __('site.minute');
    }


}
