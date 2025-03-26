<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::orderBy("id", "desc")->paginate(10);
            return response()->json([
                'message' => 'Category store successfully.',
                'data'    => $users,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $users = User::findOrFail($id);
            return response()->json([
                'message' => 'User fetch successfully.',
                'data'    => $users,
                'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $users = User::where('id', '=', $id)->first();
            if (empty($user)) {
                return response()->json([
                    'message' => 'User Id not found.',
                    'data'    => $users,
                    'status' => 401,
                ], 200);
            } else {
                $users->delete();
                return response()->json([
                    'message' => 'User deleted successfully.',
                    'data'    => $users,
                    'status' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function userStatusUpdate(Request $request, $id)
    {
        try {
            $authUser = Auth::user();
            if ($authUser) {
                $users = User::where('id', '=', $id)->update([
                    'is_active' => $request->is_active,
                ]);
                return response()->json([
                    'message' => 'User verified successfully.',
                    'data'    => $users,
                    'status' => 200,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Unauthorized user.',
                    // 'data'    => $users,
                    'status' => 200,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
