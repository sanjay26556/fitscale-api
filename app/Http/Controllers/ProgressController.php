<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weight;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $weights = Weight::where('user_id', $userId)
            ->latest('recorded_at')
            ->get();

        // Calculate a simple streak based on consecutive days of logged weight (as an example)
        $streak = 0;
        // Basic streak logic could go here, for now we will return 0 or calculate if required.

        return response()->json([
            "weights" => $weights,
            "streak" => $streak,
            "stats" => [
                "start_weight" => $weights->last()->weight ?? 0,
                "current_weight" => $weights->first()->weight ?? 0,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $validated = $request->validate([
            'weight' => 'required|numeric',
            'recorded_at' => 'nullable|date'
        ]);

        $weight = Weight::create([
            'user_id' => $userId,
            'weight' => $validated['weight'],
            'recorded_at' => $validated['recorded_at'] ?? now()
        ]);

        return response()->json($weight, 201);
    }
}
