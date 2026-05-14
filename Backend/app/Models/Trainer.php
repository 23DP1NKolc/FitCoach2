<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sport',        // vari atstāt veco, bet adminā izmantosim sport_id
        'sport_id',
        'city',
        'price_per_hour',
        'online',
    ];

    public function sportRel()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }
}