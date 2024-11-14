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
            'status' => 'required|in:present,absent',
        ]);

        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'attendance_date' => $request->attendance_date,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Attendance recorded', 'data' => $attendance], 201);
    }

    // Get attendance summary for a specific month
    public function getMonthlyAttendance($employeeId, $month, $year)
    {
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get();

        $presentDays = $attendance->where('status', 'present')->count();
        $absentDays = $attendance->where('status', 'absent')->count();

        return response()->json([
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
        ]);
    }
}
