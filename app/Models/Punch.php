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
        'late_seconds',
        'early_leave_seconds',
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

    protected $casts = [
    'punched_at' => 'datetime',
   ];


   



   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function employee()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('type', 'employee');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public static function getLastPunchOfUserToday($userId)
    {
        $today = Carbon::today();
    
        return self::where('user_id', $userId)
            ->whereDate('punched_at', $today) 
            ->orderByDesc('punched_at')
            ->first();
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

     public function getEarlyLeaveFormattedAttribute()
    {
        if (!$this->early_leave_seconds) {
            return null;
        }
    
        $hours = floor($this->early_leave_seconds / 3600);
        $minutes = floor(($this->early_leave_seconds % 3600) / 60);
    
        if ($hours > 0) {
            return $hours . ' ' . __('site.hour') . ($minutes > 0 ? ' ' . $minutes . ' ' . __('site.minute') : '');
        }
    
        return $minutes . ' ' . __('site.minute');
    }

    public function getDistanceFormattedAttribute()
    {
        if (is_null($this->distance_from_location)) {
            return null;
        }
    
        $distance = $this->distance_from_location;
    
        if ($distance < 1000) {
            // أقل من ١ كم → أظهر بالمتر
            return round($distance) . ' ' . __('site.meter');
        }
    
        // ١ كم فأكثر → أظهر بالكيلومتر
        return number_format($distance / 1000, 2) . ' ' . __('site.kilometer');
    }




}
