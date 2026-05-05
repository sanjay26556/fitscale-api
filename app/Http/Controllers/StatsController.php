<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyStat;

class StatsController extends Controller
{
    public function daily(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }

        $data = DailyStat::where('user_id', $userId)
                          ->latest()
                          ->first();

        if (!$data) {
            return response()->json([
                "message" => "No data found for this user"
            ], 404);
        }

        return response()->json([
            "protein" => $data->protein,
            "calories" => $data->calories,
            "steps" => $data->steps,
            "score" => $data->score
        ]);
    }

    public function index(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $history = DailyStat::where('user_id', $userId)
                            ->latest()
                            ->get();

        $leaderboard = DailyStat::selectRaw('user_id, SUM(score) as total_score')
                                ->groupBy('user_id')
                                ->orderByDesc('total_score')
                                ->take(10)
                                ->get();

        return response()->json([
            "history" => $history,
            "leaderboard" => $leaderboard
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $validated = $request->validate([
            'protein' => 'required|integer',
            'calories' => 'required|integer',
            'steps' => 'required|integer',
            'score' => 'required|integer'
        ]);

        $stat = DailyStat::create(array_merge($validated, ['user_id' => $userId]));

        return response()->json([
            "protein" => $stat->protein,
            "calories" => $stat->calories,
            "steps" => $stat->steps,
            "score" => $stat->score
        ], 201);
    }
}