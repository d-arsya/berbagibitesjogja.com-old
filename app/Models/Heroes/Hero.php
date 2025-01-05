<?php

namespace App\Models\Heroes;

use App\Models\Donation\Donation;
use App\Models\Volunteer\Faculty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function donation()
    {
        return $this->belongsTo(Donation::class)->with('sponsor');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
