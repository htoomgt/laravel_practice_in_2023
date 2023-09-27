<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\BearerToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Token;

class AuthController extends Controller
{
    use BearerToken;

    public function __construct()
    {
        $this->middleware('jwt.verify:api', ['except' => ['login', 'register', 'refresh']]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        $credientials = $request->only('email', 'password');

        $token =  Auth::attempt($credientials);
        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::user();
        $userWithRoles = User::with('roles')->find($user->id);
        return response()->json([
            'status' => 'success',
            'user' => $userWithRoles,
            'authorization' => [
                'type' => 'Bearer',
                'access_token' => $token,

            ]
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' =>  'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);



        if ($validator->fails()) {
            return response()->json([
                'status' => 'invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password)
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'access_token' => $token,
                'type' => 'Bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }

    public function refresh(Request $request)
    {
        $token = null;
        try {
            // $token = JWTAuth::getToken();
            // dd($token);

            // dd("be happy!");
            // if (!$token) {
            $requestHeaders = $request->headers->all();
            // dd($requestHeaders);
            // // return response()->json($requestHeaders);
            // dd($requestHeaders);
            $authorization = $requestHeaders['authorization'][0];
            $token = $this->getBearerToken($authorization);
            // dd($token);
            JWTAuth::setToken($token);
            $token = JWTAuth::getToken();
            // dd($token);
            // }

            if (!$token) {
                return response()->json([
                    'status' => 'bad request',
                    'message' => 'token not found'
                ], 400);
            }

            $newAccessToken = JWTAuth::refresh($token);
            // dd($newAccessToken);
            $user = JWTAuth::setToken($newAccessToken)->toUser();
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'JWT token error',
                'message' => $th->getMessage(),
                'requestHeaders' => $requestHeaders
            ], 400);
        }




        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $newAccessToken,
                'type' => 'bearer',
            ]
        ]);
    }
}
