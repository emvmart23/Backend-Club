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
            "abbreviation" => "required|string",
            "description" => "required|string",
        ]);

        $unit = UnitMeasure::create($data);

        return response()->json([
            "Unit" => $unit
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $unit = UnitMeasure::all();

        return response()->json([
            "unit" => $unit
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "abbreviation" => "sometimes|string",
            "description" => "sometimes|string",
        ]);

        $unit = UnitMeasure::find($id);

        if (!$unit) {
            return response()->json([
                "message" => "unit not found"
            ], 404);
        }

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

        if (!$unit) {
            return response()->json([
                "message" => "unit not found"
            ], 404);
        }

        $unit->delete();

        return response()->json([
            "message" => "Unit deleted"
        ]);
    }
}
