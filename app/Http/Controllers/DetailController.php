<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function show()
    {
        $details = Detail::all();

        return response()->json([
            "details" => $details
        ]);
    }

    public function create(Request $request)
    {
        $validateData = $request->validate([
            "client_id" => "required|integer",
            "issue_date" => "required|date",
            "*.payment" => "required|array",
            "*.payment.detail_id" => "required|integer",
            "*.payment.method_payment" => "required|string",
            "*.payment.total_price" => "required|numeric|between:0,999999.99",
            "*.payment.reference" => "required|string",
            "*.payment.mountain" => "required|numeric|between:0,999999.99"
        ]);

        $detail = Detail::create([
            "client_id" => $validateData["client_id"],
            "fecha" => $validateData["fecha"]
        ]);

        $detail->payments()->createMany(
            collect($validateData['payment'])->map(function ($paymentData) {
                return [
                    'detail_id' => $paymentData['detail_id'],
                    'method_payment' => $paymentData['method_payment'],
                    'mountain' => $paymentData['mountain'],
                    'reference' => $paymentData['reference'],
                    'total_price' => $paymentData['total_price'],
                ];
            })->toArray()
        );
    
        return response()->json(['message' => 'Orden guardada correctamente']);
    }
}
