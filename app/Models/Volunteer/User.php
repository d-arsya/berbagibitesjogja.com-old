<?php

namespace App\Models\Volunteer;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];
    public function faculty()
    {
        return $this->belongsTo(Faculty::class)->with('university');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
