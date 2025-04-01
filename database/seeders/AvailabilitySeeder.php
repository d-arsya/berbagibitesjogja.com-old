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
        User::where('name', 'BELUM')->delete();
        $userIds = User::pluck('id')->toArray();

        $data = [];

        foreach ($userIds as $id) {
            foreach (range(1, 7) as $day) {
                foreach (range(7, 21) as $hour) {
                    $data[] = [
                        "user_id" => $id,
                        "day" => $day,
                        "hour" => $hour,
                        "minute" => 0,
                        "code" => "{$day}{$hour}0",
                    ];
                    $data[] = [
                        "user_id" => $id,
                        "day" => $day,
                        "hour" => $hour,
                        "minute" => 30,
                        "code" => "{$day}{$hour}5",
                    ];
                }
            }
            Availability::insert($data);
            $data = [];
        }
        Availability::whereNull('created_at')->update(["created_at" => now(), "updated_at" => now()]);
    }
}
