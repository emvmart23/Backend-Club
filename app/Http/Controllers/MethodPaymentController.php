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
        return response()->json(["data" => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $methodPayment = MethodPayment::find($id);

        if (!$methodPayment) {
            return response()->json([
                "message" => "Method payment not found"
            ], 404);
        }
    }
}
