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

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class)->with('university');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function points()
    {
        return $this->attendances->sum('point');
    }
}
