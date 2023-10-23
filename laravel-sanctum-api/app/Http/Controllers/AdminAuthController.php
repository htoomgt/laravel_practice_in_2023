<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use DateTime;

enum TokenAbility: string
{
    case ISSUE_ACCESS_TOKEN = 'issue-access-token';
    case ACCESS_API = 'access-api';
}

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:admins,email',
            'password' => 'required|string|confirmed'
        ]);

        $admin = Admin::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $adminAccessToken = $admin->createToken('admin-access-token', [TokenAbility::ACCESS_API->value], new DateTime(config('sanctum.expiration'). " minutes"))->plainTextToken;
        $adminRefreshToken = $admin->createToken('admin-refresh-token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], new DateTime(config('sanctum.rt_expiration'). " minutes"))->plainTextToken;

        $response = [
            "status" => "success",
            "message" => "Admin created successfully.",
            "data" => [
                "admin" => $admin,
                'token_type' => 'Bearer',
                "access_token" => $adminAccessToken,
                "refresh_token" => $adminRefreshToken,
                'access_token_expires_at' => config('sanctum.expiration')." minutes",
                'refresh_token_expires_at' => config('sanctum.rt_expiration')." minutes"

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

        $admin = Admin::where('email', $fields['email'])->first();

        if(!$admin || !Hash::check($fields['password'], $admin->password)){
            return response([
                'status' => 'unauthroized',
                'message' => 'Bad Credentials'
            ]);
        }

        $adminAccessToken = $admin->createToken('admin-access-token', [TokenAbility::ACCESS_API->value], new DateTime(config('sanctum.expiration'). " minutes"))->plainTextToken;
        $adminRefreshToken = $admin->createToken('admin-refresh-token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], new DateTime(config('sanctum.rt_expiration'). " minutes"))->plainTextToken;

        $response = [
            "status" => "success",
            "message" => "Admin login successfully.",
            "data" => [
                "admin" => $admin,
                'token_type' => 'Bearer',
                "access_token" => $adminAccessToken,
                "refresh_token" => $adminRefreshToken,
                'access_token_expires_at' => config('sanctum.expiration')." minutes",
                'refresh_token_expires_at' => config('sanctum.rt_expiration')." minutes"

            ]
        ];

        return response()->json($response, 201);


    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response = [
            "status" => "success",
            "message" => "Admin logged out successfully.",
            "data" => []
        ];

        return response()->json($response, 200);
    }

    
}
