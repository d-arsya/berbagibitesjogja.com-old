<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Precence extends Model
{
    use LogsActivity;
    protected $guarded = ['id','created_at','updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->latitude)) {
                $model->latitude = '-7.775326279953496';
            }
            if (is_null($model->longitude)) {
                $model->longitude = '110.3778869084333';
            }
            if (is_null($model->max_distance)) {
                $model->max_distance = 50;
            }
            if (is_null($model->status)) {
                $model->status = 'active';
            }
        });
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class)->with('user');
    }
}
