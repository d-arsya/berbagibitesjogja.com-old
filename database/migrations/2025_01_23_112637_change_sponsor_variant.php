<?php

use App\Models\Donation\Sponsor;
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
        Schema::table('sponsors', function (Blueprint $table) {
            $table->enum('variant', ['company', 'individual'])->after('name')->nullable();
        });
        Sponsor::whereNot('status', 'always')->update(["variant" => 'individual']);
        Sponsor::where('status', 'always')->update(["variant" => 'company']);
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('variant');
        });
        Schema::table('sponsors', function (Blueprint $table) {
            $table->enum('status', ['always', 'done', 'pending'])->after('name')->nullable();
        });
    }
};
