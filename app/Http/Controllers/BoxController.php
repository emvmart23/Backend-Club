<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Box;

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
            "closing" => "nullable|date",
            "initial_balance" => "required|numeric",
            "final_balance" => "required|numeric",
            "state" => "required|boolean",
        ]);

        $box = Box::create($data);

        return response()->json([
            "box" => $box
        ]);
    }
}
