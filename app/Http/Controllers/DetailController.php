<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Console;
use Illuminate\Support\Facades\Log;

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
            "payment" => "required|array",
            "payment.*.payment_method" => "required|string",
            "payment.*.total_price" => "required|numeric|between:0,999999.99",
            "payment.*.reference" => "required|string",
            "payment.*.mountain" => "required|numeric|between:0,999999.99"
        ]);
        
        if(!$validateData){
            return response()->json([
                "message" => "Error while validating"
            ],404);
        }

        $detail = Detail::create([
            "client_id" => $validateData["client_id"],
            "issue_date" => $validateData["issue_date"]
        ]);

        $detail->payments()->createMany(
            collect($validateData['payment'])->map(function ($paymentData) use ($detail) {
                return [
                    'detail_id' => $detail->id,
                    'payment_method' => $paymentData['payment_method'],
                    'mountain' => $paymentData['mountain'],
                    'reference' => $paymentData['reference'],
                    'total_price' => $paymentData['total_price']
                ];
            })->toArray()
        );

        return response()->json(['message' => 'Orden guardada correctamente']);
    }
}
