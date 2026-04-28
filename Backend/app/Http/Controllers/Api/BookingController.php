<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // GET /api/trainers/{id}/bookings?from=YYYY-MM-DD&to=YYYY-MM-DD
    public function index(Request $request, $trainerId)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $q = Booking::query()->where('trainer_id', $trainerId);

        if ($from) $q->whereDate('date', '>=', $from);
        if ($to) $q->whereDate('date', '<=', $to);

        return response()->json(
            $q->orderBy('date')->orderBy('time')->get(['id', 'date', 'time'])
        );
    }

    // POST /api/bookings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainer_id' => ['required', 'exists:trainers,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking = Booking::create($validated);

        return response()->json($booking, 201);
    }
}