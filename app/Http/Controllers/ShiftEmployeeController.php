<?php

namespace App\Http\Controllers;

use App\ShiftEmployee;
use Illuminate\Http\Request;

class ShiftEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Int  $shift
     * @return \Illuminate\Http\Response
     */
    public function index($shift)
    {
        // @Note:
        //  - Should be equal to
        //      - week_of
        //      - position

        /*$shift = Shift::find($shift);
        return EmployeePosition::with('employee', 'position')
                        ->where('position_id', $shift->position_id)
                        ->get();*/

        /*$shift = Shift::with('position')
                    ->where('week_of', $request->get('selectedDate'))
                    ->groupBy('position_id')
                    ->orderBy('created_at', 'desc');*/

        // list all details inside that shift
        // retrieve emp
        /*$shiftEmployee = ShiftEmployee::with('employee', 'shift')
                            ->where('shift_id', $shift)
                            ->get();
        foreach ($shiftEmployee as $value) {
        }*/

        // should not display deleted employee (soft deletion)
        /*return ShiftEmployee::with(['employee' => function ($q) {
            $q->whereNotNull('deleted_at');
        }], 'shift')
                    ->where('shift_id', $shift)
                    ->get();*/
        $employeeShifts = ShiftEmployee::with('employee', 'shift')
                            ->where('shift_id', $shift)
                            ->get();
        $aw = [];
        foreach ($employeeShifts as $value) {
            if($value->employee == null) continue;
            $aw[] = $value;
        }
        return $aw;



        return ShiftEmployee::with('employee', 'shift')
                    ->where('shift_id', $shift)
                    ->get();

        // return ShiftEmployee::with('employee','shift')->where('shift_id', $shift)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShiftEmployee  $shiftEmployee
     * @return \Illuminate\Http\Response
     */
    public function show(ShiftEmployee $shiftEmployee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShiftEmployee  $shiftEmployee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShiftEmployee $shiftEmployee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShiftEmployee  $shiftEmployee
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShiftEmployee $shiftEmployee)
    {
        //
    }
}
