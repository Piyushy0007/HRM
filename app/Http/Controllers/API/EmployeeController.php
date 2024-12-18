<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\Traits\OTPSMS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\ShiftEmployee;
use App\Models\Shift;
use App\Models\Clockin;
use App\Models\ClientProperties;
use App\Timeoff;
use App\Employeemessage;
use App\Message;
use App\Models\Reportslog;
use App\Models\ReportAlert;
use App\ClientAlertReport;
use App\Models\EmployeePosition;
use App\Models\DetectShift;
use App\Models\ShiftDate;
use App\Models\ReportSubType; 
use App\Models\EmployeeParking;
use App\Models\EmployeeClient;
use App\VehicleMake;
use App\VehicleModel;
use App\VehicleType;
use App\Client;
use App\Logs;
use App\Notifications;
use App\Models\Violation;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Exception;
use Illuminate\Database\QueryException;
use JWTFactory;
use JWTAuth;
use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Mail\EmployeeSendOTP;
use App\Mail\EmployeeAlertReport;
use App\Mail\EmployeeResetPassword;
use Illuminate\Support\Facades\Storage;
use App\Notifications\WholeAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\App;
use App\Events\OnNow;
use App\Mail\SignInInstructionwatcher;


class EmployeeController extends Controller
{
    use OTPSMS;


    protected function redirectTo($request)
    {
        return route('login');
    }

    public function __construct()
    {
       // $this->middleware('auth:employee', ['except' => ['login', 'register']]);
        
    }

