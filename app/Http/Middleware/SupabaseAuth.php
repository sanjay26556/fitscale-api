<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Decode JWT payload (basic)
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $payload = json_decode(base64_decode($parts[1]), true);

        // Supabase user id is in "sub"
        $request->attributes->set('user_id', $payload['sub'] ?? null);

        return $next($request);
    }
}