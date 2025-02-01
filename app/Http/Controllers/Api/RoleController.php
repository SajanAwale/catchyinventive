<?php

namespace App\Http\Controllers\Api;

// use App\Http\Requests\StoreRoleRequest;
// use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Role::all();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while fetching roles', 'error' => $e->getMessage()], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $role = Role::create($fields);
            return response()->json(['role' => $role], 201); // Return with a 201 Created status

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while validating the request', 'error' => $e->getMessage()], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            return $role;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while fetching the role', 'error' => $e->getMessage()], 403);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $role->update($fields);
            return response()->json($role, 200); // Return with a 200 OK status

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while updating the role', 'error' => $e->getMessage()], 403);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json(['message' => 'The Role has been Deleted.'], 200); // Return with a 200 OK status

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while deleting the role', 'error' => $e->getMessage()], 403);
        }
    }
}
