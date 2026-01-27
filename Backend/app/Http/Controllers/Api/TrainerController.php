<?php

namespace App\Http\Controllers\Api;

use App\Models\Trainer;
use App\Http\Controllers\Controller;

class TrainerController extends Controller
{
    public function index()
    {
        return Trainer::all();
    }

    public function show($id)
    {
        return Trainer::findOrFail($id);
    }
}