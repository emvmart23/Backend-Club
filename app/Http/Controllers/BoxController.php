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
            "opening" => "required|date",//default
            "closing" => "sometimes|date",// null and default
            "user_opening" => "required|string",//user default
            "user_closing" => "sometimes|string",// user default
            "initial_balance" => "required|numeric|between:0,999999.99", 
            "final_balance" => "sometimes|numeric|between:0,999999.99",
            "state" => "required|boolean"
        ]);

        $box = Box::create($data);

        return response()->json([
            "box" => $box
        ]);
    }

    public function close($id){
        $box = Box::find($id);
        $box->state = false;
        $box->save();

        return response()->json([
            "box" => $box
        ]);
    }

    public function update(Request $request, $id) {
        
        $box = Box::find($id);

        if(!$box ){
            return response()->json([
                "message" => "Box not found"
            ],404);
        }

        $data = $request->validate([
            "initial_balance" => "sometimes|numeric|between:0,999999.99",
        ]);
    
        $box->update($data);

        return response()->json([
            "message" => "Box updated"
        ]);
    } 
}
