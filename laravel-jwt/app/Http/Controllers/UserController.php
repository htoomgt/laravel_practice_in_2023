<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify:api');
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $users
        ]);
    }

    public function getUserById(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => $user
                ]
            ]);
        }
    }
}
