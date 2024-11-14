<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Preferences;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class WorkreferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($employee_id)
    {
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];
            
            if($employee_id == null):
                ['status'=>false,'message'=>'Employee id is missing'];
            else:

                $Preferences = Preferences::where('employee_id',$employee_id)->orderBy('id', 'DESC')->get();
                if(count($Preferences) > 0 ){
                    $response = [ 'status' =>true,'message' =>'Your Preferences fetech successfully','data' => $Preferences];
                }else{
                    $response = ['status' =>false,'message' =>'No Record Found!'];
                } 
            endif;       
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            // check here validation 
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'work_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required'
            ]);

            // if validation failed
            if ($validator->fails()):
            
                return response(['errors'=>$validator->errors()->all()], 422);
            else:
           
                $Preferences = new Preferences();
                $Preferences->employee_id = $request->employee_id;
                $Preferences->work_date = $request->work_date;
                $Preferences->start_time = date('H:i A', strtotime($request->start_time));
                $Preferences->end_time = date('H:i A', strtotime($request->end_time));
                $Preferences->save();
                
                if($Preferences):
                    $response = ['status'=>true,'message'=>'New Preferences  Added Successfully!'];          
                else:
                    $response = ['status'=>false,'message'=>'New Preferences Not Added!'];
                endif;
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $data = [
                'work_date' =>$request->work_date,
                'start_time' =>date('H:i A', strtotime($request->start_time)),
                'end_time' =>date('H:i A', strtotime($request->end_time)),
            ];
            $Preferences = Preferences::where('id',$id)->update($data);
            
            if($Preferences):
                $response = ['status'=>true,'message'=>'Preferences Updated Successfully!'];          
            else:
                $response = ['status'=>false,'message'=>'Preferences Not Updated!'];
            endif;
            return response($response);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $Preferences =Preferences::find($id);
            $Preferences->delete();

            if($Preferences):
                $response = ['status'=>true,'message'=>'Successfully removed!'];          
            else:
                $response = ['status'=>false,'message'=>'Not removed Successfully!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }




}
