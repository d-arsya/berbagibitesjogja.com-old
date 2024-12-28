<?php

use App\Models\Donation\Sponsor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Sponsor::class, 'sponsor');
            $table->integer('quota');
            $table->integer('remain');
            $table->date('take');
            $table->integer('hour')->default(12);
            $table->integer('minute')->default(0);
            $table->string('location')->default('Podocarpus Cafe');
            $table->string('maps')->default('https://maps.app.goo.gl/WedRmXKAyym9uWph8');
            $table->string('message')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
