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
        return $this->belongsTo(Program::class)->first();
    }

    public function division()
    {
        return $this->belongsTo(Division::class)->first();
    }
}
