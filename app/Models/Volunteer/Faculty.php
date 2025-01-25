<?php

namespace App\Models\Volunteer;

use App\Models\Heroes\Hero;
use App\Models\Heroes\University;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'faculties';

    public function heroes()
    {
        return $this->hasMany(Hero::class)->with(['faculty', 'donation']);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
