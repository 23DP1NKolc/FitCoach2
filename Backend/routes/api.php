<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrainerController;

Route::get('/treneri', [TrainerController::class, 'index']);
Route::get('/treneri/{id}', [TrainerController::class, 'show']);