<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Box;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            '*.user_id' => 'required|integer',
            '*.present' => 'required|boolean',
            '*.date' => 'required|date',
            '*.box_date' => 'sometimes|date',
            '*.box_id' => 'required|integer|exists:boxes,id',
        ]);

        $attendances = collect($validatedData)->map(function ($data) {
            $box = Box::findOrFail($data['box_id']);
            $data['box_date'] = $box->opening;
            return Attendance::create($data);
        });

        return response()->json($attendances, 201);
    }

    public function show()
    {
        $attendance = Attendance::with('box')->get();

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
