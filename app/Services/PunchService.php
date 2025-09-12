<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

class PunchService
{
    public function getTypePunchOfUser($userId)
    {
        $user = User::find($userId);
        $punch = null;
    

        if ($user->last_punched_at !== null) {
            $punch = Punch::where('punched_at', $user->last_punched_at)->first();
        }else{
            $punch = Punch::getLastPunchOfUserToday($userId);
        }
    
        if (!$punch) {
            return 'in';
        }
    
        return $punch->type === 'in' ? 'out' : 'in';
    }


    public function checkLatePunch($typePunch)
    {
        if ($typePunch !== 'in') {
            return false;
        }

        $settings = Setting::first();
        if (!$settings || !$settings->work_start) {
            return false;
        }

        $graceMinutes = $settings->allowed_late_minutes ?? 0;

        $workStart = Carbon::parse(today()->toDateString() . ' ' . $settings->work_start)
            ->addMinutes($graceMinutes);
        $now= now();

        return $now->greaterThan($workStart);
    }

    public function checkEarlyPunch($typePunch)
    {
        if ($typePunch !== 'out') {
            return false;
        }

        $settings = Setting::first();
        if (!$settings || !$settings->work_end) {
            return false;
        }

        $graceMinutes = $settings->early_leave_grace ?? 0;

        $workEnd = Carbon::parse(today()->toDateString() . ' ' . $settings->work_end)
            ->subMinutes($graceMinutes);
        $now= now();

        return $now->lessThan($workEnd);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    public function checkOutOfRadiusForUserLocations($userId, $latitude, $longitude)
    {
        $user = User::find($userId);
        if (!$user->locations || $user->locations->isEmpty()) {
            return true;
        }

        foreach ($user->locations as $location) {
            $distance = $this->haversine(
                $latitude,
                $longitude,
                $location->latitude,
                $location->longitude
            );

            if ($distance <= $location->radius_meters) {
                return false;
            }
        }

        return true;
    }



    public function checkApproved($isLate, $isEarlyLeave, $isOutOfRadius)
    {
        if ($isLate || $isEarlyLeave || $isOutOfRadius) {
            return false;
        } else {
            return true;
        }
    }

    public function getNearestLocationForUser($userId, $latitude, $longitude)
    {
        $user = User::with('locations')->find($userId);

        if (!$user || !$user->locations || $user->locations->isEmpty()) {
            return [null, null]; 
        }

        $nearestLocation = null;
        $nearestDistance = null;

        foreach ($user->locations as $location) {
            $distance = $this->haversine(
                $latitude,
                $longitude,
                $location->latitude,
                $location->longitude
            );

          
            if (is_null($nearestDistance) || $distance < $nearestDistance) {
                $nearestLocation = $location;
                $nearestDistance = $distance;
            }
        }

        return [$nearestLocation?->id, $nearestDistance];
    }


    public function getMessages($isApproved, $isOutOfRadius, $isLate, $isEarlyLeave)
    {
        $messages = [];
    
        if ($isOutOfRadius) {
            $messages[] = __('api.punch.out_of_radius');
        }
    
        if ($isLate) {
            $messages[] = __('api.punch.late');
        }
    
        if ($isEarlyLeave) {
            $messages[] = __('api.punch.early_leave');
        }
    
        if ($isApproved && empty($messages)) {
            $messages[] = __('api.punch.approved');
        }
    
        return $messages;
    }



  
}
