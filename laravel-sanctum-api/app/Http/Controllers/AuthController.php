<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DateTime;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);


        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        

        $accessToken = $user
            ->createToken(
                'access_token', 
                [config('sanctum.token_ability.access_api')], 
                new DateTime(config('sanctum.expiration'). " minutes"))
            ->plainTextToken;
        
        $refreshToken = $user
            ->createToken(
                'refresh_token', 
                [config('sanctum.token_ability.issue_access_token')], 
                new DateTime(config('sanctum.rt_expiration'). " minutes"))
            ->plainTextToken;

        $response = [
            "status" => "success",
            "message" => "User created successfully.",
            "data" => [
                "user" => $user,
                "token_type" => 'bearer',
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "access_token_expiration" => config('sanctum.expiration')." minutes",
                "refresh_token_expiration" => config('sanctum.rt_expiration')." minutes"
            ]
        ];

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' =>  "Bad Credentials"
            ]);
        }

        // $token = $user->createToken('myapptoken')->plainTextToken;

        $accessToken = $user->createToken('access_token', [config('sanctum.token_ability.access_api')], new DateTime(config('sanctum.expiration'). " minutes"))->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', [config('sanctum.token_ability.issue_access_token')], new DateTime(config('sanctum.rt_expiration'). " minutes"))->plainTextToken;

        $response = [
            "status" => "success",
            "message" => "User created successfully.",
            "data" => [
                "user" => $user,
                "token_type" => 'bearer',
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "access_token_expiration" => config('sanctum.expiration')." minutes",
                "refresh_token_expiration" => config('sanctum.rt_expiration')." minutes"
            ]
        ];

        return response()->json($response, 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        $response = [
            "status" => "success",
            "message" => "User logged out successfully.",
            "data" => []
        ];

        return response()->json($response, 200);
    }

    public function refreshToken()
    {
        auth()->user()->tokens()->delete(); 
        $user = auth()->user();


        $accessToken = $user->createToken('access_token', [config('sanctum.token_ability.access_api')], new DateTime(config('sanctum.expiration'). " minutes"))->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', [config('sanctum.token_ability.issue_access_token')], new DateTime(config('sanctum.rt_expiration'). " minutes"))->plainTextToken;

        $response = [
            "status" => "success",
            "message" => "token refreshed successfully.",
            "data" => [
                "user" => $user,
                "token_type" => 'bearer',
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "access_token_expiration" => config('sanctum.expiration')." minutes",
                "refresh_token_expiration" => config('sanctum.rt_expiration')." minutes"
            ]
        ];

        return response()->json($response, 201);

    }
}
