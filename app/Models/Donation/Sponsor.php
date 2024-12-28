<?php

namespace App\Models\Donation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function donation()
    {
        return $this->hasMany(Donation::class, 'sponsor')->get();
    }
}
