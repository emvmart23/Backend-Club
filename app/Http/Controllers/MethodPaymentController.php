<?php

namespace App\Http\Controllers;

use App\Models\MethodPayment;
use Illuminate\Http\Request;

class MethodPaymentController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string"
        ]);

        $methodPayment = MethodPayment::create($data);
        return response()->json(["method" => $methodPayment], 200);
    }

    public function show()
    {
        $data = MethodPayment::all();
        return response()->json(["method" => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $methodPayment = MethodPayment::find($id);

        if (!$methodPayment) {
            return response()->json([
                "message" => "Method payment not found"
            ], 404);
        }

        $data = $request->validate([
            "name" => "sometimes|string"
        ]);

        $methodPayment->update($data);
        return response()->json(["method" => $methodPayment], 200);
    }

    public function destroy($id)
    {
        $methodPayment = MethodPayment::find($id);

        if (!$methodPayment) {
            return response()->json([
                "message" => "Method payment not found"
            ], 404);
        }

        $methodPayment->delete();

        return response()->json([
            "message" => "Method payment deleted"
        ], 200);
    }
}
