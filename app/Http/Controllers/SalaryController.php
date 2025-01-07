<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\SalaryDisbursed;
use Illuminate\Http\Request;

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
    $year = $request->input('year');

    $query = SalaryDisbursed::with(['employee', 'salary'])
        ->orderBy($sortBy, $sortOrder)
        ->offset($offset)
        ->limit($limit);

    // Use direct where for month and year
    if (!empty($month) && !empty($year)) {
        $query->where('month', $month)
              ->where('year', $year);
    }

    $salaries = $query->get();

    return response()->json($salaries);
}


    
}
