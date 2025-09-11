<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          Setting::create([
            'work_start' => '09:00',
            'work_end' => '17:00',
            'min_hours' => 8,
            'weekend_days' => ['friday', 'saturday'], // stored as JSON
            'allowed_late_minutes' => 15,
        ]);
    }
}
