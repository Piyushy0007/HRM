<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    // Create a leave request
    public function create(Request $request, $employeeId)
{
    // Validate incoming data (removed leave_date and email validation)
    $validated = $request->validate([
        'reason' => 'required|string|max:255',
        'leave_type' => 'required|string|max:50',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Optional: Check if employee exists
    $employee = Employee::find($employeeId);
    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    // Create the leave request with the current date for leave_date
    try {
        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'leave_date' => now(),  // Auto-set leave_date to current date
            'status' => LeaveRequest::STATUS_PENDING,
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to create leave request', 'error' => $e->getMessage()], 500);
    }

    // Return success response
    return response()->json(['message' => 'Leave request created successfully', 'data' => $leaveRequest], 201);
}


    // Update the status of a leave request
    public function updateStatus(Request $request, $leaveRequestId)
    {
        $request->validate([
            'status' => 'required|integer|in:1,2' // Only allow 1 (approve) or 2 (reject)
        ]);

        $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);
        $leaveRequest->update(['status' => $request->status]);

        $statusMessage = $request->status == LeaveRequest::STATUS_APPROVED ? 'approved' : 'rejected';

        return response()->json([
            'message' => "Leave request {$statusMessage} successfully",
            'data' => $leaveRequest
        ], 200);
    }

    // Show all leave requests for an employee
    public function show($employeeId)
    {
        $leaveRequests = LeaveRequest::where('employee_id', $employeeId)
                                     ->where('status', LeaveRequest::STATUS_PENDING)
                                     ->get();
    
        return response()->json($leaveRequests);
    }
   
    public function getLeaveRequestsByDateRange(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
    
        // Check if both start_date and end_date are provided
        if ($startDate && $endDate) {
            // If both dates are provided, filter by date range
            $leaveRequests = LeaveRequest::with('employee')
                                          ->whereBetween('leave_date', [$startDate, $endDate])
                                          ->get();
        } else {
            // If no date range is provided, fetch all leave requests
            $leaveRequests = LeaveRequest::with('employee')->get();
        }
    
        // If no data is found, return null
        if ($leaveRequests->isEmpty()) {
            return response()->json(null);
        }
    
        // Return the leave requests with employee details
        return response()->json($leaveRequests);
    }
    
}
