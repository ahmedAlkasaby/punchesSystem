<?php

namespace Database\Seeders;

use App\Models\Punch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PunchSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::with('locations')->get();
        $workStart = "09:00:00";
        $workEnd   = "17:00:00";

        foreach ($users as $user) {
            if ($user->locations->isEmpty()) {
                continue; // لازم يكون له مواقع
            }

            for ($day = 0; $day < 30; $day++) {
                $date = Carbon::today()->subDays($day);

                // ----------- Punch IN -----------
                $punchInTime = Carbon::parse($date->toDateString() . ' ' . $workStart)
                    ->addMinutes(rand(-15, 90));

                $workStartTime = Carbon::parse($date->toDateString() . ' ' . $workStart);
                $workEndTime   = Carbon::parse($date->toDateString() . ' ' . $workEnd);

                // لو حاولنا نحطه بعد وقت الانصراف → رجعه لآخر وقت مسموح
                if ($punchInTime->greaterThan($workEndTime)) {
                    $punchInTime = $workEndTime->copy()->subMinutes(rand(10, 30));
                }

                $lateSeconds = $punchInTime->diffInSeconds($workStartTime, false);
                $isLate = $lateSeconds > 0;
                $lateSeconds = $isLate ? $lateSeconds : null;

                // اختار location عشوائي للموظف
                $location = $user->locations->random();

                // 80% جوه الموقع – 20% بره
                $inside = $faker->boolean(80);

                if ($inside) {
                    $lat = $location->latitude + $faker->randomFloat(6, -0.001, 0.001);
                    $lng = $location->longitude + $faker->randomFloat(6, -0.001, 0.001);
                    $distance = $this->haversine($lat, $lng, $location->latitude, $location->longitude);
                    $isOut = false;
                    $locationId = $location->id;
                } else {
                    $lat = $location->latitude + $faker->randomFloat(6, 0.02, 0.05);
                    $lng = $location->longitude + $faker->randomFloat(6, 0.02, 0.05);
                    $distance = null;
                    $isOut = true;
                    $locationId = null;
                }

                Punch::create([
                    'user_id' => $user->id,
                    'type' => 'in',
                    'is_late' => $isLate,
                    'late_seconds' => $lateSeconds,
                    'is_out_of_radius' => $isOut,
                    'approved' => !$isLate && !$isOut,
                    'location_id' => $locationId,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'address' => $faker->address,
                    'device_info' => $faker->randomElement(['iPhone 14', 'Samsung S22', 'Dell Laptop', 'MacBook Pro']),
                    'distance_from_location' => $distance,
                    'note' => $faker->optional()->sentence,
                    'punched_at' => $punchInTime,
                ]);

                // ----------- Punch OUT -----------
                $punchOutTime = Carbon::parse($date->toDateString() . ' ' . $workEnd)
                    ->subMinutes(rand(-90, 15));

                // لو طلع قبل وقت الحضور → خليه عند الأقل بداية الشغل
                if ($punchOutTime->lessThan($workStartTime)) {
                    $punchOutTime = $workStartTime->copy()->addMinutes(rand(10, 30));
                }

                $earlyLeaveSeconds = $workEndTime->diffInSeconds($punchOutTime, false);
                $isEarlyLeave = $earlyLeaveSeconds > 0;
                $earlyLeaveSeconds = $isEarlyLeave ? $earlyLeaveSeconds : null;

                $location = $user->locations->random();
                $inside = $faker->boolean(90); // أغلب الخروج بيكون جوه

                if ($inside) {
                    $lat = $location->latitude + $faker->randomFloat(6, -0.001, 0.001);
                    $lng = $location->longitude + $faker->randomFloat(6, -0.001, 0.001);
                    $distance = $this->haversine($lat, $lng, $location->latitude, $location->longitude);
                    $isOut = false;
                    $locationId = $location->id;
                } else {
                    $lat = $location->latitude + $faker->randomFloat(6, 0.02, 0.05);
                    $lng = $location->longitude + $faker->randomFloat(6, 0.02, 0.05);
                    $distance = null;
                    $isOut = true;
                    $locationId = null;
                }

                Punch::create([
                    'user_id' => $user->id,
                    'type' => 'out',
                    'is_early_leave' => $isEarlyLeave,
                    'early_leave_seconds' => $earlyLeaveSeconds,
                    'is_out_of_radius' => $isOut,
                    'approved' => !$isEarlyLeave && !$isOut,
                    'location_id' => $locationId,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'address' => $faker->address,
                    'device_info' => $faker->randomElement(['iPhone 14', 'Samsung S22', 'Dell Laptop', 'MacBook Pro']),
                    'distance_from_location' => $distance,
                    'note' => $faker->optional()->sentence,
                    'punched_at' => $punchOutTime,
                ]);
            }
        }
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
}
