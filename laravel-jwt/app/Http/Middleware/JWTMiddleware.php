<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['message' => 'user not found'], 400);
            }
        } catch (TokenExpiredException $e) {
            if ($request->is("api/refresh")) {
                try {
                    $newAccessToken = JWTAuth::parseToken()->refresh();
                    $user = JWTAuth::setToken($newAccessToken)->toUser();


                    return response()->json([
                        'status' => 'success',
                        'message' => 'token has been refresh',
                        'user' => $user,
                        'authorization' => [
                            'access_token' => $newAccessToken,
                            'type' => 'bearer'
                        ]
                    ], 200);
                } catch (TokenExpiredException $e) {
                    return response()->json([
                        'status' => 'expired',
                        'message' => $e->getMessage()
                    ], 401);
                } catch (JWTException $e) {
                    return response()->json(['message' => $e->getMessage()], 500);
                }
            }

            return response()->json([
                'status' => 'expired',
                'message' => $e->getMessage()
            ], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }


        return $next($request);
    }
}
