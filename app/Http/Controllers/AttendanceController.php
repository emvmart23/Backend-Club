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
            '*.date' => 'sometimes|date',
            '*.box_date' => 'sometimes|date',
            '*.box_id' => 'sometimes|integer'
        ]);

        $latestBoxId = Box::max('id');

        $attendances = collect($validatedData)->map(function ($data) use ($latestBoxId) {
            if (!array_key_exists('box_id', $data) || $data['box_id'] === null) {
                $data['box_id'] = $latestBoxId;
            }

            $box = Box::findOrFail($data['box_id']);
            $data['box_date'] = $box->opening;
            return Attendance::create($data);
        });

        return response()->json($attendances, 201);
    }

    public function show()
    {
        $attendances = Attendance::with('user', 'box')->get()->map(function ($attendance) {
            return [
                'id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'present' => $attendance->present,
                'date' => $attendance->date,
                'box_date' => $attendance->box_date,
                'box_id' => $attendance->box_id,
                'created_at' => $attendance->created_at,
                'updated_at' => $attendance->updated_at,
                'user' => $attendance->user->name,
                'role_user' => $attendance->user->role_id,
                'box_state' => $attendance->box->state
            ];
        });
        return response()->json(['attendances' => $attendances]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            '*.user_id' => 'required|integer',
            '*.present' => 'sometimes|boolean'
        ]);

        $updatedAttendances = collect($validatedData)->map(function ($data) {
            $attendance = Attendance::where('user_id', $data['user_id'])->latest('box_date')->first();
            if ($attendance) {
                $attendance->update($data);
                return $attendance;
            }
        })->filter();

        return response()->json($updatedAttendances, 200);
    }
}
