<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    // Treneru skaits pa sporta veidiem + vidējā cena
    public function trainersBySport()
    {
        $rows = DB::table('trainers')
            ->leftJoin('sports', 'sports.id', '=', 'trainers.sport_id')
            ->selectRaw('COALESCE(sports.name, "Nav norādīts") as sport')
            ->selectRaw('COUNT(trainers.id) as treneru_skaits')
            ->selectRaw('AVG(trainers.price_per_hour) as videja_cena')
            ->groupBy('sport')
            ->orderByDesc('treneru_skaits')
            ->get();

        return response()->json($rows);
    }

    // Rezervāciju skaits pa dienām (pēdējās 30 dienas)
    public function bookingsByDay()
    {
        $rows = DB::table('bookings')
            ->selectRaw('date as diena')
            ->selectRaw('COUNT(*) as rezervaciju_skaits')
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($rows);
    }
}