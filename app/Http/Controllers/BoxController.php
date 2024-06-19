<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BoxController extends Controller
{

    public function show()
    {
        $boxes = Box::all();

        return response()->json([
            "boxes" => $boxes
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            "opening" => "required|date",
            "user_opening" => "required|string",
            "user_closing" => "sometimes|string",
            "initial_balance" => "required|numeric|between:0,999999.99",
            "final_balance" => "sometimes|numeric|between:0,999999.99",
            "state" => "required|boolean"
        ]);

        $box = Box::create($data);

        return response()->json([
            "box" => $box
        ]);
    }

    public function update(Request $request, $id)
    {

        $box = Box::find($id);

        if (!$box) {
            return response()->json([
                "message" => "Box not found"
            ], 404);
        }

        $data = $request->validate([
            "initial_balance" => "sometimes|numeric|between:0,999999.99",
        ]);

        $box->update($data);

        return response()->json([
            "message" => "Box updated"
        ]);
    }

    public function close($id)
    {
        $user = Auth::user();
        $ldate = date('Y-m-d');
        $box = Box::find($id);

        $box->closing = $ldate;
        $box->state = false;
        $box->user_closing = $user->user;
        $box->save();

        return response()->json([
            "box" => $box
        ]);
    }
}
