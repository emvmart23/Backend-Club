<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Header;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.hostess_id' => 'required|integer',
            '*.name' => 'required|string',
            '*.price' => 'required|numeric|between:0,999999.99',
            '*.count' => 'required|integer',
            '*.total_price' => 'required|numeric|between:0,999999.99',
            '*.header_id' => 'sometimes|integer',
            "*.box_date" => "sometimes|string",
            "*.current_user" => "sometimes|integer"
        ]);

        $latestHeaderId = Header::max('id');
        $user = Auth::user();
        $latestBox = Box::latest()->first();

        if (!$latestBox) {
            return response()->json(["error" => "Box not found"], 404);
        }

        $orders = collect($validatedData)->map(function ($data) use ($latestHeaderId, $user, $latestBox) {
            if (!array_key_exists('header_id', $data) || $data['header_id'] === null) {
                $data['header_id'] = $latestHeaderId;
                $data['box_date'] = $latestBox->opening;
                $data['current_user'] = $user->id;
            }
            return Order::create($data);
        });

        return response()->json($orders, 200);
    }

    public function show()
    {
        $orders = Order::all();

        if (!empty($orders)) {
            return response()->json([
                "orders" => $orders
            ]);
        } else {
            return response()->json(["error" => "No orders found"], 404);
        }
    }
}
