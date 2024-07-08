<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HeaderController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            "mozo_id" => "required|integer",
            "current_user" => "sometimes|integer",
            "box_date" => "sometimes|string"
        ]);

        $user = Auth::user();
        $latestBox = Box::latest()->first();

        if (!$latestBox) {
            return response()->json(["error" => "No hay cajas disponibles"], 404);
        }
        
        $header = Header::create([
            "mozo_id" => $data["mozo_id"],
            "current_user" => $user->id,
            "box_date" => $latestBox->opening
        ]);

        return response()->json([
            "Header" => $header
        ], 200);
    }

    public function show()
    {
        $headers = Header::with('user', 'orders.user')->get()->map(function ($header) {
            return [
                'id' => $header->id,
                'mozo_id' => $header->mozo_id,
                'mozo' => $header->user->name,
                'state' => $header->state,
                'state_doc' => $header->state_doc,
                'note_sale' => $header->note_sale,
                'created_at' => $header->created_at,
                'current_user' => $header->current_user,
                'box_date' => $header->box_date,
                'orders' => $header->orders->map(function ($order) {
                    return [
                        'name' => $order->name,
                        'count' => $order->count,
                        'price' => $order->price,
                        'total_price' => $order->total_price,
                        'hostess' => $order->user->name,
                        'hostess_id' => $order->hostess_id,
                        'date_order' => $order->created_at
                    ];
                }),
            ];
        });
        return response()->json(["header" => $headers]);
    }

    public function attended($id)
    {
        $header = Header::find($id);
        $header->state = false;
        $header->save();

        return response()->json([
            "header" => $header
        ], 200);
    }

    public function anulated($id)
    {
        $header = Header::find($id);

        $header->note_sale = null;
        $header->state_doc = true;
        $header->save();

        return response()->json([
            "header without note" => $header
        ], 200);
    }

    public function destroy($id) {
        $header = Header::find($id);

        if (!$header) {
            return response()->json([
                "message" => "Header not found"
            ], 404);
        }
        
        $header->state_doc = null;
        $header->save();

        return response()->json([
            "message" => "Header deleted"
        ]);
    }
}
