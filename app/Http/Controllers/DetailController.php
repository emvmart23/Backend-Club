<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Detail;
use App\Models\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DetailController extends Controller
{
    public function show()
    {
        $details = Detail::with('payments', 'user')->get()->map(function ($details) {
            return [
                "id" => $details->id,
                "issue_date" => $details->issue_date,
                "total_price" => $details->total_price,
                "hostess_id" => $details->hostess_id,
                "hostess_role" => $details->user->role_id,
                "box_date" => $details->box_date,
                "current_user" => $details->current_user,
                "user_name" => $details->user->user,
                "payments" => $details->payments->map(function ($payment) {
                    return [
                        "id" => $payment->id,
                        "detail_id" => $payment->detail_id,
                        "payment_id" => $payment->payment_id,
                        "mountain" => $payment->mountain,
                        "reference" => $payment->reference,
                        "created_at" => $payment->created_at,
                        "updated_at" => $payment->updated_at
                    ];
                }),
                "created_at" => $details->created_at,
                "updated_at" => $details->updated_at,
            ];
        });
        return response()->json(["details" => $details]);
    }

    public function create(Request $request, $id)
    {
        $validateData = $request->validate([
            "client_id" => "required|integer",
            "issue_date" => "required|date",
            "total_price" => "required|numeric|between:0,999999.99",
            "hostess_id" => "required|integer",
            "payment" => "required|array",
            "payment.*.payment_id" => "required|integer",
            "payment.*.reference" => "required|string",
            "payment.*.mountain" => "required|numeric|between:0,999999.99",
            "box_date" => "sometimes|string",
            "current_user" => "sometimes|integer"
        ]);

        if (!$validateData) {
            return response()->json([
                "message" => "Error while validating"
            ], 404);
        }

        $user = Auth::user();
        $latestBox = Box::latest()->first();

        if (!$latestBox) {
            return response()->json(["error" => "No hay cajas disponibles"], 404);
        }

        $detail = Detail::create([
            "hostess_id" => $validateData["hostess_id"],
            "client_id" => $validateData["client_id"],
            "issue_date" => $validateData["issue_date"],
            "total_price" => $validateData["total_price"],
            "box_date" => $latestBox->opening,
            "current_user" => $user->id
        ]);

        $detail->payments()->createMany(
            collect($validateData['payment'])->map(function ($paymentData) use ($detail) {
                return [
                    'detail_id' => $detail->id,
                    'payment_id' => $paymentData['payment_id'],
                    'mountain' => $paymentData['mountain'],
                    'reference' => $paymentData['reference'],
                ];
            })->toArray()
        );

        $header = Header::find($id);
        $detail = Detail::max('id');

        $header->state_doc = false;
        $header->note_sale = $detail;
        $header->save();

        return response()->json(['message' => 'Orden guardada correctamente']);
    }
}
