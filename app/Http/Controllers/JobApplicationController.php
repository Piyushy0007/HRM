<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function applyForJob(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Max file size 2MB
        'job_number' => 'required|exists:jobs,job_number', // Job number must exist
        'location' => 'required|string|max:255', // New validation rule for location
    ]);

    // Store the resume
    $resumePath = $request->file('resume')->store('resumes', 'public');

    // Create the application
    Application::create([
        'name' => $validated['name'],
        'phone_number' => $validated['phone_number'],
        'email' => $validated['email'],
        'resume' => $resumePath,
        'job_number' => $validated['job_number'],
        'location' => $validated['location'], // Store location
        'applied_date' => now(), // Automatically set applied date to the current date
    ]);

    return response()->json(['message' => 'Application submitted successfully!'], 201);
}

public function getApplicationWithJobDetails($id)
{
    // Fetch the application by id and include job details
    $application = Application::with('job')->find($id);

    if (!$application) {
        return response()->json(['message' => 'Application not found'], 404);
    }

    // Fetch the job details using the job_number
    $jobDetails = Job::where('job_number', $application->job_number)->first();

    return response()->json([
        'application' => $application,
        'job_details' => $jobDetails,
    ], 200);
}


}
