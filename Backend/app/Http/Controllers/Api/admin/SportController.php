<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index()
    {
        return Sport::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:sports,name'],
        ]);

        return response()->json(Sport::create($data), 201);
    }

    public function update(Request $request, Sport $sport)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:sports,name,'.$sport->id],
        ]);

        $sport->update($data);
        return response()->json($sport);
    }

    public function destroy(Sport $sport)
    {
        $sport->delete();
        return response()->json(['ok' => true]);
    }
}