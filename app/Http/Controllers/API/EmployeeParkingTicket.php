<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeParking;
use App\VehicleMake;
use App\VehicleModel;
use App\VehicleType;
use App\Client;
use App\ClientProperties;
use Carbon\Carbon;
use App\Models\Clockin;
use App\Models\DetectShift;
use App\Models\Shift;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use App\Http\Controllers\API\EmployeeController;

class EmployeeParkingTicket extends Controller
{
     

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        $response = ['status' => false,'message'=>'Something wrong'];  
        // Instantiate EmployeeController
        $EmployeeController = new EmployeeController;
        //$current_shift = $EmployeeController->CurrentShiftReturn($request['employee_id']); 

        $current_shift = Shift::with(['employee','property'])
            ->where('id',$request['locationId'])
            ->whereNull('deleted_at')
            ->first()->toArray();

        if($current_shift):

             //USER CLOCK IN THEN SUBMIT REPORT 
             $clockin_id = Clockin::where('employee_id', $request['employee_id'])
             ->where('shiftaction','=','true')
             ->whereDate('created_at', Carbon::today()->toDateString())
             ->orderByDesc('id')
             ->groupBy('employee_id')
             ->first();
            if($clockin_id != null){
       
                $validator = Validator::make($request->all(), [
                    'employee_id' =>'required',          
                    'vehicle_type' => 'required',
                    'vehicle_make' => 'required',
                    'vehicle_model' => 'required'            
                ]);
                if ($validator->fails()) {
                    $response = ['status' => false, 'error'=>$validator->errors()];           
                }else{

                    // GET HERE VECHICLE IMAGES
                    $names = []; 
                    $full_path = [];
                    if ($request->input('vehicle_image')) {
                        $files  = $request->input('vehicle_image');
                    
                        //foreach($files as $file):
                            $name =  uniqid().time().'.jpg';                  
                            $store_with_paths =  Storage::disk('public')->put('parkingticket/'.$name,base64_decode($files));
                            $names[] =$name;
                            $full_path[]  =Storage::url("public/parkingticket/{$name}");
                       // endforeach;  
                    } 

                    //$all_images = implode(",",$names);
                    $all_images = implode(",",$full_path);

                    if(empty($all_images)){
                        $all_images = '';
                    }
                    // $all_images = NULL;
                   
                    $parking = new EmployeeParking();
                    $parking->employee_id = $request['employee_id'];
                    $parking->property_id = $current_shift['property']['id'];
                    $parking->client_id = $current_shift['property']['client_id'];
                    $parking->parking_location = $current_shift['property']['address'];
                    $parking->vehicle_image = $all_images;
                    $parking->vehicle_type = $request['vehicle_type'];
                    $parking->vehicle_make = $request['vehicle_make'];
                    $parking->vehicle_model = $request['vehicle_model'];
                    $parking->licence_plate =  $request['licence_plate'];
                    $parking->color = $request['color'];
                    $parking->shift_id = $request['locationId'];
                    $parking->description = $request['description'];
                    $parking->status = "pending";
                    $parking->save();
                   
                    if($parking):

                         /*** INSERT Parking LOGS *******/
                        //  $DetectShift = new DetectShift();
                        //  $DetectShift->employee_id = $request['employee_id'];
                        //  $DetectShift->clock_out_time = date('H:i:sa');
                        //  $DetectShift->shift_id = $current_shift['id'];
                        //  $DetectShift->checked_id = $clockin_id->id;
                        //  $DetectShift->onShift = 'true';
                        //  $DetectShift->event_type ='parking';
                        //  $DetectShift->date = date('Y-m-d');
                        //  $DetectShift->save();
                         /*** END Parking LOGS *******/
                        $response = ['status'=>true, 'message'=>'Parking updated Successfully', 'data'=>$parking];    
                    else:
                        $response = ['status'=>false, 'message'=>'Parking not updated Successfully' ];  
                    endif;         
                }
            }else{
                $response = ['status'=>false,'message'=>"You're not clocked in"];
            }
        else:
            $response = ['status' => false,'message'=>'Your shift has not started']; 
        endif;
        return response($response);
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
     * @param  \App\Model\EmployeeParking  $employeeParking
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeParking $employeeParking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\EmployeeParking  $employeeParking
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeParking $employeeParking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\EmployeeParking  $employeeParking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeParking $employeeParking, $parkingId)
    {
        try{

            $response = ['status' => false,'message'=>'Something wrong']; 
            
            // Instantiate EmployeeController
            $EmployeeController = new EmployeeController;
            $current_shift = $EmployeeController->CurrentShiftReturn($request['employee_id']);
        
            if($current_shift):
                $validator = Validator::make($request->all(), [
                    'employee_id' =>'required',
                    'vehicle_type' => 'required',
                    'vehicle_make' => 'required',
                    'vehicle_model' => 'required'
                ]);
                if ($validator->fails()) {
                    $response = ['status' => false, 'error'=>$validator->errors()];           
                }else{
                    
                    $files = $request->input('vehicle_image');
                    $names = [];
                    foreach($files as $file):
                        $name = '/storage/parkingticket/'.uniqid().time().'.jpg';                  
                        $store_with_paths = Storage::disk('public')->put('parkingticket/'.$name,base64_decode($file['base64']));
                        $names[] = $name;
                    endforeach;
                    $images = implode(',', $names);
                    if(empty($images)){
                        $images = '';
                    }
                    
                    $data = [
                        'employee_id' => $request->employee_id,
                        'property_id' => $current_shift['property']['id'],
                        'client_id' => $current_shift['property']['client_id'],
                        'parking_location' => $request->parking_location,
                        'vehicle_image' => $images,
                        'vehicle_type' => $request->vehicle_type,
                        'vehicle_make' => $request->vehicle_make,
                        'vehicle_model' => $request->vehicle_model,
                        'licence_plate' => $request->licence_plate,
                        'color' => $request->color,
                        'description' => $request->description,
                    ];
                    
                    $parking = EmployeeParking::where('id',$parkingId)->update($data);
                    
                    if($parking):
                        $response = ['status' => true, 'message'=> 'Parking updated Successfully', 'data'=>$parking];    
                    else:
                        $response = ['status' => false, 'message'=> 'Parking not updated Successfully' ];  
                    endif;
                }
            else:
                $response = ['status' => false,'message'=>'Your shift has not started']; 
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\EmployeeParking  $employeeParking
     * @return \Illuminate\Http\Response
     */
    // public function destroy(EmployeeParking $employeeParking)
    // {
    //     //
    // }

    public function CreateTicketInfomation(){
        $arrTicket = [];

        // CLIENT
        $client = Client::all();
        if(count($client)> 0):
            $arrTicket['client'] = $client;
        endif;

        // CLIENT PROPERTY
        $Client_property = ClientProperties::all();
        if(count($Client_property)> 0):
            $arrTicket['property'] = $Client_property;
        endif;
        
        // VEHICLE MAKE 
        $vehicle_make = VehicleMake::all();
        if(count($vehicle_make)> 0):
            $arrTicket['vehicle_make'] = $vehicle_make;
        endif;
        
        // VEHICLE MODEL
        $vehicle_model = VehicleModel::all();
        if(count($vehicle_model)> 0):
            $arrTicket['vehicle_model'] = $vehicle_model;
        endif;

        // VEHICLE TYPE
        $vehicle_type = VehicleType::all();
        if(count($vehicle_type)> 0):
            $arrTicket['vehicle_type'] = $vehicle_type;
        endif;

        if(count($arrTicket)> 0):
            $response =['status' => true,'message'=> 'Ticket Information', 'data' =>$arrTicket];
        else:
            $response =['status' => false,'message'=> 'Not Information Found'];
        endif;
      
        return response($response);
    }

    /*
    * @employee id by parking ticket
    *   
    */
    public function GetParkingTicketByEmployeeId($employee_id){
        try{
            //  Return Response
            $response = ['status'=>false,'message'=>'something wrong'];
                // Instantiate EmployeeController
            $EmployeeController = new EmployeeController;
            $current_shift = $EmployeeController->CurrentShiftReturn($employee_id);
        
            if($current_shift):              
                // REUTN DATA HERE BY EMPLOYEE ID 
                $parking = EmployeeParking::with('client','vechicletype','vechiclemake','vechiclemodel')
                ->where('employee_id',$employee_id)
                ->get()
                ->toArray();
                //  CHECK HERE ID IS EMPLTY OR NOT 
                if(count($parking) > 0):
                    $response = ['status'=>true,'message'=>'All Parking Ticket', 'data'=>$parking];
                else:
                    $response = ['status'=>false,'message'=>'No Records Found!'];
                endif;                    
            else:
                $response = ['status' => false,'message'=>'Your shift has not started']; 
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


}
