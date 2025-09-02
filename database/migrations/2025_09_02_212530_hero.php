<?php

use App\Models\Donation\Donation;
use App\Models\Volunteer\Faculty;
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
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->foreignIdFor(Faculty::class)->constrained()->onDelete('restrict');
            $table->enum('status', ['sudah', 'belum'])->default('belum');
            $table->foreignIdFor(Donation::class)->constrained()->onDelete('restrict');
            $table->char('code', 6)->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('weight')->default(0);
            $table->timestamps();
        });
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->foreignIdFor(Faculty::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(Donation::class)->constrained()->onDelete('restrict');
            $table->char('code', 6)->nullable();
            $table->timestamps();
        });
        Schema::create('notifies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heroes');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('notifies');
    }
};
