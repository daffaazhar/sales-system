<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();

            if (!auth('user')->check()) {
                return response()->json([
                    'data' => null,
                    'status' => 'error',
                    'message' => 'Unauthorized (user only)'
                ], 401);
            }
        } catch (TokenInvalidException $e) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Token is invalid'
            ], 401);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Token has expired'
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'data' => null,
                'status' => 'error',
                'message' => 'Authorization token not found'
            ], 401);
        }
        return $next($request);
    }
}
