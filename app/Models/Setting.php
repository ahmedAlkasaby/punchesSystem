<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends MainModel
{
    protected $fillable = [
        'work_start',
        'work_end',
        'min_hours',
        'weekend_days',
        'allowed_late_minutes',
    ];

    protected $casts = [
        'weekend_days' => 'array',
      
    ];
}
