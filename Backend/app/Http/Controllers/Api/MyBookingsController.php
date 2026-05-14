<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class MyBookingsController extends Controller
{
    // GET /api/my-bookings
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        return Booking::with('trainer:id,name')
            ->where('user_id', $userId)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();
    }

    // PUT /api/my-bookings/{booking}
    public function update(Request $request, Booking $booking)
    {
        $userId = $request->user()->id;

        if ($booking->user_id !== $userId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking->update($data);
        return response()->json($booking);
    }

    // DELETE /api/my-bookings/{booking}
    public function destroy(Request $request, Booking $booking)
    {
        $userId = $request->user()->id;

        if ($booking->user_id !== $userId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $booking->delete();
        return response()->json(['ok' => true]);
    }
}