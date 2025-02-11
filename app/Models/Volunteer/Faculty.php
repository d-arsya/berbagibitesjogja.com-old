<?php

namespace App\Models\Volunteer;

use App\Models\Heroes\Hero;
use App\Models\Heroes\University;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Faculty extends Model
{
    use LogsActivity, HasFactory;
    protected $guarded = ['id','created_at','updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded();
    }

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
