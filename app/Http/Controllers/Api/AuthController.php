<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRolePivot;
use App\Models\UserDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8'
            ]);

            DB::beginTransaction();
            // dd($request->all());
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'is_active' => true,
            ]);

            $rolePivot = UserRolePivot::insert([
                'user_id' => $user->id,
                'role_id' => $request['role_id'] ?? 1
            ]);

            $userDetails = UserDetails::create([
                'user_id' => $user->id
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();
            return response()->json([
                'user' => $user,
                'access_token' => $token,
            ], 200);

            // return response()->json(['message' => 'User registered successfully!']);
        } catch (Exception $e) {
            return response()->json([
                // 'message' => 'Error occurred while registering admin user',
                'error' => $e->getMessage()
            ], 403);
        }
    }

    public function login(Request $request)
    {
        try {
            // dd(1);
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::with('roles')->where('email', strtolower($request->email))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Create a token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Format role information
            $roles = $user->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name
                ];
            });

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'roles' => $roles,
                'auth_token' => $token,
            ]); // Secure, HttpOnly, SameSite=Lax
        } catch (Exception $e) {
            // Handle exception (e.g., if the token is invalid or expired)
            return response()->json(['error' => 'Unauthorized'], 500);
        }
    }

    public function whoAmI(Request $request)
    {
        try {
            // dd(1);
            $user = Auth::user()->id;

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } else {
                $userInfo = User::select('id', 'name', 'email', 'created_at', 'updated_at', 'is_active')
                    ->where('id', $user)
                    ->first();

                return response()->json($userInfo);
            }


        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // $user = Auth::user();
        // try {
        //     // Automatically authenticate using Sanctum
        //     $user = $request->user(); // Or auth()->user() - this will get the authenticated user

        //     if (!$user) {
        //         return response()->json(['error' => 'Unauthorized'], 401);
        //     }
        //     // Format role information
        //     $roles = $user->roles->map(function ($role) {
        //         return [
        //             'id' => $role->id,
        //             'name' => $role->name
        //         ];
        //     });
        //     // Return response with user and roles
        //     return response()->json([
        //         'user' => [
        //             'id' => $user->id,
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'email_verified_at' => $user->email_verified_at,
        //         ],
        //         'roles' => $roles
        //     ]);
        // } catch (Exception $e) {
        //     // Handle exception (e.g., if the token is invalid or expired)
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
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
