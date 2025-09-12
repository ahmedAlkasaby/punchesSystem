<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends MainModel
{
    protected $table = 'holidays';
    protected $fillable = [
        'name',
        'description',
        'date',
        'active',
        'order_id',
    ];

    protected $casts = [
         'name' => \App\Casts\UnescapedJson::class,
        'description' => \App\Casts\UnescapedJson::class,
        'date' => 'date',
        'active' => 'boolean',
    ];

    
}
