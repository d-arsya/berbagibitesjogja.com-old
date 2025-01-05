<?php

namespace App\Models\Donation;

use App\Models\Heroes\Hero;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function heroes()
    {
        return $this->hasMany(Hero::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
