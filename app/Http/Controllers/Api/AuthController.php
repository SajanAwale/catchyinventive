<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    /**
     * Register admin user
     * @param Request $request
     * @
     */
    public function registerAdminUser(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);

            $user = User::create($fields);

            $token = $user->createToken($request->name);
            return [
                'user' => $user,
                'token' => $token
            ];

            // return response()->json(['message' => 'User registered successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while registering admin user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'The provided password is incorrect.'
            ];
        }

        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out successfully'
        ];
    }
}
