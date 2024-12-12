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

    public function getMonthlyAttendance(Request $request, $employeeId)
{
    // Retrieve 'month' and 'year' from query parameters
    $month = $request->query('month');
    $year = $request->query('year');

    // Validate 'month' and 'year' values
    if (!is_numeric($month) || $month < 1 || $month > 12) {
        return response()->json(['error' => 'Invalid month provided. Must be between 1 and 12.'], 400);
    }

    if (!is_numeric($year) || $year < 2000) {
        return response()->json(['error' => 'Invalid year provided. Must be greater than or equal to 2000.'], 400);
    }

    // Fetch attendance for the given employee, month, and year
    $attendance = Attendance::where('employee_id', $employeeId)
        ->whereMonth('attendance_date', $month)
        ->whereYear('attendance_date', $year)
        ->get(['attendance_date', 'status']);

    // Prepare data for response
    $formattedAttendance = $attendance->map(function ($entry) {
        return [
            'date' => $entry->attendance_date,
            'status' => $entry->status === Attendance::STATUS_PRESENT ? 'Present' : 'Absent',
        ];
    });

    // Count present and absent days
    $presentDays = $attendance->where('status', Attendance::STATUS_PRESENT)->count();
    $absentDays = $attendance->where('status', Attendance::STATUS_ABSENT)->count();

    return response()->json([
        'employee_id' => $employeeId,
        'month' => $month,
        'year' => $year,
        'present_days' => $presentDays,
        'absent_days' => $absentDays,
        'attendance' => $formattedAttendance,
    ]);
}

    
}
