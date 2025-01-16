<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

public function getAttendancesByDateRange(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'per_page' => 'nullable|integer|min:1',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $perPage = $request->input('per_page', 10); // Default to 10 per page if not provided

    // Fetch attendance records with related employee data
    $attendances = Attendance::with('employee')
                              ->whereBetween('attendance_date', [$startDate, $endDate])
                              ->paginate($perPage);

    if ($attendances->isEmpty()) {
        return response()->json(null);
    }

    return response()->json($attendances);
}

public function getAttendanceSummary(Request $request)
{
    // Get start_date and end_date from the request
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Validate the input dates
    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'Start date and end date are required.'], 400);
    }

    // Attendance queries: filter by attendance_date
    $presentCount = Attendance::where('status', Attendance::STATUS_PRESENT)
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->count();

    $absentCount = Attendance::where('status', Attendance::STATUS_ABSENT)
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->count();

    // Leave request query: filter by created_date
    $leaveCount = LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)
        ->whereBetween('leave_date', [$startDate, $endDate])
        ->count();

    // Distinct employee IDs
    $presentEmployees = Attendance::where('status', Attendance::STATUS_PRESENT)
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->pluck('employee_id');

    $absentEmployees = Attendance::where('status', Attendance::STATUS_ABSENT)
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->pluck('employee_id');

    $leaveEmployees = LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)
        ->whereBetween('leave_date', [$startDate, $endDate])
        ->pluck('employee_id');

    // Merge all employee IDs (union ensures distinct employees)
    $allEmployees = $presentEmployees->merge($absentEmployees)->merge($leaveEmployees)->unique();

    // Total distinct employees within the date range
    $totalEmployees = $allEmployees->count();

    // Prepare response with default values if no records exist
    $data = [
        'total_employees' => $totalEmployees > 0 ? $totalEmployees : null,
        'present' => $presentCount > 0 ? $presentCount : null,
        'absent' => $absentCount > 0 ? $absentCount : null,
        'on_leave' => $leaveCount > 0 ? $leaveCount : null,
    ];

    return response()->json($data);
}

public function markedAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:tblm_employee,id',
            'attendance_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        // Attendance record create karein
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'attendance_date' => $request->attendance_date,
            'status' => 1, // 1: Present
        ]);

        return response()->json([
            'message' => 'Attendance marked successfully.',
            'attendance' => $attendance,
        ], 201);
    }


    
}
