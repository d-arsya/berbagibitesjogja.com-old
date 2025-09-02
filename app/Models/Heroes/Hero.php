<?php

namespace App\Models\Heroes;

use App\Models\Donation\Donation;
use App\Models\Volunteer\Faculty;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Hero extends Model
{
    use LogsActivity;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class)->with('sponsor');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class)->with('university');
    }
}
