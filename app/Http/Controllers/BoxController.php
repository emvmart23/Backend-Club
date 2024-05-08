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
            //add
            // user por default
            "closing" => "required|date",
            "initial_balance" => "required|numeric|between:0,999999.99",
            "final_balance" => "required|numeric|between:0,999999.99",
            "state" => "required|boolean",
        ]);

        $box = Box::create($data);

        return response()->json([
            "box" => $box
        ]);
    }

    public function close(Request $request,$id){
        $box = Box::find($id);
        $box->state = false;
        $box->save();
    }
}
