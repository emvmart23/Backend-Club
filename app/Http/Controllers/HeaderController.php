<?php

namespace App\Http\Controllers;

use App\Models\Header;
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

    // public function show()
    // {
    //     $headers = Header::with('order')->get()->map(function ($header) {
    //         return [
    //             'id' => $header->id,
    //             'order_id' => $header->order_id,
    //             'total_price' => $header->order->totals_price,
    //             'hostess' => $header->order->hostess,
    //             'mozo' => $header->order->mozo
    //         ];
    //     });
    //     return response()->json(['attendances' => $headers]);
    // }
}
