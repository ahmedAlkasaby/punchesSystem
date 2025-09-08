<?php

namespace App\Models;

use App\StatusExceptionEnum;
use Illuminate\Database\Eloquent\Model;

class Exception extends MainModel
{
    protected $fillable = [
        'employee_id',
        'from_date',
        'to_date',
        'type',
        'reason',
        'status',
        'created_by',
    ];
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'status' => StatusExceptionEnum::class,
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
