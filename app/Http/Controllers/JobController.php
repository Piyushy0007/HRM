<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:10',
            'job_posting_location' => 'required|in:onsite,hybrid,remote',
            'people_to_hire' => 'required|integer|min:1',
            'recruitment_timeline' => 'nullable|string|max:50',
            'schedule' => 'nullable|string',
            'job_type' => 'required|string|max:255',
            'benefits' => 'nullable|string',
            'job_description' => 'required|string',
            'pay_minimum' => 'nullable|numeric',
            'pay_maximum' => 'nullable|numeric',
            'communication_preference_email' => 'required|boolean',
            'employee_id' => 'required|exists:tblm_employee,id', // Validate employee ID
            'job_status' => 'boolean',
            'application_deadline' => 'boolean', // New field validation
            'planned_start_date' => 'boolean', // New field validation
            'start_date' => 'nullable|date|required_if:planned_start_date,1',
            'cv_option' => 'required|integer|in:1,2', // Validate for 1 or 2

        ]);

        $job = Job::create($validated);

        return response()->json([
            'message' => 'Job created successfully!',
            'data' => $job,
        ], 201);
    }

    public function index(Request $request)
    {
        // Ensure employee_id is provided in the request
        $employeeId = $request->input('employee_id');
    
        if (!$employeeId) {
            return response()->json(['error' => 'Employee ID is required'], 400);
        }
    
        // Retrieve jobs by employee_id
        $jobs = Job::where('employee_id', $employeeId)->get();
    
        // Return jobs in JSON format
        return response()->json($jobs);
    }
}
