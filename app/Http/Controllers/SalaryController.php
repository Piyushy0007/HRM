<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\PayrollStatus;
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

public function calculatePayrollStatus(Request $request)
{
    $validated = $request->validate([
        'month' => 'required|integer|min:1|max:12',
        'year' => 'required|string|max:9', // e.g., "2024-25"
    ]);

    $month = $validated['month'];
    $year = $validated['year'];

    // Fetch salary details for active employees only
    $salaryDetails = DB::table('tblm_employee')
    ->join('salaries', 'tblm_employee.id', '=', 'salaries.employee_id')
    ->selectRaw('
        COUNT(tblm_employee.id) as total_employees,
        SUM(salaries.gross_salary) as total_gross_salary,
        SUM(salaries.net_salary) as total_net_salary,
        SUM(salaries.net_salary * 0.01) as total_tax,
        SUM(salaries.basic_salary) as total_basic_salary
    ')
    ->where('tblm_employee.status', 'active') // Ensure 'active' is in quotes as it's a string
    ->whereMonth('salaries.payment_date', $month)
    ->whereYear('salaries.payment_date', $year)
    ->first();

    if (!$salaryDetails || $salaryDetails->total_employees == 0) {
        return response()->json(['message' => 'No salary data found for the given month and year'], 404);
    }

    // Save to payroll_status table
    DB::table('payroll_status')->updateOrInsert(
        ['month' => $month, 'year' => $year],
        [
            'total_gross_salary' => $salaryDetails->total_gross_salary,
            'total_net_salary' => $salaryDetails->total_net_salary,
            'total_tax' => $salaryDetails->total_tax,
            'total_basic_salary' => $salaryDetails->total_basic_salary,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

    return response()->json([
        'message' => 'Payroll status calculated successfully',
        'data' => [
            'month' => $month,
            'year' => $year,
            'total_employees' => $salaryDetails->total_employees,
            'total_gross_salary' => $salaryDetails->total_gross_salary,
            'total_net_salary' => $salaryDetails->total_net_salary,
            'total_tax' => $salaryDetails->total_tax,
            'total_basic_salary' => $salaryDetails->total_basic_salary,
        ],
    ], 200);
}

public function insertSalaryDisbursements(Request $request)
{
    $validated = $request->validate([
        'month' => 'required|integer|min:1|max:12',
        'year' => 'required|string|max:9', // e.g., "2024-25"
    ]);

    $month = $validated['month'];
    $year = $validated['year'];

    try {
        // Fetch salary details and insert/update disbursements
        $salaryDetails = DB::table('tblm_employee')
            ->join('salaries', 'tblm_employee.id', '=', 'salaries.employee_id')
            ->select('tblm_employee.id as employee_id', 'salaries.id as salary_id', 'salaries.net_salary')
            ->where('tblm_employee.status', 'active')
            ->whereMonth('salaries.payment_date', $month)
            ->whereYear('salaries.payment_date', substr($year, 0, 4))
            ->get();

        foreach ($salaryDetails as $salary) {
            DB::table('salary_disbursements')->updateOrInsert(
                [
                    'employee_id' => $salary->employee_id,
                    'salary_id' => $salary->salary_id,
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'disbursed_amount' => $salary->net_salary,
                    'status' => 'Pending',
                    'payment_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json([
            'message' => 'Salary disbursements inserted successfully',
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while inserting salary disbursements',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    
}
