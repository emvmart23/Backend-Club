<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.hostess'=> 'required|string',
            '*.name' => 'required|string',
            '*.price' => 'required|numeric|between:0,999999.99',
            '*.count' => 'required|integer',
            '*.total_price' => "required|numeric|between:0,999999.99",
            '*.header_id' => 'sometimes|integer'
        ]);

        $latestHeaderId = Header::max('id');

        $orders = collect($validatedData)->map(function ($data) use ($latestHeaderId) {
            if (!array_key_exists('header_id', $data) || $data['header_id'] === null) {
                $data['header_id'] = $latestHeaderId;
            }
            
            return Order::create($data);
        });

        return response()->json($orders, 200);
    }
}