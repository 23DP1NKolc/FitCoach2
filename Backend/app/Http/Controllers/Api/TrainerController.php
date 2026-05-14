<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    // GET /api/treneri?q=&sport_id=&city=&online=&min_price=&max_price=&sort=&dir=&per_page=&page=
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'sport_id' => ['nullable', 'integer', 'min:1'],
            'city' => ['nullable', 'string', 'max:120'],
            'online' => ['nullable', 'in:0,1'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:name,price,city,sport'],
            'dir' => ['nullable', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $q = $validated['q'] ?? null;
        $sportId = $validated['sport_id'] ?? null;
        $city = $validated['city'] ?? null;
        $online = $validated['online'] ?? null;
        $minPrice = $validated['min_price'] ?? null;
        $maxPrice = $validated['max_price'] ?? null;

        $sort = $validated['sort'] ?? 'name';
        $dir = $validated['dir'] ?? 'asc';
        $perPage = $validated['per_page'] ?? 12;

        $query = DB::table('trainers')
            ->leftJoin('sports', 'sports.id', '=', 'trainers.sport_id')
            ->select([
                'trainers.id',
                'trainers.name',
                'trainers.description',
                'trainers.city',
                'trainers.price_per_hour',
                'trainers.online',
                'trainers.sport_id',
                'sports.name as sport_name',
            ]);

        // Advanced filter (vairāki kritēriji vienlaikus)
        if ($sportId) {
            $query->where('trainers.sport_id', $sportId);
        }

        if ($city) {
            $query->where('trainers.city', $city);
        }

        if ($online !== null) {
            $query->where('trainers.online', (int)$online);
        }

        if ($minPrice !== null) {
            $query->where('trainers.price_per_hour', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('trainers.price_per_hour', '<=', $maxPrice);
        }

        // Meklēšana (ar JOIN datiem)
        if ($q) {
            $qLike = '%' . mb_strtolower($q) . '%';
            $query->where(function ($w) use ($qLike) {
                $w->whereRaw('LOWER(trainers.name) LIKE ?', [$qLike])
                  ->orWhereRaw('LOWER(trainers.description) LIKE ?', [$qLike])
                  ->orWhereRaw('LOWER(COALESCE(trainers.city, "")) LIKE ?', [$qLike])
                  ->orWhereRaw('LOWER(COALESCE(sports.name, "")) LIKE ?', [$qLike]);
            });
        }

        // Kārtošana
        $sortMap = [
            'name' => 'trainers.name',
            'price' => 'trainers.price_per_hour',
            'city' => 'trainers.city',
            'sport' => 'sport_name',
        ];

        $query->orderBy($sortMap[$sort], $dir);

        // Pagination (ērti UI un prasībām)
        $result = $query->paginate($perPage)->withQueryString();

        return response()->json($result);
    }

    // GET /api/treneri/{trainer}
    public function show(Trainer $trainer)
    {
        $sportName = DB::table('sports')->where('id', $trainer->sport_id)->value('name');

        return response()->json([
            'id' => $trainer->id,
            'name' => $trainer->name,
            'description' => $trainer->description,
            'city' => $trainer->city,
            'price_per_hour' => $trainer->price_per_hour,
            'online' => (bool)$trainer->online,
            'sport_id' => $trainer->sport_id,
            'sport_name' => $sportName,
        ]);
    }
}