<?php

namespace App\Models\Donation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'foods';

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation', 'id')->first();
    }

    public static function totalGram()
    {
        return self::where('unit', 'gr')->sum('weight');
    }
}
