<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MyBookingsController;
use App\Http\Controllers\Api\StatsController;

use App\Http\Controllers\Api\Admin\SportController as AdminSportController;
use App\Http\Controllers\Api\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| baseURL: /api
*/

/* ---------------------------
| Publiskie (bez login)
|----------------------------*/
Route::get('/treneri', [TrainerController::class, 'index']);     // advanced filter + paginate (server-side)
Route::get('/treneri/{trainer}', [TrainerController::class, 'show']);

Route::get('/trainers/{trainer}/bookings', [BookingController::class, 'index']); // aizņemti laiki konkrētam trenerim

Route::get('/stats/trainers-by-sport', [StatsController::class, 'trainersBySport']);
Route::get('/stats/bookings-by-day', [StatsController::class, 'bookingsByDay']);

/* ---------------------------
| Auth (token, Sanctum)
|----------------------------*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

/* ---------------------------
| User (jābūt ielogotam)
|----------------------------*/
Route::middleware('auth:sanctum')->group(function () {

    // Rezervāciju izveide (tikai ielogotiem -> lai user_id vienmēr saglabājas)
    Route::post('/bookings', [BookingController::class, 'store']);

    // Manas rezervācijas (UI CRUD: GET/PUT/DELETE)
    Route::get('/my-bookings', [MyBookingsController::class, 'index']);
    Route::put('/my-bookings/{booking}', [MyBookingsController::class, 'update']);
    Route::delete('/my-bookings/{booking}', [MyBookingsController::class, 'destroy']);
});

/* ---------------------------
| Admin (jābūt ielogotam + admin)
|----------------------------*/
Route::prefix('admin')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {

        // Sports CRUD
        Route::get('/sports', [AdminSportController::class, 'index']);
        Route::post('/sports', [AdminSportController::class, 'store']);
        Route::put('/sports/{sport}', [AdminSportController::class, 'update']);
        Route::delete('/sports/{sport}', [AdminSportController::class, 'destroy']);

        // Treneri CRUD
        Route::get('/trainers', [AdminTrainerController::class, 'index']);
        Route::post('/trainers', [AdminTrainerController::class, 'store']);
        Route::put('/trainers/{trainer}', [AdminTrainerController::class, 'update']);
        Route::delete('/trainers/{trainer}', [AdminTrainerController::class, 'destroy']);

        // Rezervācijas CRUD
        Route::get('/bookings', [AdminBookingController::class, 'index']);
        Route::put('/bookings/{booking}', [AdminBookingController::class, 'update']);
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy']);
    });