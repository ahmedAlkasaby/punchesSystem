<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends MainModel
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'active',
        'order_id',
    ];

    protected $casts = [
        'date' => 'date',
        'active' => 'boolean',
    ];

    
}
