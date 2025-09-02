<?php

namespace Database\Seeders;

use App\Models\Volunteer\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared(file_get_contents(public_path('data.sql')));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
