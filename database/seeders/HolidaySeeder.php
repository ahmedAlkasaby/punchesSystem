<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            [
                'name' => [
                    'en' => 'New Year\'s Day',
                    'ar' => 'رأس السنة',
                ],
                'description' => [
                    'en' => 'Celebration of the new year',
                    'ar' => 'الاحتفال ببداية العام الجديد',
                ],
                'date' => '2025-01-01',
                'active' => true,
                'order_id' => 1,
            ],
            [
                'name' => [
                    'en' => 'Labor Day',
                    'ar' => 'عيد العمال',
                ],
                'description' => [
                    'en' => 'Workers celebration',
                    'ar' => 'احتفال بالعمال',
                ],
                'date' => '2025-05-01',
                'active' => true,
                'order_id' => 2,
            ],
            [
                'name' => [
                    'en' => 'Christmas',
                    'ar' => 'عيد الميلاد',
                ],
                'description' => [
                    'en' => 'Christmas holiday',
                    'ar' => 'عطلة عيد الميلاد',
                ],
                'date' => '2025-12-25',
                'active' => true,
                'order_id' => 3,
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']], // Prevent duplicates
                $holiday
            );
        }
    }
}
