<?php

namespace App\Models;

use App\StatusPunchEnum;
use Illuminate\Database\Eloquent\Model;

class Punch extends MainModel
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'location_id',
        'device_info',
        'note',
    ];


    protected $casts = [
        'status' => StatusPunchEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }


}
