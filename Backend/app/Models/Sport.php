<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name'];

    public function trainers()
    {
        return $this->hasMany(Trainer::class);
    }
}