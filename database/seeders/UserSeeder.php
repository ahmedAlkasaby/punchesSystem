<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
     

        // Create users and assign locations
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => bcrypt('password'),
                'type' => 'employee',
                'active' => true,
                'lang' => 'ar',
                'theme' => 'dark',
            ]);

            $locations = Location::where('active', 1)->inRandomOrder()->limit(3)->pluck('id');

            $user->locations()->attach($locations);
        }
    }
}
