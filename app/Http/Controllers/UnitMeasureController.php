<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitMeasure;

class UnitMeasureController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            "abbreviation" => "required|unique:",
            "description" => "required",
        ]);

        $unitData = $data;
        $unit = UnitMeasure::create($unitData);

        return response()->json([
            "Unit Measure created successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $unit = UnitMeasure::find($id);

        if (!$unit) {
            return response()->json([
                "message" => "unit not found"
            ],404);
        }

        return response()->json([
            "unit" => $unit
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unit = UnitMeasure::find($id);

        if(!$unit){
            return response()->json([
                "message" => "unit not found"
            ],404);
        }

        $data = $request->validate([
            "name" => "sometimes|string",
            "description" => "sometimes|string",
        ]);

        $unit->update($data);

        return response()->json([
            "message" => "Unit updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unit = UnitMeasure::find($id);

        if(!$unit){
            return response()->json([
                "message" => "unit not found"
            ],404);
        }

        $unit->delete();

        return response()->json([
            "message" => "Unit deleted"
        ]);
    }
}
