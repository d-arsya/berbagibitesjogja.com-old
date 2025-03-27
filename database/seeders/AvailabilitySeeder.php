<?php

namespace Database\Seeders;

use App\Models\Volunteer\Availability;
use App\Models\Volunteer\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ids = User::all()->pluck('id');

        foreach ($ids as $id) {
            for ($day = 1; $day <= 7; $day++) {
                for ($hour = 7; $hour <= 21; $hour++) {
                    Availability::create([
                        "user_id" => $id,
                        "day" => $day,
                        "hour" => $hour,
                        "minute" => 0,
                        "code" => $day . $hour . "0"
                    ]);
                    Availability::create([
                        "user_id" => $id,
                        "day" => $day,
                        "hour" => $hour,
                        "minute" => 30,
                        "code" => $day . $hour . "5"
                    ]);
                }
            }
        }
    }
}
