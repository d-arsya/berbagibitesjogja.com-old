<?php

use App\Models\Volunteer\Precence;
use App\Models\Volunteer\User;
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
        Schema::create('precences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('latitude')->nullable()->default('-7.775326279953496');
            $table->string('longitude')->nullable()->default('110.3778869084333');
            $table->integer('max_distance')->nullable()->default(20);
            $table->char('code', 20);
            $table->enum('status', ['active', 'end'])->default('active');
            $table->timestamps();
        });
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Precence::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->integer('distance');
            $table->integer('point')->nullable();
            $table->unique(['precence_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precences');
        Schema::dropIfExists('attendances');
    }
};
