<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'trainer_id',
        'user_id',
        'client_name',
        'client_email',
        'date',
        'time',
        'message',
        'status',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}