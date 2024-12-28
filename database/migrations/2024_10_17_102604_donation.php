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
            $table->integer('hour');
            $table->integer('minute');
            $table->string('location');
            $table->string('maps');
            $table->string('message')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['aktif', 'selesai']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
