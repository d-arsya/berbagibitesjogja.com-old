<?php

namespace Database\Seeders;

use App\Models\Heroes\Hero;
use App\Models\Heroes\University;
use App\Models\Volunteer\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $foundations = ["Rumah Singgah"];
    // private $societies = ["Barista", "PK4L", "Driver Gojek"];
    // private $faculties = ["Volunteer"];
    public function run(): void
    {
        foreach ($this->foundations as $item) {
            $foundation = University::create(["name" => $item, "variant" => "foundation"]);
            $foundation = Faculty::create(["name" => $item, "university_id" => $foundation->id]);
            Hero::where('name', 'LIKE', "%$item%")->update(["faculty_id" => $foundation->id]);
        }
        // foreach ($this->societies as $item) {
        //     $society = Faculty::create(["name" => $item, "university_id" => 2]);
        //     Hero::where('name', 'LIKE', "%$item%")->update(["faculty_id" => $society->id]);
        // }
        // foreach ($this->faculties as $item) {
        //     $faculty = Faculty::create(["name" => $item, "university_id" => 1]);
        //     Hero::where('name', 'LIKE', "%$item%")->update(["faculty_id" => $faculty->id]);
        // }
    }
}
