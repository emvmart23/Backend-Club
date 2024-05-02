<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            "user_id" => "required|integer",
            "present" => "required|boolean",
            "late" => "required|boolean",
            "absent" => "required|boolean",
            "date" => "required|date"
        ]);


        $createdAttendances = [];
        foreach ($data as $attendanceData) {
            $attendance = Attendance::create($attendanceData);
            array_push($createdAttendances, $attendance);
        }

        return response()->json($createdAttendances, 201);
    }
}