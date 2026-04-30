<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyStat;

class StatsController extends Controller
{
    public function daily(Request $request)
    {
        // 👇 Get user_id set by SupabaseAuth middleware
        $userId = $request->attributes->get('user_id');

        // If middleware didn't set it (no/invalid token)
        if (!$userId) {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }

        // Get latest record for this user
        $data = DailyStat::where('user_id', $userId)
                          ->latest()
                          ->first();

        if (!$data) {
            return response()->json([
                "message" => "No data found for this user"
            ], 404);
        }

        return response()->json([
            "user_id" => $data->user_id,
            "protein" => $data->protein,
            "calories" => $data->calories,
            "steps" => $data->steps,
            "score" => $data->score
        ]);
    }
}