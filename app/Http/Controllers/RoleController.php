<?php

namespace App\Http\Controllers;
use App\Models\Role;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "role_name" => "required|string",
        ]);

        $roleData = $data;
        $role = Role::create($roleData);

        return response()->json([
            "role" => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $role = Role::all();

        return response()->json([
            "role" => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "Role not found"
            ],404);
        }

        $data = $request->validate([
            "role_name" => "sometimes|string",
        ]);

        $role->update($data);

        return response()->json([
            "message" => "Role updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json([
                "message" => "Customer not found"
            ],404);
        }

        $role->delete();

        return response()->json([
            "message" => "role deleted"
        ]);
    }
}
