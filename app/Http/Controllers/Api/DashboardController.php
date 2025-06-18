<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboardCount()
    {
        try {
            $user = User::where('deleted_at', null)->count();
            // return response()->json($user);
            return response()->json([
                'message' => 'Product created successfully.',
                'data' => [
                    'users' => $user,
                ],
                'status' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
