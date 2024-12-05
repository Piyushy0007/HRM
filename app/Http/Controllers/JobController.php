<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request)
    {
        // Validation rules
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
            'employee_id' => 'required|exists:tblm_employee,id',
            'job_status' => 'boolean',
            'application_deadline' => 'boolean',
            'planned_start_date' => 'boolean',
            'start_date' => 'nullable|date|required_if:planned_start_date,1',
            'cv_option' => 'required|integer|in:1,2',
            'pay_rate_type' => 'required|in:monthly,yearly',
            'email_1' => 'nullable|email|max:255',
            'email_2' => 'nullable|email|max:255',
        ]);
    
        // Create Job (job_number will be auto-generated in the model)
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


    public function getJobByNumber($job_number)
{
    // Fetch the job using job_number
    $job = Job::where('job_number', $job_number)->first();

    // Check if the job exists
    if (!$job) {
        return response()->json([
            'message' => 'Job not found!'
        ], 404);
    }

    // Return the job data
    return response()->json([
        'message' => 'Job retrieved successfully!',
        'data' => $job
    ], 200);
}


}
