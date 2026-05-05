<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProgressController;

Route::middleware(['auth.supabase'])->group(function () {
    Route::get('/daily-stats', [StatsController::class, 'daily']);
    Route::post('/daily-stats', [StatsController::class, 'store']);
    Route::get('/stats', [StatsController::class, 'index']);
    
    Route::get('/food-chart', [FoodController::class, 'index']);
    Route::post('/meals', [FoodController::class, 'store']);
    
    Route::get('/progress', [ProgressController::class, 'index']);
    Route::post('/weight', [ProgressController::class, 'store']);
});