<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Header;
use App\Models\Order;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            "mozo" => "required|string",
        ]);

        $header = Header::create($data);

        return response()->json([
            "Header" => $header
        ], 200);
    }

    public function show()
    {
        $headers = Header::with('orders')->get()->map(function ($header) {
            
            $order = Order::find($header->order_id);

            return [
                'id' => $header->id,
                'mozo' => $header->mozo,
                'total_price' => $order->total_price,
                'hosstes'=> $order->hosstes,
                'date_order' => $order->created_at
            ];
        });
        return response()->json(['attendances' => $headers]);
    }
}
