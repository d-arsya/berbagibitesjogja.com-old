<?php

use App\Models\Heroes\University;
use App\Models\Volunteer\Faculty;
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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('variant', ['student', 'society', 'foundation']);
            $table->timestamps();
        });
        University::create(["name" => "Universitas Gadjah Mada", "variant" => "student"]);
        University::create(["name" => "Masyarakat Umum", "variant" => "society"]);
        Schema::table('faculties', function (Blueprint $table) {
            $table->foreignIdFor(University::class)->after('name');
        });
        Faculty::whereNot('name', 'Kontributor')->update(["university_id" => 1]);
        Faculty::where('name', 'Kontributor')->update(["university_id" => 2]);
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(Faculty::class)->after('role');
        });
        $users = User::all();
        foreach ($users as $user) {
            $user->faculty_id = $user->program->faculty->id;
            $user->save();
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('program_id');
        });
        Schema::dropIfExists('programs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropColumn('university_id');
        });
        Schema::dropIfExists('universities');
    }
};
