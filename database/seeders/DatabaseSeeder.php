<?php

namespace Database\Seeders;

use App\Models\Volunteer\Division;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $division = ['Food', 'Sosmed', 'Fund', 'Operational Manager', 'Friend', 'Volunteer', 'Bendahara', 'Sekretaris'];

    public function run(): void
    {
        foreach ($this->division as $item) {
            Division::create(['name' => $item]);
        }
    }
}
