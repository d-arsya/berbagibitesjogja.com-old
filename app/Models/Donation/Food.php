<?php

namespace App\Models\Donation;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Food extends Model
{
    use LogsActivity;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }

    protected $table = 'foods';

    public function donation()
    {
        return $this->belongsTo(Donation::class)->with('sponsor');
    }

    public static function totalGram()
    {
        return self::where('unit', 'gr')->sum('weight');
    }
}
