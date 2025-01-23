<?php

namespace App\Models\Donation;

use App\Models\Heroes\Hero;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function donation()
    {
        return $this->hasMany(Donation::class);
    }
    public function foods()
    {
        return $this->hasManyThrough(Food::class, Donation::class);
    }
    public function heroes()
    {
        return $this->hasManyThrough(Hero::class, Donation::class);
    }
}
