<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Model;

class Precence extends Model
{
    protected $guarded = [];

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
