<!-- <?php

namespace App\Http\Controllers;

use App\Employee;
use App\Models\JoiningLetter; // Make sure this matches the correct namespace

use Illuminate\Http\Request;

class EmployeeDocumentController extends Controller
{
    public function postJoiningLetter(Request $request, $employee_id)
{
    // Validate the incoming request
    $request->validate([
        'letter_content' => 'required|string',
        'joining_date' => 'required|date',
        'pdf_file' => 'nullable|file|mimes:pdf|max:2048', // Validate the PDF file
    ]);

    // Find the employee or fail
    $employee = Employee::findOrFail($employee_id);

    // Create the joining letter for the employee
    $joiningLetter = new JoiningLetter([
        'employee_id' => $employee_id,
        'letter_content' => $request->input('letter_content'),
        'joining_date' => $request->input('joining_date'),
    ]);

    // Handle the PDF file upload if provided
    if ($request->hasFile('pdf_file')) {
        // Store the file and get the path
        $path = $request->file('pdf_file')->store('joining_letters', 'public'); // Store in public storage
        $joiningLetter->pdf_file = $path; // Set the path to pdf_file
    }

    // Save the joining letter
    $joiningLetter->save();

    return response()->json([
        'status' => true,
        'message' => 'Joining letter created successfully!',
        'data' => $joiningLetter,
    ]);
}
}


    // public function postPerformanceDocument(Request $request, $employeeId)
    // {
    //     $employee = Employee::findOrFail($employeeId);

    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'document_name' => 'required|string',
    //         'document_content' => 'required|string',
    //     ]);

    //     // Create new performance document
    //     $performanceDocument = $employee->performanceDocuments()->create([
    //         'document_name' => $validatedData['document_name'],
    //         'content' => $validatedData['document_content'],
    //     ]);

    //     return response()->json(['message' => 'Performance document saved successfully', 'performance_document' => $performanceDocument], 201);
    // }

    // // public function postSalarySlip(Request $request, $employeeId)
    // // {
    // //     $employee = Employee::findOrFail($employeeId);

    // //     // Validate the request data
    // //     $validatedData = $request->validate([
    // //         'month_year' => 'required|string',
    // //         'salary_details' => 'required|string',
    // //     ]);

    // //     // Create new salary slip
    // //     $salarySlip = $employee->salarySlips()->create([
    // //         'month_year' => $validatedData['month_year'],
    // //         'salary_details' => $validatedData['salary_details'],
    // //     ]);

    // //     return response()->json(['message' => 'Salary slip saved successfully', 'salary_slip' => $salarySlip], 201);
    // // }
    // // Retrieve Joining Letter for an Employee
    // public function getJoiningLetter($employeeId)
    // {
    //     $employee = Employee::findOrFail($employeeId);
    //     $joiningLetter = $employee->joiningLetter;

    //     if ($joiningLetter) {
    //         return response()->json(['joining_letter' => $joiningLetter], 200);
    //     }

    //     return response()->json(['message' => 'Joining letter not found'], 404);
    // }

    // // Retrieve Performance Documents for an Employee
    // public function getPerformanceDocuments($employeeId)
    // {
    //     $employee = Employee::findOrFail($employeeId);
    //     $performanceDocuments = $employee->performanceDocuments;

    //     return response()->json(['performance_documents' => $performanceDocuments], 200);
    // }

    // // Retrieve Salary Slip for an Employee by Month
    // // public function getSalarySlip($employeeId, $month)
    // // {
    // //     $employee = Employee::findOrFail($employeeId);
    // //     $salarySlip = $employee->salarySlips()->where('month_year', $month)->first();

    // //     if ($salarySlip) {
    // //         return response()->json(['salary_slip' => $salarySlip], 200);
    // //     }

    // //     return response()->json(['message' => 'Salary slip not found'], 404);
    // // }
 -->
