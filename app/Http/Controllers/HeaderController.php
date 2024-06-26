<?php

namespace App\Http\Controllers;

use App\Models\Header;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            "mozo_id" => "required|integer"
        ]);

        $header = Header::create($data);

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
                'note_id' => $header->note_id,
                'created_at' => $header->created_at,
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
        return response()->json($headers);
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
        $header->note_id = null;
        $header->state_doc = true;
        $header->save();

        return response()->json([
            "header without note" => $header
        ], 200);
    }
}
