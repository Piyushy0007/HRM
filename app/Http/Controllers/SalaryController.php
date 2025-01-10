<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\SalaryDisbursed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    // Show all salary disbursed records with pagination and sorting by month
    public function index(Request $request)
{
    $limit = $request->input('limit', 10);
    $offset = $request->input('offset', 0);
    $sortBy = $request->input('sortBy', 'salary_date');  // Sort by date column
    $sortOrder = $request->input('sortOrder', 'desc');
    $month = $request->input('month');
   

    $query = SalaryDisbursed::with(['employee', 'salary'])
        ->orderBy($sortBy, $sortOrder)
        ->offset($offset)
        ->limit($limit);

    // Use direct where for month and year
    if (!empty($month) ) {
        $query->where('month', $month);
    }

    $salaries = $query->get();

    return response()->json($salaries);
}

public function calculateTds(Request $request)
{
    // Validate input
    $request->validate([
        'employee_id' => 'required|integer',
        'start_month' => 'required|integer|min:1|max:12',
        'end_month' => 'required|integer|min:1|max:12',
        'year' => 'required|integer',
    ]);

    $employeeId = $request->employee_id;
    $startMonth = $request->start_month;
    $endMonth = $request->end_month;
    $year = $request->year;

    // Fetch salary data for the given employee and date range
    $salaries = DB::table('salaries')
        ->where('employee_id', $employeeId)
        ->get();

    // Calculate total disbursed amount
    $totalDisbursedAmount = $salaries->sum('disbursed_amount');

    // Example: Simple TDS calculation (adjust based on your tax logic)
    $tdsRate = 0.10; // Flat 10% tax for this example
    $totalTax = $totalDisbursedAmount * $tdsRate;

    // Prepare response
    return response()->json([
        'message' => 'TDS calculated successfully',
        'data' => [
            'total_disbursed_amount' => $totalDisbursedAmount,
            'total_tax' => $totalTax,
            'tds_rate' => $tdsRate * 100 . '%',
            'salary_details' => $salaries,
        ]
    ]);
}

    
}
