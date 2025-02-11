<?php

namespace App\Models\Heroes;

use App\Models\Donation\Donation;
use App\Models\Volunteer\Faculty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Backup extends Model
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
        return $this->belongsTo(Donation::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
