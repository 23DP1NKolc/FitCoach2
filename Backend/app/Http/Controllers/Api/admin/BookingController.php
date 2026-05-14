<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with('trainer:id,name')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required','in:pending,approved,rejected'],
        ]);

        $booking->update($data);
        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['ok' => true]);
    }
}