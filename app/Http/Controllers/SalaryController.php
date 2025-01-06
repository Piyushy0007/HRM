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
        $sortBy = $request->input('sortBy', 'month');
        $sortOrder = $request->input('sortOrder', 'desc');

        $salaries = SalaryDisbursed::with(['employee', 'salary'])
            ->orderBy($sortBy, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json($salaries);
    }
}
