<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function addExperience(Request $request, $employeeId)
    {
        $request->validate([
            'job_title' => 'required|string',
            'company_name' => 'required|string',
            'company_industry' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'responsibilities' => 'required|string',
        ]);

        $employee = Employee::findOrFail($employeeId);

        $experience = $employee->experiences()->create([
            'job_title' => $request->job_title,
            'company_name' => $request->company_name,
            'company_industry' => $request->company_industry,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'responsibilities' => $request->responsibilities,
        ]);

        return response()->json([
            'message' => 'Experience added successfully',
            'experience' => $experience,
        ], 201);
    }
}