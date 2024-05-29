<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.name' => 'required|string',
            '*.price' => 'required|numeric|between:0,999999.99',
            '*.count' => 'required|integer',
            '*.total_price' => "required|numeric|between:0,999999.99",
        ]);

        $orders = collect($validatedData)->map(function ($data) {
            return Order::create($data);
        });

        return response()->json($orders, 200);
    }
}