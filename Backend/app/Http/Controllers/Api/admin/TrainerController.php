<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        return Trainer::with('sportRel:id,name')
            ->orderBy('name')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['required','string','max:2000'],
            'sport_id' => ['nullable','exists:sports,id'],
            'city' => ['nullable','string','max:255'],
            'price_per_hour' => ['nullable','numeric','min:0'],
            'online' => ['required','boolean'],
        ]);

        // uzturam arī veco tekstu sport laukā (ja vajag)
        if (!empty($data['sport_id'])) {
            $sportName = \App\Models\Sport::find($data['sport_id'])?->name;
            $data['sport'] = $sportName;
        }

        return response()->json(Trainer::create($data), 201);
    }

    public function update(Request $request, Trainer $trainer)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['required','string','max:2000'],
            'sport_id' => ['nullable','exists:sports,id'],
            'city' => ['nullable','string','max:255'],
            'price_per_hour' => ['nullable','numeric','min:0'],
            'online' => ['required','boolean'],
        ]);

        if (!empty($data['sport_id'])) {
            $sportName = \App\Models\Sport::find($data['sport_id'])?->name;
            $data['sport'] = $sportName;
        } else {
            $data['sport'] = null;
        }

        $trainer->update($data);
        return response()->json($trainer);
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return response()->json(['ok' => true]);
    }
}