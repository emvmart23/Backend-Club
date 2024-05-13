<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register( Request $request ){
        $data = $request->validate([
            "user" => "required|unique:users",
            "name" => "required|string",
            "password" => "required|confirmed",
            "salary" => "required|numeric|between:0,999999.99",
            "profit_margin" => "required|integer",
            "role_id" => "required|integer",
            "is_active" => "boolean"
        ]);

        $userData = $data;
        $user = User::create($userData);
        $token = $user->createToken("my-token")->plainTextToken;

        return response()->json([
            "token" => $token,
            "Type" => "Bearer"
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "user" => "required",
            "password" => "required",
        ]);

        $user = User::where("user", $fields["user"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "Credenciales Incorrectas"
            ], 401);
        }

        $request->session()->regenerate();
        auth()->login($user);

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json([
            "data" => $user,
            "token" => $token,
            "Type" => "Bearer",
            "role_id" => $user->role_id,
        ]);
    }
}
