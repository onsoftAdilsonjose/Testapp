<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class HandleTokenExpiration
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TokenExpiredException $e) {
            // Try to refresh the token
            try {
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($newToken);
                $request->headers->set('Authorization', 'Bearer ' . $newToken);
                return $next($request);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Token expired'], 401);
            }
        }
    }
}
