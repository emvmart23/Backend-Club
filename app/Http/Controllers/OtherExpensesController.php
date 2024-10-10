<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\OtherExpense;
use App\Models\UnitMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtherExpensesController extends Controller
{

    public function create(Request $request)
    {
        $data = $request->validate([
            "count" => "required|integer",
            "name" => "required|string",
            "unit_id" => "required|integer",
            "total" => "required|integer",
            "box_date" => "sometimes|string",
            "current_user" => "sometimes|integer",
        ]);

        $user = Auth::user();
        $latestBox = Box::latest()->first();

        $otherExpenses = OtherExpense::create([
            "count" => $data["count"],
            "name" => $data["name"],
            "unit_id" => $data["unit_id"],
            "total" => $data["total"],
            "current_user" => $user->id,
            "box_date" => $latestBox->opening
        ]);

        return response()->json([
            "other" => $otherExpenses
        ]);
    }

    public function show()
    {
        $others = OtherExpense::with('unit')->get()->map(function ($other) {
            $unitName = UnitMeasure::where('unit_id', $other->unit_id)->first();
            return [
                'id' => $other->id,
                'count' => $other->count,
                'name' => $other->name,
                'total' => $other->total,
                'unit_id' => $other->unit_id,
                'unit_name' => $unitName->abbreviation,
                "box_date" => $other->box_date
            ];
        });

        return response()->json(["others" => $others]);
    }

    public function update(Request $request, $id)
    {
        $other = OtherExpense::find($id);

        if (!$other) {
            return response()->json([
                "message" => "Other not found"
            ], 404);
        }

        $data = $request->validate([
            "count" => "sometimes|integer",
            "name" => "sometimes|string",
            "unit_id" => "sometimes|integer",
            "total" => "sometimes|integer",
        ]);

        $other->update($data);

        return response()->json([
            "message" => "Other expenses updated"
        ]);
    }

    public function destroy($id)
    {
        $other = OtherExpense::find($id);

        if (!$other) {
            return response()->json([
                "message" => "Other not found"
            ], 404);
        }

        $other->delete();

        return response()->json([
            "message" => "Product deleted"
        ]);
    }
}
