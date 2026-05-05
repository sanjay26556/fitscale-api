<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        \Illuminate\Support\Facades\Log::info('Token received: ' . ($token ? 'Yes' : 'No'));

        if (!$token) {
            \Illuminate\Support\Facades\Log::info('No token found in request');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $secret = env('SUPABASE_JWT_SECRET');
            if (!$secret) {
                \Illuminate\Support\Facades\Log::error('SUPABASE_JWT_SECRET is missing');
                return response()->json(['error' => 'Server configuration error'], 500);
            }
            
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, 'HS256'));
            \Illuminate\Support\Facades\Log::info('Decoded JWT payload: ', (array) $decoded);
            
            $userId = $decoded->sub ?? null;
            \Illuminate\Support\Facades\Log::info('Extracted user_id: ' . $userId);
            
            $request->attributes->set('user_id', $userId);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('JWT Decode Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}