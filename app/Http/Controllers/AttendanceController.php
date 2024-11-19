<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Record attendance
    public function markAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:tblm_employee,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:' . Attendance::STATUS_PRESENT . ',' . Attendance::STATUS_ABSENT,
        ]);
    
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'attendance_date' => $request->attendance_date,
            'status' => $request->status,
        ]);
    
        return response()->json(['message' => 'Attendance recorded successfully', 'data' => $attendance], 201);
    }

    // Get attendance summary for a specific month
    public function getAttendance($attendanceId)
{
    $attendance = Attendance::findOrFail($attendanceId);

    $statusText = $attendance->status == Attendance::STATUS_PRESENT ? 'Present' : 'Absent';

    return response()->json([
        'attendance_id' => $attendance->id,
        'employee_id' => $attendance->employee_id,
        'attendance_date' => $attendance->attendance_date,
        'status' => $statusText,
    ]);
}
}
