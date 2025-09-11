<?php

namespace App\Models;


class Location extends MainModel
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'redius_meter',
        'active',
        'order_id',
        'city_id',
        'region_id',
    ];

   


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_location')
            ->withPivot('primary_location')
            ->withTimestamps();
    }
}
