<?php

use App\Models\Donation\Donation;
use App\Models\Heroes\Hero;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $donations = Donation::all();
        foreach ($donations as $item) {
            if ($item->heroes->count() == 0) {
                Hero::create([
                    "name" => "KOSONG",
                    "phone" => "628123456789",
                    "faculty_id" => 21,
                    "status" => "sudah",
                    "donation_id" => $item->id,
                    "code" => "000000",
                    "quantity" => 0
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Hero::where('name', 'KOSONG')->delete();
    }
};
