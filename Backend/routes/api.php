<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\BookingController;

Route::get('/trainers/{trainer}/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/treneri', [TrainerController::class, 'index']);
Route::get('/treneri/{id}', [TrainerController::class, 'show']);