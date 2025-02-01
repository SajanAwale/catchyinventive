<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRolePivot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // dd($request['role_id']);
        // $validated = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed'
        // ]);
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error occurred while registering admin user',
                'error' => $validated->errors()
            ], 403);
        }
        try {

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            $rolePivot = UserRolePivot::insert([
                'user_id' => $user->id,
                'role_id' => $request['role_id']
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token
            ], 200);

            // return response()->json(['message' => 'User registered successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while registering admin user',
                'error' => $e->getMessage()
            ], 403);
        }
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required:string|min:8',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error occurred while logging in',
                'error' => $validated->errors()
            ], 403);
        }

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password
        ];

        try {
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid credentials'
                ], 401);
            }

            $user = User::with('roles')->where('email', $request->email)->first();
            // dd($user);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return [
                    'message' => 'The provided password is incorrect.'
                ];
            }

            $token = $user->createToken($user->name);
            // dd($token);
            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function logout(Request $request)
    {
        // dd(34);
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();

        return [
            'message' => 'Logged out successfully'
        ];
    }
}
