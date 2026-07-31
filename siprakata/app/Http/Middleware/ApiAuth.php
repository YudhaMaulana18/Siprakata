<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.'], 401);
        }

        $apiToken = ApiToken::where('token', $token)->with('user')->first();

        if (!$apiToken) {
            return response()->json(['status' => 'error', 'message' => 'Token tidak valid atau sudah expired.'], 401);
        }

        auth()->setUser($apiToken->user);

        return $next($request);
    }
}
