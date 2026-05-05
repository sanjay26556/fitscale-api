<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $meals = Meal::where('user_id', $userId)->latest('eaten_at')->get();

        $macroSummary = [
            'calories' => $meals->sum('calories'),
            'protein' => $meals->sum('protein'),
            'carbs' => $meals->sum('carbs'),
            'fats' => $meals->sum('fats'),
        ];

        return response()->json([
            "meals" => $meals,
            "macro_summary" => $macroSummary
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'calories' => 'required|integer',
            'protein' => 'required|integer',
            'carbs' => 'required|integer',
            'fats' => 'required|integer',
            'eaten_at' => 'nullable|date'
        ]);

        $meal = Meal::create(array_merge($validated, [
            'user_id' => $userId,
            'eaten_at' => $validated['eaten_at'] ?? now()
        ]));

        return response()->json($meal, 201);
    }
}
