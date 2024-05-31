<?php

namespace App\Http\Controllers;

use App\Models\Box;
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

    public function show()
    {
        $headers = Header::with('orders')->get()->map(function ($header) {
            
            return [
                'id' => $header->id,
                'mozo' => $header->mozo
            ];
        });
        return response()->json(['attendances' => $headers]);
    }
}
