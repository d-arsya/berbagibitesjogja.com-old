<?php

namespace App\Models\Heroes;

use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Volunteer\Faculty;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $guarded = [];
    protected $table = 'universities';
    public function faculties()
    {
        return $this->hasMany(Faculty::class)->with('heroes');
    }
    public function heroes()
    {
        return $this->hasManyThrough(Hero::class, Faculty::class);
    }
    public function foods()
    {
        $foods = $this->heroes->pluck('donation_id')->unique();
        $foods = Food::whereIn('donation_id', $foods);
        return $foods;
    }
}
