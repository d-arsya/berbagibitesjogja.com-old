<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('app_configuration', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        DB::table('app_configuration')->insert(["key" => "REIMBURSE_CONTACT", "value" => "6289636055420"]);
        DB::table('app_configuration')->insert(["key" => "GROUP_WA", "value" => "6289636055420"]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_configuration', function (Blueprint $table) {
            $table->addColumn('datetime', 'created_at');
            $table->addColumn('datetime', 'updated_at');
        });
        DB::table('app_configuration')->where('key', 'REIMBURSE_CONTACT')->delete();
        DB::table('app_configuration')->where('key', 'GROUP_WA')->delete();
    }
};
