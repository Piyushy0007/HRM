<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Fetch all roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRoles()
    {
        try {
            // Fetch all roles
            $roles = Role::orderBy('role_name', 'asc')->get();
            // dd($roles);
            // Return response
            return response()->json([
                'status' => true,
                'message' => 'Roles fetched successfully.',
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch roles.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}