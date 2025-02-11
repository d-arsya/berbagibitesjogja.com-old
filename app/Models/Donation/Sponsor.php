<?php

namespace App\Models\Donation;

use App\Models\Heroes\Hero;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sponsor extends Model
{
    use LogsActivity;
    protected $guarded = ['id','created_at','updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded();
    }

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
