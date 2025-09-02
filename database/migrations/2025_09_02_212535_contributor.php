<?php

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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->unique();
            $table->string('name');
            $table->string('phone');
            $table->string('location');
            $table->integer('quota');
            $table->string('description');
            $table->timestamp('take')->nullable();
            $table->enum('variant', ['company', 'individual']);
            $table->enum('status', ['waiting', 'cancel', 'done'])->default("waiting");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
