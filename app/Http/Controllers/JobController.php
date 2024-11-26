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
        ]);

        $job = Job::create($validated);

        return response()->json([
            'message' => 'Job created successfully!',
            'data' => $job,
        ], 201);
    }

    public function index()
{
    // Retrieve all jobs
    $jobs = Job::all();

    // Return jobs in JSON format
    return response()->json($jobs);
}
}
