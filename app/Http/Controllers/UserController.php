<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
            "message" => "Usuario Eliminado"
        ]);
    }

    public function update(Request $request, $id){
        $data = $request->validate([
            "user" => ["sttring"],
            "name" => ["string"],
            "rol_id" => ["integer"],
        ]);
        
        $user = User::find($id);

        if(!$user){
            return response()->json([
                "message" => "User not found"
            ],404);
        }

        $user->update($data);
        return response()->json([
            "message" => "Usuario Actualizado"
        ]);
    }
}