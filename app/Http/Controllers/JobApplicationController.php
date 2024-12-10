<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // Max file size 2MB
            'job_number' => 'required|exists:jobs,job_number', // Job number must exist
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
        ]);

        return response()->json(['message' => 'Application submitted successfully!'], 201);
    }
}
