<?php

use App\Models\Donation\Donation;
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
            if ($item->remain > 0) {
                $item->quota = $item->quota - $item->remain;
                $item->remain = 0;
                $item->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
