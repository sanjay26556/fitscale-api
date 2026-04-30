<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;

Route::middleware(['auth.supabase'])
    ->get('/daily-stats', [StatsController::class, 'daily']);