    protected function guard()
	{
	    return Auth::guard('employee');
	}

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'status'=>true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->guard('employee')->factory()->getTTL() * 3600*90, 
            'user' => auth()->guard('employee')->user()
        ]);
    }

    /**
     * check the token in Header.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function bearerToken()
    {
        $header = $this->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    
    public function register(Request $request)
    {

       // Validate requested data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:tblm_employee',
            'password' => 'required|string|min:6',
            //'phone'=> 'required|numeric|digits:10',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }     
     
        $user = Employee::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password),'plain_password' => $request->password,'zip'=>$request->zip]
        ));
        $position = EmployeePosition::create([
                'employee_id' => $user->id,
                'position_id' => 16
        ]);

        
        //EmployeeClient
        $clientlistnearzip = Client::where('zip', $request->zip)->get();
        foreach($clientlistnearzip as $clientlistnearzipval){
            $emplyereport = new EmployeeClient();
            $emplyereport->client_id = $clientlistnearzipval->id;
            $emplyereport->employee_id = $user->id;
            $emplyereport->save();
        }


        $when = now()->addMinutes(1); 
        Mail::to($request->email)->later($when, new SignInInstructionwatcher($user));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
        
    }

   /*
    * Login api
    *
    * @param  [string] email
    * @param  [string] password
    * @param  [boolean] remember_me
    * @return [string] access_token
    * @return [string] token_type
    * @return [string] expires_at
    */

    public function login(Request $request)
{
    // Validate the input
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors());
    }

    // Fetch the employee along with role
    $employee = Employee::with('role')
        ->where('email', $request->email)
        ->whereNull('deleted_at')
        ->first();

    if (!$employee) {
        return response()->json(['status' => false, 'message' => 'User does not exist on this email']);
    }

    // Attempt login
    if (!$token = auth()->guard('employee')->attempt([
        'email' => $request->email,
        'password' => $request->password
    ])) {
        return response()->json(['status' => false, 'message' => 'Unauthorized user']);
    }

    // Update device token if provided
    if ($request->has('deviceToken')) {
        $employee->update([
            'deviceToken' => $request->deviceToken
        ]);
    }

    // Return token and user information including role
    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'token' => $token,
        'employee' => $employee
    ]);
}

    
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function  logout(Request $request){
        auth()->guard('employee')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }


        /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->guard('employee')->refresh());
    }

    
     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request) {
        
        return response()->json(auth()->guard('employee')->user());  
    }

    public function destroy(Request $request)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $employee = Employee::find($request['id']);
            $employee->delete();
            if($employee):            
                $response = ['status'=>true,'message'=>'Record Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    public function checktimeoffwithshift($timeoff, &$shift,&$emp,$i){
        
        $current_date = Carbon::now()->toDateString();
        $timeoff_dates  = array_merge(explode("~", $timeoff['time_off']));

        if(in_array($current_date, $timeoff_dates)){
         
            if($timeoff['whole_day'] == '1'){
                array_splice($emp['shifts'],$i);
                return false;
            }else{
                         
                $is_start =false;
                $is_end = false;
                if($timeoff['from_time'] <= $emp[$i]['start'] && $timeoff['to_time'] >= $emp[$i]['start'] ){
                    $is_start =true;
                }

                if($timeoff['from_time'] <=$emp[$i]['end'] && $timeoff['to_time'] >= $emp[$i]['end'] ){
                    $is_end = true;
                }

                if($is_start == true && $is_end == true){
                    array_splice($emp,$i);

                }else{
                    if($is_start == true && $is_end == false){
                        $emp[$i]['start'] = $timeoff['to_time'];
                    }
                    if($is_start == false && $is_end == true){
                        $emp[$i]['end'] = $timeoff['from_time'];
                    }
                } 
            }
        }

    }

    /***
     * CHECK HERE TODAY SHIFT EXIST OR NOT
     * 
     */
    public function  TodaydateShifIsExist($employee_id){
         
        $response=['status'=>false,'message'=>'Something Wrong']; 

        $current_date = Carbon::now()->toDateString();
        $data = [];  

        $shifts = Shift::with(['employee','property',
                'employee.timeoff'=>function($q){
                    $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
                    $q->where('status', 'approved');
                }, 'logs'
            ])
            ->whereDate('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
            ->where('employee_id',$employee_id)
            ->whereNull('deleted_at')
            ->get()->toArray();
      
        foreach($shifts as &$shift):
            for($i =0; $i < count($shifts); $i++):
              
                $shifts[$i]['clockin'] = $this->GetClockInOutByEmployeeId($employee_id,$shifts[$i]['id'],$current_date,$shifts[$i]['start'],$shifts[$i]['end']);
                foreach($shift['employee']['timeoff'] as &$timeoff):
                    $this->checktimeoffwithshift($timeoff,$shifts[$i],$shifts,$i);
                endforeach;
            endfor;         
        endforeach;
        return $shifts;                  
    }


    public function newTodaydateShifIsExist($locationId,$employee_id){
         
        $response=['status'=>false,'message'=>'Something Wrong']; 

        $current_date = Carbon::now()->toDateString();
        $data = [];  

        $shifts = Shift::with(['employee','property',
                'employee.timeoff'=>function($q){
                    $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
                    $q->where('status', 'approved');
                }, 'logs'
            ])
            ->where('id',$locationId)
            ->whereNull('deleted_at')
            ->get()->toArray();
      
        foreach($shifts as &$shift):
            for($i =0; $i < count($shifts); $i++):
              
                $shifts[$i]['clockin'] = $this->GetClockInOutByEmployeeId($employee_id,$shifts[$i]['id'],$current_date,$shifts[$i]['start'],$shifts[$i]['end']);
                foreach($shift['employee']['timeoff'] as &$timeoff):
                    $this->checktimeoffwithshift($timeoff,$shifts[$i],$shifts,$i);
                endforeach;
            endfor;         
        endforeach;
        return $shifts;                  
    }

    public function GetClockInOutByEmployeeId($employee_id,$shift_id,$current_date,$check_start,$check_end){
       
        $clockin = ClockIn::where('employee_id',$employee_id);
        if ($shift_id != null) {
			$clockin->where('shift_id',$shift_id);
		}
        if ($shift_id != null) {
			$clockin->orWhereDate('check_date',$current_date);
		}

        if ($check_start != null) {
			$clockin->where('check_start',$check_start);
		}

        if ($check_end != null) {
			$clockin->where('check_end',$check_end);
		}

        $clockinout = $clockin->first();
        //dd($this->getEloquentSqlWithBindings($clockin)); 
        return $clockinout; 
    }

   
   /*
   * EMPLOYEE ID BY SEND SHIFT DETAILS
   * EMPLOYEE ID 
   */
   	public function send_shift_start_end_details(Request $request){
                
        $response=['status'=>false,'message'=>'Something Went Wrong']; 

        $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
            
        $employee = Employee::where('id', $request['employee_id'])->first();
        $subject ='Update Shift Request';
        $message = 'Update Shift Request';
        $include_player_id =[$employee['employee_player_id']];
        $app_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2'; 

        //
        $usershift = new WholeAppNotification();    
        $usershift->PushNotification($app_id,$include_player_id, $subject,$message);
        $notification = [
            'employee_id'=>$request['employee_id'],
            'subject'=> $subject,
            'description'=> $message,
        ];
        Notifications::create($notification);
      
        if($currentshift){
            $response = ['status'=>true, 'message'=>'Today shift','data'=>$currentshift];
        }else{
            $response = ['status'=>false, 'message'=>'Your shift has not started'];
        }
        return response($response);
   	} 


       
    public function isThisCurrentShift($shift){

        if($shift['clockin'] != null && $shift['clockin']['shiftaction'] == true){
            return  true;
        }
        $current_time = date('H:i:s');
        
        if($current_time >=  $shift['start']  && $current_time  <= $shift['end']  ):
            return  true;
        endif;
        return false;
       
    }

   	/**
    ** EMPLOYEE CLOCK IN API
    **
    **/   
   	public function  clock_in(Request $request){

       $response = ['status'=>false, 'message'=>'Something went Wrong'];

        //  RETURN ONLY TODAY DATE SHIFT 
        // $currentshift = $this->newTodaydateShifIsExist($request['locationId'],$request['employee_id']);ashish ask to do that
         $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
        // DECLARE NULL VARIABLE FOR SHIFT 
        $SHIFT =null;
        // CHECK CURRENT SHIFT ONLY WITH CURRENT TIME
        foreach($currentshift  as  $shiftdata):
            if($this->isThisCurrentShift($shiftdata)){
                $SHIFT = $shiftdata;
            }                           
        endforeach;   
            //dd($SHIFT);
            // CHECK SHIFT EXIST OR NOT 
            if($SHIFT){ 
                // CHECK SHIFT CURRENT TIME
                $current_time = date('H:i:s'); 
                //dd($current_time);             
                if($current_time >= $SHIFT['start'] && $current_time <= $SHIFT['end'] ):

                    //  CHECK HERE USER ALREADY LOGIN OR NOT 
                    $check_already_clockin = Clockin::where('employee_id',$request['employee_id'])
                        ->where('shift_id', $SHIFT['id'])->orderBy('id','desc')->first();


                    if($check_already_clockin == null){
                        
                        $clockin = new Clockin();
                        $clockin->employee_id =$request['employee_id'];
                        $clockin->start_time = date('H:i:s');
                        $clockin->shift_id = $request['locationId'];
                        $clockin->shiftaction ='true';
                        $clockin->check_date = date('Y-m-d');
                        $clockin->check_start = $SHIFT['start'];
                        $clockin->check_end = $SHIFT['end'];
                        $clockin->save();   

                        if($clockin):

                            $deviloation = DetectShift::where('employee_id',$request['employee_id'])                   
                            ->whereDate('created_at',Carbon::today()->toDateString())
                            ->orderBy('id','desc')
                            ->first();
                            if($deviloation == null || $deviloation->event_type != 'clockin'){
                                $DetectShift = new DetectShift();
                                $DetectShift->employee_id = $request['employee_id'];
                                $DetectShift->clock_in_time = date('H:i:s');
                                $DetectShift->shift_id = $request['locationId'];
                                $DetectShift->checked_id = $clockin->id;
                                $DetectShift->event_type = 'clockin';
                                $DetectShift->onShift ='true';
                                $DetectShift->date = date('Y-m-d');
                                $DetectShift->save();
                                $event = broadcast(new OnNow($DetectShift))->toOthers();
                            }
    
                            $employee = $this->TodaydateShifIsExist($request['employee_id']);
                            //
                            $propertyname = '';
                            $emp = Employee::where('id',$request['employee_id'])->first();
                            //echo "<pre>"; print_r($emp);
                                if($emp->deviceToken!=""){
                                  $curl = curl_init();
                                  $token = $emp->deviceToken;
                                  $message = "You are marked as Clocked In";
                                  curl_setopt_array($curl, array(
                                  CURLOPT_URL => "http://3.142.74.2:7889/callpushNotification",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "POST",
                                  CURLOPT_POSTFIELDS => '{"message": "'.$message.'", "token": "'.$token.'"}',
                                  CURLOPT_HTTPHEADER => array(
                                    "Content-Type: application/json"
                                  ),
                                ));
                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                curl_close($curl);
                            }
                            $response = ['status' =>true, 'message'=>"Clockin Successfully",'shift'=>$employee ];        
                        else:
                            $response =['status' =>false, 'message'=>'Clockin not Successfully' ]; 
                        endif; 
                        
                    }else{
                        $update =[
                            'employee_id' => $request['employee_id'],
                            'end_time'=> date('H:i:s'),
                            'shiftaction' =>'true',                       
                        ];

                        $clockin = Clockin::where('id', $check_already_clockin->id)->update($update);

                        if($clockin):
                            $DetectShift = new DetectShift();
                            $DetectShift->employee_id = $request['employee_id'];
                            $DetectShift->clock_in_time = date('H:i:s');
                            $DetectShift->shift_id = $request['locationId'];
                            $DetectShift->checked_id = $check_already_clockin->id;
                            $DetectShift->event_type = 'clockin';
                            $DetectShift->onShift ='true';
                            $DetectShift->date = date('Y-m-d');
                            $DetectShift->save();
                            $event = broadcast(new OnNow($DetectShift))->toOthers();
    
                            $employee = $this->TodaydateShifIsExist($request['employee_id']);
                            $propertyname = '';
                            $emp = Employee::where('id',$request['employee_id'])->first();
                                if($emp->deviceToken!=""){
                                  $curl = curl_init();
                                  $token = $emp->deviceToken;
                                  $message = "You are marked as Clocked In";
                                  curl_setopt_array($curl, array(
                                  CURLOPT_URL => "http://3.142.74.2:7889/callpushNotification",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "POST",
                                  CURLOPT_POSTFIELDS => '{"message": "'.$message.'", "token": "'.$token.'"}',
                                  CURLOPT_HTTPHEADER => array(
                                    "Content-Type: application/json"
                                  ),
                                ));
                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                curl_close($curl);
                            }
                            $response = ['status' =>true, 'message'=>"Clockin Successfully",'shift'=>$employee ];        
                        else:
                            $response =['status' =>false, 'message'=>'Clockin not Successfully' ]; 
                        endif; 
                    }    
                
                else:                    
                    $response =['status' =>false, 'message'=>'Your shift has not started',$currentshift];
                endif;       
            }else{
                $response  = ['status' =>false, 'message'=>'Your shift has not started',$currentshift];   
            }
      
        return response($response);
	}
   


	/*
	* shift over api
	*/
	public function clock_out(Request $request){


        $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
        //$currentshift = $this->newTodaydateShifIsExist($request['locationId'],$request['employee_id']);
        $current_time = date('H:i:s');

        $SHIFT =null;
        
        foreach($currentshift as $shiftdata):

            if($this->isThisCurrentShift($shiftdata)){
                $SHIFT = $shiftdata;
            }                           
        endforeach;                   
        
            if($SHIFT){
            //     if($current_time >= $SHIFT['end']):
                    $update =[
                        'employee_id' => $request['employee_id'],
                        'end_time'=> date('H:i:s'),
                        'shift_id' =>$request['locationId'],
                        'shiftaction' =>'false',                       
                    ];

                    $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                            ->where('shiftaction','=','true')
                            ->whereDate('created_at', Carbon::today()->toDateString())
                            ->orderByDesc('id')
                            ->groupBy('employee_id')
                            ->first();

                    $check_clock_in_id = null;

                    if($clockin_id != ''):
                        $check_clock_in_id = $clockin_id->id;
                    endif;

                    $clockin = Clockin::where('id', $check_clock_in_id)->update($update);
                    if($clockin):
                        $DetectShift = new DetectShift();
                        $DetectShift->employee_id = $request['employee_id'];
                        $DetectShift->clock_out_time = date('H:i:s');
                        $DetectShift->shift_id = $request['locationId'];
                        $DetectShift->checked_id = $check_clock_in_id; 
                        $DetectShift->event_type = 'clockout';
                        $DetectShift->onShift = 'false';
                        $DetectShift->reason = $request['reason'];
                        $DetectShift->date = date('Y-m-d');
                        $DetectShift->save();
                        $event = broadcast(new OnNow($DetectShift))->toOthers();

                        $employee = $this->TodaydateShifIsExist($request['employee_id']);
                        $emp = Employee::where('id',$request['employee_id'])->first();
                        if($emp->deviceToken!=""){
                              $curl = curl_init();
                              $token = $emp->deviceToken;
                              $message = "You are marked as Clocked Out";
                              curl_setopt_array($curl, array(
                              CURLOPT_URL => "http://3.142.74.2:7889/callpushNotification",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "POST",
                              CURLOPT_POSTFIELDS => '{"message": "'.$message.'", "token": "'.$token.'"}',
                              CURLOPT_HTTPHEADER => array(
                                "Content-Type: application/json"
                              ),
                            ));
                            $response = curl_exec($curl);
                            $err = curl_error($curl);
                            curl_close($curl);
                        }

                        $response  = ['status' =>true,'message'=>'Clock out Successfully', 'shift'=>$employee ];
                    else: 
                        $response  =['status' =>false, 'message'=>'Clock out not Successfully'];
                    endif;
            }else{
                $response  = ['status' =>false, 'message'=>'Your shift has not started',$currentshift];
            }
        return response($response);
	}


    /***
     *  CHECK USER SHIFT ACTIVE OR NOT 
     *  THEN CHECK USER IN GEOFENCE  
     *  @GEOFENCE PROPERTY
     * 
     */
    public function detectClockInEmployeeposition(Request $request){

        $response = ['status'=>false,'message'=>'Something Wrong'];

        
        $clockin_id = Clockin::where('employee_id', $request['employee_id'])
        ->where('shiftaction','=','true')
        ->whereDate('created_at',Carbon::today()->toDateString())
        ->first();

        if(isset($clockin_id) && $clockin_id != NULL){
            $value = $clockin_id->id;       

            // CHECK CURRENT SHIFT
            $employee = $this->TodaydateShifIsExist($request['employee_id']);
            $SHIFT =null;            
            foreach($employee as $shiftdata):

                if($this->isThisCurrentShift($shiftdata)){
                    $SHIFT = $shiftdata;
                }                           
            endforeach;           
            
            $deviloation = DetectShift::where('employee_id',$request['employee_id'])
                ->where('event_type','!=','clockin')                    
                ->whereDate('created_at',Carbon::today()->toDateString())
                ->orderBy('id','desc')
                ->first();

                $update = [
                    'employee_id' => $request['employee_id'],
                    'clock_out_time' => date('H:i:s'),
                    'onShift'=>'false'
                ];
                if($deviloation != Null){
                    $update_detect = DetectShift::where('id',$deviloation->id)
                    ->update($update);
                           
            
                    $DetectShift = new DetectShift();
                    $DetectShift->employee_id = $request['employee_id'];
                    $DetectShift->clock_in_time = date('H:i:s');
                    $DetectShift->shift_id = $SHIFT['id'];
                    $DetectShift->checked_id = $value;
                    $DetectShift->onShift = 'true';
                    $DetectShift->event_type = 'clockin';
                    $DetectShift->date= date('Y-m-d');
                    $DetectShift->save();

                    $event = broadcast(new OnNow($DetectShift))->toOthers();

                    if($DetectShift){
                        $response  = [ 'status' =>true,'message'=>'You are in your location','data'=>$DetectShift];
                    }else{
                        $response  = ['status' =>false,'message'=>'You are not in your location'];
                    }
                }else{
                    $response = ['status' =>false, 'message'=>'Your are Not Clockin'];
                } 
        }else{
            $response = ['status' =>false, 'message'=>'Your are Not Clockin'];
        }
        return response($response);
    }


    /***
     *  CHECK USER SHIFT ACTIVE OR NOT 
     *  THEN CHECK USER out GEOFENCE  
     *  @GEOFENCE PROPERTY
     * 
     */
    public function DetectClockOutEmployeePosition(Request $request){

        $response = ['status'=>false,'message'=>'Something Wrong'];

        $clockin_id = Clockin::where('employee_id', $request['employee_id'])
            ->where('shiftaction','=','true')
            ->whereDate('created_at',Carbon::today()->toDateString())
            ->orderByDesc('id')
            ->groupBy('employee_id')
            ->first();

        if(isset($clockin_id) && $clockin_id != NULL){
            $value = $clockin_id->id;
        
            // CHECK CURRENT USER SHIFT ACTIVE
            $employee = $this->TodaydateShifIsExist($request['employee_id']);
            $SHIFT =null;
        
            foreach($employee as $shiftdata):

                if($this->isThisCurrentShift($shiftdata)){
                    $SHIFT = $shiftdata;
                }                           
            endforeach;          
            
                // // check here if user traveling
            $break_violation = DetectShift::where('employee_id',$request['employee_id'])
                ->where('checked_id',$value)
                ->orderBy('id','desc')
                ->whereDate('created_at',Carbon::today()->toDateString())
                ->first();

            if($break_violation != null &&  $break_violation->event_type == 'clockin'){
        
                $DetectShift = new DetectShift();
                $DetectShift->employee_id = $request['employee_id'];
                $DetectShift->clock_out_time = date('H:i:s');
                $DetectShift->shift_id = $SHIFT['id'];
                $DetectShift->checked_id =  $value;
                $DetectShift->onShift = 'true';
                $DetectShift->event_type = 'perimeterviolation';
                $DetectShift->date = date('Y-m-d');
                $DetectShift->save();

                $event = broadcast(new OnNow($DetectShift))->toOthers();

                if($DetectShift){
                    $response  = ['status' =>true, 'message'=>'Your are in your location', 'data'=>$DetectShift];
                }else{
                    $response  = ['status' =>false, 'message'=>'Not on site'];
                }
            }else{
                $response  = ['status' =>false, 'message'=>'Not on site'];
            } 
        }else{
            $response  = ['status' =>false, 'message'=>'Your are not clockin'];
        }
        return response($response);
    }

	
    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }


    function sortByOrder($a, $b) {
        $t1 = strtotime($a['created_at']);
        $t2 = strtotime($b['created_at']);
        return $t2 - $t1;
		//return $a['id'] - $b['id'];
	}

    /*
    * employee logs
    */
    public function employee_logs_details(Request $request)
    {
        try{
            $response = ['status' =>false,'message' =>'Something wrong'];
           
            $Clockin =  DetectShift::with('employee','Shift.property')
            ->where('employee_id', $request->employee_id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->orderBy('created_at', 'DESC')
            ->get()->toArray();

            // HOURLY LOGS 
            $hourlySQL = Reportslog::with('employee','property','report');
			$hourlySQL->where('report_type_id', 1);
			$hourlySQL->where('employee_id', $request->employee_id);
			$hourlySQL->whereDate('date', Carbon::today()->toDateString());
            $hourlySQL->orderBy('created_at', 'DESC');
			$hourly = $hourlySQL->get()->toArray();
           
            // INCIDENT LOGS
            $incidentSQL = Reportslog::with('employee','property','report');
			$incidentSQL->where('report_type_id', 2);
			$incidentSQL->where('employee_id', $request->employee_id);
			$incidentSQL->whereDate('date', Carbon::today()->toDateString());
            $incidentSQL->orderBy('created_at', 'DESC');
			$incident = $incidentSQL->get()->toArray();
           
            // PARKING TICKECT
            $parking = EmployeeParking::with('client','vechicletype','vechiclemake','vechiclemodel')
            ->where('employee_id',$request->employee_id)
            ->whereDate('created_at', Carbon::today()->toDateString())
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();           
           
             $getnew1 = array_merge($Clockin,$hourly);
             $getnew2 = array_merge($incident,$parking);
            //$newAry = [...$Clockin, ...$hourly, ...$incident, ...$parking];
            $newAry = array_merge($getnew1,$getnew2);
            // echo "<pre>"; print_r($newAry); die();
            usort($newAry, [$this,'sortByOrder']);
    

            if(count($newAry) > 0):
                $response= ['status' =>true, 'message'=>'Your Daily Logs', 'data'=> $newAry];
            else :
                $response = ['status' =>false, 'message' =>'No Record Found!'];
            endif;
            
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
       
    }

    
    /*
    * employee week off schedule
    */
    public function TimeOffRequest(Request $request){
        try{
            $response =  ['status'=>false,'message'=>'Something wrong'];

            $EmployeeController = new EmployeeController;
            $current_shift = $EmployeeController->CurrentShiftReturn($request['employee_id']);
            if($current_shift):
                // time off schedule request 
                $time_off_date = json_decode($request['time_off']);
                               
                $implode_diffrent = implode('~', $time_off_date);
                
                $Timeoff = new Timeoff ();
                $Timeoff->employee_id = $request['employee_id'];
                $Timeoff->description = $request['description'];
                $Timeoff->time_off =  $implode_diffrent;
                $Timeoff->status = "pending";
                $Timeoff->whole_day = $request['whole_day'];
                $Timeoff->from_time = $request['from_time'];
                $Timeoff->to_time = $request['to_time'];
                $Timeoff->save();

                if($Timeoff):

                    $employee = Employee::where('id', $request->input('employee_id'))->first();
                    $subject ='Time Off Request';
                    $message = 'Your Time Off Request is pending';
                    $include_player_id =[$employee['employee_player_id']];  

                    $usershift = new WholeAppNotification();
                    $app_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2'; 
                    //$app_id = getenv('ONESIGNAL_APP_ID');          
                    $usershift->PushNotification($app_id,$include_player_id, $subject,$message);

                    // insert NOTIFICATION HERE
                    $notification = [
                        'employee_id'=>$request['employee_id'],
                        'subject'=> $subject,
                        'description'=> $message,
                    ];
                    Notifications::create($notification);


                    $response =['status'=> true,'message'=>'Time Off Request insert successfully','response'=>$Timeoff];
                else:
                    $response = ['status'=>false, 'message'=>'Time off Request not send! Try Again'];
                endif;
            else:
                $response = ['status' => false,'message'=>'Currently No Active Shift ']; 
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /**
     * @send monthly schedules 
     */  
    public function MonthSchedule($employee_id){
        try{

        $response=['status'=>false,'message'=>'something wrong']; 
        $date = Carbon::today();

        //dd(date('Y-m-d', strtotime(date('Y-m')." -3 month")));
         
        //$start = date("Y-m-d", strtotime("first day of previous month"));
        $threemonthstart = date('Y-m-d', strtotime(date('Y-m')." -3 month"));
        $currentend = Carbon::now()->endOfMonth()->toDateString() ;

        $shifts = Shift::with(['employee'=>function($q) use($employee_id){
            $q->where('id', $employee_id);
        },'property',
            'employee.timeoff'=>function($q){
                $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
                $q->where('status', 'approved');
            }
        ])
        ->whereBetween('added_to', [$threemonthstart,$currentend ])
        ->where('employee_id',$employee_id)
        ->whereNull('deleted_at')
        ->get()
        ->toArray();
        
        
        if(count($shifts) > 0):         
            $send_schedule = [];
            $send_timeoff = []; 
            $listing = [];    
               
            // send here the user shift data
                         
            foreach($shifts as $shift):
                $dates =  explode("~", $shift['added_to']);
                //DATE BY SHIFT DETAILS 
                for($i = 0; $i < count($dates); $i++) {
                    if($threemonthstart <= $dates[$i] ){
                        $send_schedule[$dates[$i]][] = $shift;
                    }                       
                }

                 // TIME OFF SCHEDULE
                foreach($shift['employee']['timeoff'] as $time):
                    $timeofed = explode('~',$time['time_off']);
                    $timeoff = array_unique($timeofed);

                    for($i = 0; $i < count($timeoff); $i++) {
                        $send_timeoff[$timeoff[$i]] = $time;
                    }                  
                endforeach;    
            endforeach;  
                                         
            // CHECK TIME OFF IS APPROVED OR NOT 
            $timeoff_key = [];
            foreach($send_timeoff as $key=>$schedule):
                if($schedule['whole_day'] == 'true'):
                    $timeoff_key[] = $key;
                endif;
            endforeach;
           
            // IF TRUE REURN THE DATE OFF HOLIDAY
            foreach($send_schedule as $key=>$schedule):
                //dd($timeoff_key);
                if(!in_array($key, $timeoff_key)):
                   $listing[$key] = $schedule; 
                endif;
            endforeach;

                if(count($send_schedule) > 0 || count($listing) > 0  || count($send_timeoff) > 0  ):
                    
                        $response =['status'=>true,'message'=>'Monthly schedule sds','send_schedule'=>$send_schedule,'send_timeoff' =>$send_timeoff,'listing'=>$listing];             
                                                          
                else:
                    $response = ['status'=>false,'message'=>'This month No schedule']; 
                endif;
            else:
                $response = ['status'=>false,'message'=>'This month No schedule'];     
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
   	}

     /**
     * @send DaySchedule schedules 
     */  
    public function dayshiftdata($employee_id){
        try{

        $response=['status'=>false,'message'=>'something wrong']; 
        $date = Carbon::today();

        //dd(date('Y-m-d', strtotime(date('Y-m')." -3 month")));
         
        //$start = date("Y-m-d", strtotime("first day of previous month"));
        $threemonthstart = date('Y-m-d', strtotime(date('Y-m')." -3 month"));
        $currentend = Carbon::now()->endOfMonth()->toDateString() ;

        $shifts = Shift::with(['employee'=>function($q) use($employee_id){
            $q->where('id', $employee_id);
        },'property',
            'employee.timeoff'=>function($q){
                $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
                $q->where('status', 'approved');
            }
        ])
        ->whereBetween('added_to', [$threemonthstart,$currentend ])
        ->where('employee_id',$employee_id)
        ->whereNull('deleted_at')
        ->get()
        ->toArray();
        
        
        if(count($shifts) > 0):         
            $send_schedule = [];
            $send_timeoff = []; 
            $listing = [];    
               
            // send here the user shift data
                         
            foreach($shifts as $shift):
                $dates =  explode("~", $shift['added_to']);
                //DATE BY SHIFT DETAILS 
                for($i = 0; $i < count($dates); $i++) {
                    if($threemonthstart <= $dates[$i] ){
                        $send_schedule[$dates[$i]] = $shift;
                    }                       
                }

                 // TIME OFF SCHEDULE
                foreach($shift['employee']['timeoff'] as $time):
                    $timeofed = explode('~',$time['time_off']);
                    $timeoff = array_unique($timeofed);

                    for($i = 0; $i < count($timeoff); $i++) {
                        $send_timeoff[$timeoff[$i]] = $time;
                    }                  
                endforeach;    
            endforeach;  
                                         
            // CHECK TIME OFF IS APPROVED OR NOT 
            $timeoff_key = [];
            foreach($send_timeoff as $key=>$schedule):
                if($schedule['whole_day'] == 'true'):
                    $timeoff_key[] = $key;
                endif;
            endforeach;
           
            // IF TRUE REURN THE DATE OFF HOLIDAY
            foreach($send_schedule as $key=>$schedule):
                //dd($timeoff_key);
                if(!in_array($key, $timeoff_key)):
                   $listing[] = $schedule; 
                endif;
            endforeach;

                if(count($send_schedule) > 0 || count($listing) > 0  || count($send_timeoff) > 0  ):
                                             
                    $response =['status'=>true,'message'=>'day schedule','send_schedule'=>$send_schedule,'send_timeoff' =>$send_timeoff,'listing'=>$listing];             
                else:
                    $response = ['status'=>false,'message'=>'This month No schedule']; 
                endif;
            else:
                $response = ['status'=>false,'message'=>'This month No schedule'];     
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /****
     *  STORE ONESIGNAL PLAYER ID IN EMPLOYEE TABLE 
     * 
     */    
    public function OneApiSignalEmployeePLayerId(Request $request){
        try{
            $response= ['status'=>false,'message'=>'Something Wrong'];
    
            $update = ['employee_player_id' => $request->player_id ];
            $emplyee_records = Employee::where('id',$request->employee_id)->update($update);
            if($emplyee_records){
                $response = ['status'=>true,'message'=>'Record Update'];
            }else{
                $response = ['status'=>false,'message'=>'Record Not Update'];
            }    
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }
    
   
    /**
     * EMPLOYEE ADD REPORTS
     */
    public function EmployeeAddReport(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];

            $shift = $this->TodaydateShifIsExist($request['employee_id']);
           
            /*$property = [];
            $SHIFT = [];
            
            if($shift):        

                foreach($shift as $shiftdata):
                    //dd($shiftdata);
                    $current_time = date('H:i:s');
                    if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                        $property[] = $shiftdata['property'];
                        $SHIFT[] = $shiftdata;
                    endif;
                endforeach;
                
            else:
                $response = ['status'=>false,'message'=>'Your shift has not started'];
            endif;*/

            $current_shift = Shift::with(['employee','property'])
            ->where('id',$request['locationId'])
            ->whereNull('deleted_at')
            ->first()->toArray();
           
            if($current_shift){

                //USER CLOCK IN THEN SUBMIT REPORT 
                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                ->where('shiftaction','=','true')
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->orderByDesc('id')
                ->groupBy('employee_id')
                ->first();
               if($clockin_id != null){

                    // UPLOAD IMAGE HERE
                    $names= [];
                    $full_path =[]; 
                    if ($request->input('report_image')) {
                        $files = $request->input('report_image');
                        //foreach($files as $file):
                        //dd($file);
                        $name =  uniqid().time().'.jpg';                
                        $store_with_paths =  Storage::disk('public')->put('reports/'.$name,base64_decode($files));
                        $names[] =$name;
                        $full_path[]  = Storage::url("public/reports/{$name}");
                        //endforeach;
                    }
                
                    $reports_img = implode(",",$full_path);
                    if(empty($reports_img)){
                        $reports_img = '';
                    }
            
                    // MAKE A TIME SLOT
                    $slot = '';           
                    if($request->report_type_id == '1'):
                        $start_time = date('H:00');   
                        $end_time = date('H:00', strtotime($start_time) + 3600);

                        if(empty($request->slot)):
                            $slot = date('H:i A',strtotime($start_time)).' - '.date('H:i A',strtotime($end_time));
                        else:
                            $slot = $request->slot;
                        endif;
                    endif;
                   
                    // INSERT LOG HERE 
                    $report = new Reportslog();
                    $report->employee_id = $request->employee_id;
                    $report->client_id = $current_shift['property']['client_id'];
                    $report->property_id = $current_shift['property']['id'];
                    $report->report_type_id = $request->report_type_id;
                    $report->location =  $current_shift['property']['address'];
                    $report->description = $request->description;
                    $report->shift_id = $request['locationId'];
                    $report->report_subtype_id = $request->report_subtype_id;
                    $report->police_report = $request->police_report;
                    $report->police_number = $request->police_number;
                    $report->report_image = $reports_img;
                    $report->range = $request->range;
                    $report->date = date('Y-m-d');
                    $report->time = date('H:i:s');
                    $report->slot = $slot;
                    $report->status = "pending";
                    $report->save();
                   
                    if($report){
                        if($request->report_type_id == 1){

                            /*** INSERT HOURLY LOGS *******/
                            // $DetectShift = new DetectShift();
                            // $DetectShift->employee_id = $request['employee_id'];
                            // $DetectShift->clock_out_time = date('H:i:sa');
                            // $DetectShift->shift_id = $SHIFT[0]['id'];
                            // $DetectShift->checked_id = $clockin_id->id;
                            // $DetectShift->onShift = 'true';
                            // $DetectShift->event_type ='hourly';
                            // $DetectShift->date = date('Y-m-d');
                            // $DetectShift->save();
                            /*** END HOURLY LOGS *******/
                            $employee = Employee::where('id',$request['employee_id'])->first();
                            $player_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
                            $subject = "Add Hourly Report ";
                            $message = "New Hourly Report Added ";
                            if($employee['employee_player_id'] != ''):
                                // send Notification by custom method
                                $addreportnotification = new WholeAppNotification();
                                $addreportnotification->PushNotification($player_id,$employee['employee_player_id'],$subject,$message);
                                // INSERT NOTIFICATION
                                $notification = [
                                    'employee_id'=>$employee['id'],
                                    'subject'=> $subject,
                                    'description'=> $message,
                                ];
                                Notifications::create($notification);
                            endif;

                            $getreport = $this->RepeatHourlyReport($request->employee_id, $property[0]['client_id']);
                            $response = ['status' =>true,'message'=>'Your Hourly Report has been submitted successfully!', 'data'=>$getreport];
                        }else if($request->report_type_id == 2){
                            /*** INSERT INCIDENT LOGS *******/
                            // $DetectShift = new DetectShift();
                            // $DetectShift->employee_id = $request['employee_id'];
                            // $DetectShift->clock_out_time = date('H:i:sa');
                            // $DetectShift->shift_id = $SHIFT[0]['id'];
                            // $DetectShift->checked_id = $clockin_id->id;
                            // $DetectShift->onShift = 'true';
                            // $DetectShift->event_type ='incident';
                            // $DetectShift->date = date('Y-m-d');
                            // $DetectShift->save();
                            /*** END INCIDENT LOGS *******/
                            
                            $incident = $this-> RepeatIncidentReport($request->employee_id);

                            $employee = Employee::where('id',$request['employee_id'])->first();
                            $player_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
                            $subject = "Add incident Report ";
                            $message = "New Incident Report Added ";
                            if($employee['employee_player_id'] != ''):
                                // send Notification by custom method
                                $addreportnotification = new WholeAppNotification();
                                $addreportnotification->PushNotification($player_id,$employee['employee_player_id'],$subject,$message);
                                // INSERT NOTIFICATION
                                $notification = [
                                    'employee_id'=>$employee['id'],
                                    'subject'=> $subject,
                                    'description'=> $message,
                                ];
                                Notifications::create($notification);
                            endif;

                            $response = ['status' =>true,'message'=>'Your Incident Report has been submitted successfully!', 'data'=>$incident];
                        }              
                    
                    }else{
                        $response = ['status' =>false, 'message'=>'Your Report not insert Successfully. Please try!'];
                    }
                }else{
                    $response = ['status'=>false,'message'=>"You're not clocked in"];
                }
            }else{
                $response = ['status'=>false,'message'=>'Your shift has not started'];
            }      
        return response($response);

        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    
    public function testalertreport(Request $request){
        $fullreport_path =[];
        $files = ['/storage/alertreport/631b6edebeab01662742238.jpg','/storage/alertreport/631b6edebef3d1662742238.jpg','/storage/alertreport/631b6edebf1211662742238.jpg'];
        //$getfiles = $files['img'];
        foreach($files as $file):
            $fullreport_path[] = 'http://staging.admin.trakastar.com'.$file;
        endforeach;
        // send reports on email
        $fullreport_pathdata = implode(" , ",$fullreport_path);
        if(empty($fullreport_pathdata)){ $fullreport_pathdata = '';  }

        $request->employee_id = 27;
        $request->description = '6 images';
        $request->category = 'Incident Report';

        $employee = Employee::where('id', $request->employee_id)->first();
        $getClientId = $employee->added_by;
        if($getClientId!=null){
            $clientdata = Client::where('id', $getClientId)->first();
            if($clientdata){
                $data= ['data_images'=>$fullreport_pathdata,'description'=>$request->description,'category'=>$request->category];
                Mail::to($clientdata->email)->send(new EmployeeAlertReport($data));
            }
        }else{
            $data= ['data_images'=>$fullreport_pathdata,'description'=>$request->description,'category'=>$request->category];
            Mail::to('ashishdevswami@gmail.com')->send(new EmployeeAlertReport($data));
        }
        $response = ['status' =>true,'message'=>'Your Alert Report has been submitted successfully!'];
        return response($response);
    }

    /**
     * addalertreport
     */
    public function addalertreport(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];
            // UPLOAD IMAGE HERE
            $names= [];
            $full_path =[];
            $fullreport_path =[];
            if ($request->input('galaryImage')) {
                $files = $request->input('galaryImage');
                //$getfiles = $files['img'];
                foreach($files as $file):
                //dd($file);
                    $name =  uniqid().time().'.jpg';                
                    $store_with_paths =  Storage::disk('public')->put('alertreport/'.$name,base64_decode($file));
                    $names[] =$name;
                    $full_path[]  = Storage::url("public/alertreport/{$name}");
                    $fullreport_path[] = 'http://staging.admin.trakastar.com'.Storage::url("public/alertreport/{$name}");
                endforeach;
            }

            // if ($request->input('cameraImage')) {
            //     $files = $request->input('cameraImage');
            //     $getfiles = $files['img'];
            //     foreach($getfiles as $file):
            //     //dd($file);
            //         $name =  uniqid().time().'.jpg';                
            //         $store_with_paths =  Storage::disk('public')->put('alertreport/'.$name,base64_decode($file));
            //         $names[] =$name;
            //         $full_path[]  = Storage::url("public/alertreport/{$name}");
            //     endforeach;
            // }

             

        
            $reports_img = implode(",",$full_path);
            if(empty($reports_img)){ $reports_img = '';  }

            $fullreport_pathdata = implode(" , ",$fullreport_path);
            if(empty($fullreport_pathdata)){ $fullreport_pathdata = '';  }
            
            // INSERT LOG HERE 
            $report = new ReportAlert();
            $report->employee_id = $request->employee_id;
            $report->description = $request->description;
            $report->category = $request->category;
            $report->data_images = $reports_img;
            $report->updated_at = date('Y-m-d H:i:s');
            $report->created_at = date('Y-m-d H:i:s');
            $report->save();

            

            // send reports on email
            $employee = Employee::where('id', $request->employee_id)->first();
            $getClientId = $employee->added_by;

            //assign report to there
            // $property = new ClientAlertReport();
            // $property->client_id = $getClientId;
            // $property->report_id = $report->id;
            // $property->save();

            //assign report to 
            $clientlistnearzip = Client::where('zip', $employee->zip)->get();
            foreach($clientlistnearzip as $clientlistnearzipval){
                $property = new ClientAlertReport();
                $property->client_id = $clientlistnearzipval->id;
                $property->report_id = $report->id;
                $property->save();
            }

            if($getClientId!=null){
                $clientdata = Client::where('id', $getClientId)->first();
                if($clientdata){
                    $data= ['data_images'=>$fullreport_pathdata,'description'=>$request->description,'category'=>$request->category];
                    Mail::to($clientdata->email)->send(new EmployeeAlertReport($data));
                    if($clientdata->police_email!=""){
                        Mail::to($clientdata->police_email)->send(new EmployeeAlertReport($data));
                    }
                }
            }else{
                $data= ['data_images'=>$fullreport_pathdata,'description'=>$request->description,'category'=>$request->category];
                Mail::to('richard@trakastar.com')->send(new EmployeeAlertReport($data));
            }

            $response = ['status' =>true,'message'=>'Your Alert Report has been submitted successfully!'];
            return response($response);

        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /**
     * GET HERE ALL REPORT TYPE 
     * 
     * */ 
    public function GetAllReportSubType(){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];

            $ReportSubType = ReportSubType::all();
            if(count($ReportSubType) > 0 ){
                $response =['status' =>true,'message'=> 'All SubType Report','data'=> $ReportSubType];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /***
     *  GET HERE ALL REPEAT HOURLY REPORT
     * 
     */
    public function RepeatHourlyReport($employee_id,$client_id){
        try{

            $response =['status'=>false,'message'=>'Something wrong'];
              
            $report = Reportslog::with('employee','property','report')
                    // ->where(function($q) {
                        ->where('report_type_id','=', 1)
                        ->Where('employee_id', $employee_id)
                        ->Where('client_id', $client_id)
                        ->whereDate('date', Carbon::today()->toDateString());
                    //});
                $data = $report->get()->toArray();            
            // dd($data);
                if(count($data) > 0):
                    $EmpArray = []; 
                    foreach($data as $key=>$d):                    
                        $EmpArray[$d['slot']][] = $d; 
                    endforeach;
                   return $EmpArray;
                else:
                   return false;
                endif;       
            return response($response);
        }catch (\Exception $e) {
            return false; 
        }
    }


    
     /**
     * GET EMPLOYEE ID BY INCIDENT REPORT
     * 
     */
    public function RepeatIncidentReport($employee_id){
        $response =['status'=>false,'message'=>'Something wrong'];
            $report = Reportslog::with('employee','property','report')
                    ->where('report_type_id','=', 2)
                    ->Where('employee_id','=',$employee_id)
                    ->whereDate('date',Carbon::today()->toDateString())
                    ->get()
                    ->toArray();
            if(count($report) > 0):
                return $report;
            endif;
        return response($response);       
    }


    /***
     *  GET CURRENT SHIFT PROPERTY
     * 
     */
    public function CurrentShiftProperty($employee_id){
       
        $shift = $this->TodaydateShifIsExist($employee_id);

        $property = [];
        if($shift):     

            foreach($shift as $shiftdata):
                //dd($shiftdata);
                $current_time = date('H:i:s');
                if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                    return  $property[] = $shiftdata['property'];
                endif;
            endforeach;
                  
        endif;
    }

    /***
     * RETURN CURRENT SHIFT
     * 
     */

    public function CurrentShiftReturn($employee_id){

        $shift = $this->TodaydateShifIsExist($employee_id);
        // $current_time = date('H:i:s');
        // dd($shift,$current_time);
        $SHIFT = [];
        if($shift):      

            foreach($shift as $shiftdata):
                $current_time = date('H:i:s');
                if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                    return  $SHIFT[] = $shiftdata;
                endif;
            endforeach;        
        endif;
    }
   
    /*
    * GET HOURLY REPORT 
    */
    public function GETHourlyReport($employee_id){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
                     
            $property = $this->CurrentShiftProperty($employee_id);
            $hourly = $this->RepeatHourlyReport($employee_id,$property['client_id']);
                          
                if($hourly):                   
                    $response = ['status' =>true, 'message' =>'Record Found!', 'data'=> $hourly];
                else:
                    $response = ['status' =>false,'message' =>'No hourly Record Found!'];
                endif;       
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


     /**
     * GET EMPLOYEE ID BY INCIDENT REPORT
     * 
     */
    public function GetIncidentReport($employee_id){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];

            $report = Reportslog::with('employee','property','report')
                    ->where('report_type_id','=', 2)
                    ->Where('employee_id','=',$employee_id)
                    ->whereDate('date',Carbon::today()->toDateString())
                    ->get()
                    ->toArray();

            if(count($report)> 0 ):
                $response = ['status'=>true,'message'=>'Record Found','data'=>$report];
            else:
                $response = ['status'=>false,'message'=>'No Incident Record Found!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /****
     *  SELECT ONLY ASSIGN CLIENT PROPERTY NAME SHOW IN DROPDOWN
     *  SHOW LOCATION  
     */
    public function  ShowAssignShitClientPropertyLocation($employee_id){
        try{
            
            $property =  ShiftEmployee::with(['employee', 'shift','shift.property'])
            ->Where(function($q){
                    $q->whereHas('shift',function($query) {
                        $query->where('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%");  
                    });
                })
            ->where('employee_id', $employee_id)
            ->get()
           ->toArray();
            if($property):
                $response = ['status' =>true, 'message'=>'Your current Shift Location' ,'property'=>$property];        
            else:
                $response = ['status' =>false, 'message'=>'Your shift has not started'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


     /****
     *  SELECT ONLY ASSIGN CLIENT PROPERTY NAME SHOW IN 
     *  SHOW LOCATION  
     */
    public function  ShowCurrentShiftPropertyLocation($employee_id){
        try{
            $property = $this->CurrentShiftProperty($employee_id);

            if($property):
                $response = ['status' =>true, 'message'=>'Your current Shift Location' ,'property'=>$property];        
            else:
                $response = ['status' =>false, 'message'=>'Your shift has not started'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }
  

    /***
     *  Time off schedule
     * 
     */
    public function TimeOffScheduleslog($employee_id){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
            // EMPLOYEE ID BY RETURN TIME OFF REQUEST
            $timeoff = Timeoff::where('employee_id',$employee_id)
            ->orderBy('created_at','desc')
            ->pluck('time_off');
           //  DECLARE EMPTY ARRAY HERE
            $export_date = [];
            foreach($timeoff as $timedate):
                // MERGE ARRAY AND RETURN IN ONE ARRAY
                $export_date = array_merge(explode('~',$timedate),$export_date); 
            endforeach;
            $timeofflogs = [];
           
            foreach($export_date as $date):                               
                for($i = 0; $i < count($export_date); $i++) {
                    $timeofflogs[$date] = Timeoff::where('employee_id',$employee_id)
                    ->where('time_off','LIKE',"%$date%")
                    //->orderBy('id','desc')
                    ->get()
                    ->toArray();
                }                   
                         
            endforeach;           
           // dd($timeofflogs);
            if(count($timeofflogs) > 0):
                $response = ['status' =>true,'message' =>'Your Day/Time Schedule!','data'=> $timeofflogs];
            else:
                $response = ['status' =>false,'message' =>'No Day/Time Schedule Record Found!'];
            endif;
           
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /***
     *  Time off schedule
     * 
     */
    public function LastShiftEntry($employee_id){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
            // EMPLOYEE ID BY RETURN TIME OFF REQUEST

            /*$clockin_id = Clockin::where('employee_id', $employee_id)
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->latest()
                    ->first();*/

            $clockin_id = DetectShift::where('employee_id',$employee_id)                   
                ->whereDate('created_at',Carbon::today()->toDateString())
                ->latest()
                ->first();

            if($clockin_id):
                $response = ['status' =>true,'message' =>'Your Last Today Schedule!','data'=> $clockin_id];
            else:
                $response = ['status' =>false,'message' =>'No Day/Time Schedule Record Found!'];
            endif;
           
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /***
     * VERIFICATION CODE SEND BY EMAIL
     */
    public function VerifictionCodeSendByEmail(Request $request){
        try{
          
            $response =['status'=>false,'message'=>'Something wrong'];
            //Validate requested data
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()):
                $response =['status'=>false, 'message'=>$validator->errors()->all()];
            else:
                $employee = Employee::where('email', $request->email)->first();
    
                if($employee):
                    $OTP = rand(1000,9999);
                    $update_data = ['verification_code'=>$OTP,'isVerified' => false];

                    $data= ['verification_code'=>$OTP];
                    
                    $update = Employee::where('email', $request->email)->update($update_data);
                    if($update):
                        Mail::to($request->email)->send(new EmployeeSendOTP($data)); 
                        $response =['status'=>true, 'message'=>'OTP Send on your email. Please check!'];
                    else:
                        $response =['status'=>false, 'message'=>'Sorry, Email not send!'];
                    endif;
                else:
                    $response =['status'=>false, 'message'=>'User not exist by this email!'];
                endif;            
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /***
    * CHECK otp IS vALID OR NOT 
    *
    */
    public function matchOTP(Request $request){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];

            $employee = Employee::where('email', $request->email)->first();
            
            if($employee):
                if($employee->verification_code == $request['verification_code']):
                    $response =['status'=>true, 'message'=>'OTP matched.'];
                else:
                    $response =['status'=>false, 'message'=>'OTP Not match!please try again'];
                endif;
            else:
                $response =['status'=>false, 'message'=>'User not exist by this email!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
      }
      

    /***
     * EMPLOYEE FORGET PASSWORD  
     */
    public function ForgetPassword(Request $request){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
            $employee = Employee::where('email', $request->email)->first();

            if($employee):
                $updade_data = ['password' =>Hash::make($request->password),'isVerified' => true];
                $update = Employee::where('email', $request->email)->update($updade_data);
                $data = ['password'=>$request->password];
                if($update):
                    Mail::to($request->email)->send(new EmployeeResetPassword($data)); 
                    $response =['status'=>true,'message'=>'Your Password Update Successfully!!'];
                else:
                    $response =['status'=>false,'message'=>'password not updated'];
                endif;
            else:
                $response =['status'=>false,'message'=>'Email is in valid!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


  
    public function EmployeeUpdateReport(Request $request,$report_id){
        try{   
            $response = ['status'=>false,'message'=>'Something wrong'];
              // Instantiate EmployeeController
                $EmployeeController = new EmployeeController;
                $current_shift = $EmployeeController->CurrentShiftReturn($request['employee_id']);
                if($current_shift):
                    $names=[];
                    if($request->input('report_image')):
                        $files = $request->input('report_image');
                
                        foreach($files as $file):
                            $name = '/storage/reports/'.uniqid().time().'.jpg';                     
                            $store_with_paths =  Storage::disk('public')->put('reports/'.$name,base64_decode($file['base64']));
                            $names[] = $name;
                        endforeach;
                    endif;
                    $reports_img = implode(",",$names);
                    if(empty($all_images)){
                        $all_images = '';
                    }

                    $data = [
                        'employee_id' => $request['employee_id'],
                        'client_id' => $current_shift['property']['client_id'],
                        'property_id' => $current_shift['property']['id'],
                        'report_type_id' => $request['report_type_id'],
                        'location' => $request['location'],
                        'description' => $request['description'],
                        'shift_id' => $request['shift_id'],
                        'report_subtype_id' => $request['report_subtype_id'],
                        'police_report' => $request['police_report'],
                        'police_number' => $request['police_number'],
                        'report_image' => $reports_img,
                        'range' => $request['range'],
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'slot' => $request['slot'],
                        'status' =>'pending',
                    ];
                    $report = Reportslog::where('id',$report_id)->update($data);
                    if($report):
                        $response = ['status' =>true,'message'=>'Your Report insert Successfully!', 'data'=>$report];
                    else :
                        $response = ['status' =>false, 'message'=>'Your Report not insert Successfully. Please try!'];
                    endif;
                else:
                    $response = ['status' => false,'message'=>'No shift Active']; 
                endif;        
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }




    /**
     *  Voilation Close 
     * 
     */
    public function CancelRequest(Request $request){

        $response =['status'=>false,'message'=>'Something wrong'];
        $current_shift = $this->CurrentShiftReturn($request['employee_id']);
        
            if($current_shift && $current_shift != null){

                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                    ->where('shiftaction','=','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();
               
                if($clockin_id != null){
                        $detect_violation = new DetectShift();
                        $detect_violation->employee_id = $request['employee_id'];
                        $detect_violation->clock_in_time = date('H:i:s');
                        $detect_violation->clock_out_time = date('H:i:s');
                        $detect_violation->shift_id = $current_shift['id'];
                        $detect_violation->checked_id = $clockin_id->id;
                        $detect_violation->event_type = 'cancel';
                        $detect_violation->date = date('Y-m-d');
                        $detect_violation->save();

                    if($detect_violation){
                        $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
                        $response = ['status' =>true,'message'=>'Request Cancel', 'data'=>$currentshift];
                    }else{
                        $response = ['status' =>false,'message'=>'Break not insert Successfully!'];
                    }
                }else{
                    $response = ['status' => false,'message'=>'You are not Clockin']; 
                }
            }else{

                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                    ->where('shiftaction','=','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();

                    $update =[
                        'employee_id' => $employee_id,
                        'shift_id' => $clockin_id->shift_id,
                        'end_time'=> date('H:i:s'),
                        'shiftaction' =>'false',                       
                    ];
    
                    $clockin = Clockin::where('id', $check_clock_in_id)->update($update);

                $detect_violation = new DetectShift();
                $detect_violation->employee_id = $request['employee_id'];
                $detect_violation->clock_in_time = date('H:i:s');
                $detect_violation->clock_out_time = date('H:i:s');
                $detect_violation->shift_id = $clockin_id->shift_id;
                $detect_violation->checked_id = $clockin_id->id;
                $detect_violation->event_type = 'clockout';
                $detect_violation->date = date('Y-m-d');
                $detect_violation->save();

                $response = ['status' => false,'message'=>'clockout successfully. Your shift over']; 
            }
        return response($response);
    }




    /*******
     * Voilation On Break
     *  Employee going on break 
     *  start time and end time 
     * 
     */
    public function OnBreak(Request $request){
        $response =['status'=>false,'message'=>'Something wrong'];

            $current_shift = $this->CurrentShiftReturn($request['employee_id']);
            
            if($current_shift):

                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                    ->where('shiftaction','=','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();
        
                if($clockin_id != null){

                    $break_violation = DetectShift::where('employee_id',$request['employee_id'])
                    ->where('event_type','break')
                    ->where('onShift','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();

                    if($break_violation == null){

                        $detect_violation = new DetectShift();
                        $detect_violation->employee_id = $request['employee_id'];
                        $detect_violation->clock_in_time = date('H:i:s');
                        $detect_violation->shift_id = $current_shift['id'];
                        $detect_violation->checked_id = $clockin_id->id;
                        $detect_violation->event_type = 'break';
                        $detect_violation->onShift = 'true';
                        $detect_violation->date = date('Y-m-d');
                        $detect_violation->save();
                        
                        if($detect_violation){
                            $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
                            $response = ['status' =>true,'message'=>'Break Time start!', 'data'=>$currentshift];
                        }else{
                            $response = ['status' =>false,'message'=>'Break not insert Successfully!'];
                        }
                    }else{

                        $update =[
                            'employee_id' => $request['employee_id'],
                            'clock_out_time' => date('H:i:s'),
                            'shift_id' => $current_shift['id'],
                            'checked_id' => $clockin_id->id,
                            'date' => date('Y-m-d'),
                            'event_type' =>'break',
                            'onShift' => 'false',
                        ];
                        $DetectShift =  DetectShift::where('id',$break_violation['id'])->update($update);
                        if($DetectShift){
                            $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
                            $response = ['status' =>true,'message'=>'Break Time Close!', 'data'=>$currentshift];
                        }else{
                            $response = ['status' =>false,'message'=>'Break not insert Successfully!'];
                        }
                    }     
                }else{
                    $response = ['status' => false,'message'=>'You are not Clockin']; 
                }
            else:

                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                    ->where('shiftaction','=','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();

                    $update =[
                        'employee_id' => $employee_id,
                        'shift_id' => $clockin_id->shift_id,
                        'end_time'=> date('H:i:s'),
                        'shiftaction' =>'false',                       
                    ];
    
                    $clockin = Clockin::where('id', $check_clock_in_id)->update($update);

                $detect_violation = new DetectShift();
                $detect_violation->employee_id = $request['employee_id'];
                $detect_violation->clock_in_time = date('H:i:s');
                $detect_violation->clock_out_time = date('H:i:s');
                $detect_violation->shift_id = $clockin_id->shift_id;
                $detect_violation->checked_id = $clockin_id->id;
                $detect_violation->event_type = 'clockout';
                $detect_violation->date = date('Y-m-d');
                $detect_violation->save();

                $response = ['status' => false,'message'=>'clockout successfully. Your shift over']; 
            endif;
        return response($response);
    }

     
    /***
     * Voilation on Travel
     *  Employee travel to on shift to another shift
     * 
     */
    public function onTravel(Request $request){

        $response =['status'=>false,'message'=>'Something wrong'];

        $autoclockout =$this->AutoClockout($request['employee_id']);
       
            $shift_data  =  Shift::where('id',$request['shift_id'])->first();
            $autoClockin = $this->AutoClockIn($request['employee_id'],$shift_data);

            if($autoClockin):
                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                    ->where('shiftaction','true')
                    ->whereDate('created_at',Carbon::today()->toDateString())
                    ->first();
       
                if($clockin_id != null){

                    $detect_violation = new DetectShift();
                    $detect_violation->employee_id = $request['employee_id'];
                    $detect_violation->clock_in_time = date('H:i:s');
                    $detect_violation->shift_id = $shift_data['id'];
                    $detect_violation->checked_id = $clockin_id->id;
                    $detect_violation->event_type = 'travel';
                    $detect_violation->onShift = 'true';
                    $detect_violation->date = date('Y-m-d');
                    $detect_violation->save();

                    if($detect_violation){

                        $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
                        $response = ['status' =>true,'message'=>'Travelling start!', 'data'=>$currentshift];
                    }else{
                        $response = ['status' =>false,'message'=>'Travelling not insert Successfully!'];
                    }
                }else{
                    $response = ['status' => false,'message'=>'You are not Clockin'];
                }
            endif;

        return response($response);
    }



    public function travelProperty($employee_id){
        //dd($employee_id);
        $response =['status'=>false,'message'=>'Something wrong'];
        $travel_property = Shift::with('property')
            ->where('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
            ->where('employee_id',$employee_id)->get()->toArray();

        if($travel_property){
            $response = ['status' =>true,'message'=>'Travel property', 'property'=>$travel_property];
        }else{

            $response = ['status' =>false,'message'=>'Not Travel property!'];
        }
        return response($response);
    }


    /****
     *  If user traveling 
     *  clock in employee
     * 
     */
    public function AutoClockout($employee_id){

        $current_shift = $this->CurrentShiftReturn($employee_id); 

        if($current_shift):
           
            $clockin_id = Clockin::where('employee_id', $employee_id)
                    ->where('shiftaction','true')
                    ->whereDate('created_at', Carbon::today()->toDateString())
                    ->orderByDesc('id')
                    ->groupBy('employee_id')
                    ->first();

            $check_clock_in_id = null;
            
            if($clockin_id != ''):
                $check_clock_in_id = $clockin_id->id;
            endif;
                $update =[
                    'employee_id' => $employee_id,
                    'shift_id' => $current_shift['id'],
                    'end_time'=> date('H:i:s'),
                    'shiftaction' =>'false',                       
                ];

                $clockin = Clockin::where('id', $check_clock_in_id)->update($update);
            if($clockin):
                $DetectShift = new DetectShift();
                $DetectShift->employee_id = $employee_id;
                $DetectShift->clock_out_time = date('H:i:s');
                $DetectShift->shift_id = $current_shift['id'];
                $DetectShift->checked_id = $check_clock_in_id;
                $DetectShift->onShift = 'false';
                $DetectShift->event_type = 'clockout';
                $DetectShift->date = date('Y-m-d');
                $DetectShift->save();

                return true;
            endif;
        endif;
        return false;
        
    }

     /***
      * if user traveling
      * clock out employee
      */
    public function AutoClockIn($employee_id,$shift){
        //dd($shift);
        
        $clockin = new Clockin();
        $clockin->employee_id =$employee_id;
        $clockin->start_time = date('H:i:s');
        $clockin->shift_id = $shift['id'];
        $clockin->shiftaction ='true';
        $clockin->check_date = date('Y-m-d');
        $clockin->check_start = $shift['start'];
        $clockin->check_end = $shift['start'];
        $clockin->save();         

        if($clockin):
            // $DetectShift = new DetectShift();
            // $DetectShift->employee_id = $employee_id;
            // $DetectShift->clock_in_time = date('H:i:s');
            // $DetectShift->shift_id = $shift['id'];
            // $DetectShift->checked_id = $clockin->id;
            // $DetectShift->onShift ='true';
            // $DetectShift->event_type ='clockin';
            // $DetectShift->date = date('Y-m-d');
            // $DetectShift->save();
             return true;
        endif;
        return false;
        
    }



    public function forcedClockOut(Request $request){

        $response =['status'=>false,'message'=>'Something wrong'];

        $currentshift = $this->TodaydateShifIsExist($request['employee_id']);
        $SHIFT =null;
        
        foreach($currentshift as $shiftdata):

            $current_time = date('H:i:s');
            if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                return  $SHIFT = $shiftdata;
            endif;                       
        endforeach;                   
            if($SHIFT):   
               
                $update =[
                    'employee_id' => $request['employee_id'],
                    'shift_id' => $SHIFT['id'],
                    'end_time'=> date('H:i:s'),
                    'shiftaction' =>'false',                       
                ];

                $clockin_id = Clockin::where('employee_id', $request['employee_id'])
                        ->where('shiftaction','=','true')
                        ->whereDate('created_at', Carbon::today()->toDateString())
                        ->orderByDesc('id')
                        ->groupBy('employee_id')
                        ->first();

                $clockin = Clockin::where('id', $clockin_id->id)->update($update);
                
                $DetectShift = new DetectShift();
                $DetectShift->employee_id = $request['employee_id'];
                $DetectShift->clock_out_time = date('H:i:s');
                $DetectShift->shift_id = $SHIFT['id'];
                $DetectShift->checked_id = $check_clock_in_id;
                $DetectShift->onShift = 'false';
                $DetectShift->event_type ='clockout';
                $DetectShift->date = date('Y-m-d');
                $DetectShift->save();
                if($DetectShift):
                    $response = ['status' =>true,'message'=>'Clock out Successfully'];
                else:
                    $response = ['status' =>false,'message'=>'Clock out not Successfully'];
                endif;
            else:
                $response = ['status' =>false,'message'=>'Your shift has not started'];
            endif;

        return response($response);
    }



    public function CheckErrorInAPI(Request $request){
        // $current_time = date('H:i:s');
        // $data = ['time'=>$current_time, 'log_status'=>$log_status];
        
        $logs = Logs::create($request->all());
        if($logs):
            $response = ['status' =>true,'message'=>'Error in api'];
        else:
            $response = ['status' =>false,'message'=>'Error not in api'];
        endif;
        return response($response);

    }




    
}
