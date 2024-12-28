<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
