<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function destroy($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
                "message" => "User not found"
            ],404);
        }

        $user->delete();

        return response()->json([
            "message" => "Usuario deleted"
        ]);
    }

    public function update(Request $request, $id){
        $user = User::find($id);
        
        if(!$user){
            return response()->json([
                "message" => "User not found"
            ],404);
        }

        $data = $request->validate([
            "user" => "sometimes|string",
            "name" => "sometimes|string",
            "salary" => "sometimes|numeric|between:0,999999.99",
            "profit_margin" => "sometimes|integer",
            "role_id" => "sometimes|integer",
            "is_active" => "sometimes|boolean",
        ]);
        
        $user->update($data);

        return response()->json([
            "message" => "User updated"
        ]);
    }
}