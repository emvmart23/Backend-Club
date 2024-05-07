<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.user_id' => 'required|integer',
            '*.present' => 'required|boolean',
            '*.date' => 'required|date',
            '*.date_box' => 'required|date',
        ]);

        $attendances = collect($validatedData)->map(function ($data) {
            return Attendance::create($data);
        });

        return response()->json($attendances, 201);
    }

    public function show()
    {
        $attendance = Attendance::all();

        return response()->json([
            "attendances" => $attendance
        ]);
    }

    public function update(Request $request)
{
    $validatedData = $request->validate([
        '*.user_id' => 'required|integer',
        '*.present' => 'sometimes|boolean'
    ]);

    $updatedAttendances = collect($validatedData)->map(function ($data) {
        $attendance = Attendance::where('user_id', $data['user_id'])->latest('id')->first();
        if ($attendance) {
            $attendance->update($data);
            return $attendance;
        }
    })->filter();

    return response()->json($updatedAttendances, 200);
}
}
