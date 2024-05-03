<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.user_id' => 'sometimes|integer',
            '*.present' => 'sometimes|boolean',
            '*.date' => 'sometimes|date',
            '*.date_box' => 'sometimes|date',
        ]);

        $attendances = collect($validatedData)->map(function ($data) {
            return Attendance::create($data);
        });

        return response()->json($attendances, 201);
    }
}