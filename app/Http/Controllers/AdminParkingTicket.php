<?php

namespace App\Http\Controllers;

use App\Parking;
use App\VehicleMake;
use App\VehicleModel;
use App\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminParkingTicket extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Parking $parking)
    {
        try{
            $response= ['status'=>false,'message'=>'Something Wrong'];
            $parking = Parking::with('vechiclemake','vechiclemodel','vechicletype','property.client','client')->orderBy('id', 'DESC')->get();
            if(count($parking) > 0){
                $response = ['status'=>true,'message'=>'All Record', 'data'=>$parking];
            }else{
                $response = ['status'=>false,'message'=>'No Record Found!'];
            }
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
    public function create()
    {
        
        //
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
     * @param  \App\parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function view(Parking $parking,$parking_id)
    {
        try{
            $response= ['status'=>false,'message'=>'Something Wrong'];
            if($parking_id == '' && $parking_id == null){
                $response= ['status'=>false,'message'=>'Request parameter missing'];
            }else{
                $parking = Parking::with('vechiclemake','vechiclemodel','vechicletype','property.client','client')
                    ->where('id',$parking_id)->get();
                if(count($parking) > 0){
                    $response = ['status'=>true,'message'=>'All Record', 'data'=>$parking];
                }else{
                    $response = ['status'=>false,'message'=>'No Record Found!'];
                }
            }            
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function edit($parking_id)
    {
        try{
            $response= ['status'=>false,'message'=>'Something Wrong'];
            if($parking_id == '' && $parking_id == null){
                $response= ['status'=>false,'message'=>'Request parameter missing'];
            }else{
                $parking = Parking::with('vechiclemake','vechiclemodel','vechicletype','property.client')
                    ->where('id',$parking_id)->get();
                if(count($parking) > 0){
                    $response = ['status'=>true,'message'=>'All Record', 'data'=>$parking];
                }else{
                    $response = ['status'=>false,'message'=>'No Record Found!'];
                }
            }            
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, parking $parking)
    {
        $response = ['status' => false,'message'=>'Something wrong'];  
        $validator = Validator::make($request->all(), [
            'parking_location' => 'required',
            'vehicle_type' => 'required',
            'vehicle_make' => 'required',
            'vehicle_model' => 'required', 
            'licence_plate' => 'required',
            'color' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) :
            $response = ['status' => false, 'error'=>$validator->errors()];           
        else:
           
            if($vehicle_image =$request->file('vehicle_image')):
                $request->vehicle_image  =  Storage::disk('public')->put('parkingticket',$vehicle_image);
            else:
                unset($request->vehicle_image);
            endif;

            $parking_id = $request['parking_id'];                    
            $parking_update = array(
                'client_id'=>  $request['client_id'],           
                'property_id'=> $request['property_id'],
                'parking_location' => $request['parking_location'],
                'vehicle_image' => $request->vehicle_image,
                'vehicle_type' => $request['vehicle_type'],
                'vehicle_make' => $request['vehicle_make'],
                'vehicle_model' => $request['vehicle_model'],
                'licence_plate' => $request['licence_plate'],
                'color' => $request['color'],
                'description' => $request['description'],
            );
            $updated =  Parking::where('id',$parking_id)-> update($parking_update);
            if($updated):
                $response = ['status' => true, 'message'=> 'parking updated Successfully', 'data'=>$updated];    
            else:
                $response = ['status' => false, 'message'=> 'Parking noT updated Successfully' ];  
            endif;     
          
        endif;
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\parking  $parking
     * @return \Illuminate\Http\Response
     */
    public function destroy(parking $parking,$parking_id)
    {
        try{
            $response =['status'=>true,'message'=>'already deleted'];
            $parking = Parking::find($parking_id);
            $parking->delete();
            if($parking){
                $response = ['status' =>true,'message'=>'Parking Deleted Successfully'];  
            }else{
                $response = [ 'status' =>false, 'message'=>'Parking Not Deleted Successfully']; 
            } 
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }


    //  GET HERE VEHICLE MAKE 
    public function GetAllvehicleMake(Request $request){
        try{
            $response =['status'=>true,'message'=>'Something Wrong'];
            $make = VehicleMake::all();
            if(count($make) > 0 ){
                $response = ['status' =>true,'message'=> 'All Vehicle Make','data'=> $make];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    //  GET HERE ALL VEHICLE MODEL  
    public function GetAllVehicleModel(Request $request){
        try{
            $response = ['status'=>true,'message'=>'Something Wrong'];
            $model = VehicleModel::all();
            if(count($model) > 0 ){
                $response =['status' =>true,'message'=> 'All Vehicle Model','data'=> $model];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    //  GET HERE ALL VEHICLE TYPE 
    public function GetAllVehicleType(Request $request){
        try{
            $response =['status'=>true,'message'=>'Something Wrong'];
            $type = VehicleType::all();
            if(count($type) > 0 ){
                $response = ['status' =>true, 'message'=> 'All Vehicle Type','data'=> $type];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    // APPROVE PARKING TICKET
    public function ApproveParkingTicket(Request $request, $parking_id){
        try{
            $response =['status'=>true,'message'=>'Something Wrong'];

            $parking_update = ['status' => 'approved'];
            
            $updated =  Parking::where('id',$parking_id)-> update($parking_update);
            if($updated){
                $response = ['status' =>true,'message'=> 'Status approve','data'=> $updated];
            }else{
                $response = ['status' =>false, 'message' =>'No Record Found!'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }
    
    
}
