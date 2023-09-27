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
        $token = $request->bearerToken();

        if (!$token) {
            $requestHeaders = request()->headers->all();
            $authorization = $requestHeaders['authorization'][0];
            $token = $this->getBearerToken($authorization);
        }



        if (!$token) {
            return response()->json([
                'status' => 'bad request',
                'message' => 'token not found'
            ], 400);
        }

        try {
            // $user = JWTAuth::parseToken()->authenticate();

            $user = JWTAuth::setToken($token)->toUser();
            $newAccessToken = Auth::login($user);

            if (!$user) {
                return response()->json(['message' => 'user not found'], 400);
            }
        } catch (TokenExpiredException $e) {


            return response()->json([
                'status' => 'token expired',
                'message' => $e->getMessage()
            ], 401);
        } catch (JWTException $e) {

            return response()->json(['status' => 'token error', 'message' => $e->getMessage()], 400);
        }


        return $next($request);
    }

    public function getBearerToken($authorization)
    {


        $position = strrpos($authorization, 'Bearer ');


        if ($position !== false) {
            $header = substr($authorization, $position + 7);

            return str_contains($header, ',') ? strstr($header, ',', true) : $header;
        }
    }
}
