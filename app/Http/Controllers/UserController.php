<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show()
    {
        $user = User::with('role')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'user' => $user->user,
                'name' => $user->name,
                'role_id' => $user->role_id,
                'role' => $user->role->role_name,
                'salary' => $user->salary,
                'profit_margin' => $user->profit_margin,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at
            ];
        });
        return response()->json(["user" => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $user->delete();

        return response()->json([
            "message" => "Usuario deleted"
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "user" => "sometimes|string",
            "name" => "sometimes|string",
            "salary" => "sometimes|numeric|between:0,999999.99",
            "profit_margin" => "sometimes|integer",
            "role_id" => "sometimes|integer",
            "is_active" => "sometimes|boolean",
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $user->update($data);

        return response()->json([
            "message" => "User updated"
        ]);
    }
}
