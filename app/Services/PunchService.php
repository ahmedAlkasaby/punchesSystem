<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Punch;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

class PunchService
{

    public function canPunchNow($type)
    {
        $settings = Setting::first();
        if (!$settings || !$settings->work_start || !$settings->work_end) {
            return ['allowed' => true, 'message' => null];
        }

        $now = now();
        $workStart = Carbon::parse(today()->toDateString() . ' ' . $settings->work_start);
        $workEnd   = Carbon::parse(today()->toDateString() . ' ' . $settings->work_end);

        // ممنوع دخول بعد انتهاء الدوام
        if ($type === 'in' && $now->greaterThan($workEnd)) {
            return [
                'allowed' => false,
                'message' => __('api.punch_after_work_end'), // ⏰ Attendance is closed, you cannot check in after working hours
            ];
        }

        // ممنوع خروج قبل بداية الدوام
        if ($type === 'out' && $now->lessThan($workStart)) {
            return [
                'allowed' => false,
                'message' => __('api.punch_before_work_start'), // 🚫 You cannot check out before working hours
            ];
        }

        return ['allowed' => true, 'message' => null];
    }


    public function getTypePunchOfUser($userId)
    {
        $user = User::find($userId);
        $punch = null;


        if ($user->last_punched_at !== null) {
            $punch = Punch::where('punched_at', $user->last_punched_at)->first();
        } else {
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
        $now = now();

        return $now->greaterThan($workStart);
    }

    public function getLateSeconds($isLate)
    {
        if ($isLate !== true) {
            return 0;
        }

        $settings = Setting::first();
        if (!$settings || !$settings->work_start) {
            return 0;
        }

        $graceMinutes = $settings->allowed_late_minutes ?? 0;

        $workStart = Carbon::parse(today()->toDateString() . ' ' . $settings->work_start)
            ->addMinutes($graceMinutes);

        $now = Carbon::parse(now());

        if ($now->lessThanOrEqualTo($workStart)) {
            return 0;
        }

        return $now->diffInSeconds($workStart);
    }





    public function getEarlyLeaveSecands($isEarlyLeave)
    {
        if ($isEarlyLeave !== true) {
            return 0;
        }

        $settings = Setting::first();
        if (!$settings || !$settings->work_end) {
            return 0;
        }

        $graceMinutes = $settings->early_leave_grace ?? 0;

        // وقت نهاية الدوام - فترة السماح
        $workEnd = Carbon::parse(today()->toDateString() . ' ' . $settings->work_end)
            ->subMinutes($graceMinutes);

        $now = now();

        // لو خارج في معاده أو بعده → صفر
        if ($now->greaterThanOrEqualTo($workEnd)) {
            return 0;
        }

        // رجع الفرق بالثواني
        return $workEnd->diffInSeconds($now);
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
        $now = now();

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
