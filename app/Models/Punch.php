<?php

namespace App\Models;

use App\Enums\StatusPunchEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Punch extends MainModel
{
    protected $fillable = [
        'user_id',
        'type',
        'is_late',
        'is_early_leave',
        'is_out_of_radius',
        'approved',
        'approved_by',
        'approved_at',
        'location_id',
        'latitude',
        'longitude',
        'address',
        'distance_from_location',
        'device_info',
        'note',
        'punched_at',
    ];

   



   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

     public static function getLastPunchOfUserToday($userId)
    {
        $today = Carbon::today();

        return self::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->orderByDesc('created_at')
            ->first();
    }


}
