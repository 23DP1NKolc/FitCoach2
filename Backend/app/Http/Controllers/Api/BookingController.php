<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    // GET /api/trainers/{trainer}/bookings
    // Atgriež aizņemtos laikus konkrētam trenerim (frontend kalendāram)
    public function index($trainerId, Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date'], // ja iedod date=YYYY-MM-DD, atgriezīs tikai tās dienas laikus
        ]);

        $q = Booking::query()->where('trainer_id', $trainerId);

        if ($request->filled('date')) {
            $q->where('date', $request->input('date'));
        }

        // Minimāli lauki, ko vajag UI
        return response()->json(
            $q->orderBy('date')->orderBy('time')->get(['id', 'date', 'time', 'status'])
        );
    }

    // POST /api/bookings   (jābūt aiz auth:sanctum routes/api.php)
    // Izveido rezervāciju un piesaista user_id (lai parādās "Manas rezervācijas")
    public function store(Request $request)
{
    $validated = $request->validate([
        'trainer_id' => ['required', 'exists:trainers,id'],
        'date' => ['required', 'date', function ($attribute, $value, $fail) {
            $tomorrow = Carbon::now('Europe/Riga')->startOfDay()->addDay();
            $selected = Carbon::parse($value, 'Europe/Riga')->startOfDay();
            if ($selected->lt($tomorrow)) {
                $fail('Rezervāciju var veikt tikai sākot no rītdienas.');
            }
        }],
        'time' => ['required', 'string'],
        'message' => ['nullable', 'string'],
    ]);

    // normalizē laiku uz HH:MM:SS
    $time = $validated['time'];
    if (preg_match('/^\d{2}:\d{2}$/', $time)) {
        $time .= ':00';
    }

    $user = $request->user(); // jābūt auth:sanctum maršrutam!

    // ja kādreiz nav user (drošībai)
    if (!$user) {
        return response()->json(['message' => 'Nepieciešams ielogoties.'], 401);
    }

    // pārbaude pret dublikātu (trainer+date+time)
    $exists = \App\Models\Booking::where('trainer_id', $validated['trainer_id'])
        ->where('date', $validated['date'])
        ->where('time', $time)
        ->exists();

    if ($exists) {
        return response()->json(['message' => 'Šis laiks jau ir aizņemts.'], 422);
    }

    $booking = \App\Models\Booking::create([
        'trainer_id' => $validated['trainer_id'],
        'date' => $validated['date'],
        'time' => $time,
        'message' => $validated['message'] ?? null,

        // ŠEIT ir galvenais fix:
        'client_name' => $user->name,
        'client_email' => $user->email,

        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    return response()->json($booking, 201);
}
}