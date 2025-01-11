<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\ShiftEmployee;
use App\Models\Shift;
use App\Models\Reportslog;
use App\Models\Employee;
use App\Models\ReportSubType; 
use App\Mail\ClientMessageToEmployee;
use App\Invoices;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Auth;
use Exception;
use Carbon\Carbon;
use JWTFactory;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth; // Ensure this import is present
use JWTAuthException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;




class ClientWebController extends Controller
{
    protected $redirectTo = '/client_dashboard';
    
    protected function redirectTo($request)
    {
        return route('login');
    }
       
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['client-login', 'register']]);
       // $this->middleware('jwt.auth');
        
    }


    protected function guard()
	{
	    return Auth::guard('clients');
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
            'expires_in' => auth()->guard('clients')->factory()->getTTL() * 3600, 
            'user' => auth()->guard('clients')->user()
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


    public function register(Request $request)
    {

       // Validate requested data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:tblm_clients',
            'password' => 'required|string|min:6',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }     
     
        $user = Client::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
        
    }

	/**
	* client web login check infomation and redirect 
	*/
	public function clientLogin(Request $request){
        //dd("reach");
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $client = Client::where('email', $request->email)->first();
        
        if ($client) {
            if (Hash::check($request->password, $client->password)) {
        
                if (!$token = auth()->guard('clients')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return response()->json(['message' => 'Unauthoriz'], 401);
                }
                return $this->createNewToken($token);
            } else {
                $response = ["message" => "Password is incorrrect"];
                return response($response);
            }
        } else {
            $response = ["message" =>'Email is not found'];
            return response($response);
        }

       
    }

    /**
    * client Logout
    */
    public function Clientlogout(Request $request) {
        auth()->guard('clients')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->guard('clients')->refresh());
    }



    /**
     *  CLIENT GET OUR PROPERTIES WITH CURRENT SHIFT ASSIGN
    */
    public function GetOurProperties(Request $request,$client_id){
        
        // $client_id = 1;
        $date = Carbon::now();
        $current_date = $date->toDateString();

        $start_week = Carbon::now()->startOfWeek();
        $end_week = Carbon::now()->endOfWeek();
        // dd($start_week,$end_week);

        //echo "sds"; die();
        $response = ['status'=>false,'message'=>'Something Wrong'];
        if($client_id == '' && $client_id == null){
            $response = [ 'status'=>false,'message'=>'Request paramters are missing'];
        }else{           
            $property = ClientProperties::with(['client','shift'=>function($q) use($start_week,$end_week){
                $q->whereBetween('added_to',[$start_week,$end_week]);
            },'shift.employee'])
            ->where('client_id',$client_id)
            ->get();
            $property_data = $property->toArray();
          
            if(!empty($property_data)){
                // send property data in one array 
                $prop_data = [];
                foreach($property_data as $prop){
                    //dd($prop);
                    $properties = [];
                    $properties['properties_id'] = $prop['id'];
                    $properties['properties_name'] = $prop['name'];
                    $properties['properties_address'] = $prop['address'];
                    $properties['client_id'] = $prop['client']['id'];
                    $properties['client_name'] = $prop['client']['clientname'];
                    $properties['client_address'] = $prop['client']['address'];
                    $properties['client_email'] = $prop['client']['email'];
                    $properties['client_phone'] = $prop['client']['phone'];
                    $employees = [];
                    
                    foreach($prop['shift'] as $value){                                                                              
                        $employee=[];
                        $employee['emp_id'] = $value['employee']['id'];
                        $employee['emp_firstname'] = $value['employee']['firstname'];
                        $employee['emp_lastname'] = $value['employee']['lastname'];
                        $employee['emp_email'] = $value['employee']['email'];
                        $employees[]  = $employee;
                    }
                    $properties['employees'] = $employees;
                    $prop_data[] = $properties;
                }
                $response = ['status'=>true, 'message'=>'current date shift info','data'=>$prop_data];
            }else{
                $response = ['status'=>false,'message'=>'No nay user shift'];  
            }              
        }
        return response($response);
    }

    /**
     *  ADDING PROPERTIES BY CLIENT
    */
    public function AddClientProperties(Request $request){
        $response = array('status'=>false,'message'=>'Problem in code');
        $client_id = $request->client_id;
        if($client_id == '' && $client_id == null){
            $response = array(
                'status'=>false,
                'message'=>'Request paramters are missing'
            );
        }else{
            $property = new ClientProperties();
            $property->client_id = $client_id;
            $property->name = $request->name;
            $property->address = $request->address;
            $property->lat = $request->lat;
            $property->long = $request->long;
            $property->coordinates = ($request->coordinates == null) ? '' : json_encode($request->coordinates); 

            if($property->save() ){
                $response = ['status' => true,'message' =>'New Property added successfully','data' => $property];
            }else{
                $response =['status' => false,'message' =>'New Property Not added successfully'];   
            }
        }
        return response($response);
    }


     /**
     *  UPDATE PROPERTIES BY CLIENT
    */
    public function UpdateClientProperties(Request $request){
        $property_id = $request->property_id;
        $response = array('status'=>false,'message'=>'Problem in code');
        if($property_id == '' && $property_id == null){
            $response =['status'=>false,'message'=>'Request paramters are missing'];
        }else{
            $property = ClientProperties::find($property_id);
            if(!empty($property)){
                
                $update = array(
                    'name'=>$request['name'],
                    'address'=>$request['address'],
                    'lat'=> $request['lat'],
                    'long'=>$request['long'],
                    'coordinates'=> ($request['coordinates'] == null) ? '' : json_encode($request['coordinates']),
                );
                $updated =  ClientProperties::where('id',$property_id)-> update($update);

                if($updated ){               
                    $response = array(
                        'status' => true,
                        'message' =>'Property updated successfully',
                        'data'=> $updated  
                    );
                }else{
                    $response = array(
                        'status' => false,
                        'message' =>'Property Not updated successfully',   
                    );  
                }
            }else{
                $response = array(
                    'status' => false,
                    'message' =>'No property exist',   
                );   
            }
        }
        return response($response);
    }


    /**
     * CLIENT VIEW PROPERTY
     */
    public function ClientViewProperties(Request $request, $property_id){
        $response = array('status'=>false,'message'=>'Problem in code');
        if($property_id == '' && $property_id == null){
            $response = array(
                'status'=>false,
                'message'=>'Request paramters are missing'
            );
        }else{
            $property = ClientProperties::where('id',$property_id)->first();
            if(!empty($property)){
                $response = array(
                    'status' => true,
                    'message' =>'Your property details', 
                    'data'=>$property  
                );               
            }else{
                $response = array(
                    'status' => false,
                    'message' =>'Theres is no Property',   
                );   
            }
        }
        return response($response);
    }



    // DELETE PROPERTY BY CLIENT
    public function DeleteProperty(Request $request, $property_id){
        $response =array('status'=>false,'message'=>'Some Problem in code');

        $deleteproperty = ClientProperties::find($property_id);
        if($deleteproperty->delete()){
            $response = ['status' =>true,'message'=>'property Deleted Successfully']; 
        }else{
            $response = [ 'status' =>false, 'message'=>'Property Not Deleted Successfully']; 
        }
        return response($response, 200);
    }


    /***
     * CLIENT PROFILE VIEW 
    */
    public function ClientProfileView(Request $request,$client_id){
        $response = array('status'=>false ,'message'=>'Something wrong' );
        if($client_id == null && $client_id == ''){
            $response = [ 'status' =>false,'message'=>'Some problem in requested parameter']; 
        }else{
            $profile = Client::find($client_id);
            if($profile){
                $response = ['status' =>true,'message'=>'Profile Info', 'data'=>$profile];
            }else{
                $response =[ 'status' =>false, 'message'=>'Some Problem in your profile ', ];
            }
        }
        return response($response);
    }


    /**
     * Client update profile
     * @update
     * @ by client id
     */

    public function UpdateClientProfile(Request $request)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            // UPDALOAD IMAGE HERE 
            // $client_image = $request->file('client_image');
            // $client_logo = $request->file('client_background_logo');
                  
            if($client_image =$request->file('client_image')):
                $request->client_image  =  Storage::disk('public')->put('client_profile',$client_image);
            else:
                unset($request->client_image);
            endif;


            if($client_logo =$request->file('client_background_logo')):
                $request->client_background_logo =  Storage::disk('public')->put('client_bg_logo',$client_logo); 
            else:
                unset($request->client_background_logo);
            endif;
             //dd($request->client_background_logo);
                $profile = Client::find($request['id']);
                $profile->contactname =$request->contactname;
                $profile->address =$request->address;
                $profile->state =$request->state;
                $profile->job_title =$request->job_title;
                $profile->client_image =$request->client_image;
                $profile->client_background_logo =$request->client_background_logo;
                $profile->clientname =$request->clientname;
                $profile->email =$request->email;
                $profile->phone =$request->phone;
                $profile->website =$request->website;
                if($request->password!=""){
                    $profile->password= Hash::make($request->password);
                }
                $profile->save(); 
                if($profile):
                    $response = ['status' =>true, 'message'=>'Profile Updated', 'data'=>$profile];
                else:
                    $response = ['status' =>false,'message'=>'Your Profile Not updated successfully!'];
                endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /**
    * CLEINT SEND MESSGE TO EMPLOYEE ASSIGN ON PROPERTIES
    */
    public function ClientSendMessageToAssignEmployee(Request $request){
        
        $response = ['status'=>false,'message'=>'Something wrong'];
        try {

            // $subject  = "Client Send Message";
            $data = [
                'message'=> $request->message,
                'subject' => $request->subject,
                'propertyname'=>$request->propertyname
            ];
                       
            $emails_withid = $request->data;
            $arrMessageEmail = [];
       
            foreach($emails_withid as $value):
                    $arrMessageEmail[] = $value['email'];
            endforeach;
                
                Mail::to($arrMessageEmail)->send(new ClientMessageToEmployee($data));
                if(Mail::failures()) :
                    $response = ['status'=>false,'message'=>'Emails not Send! Please check']; 
                else:
                    $response = ['status'=>true,'message'=>'All Emails Send Successfully']; 
                endif;
                          
            //$response = ['status'=>true,'message'=>'All Emails Send Successfully'];     
        } catch (\Exception $e) {
            $response = ['status'=>false,'message'=>$e->getMessage()];    
        }
        return response($response);
    }


    
    //  GET HERE ALL REPORT TYPE 
    public function GetAllReportSubType(Request $request){

        $ReportSubType = ReportSubType::all();
        if(count($ReportSubType) > 0 ){
            $response = [ 'status' =>true, 'message'=> 'All SubType Report','data'=> $ReportSubType];
        }else{
            $response = ['status' =>false, 'message' =>'No Record Found!'];
        }
        return response($response);
    }

    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    /*** MAKE A 60 MIN SLOTS */
    // function SplitTime($StartTime, $EndTime, $Duration="60"){
    //     $ReturnArray = array ();// Define output
    //     $StartTime    =  strtotime ($StartTime); //Get Timestamp
    //     $EndTime      = strtotime ($EndTime); //Get Timestamp
    
    //     $AddMins  = $Duration * 60;
    
    //     while ($StartTime <= $EndTime) //Run loop
    //     {
    //         $returnstrt = date ("H:i:A", $StartTime);
    //         $end_time =  $StartTime += $AddMins;

    //         $ReturnArray[] =$returnstrt;
    //         $StartTime += $AddMins; //Endtime check
    //     }
    //     return $ReturnArray;
    // }
    // END HERE SLOTS FUNCTION 


    /**
     * CLIENT REPORT 
    */

    public function ClientHourlyReport(Request $request){ 
        //dd($request->all());

        $response = array('status'=>false,'message'=>'Something wrong');
            $report = Reportslog::with('employee', 'property','report')
                    ->where(function($q) use ($request) {
                        $q->where('report_type_id','=', 1)
                        ->Where('client_id', $request->client_id)
                        ->where('date',$request->current_date);
                    });
            $data = $report->get();

            $EmpArray = [];
            //dd($data);
            foreach($data as $key=>$d) {
                if(!isset($EmpArray[$d->employee_id])) {
                    $EmpArray[$d->employee_id] = [];
                }

                if(!isset($EmpArray[$d->employee_id][$d->slot])) {
                    $EmpArray[$d->employee_id][$d->slot] = [];
                }

                $data =[]; 
                $data['id'] = $d->id;
                $data['description'] = $d->description;
                $data['location'] = $d->location;
                $data['date'] = $d->date;
                $data['time'] = $d->time;
                $data['slot'] = $d->slot;
                $data['status'] = $d->status;

                foreach($d->employee as $value){
                    $emp = [];
                    $emp['emp_id']= $value['id'];
                    $emp['firstname']= $value['firstname'];
                    $emp['lastname']= $value['lastname'];
                    $data['emp'][] = $emp;
                }

                foreach($d->property as $value){
                    $emp = [];
                    $emp['address']= $value['address'];
                    $emp['lat']= $value['lat'];
                    $emp['long']= $value['long'];
                    $data['property'][] = $emp;
                }

                foreach($d->report as $value){
                    $emp = [];
                    $emp['report_type']= $value['report_name'];
                    $data['report_type'][] = $emp;
                }
                $EmpArray[$d->employee_id][$d->slot][] = $data;
            }

            if(count($EmpArray) > 0):
                $response = [ 'status' =>true,'message' =>'Record Found!','data'=> $EmpArray];
            else :
                $response = ['status' =>false,'message' =>'No Record Found!'];
            endif;
        return response($response);
    }

    /**
     * CLIENT INCIDENT 
    */
    public function ClientIncidentReport(Request $request){
        $response = array('status'=>false,'message'=>'Something wrong');
        
            $report = Reportslog::with('employee','property','report')
                    ->where('report_type_id',2)
                    ->where('client_id',$request->client_id)
                    ->where('date',$request->current_date)
                    ->get();

            $hourly = $report->toArray();

            if(count($hourly) > 0):
                $response = ['status' =>true, 'message' =>'Record Found!','data'=> $hourly];
            else :
                $response = [ 'status' =>false, 'message' =>'No Record Found!'];
            endif;
        return response($response);
    }



    public function ReportsLocationFilterHourly(Request $request){
        $response = array('status'=>false,'message'=>'Something wrong');

        $reportSql = Reportslog::with('employee','property','report');
        if ($request->search != null) {
			$reportSql = $reportSql->where(function($query) use ($request) {
                $query->where('location', 'Like', "%{$request->search}%");
			});
        }
        $reportSql->where('date', $request->current_date);
        $reportSql->where('report_type_id','=', $request->report_type_id);
        $reportSql->Where('client_id', $request->client_id);
        $reportSql_data  = $reportSql->get();

        $EmpArray = [];
            foreach($reportSql_data as $key=>$d) {
                if(!isset($EmpArray[$d->employee_id])) {
                    $EmpArray[$d->employee_id] = [];
                }

                if(!isset($EmpArray[$d->employee_id][$d->slot])) {
                    $EmpArray[$d->employee_id][$d->slot] = [];
                }

                $data =[]; 
                $data['id'] = $d->id;
                $data['description'] = $d->description;
                $data['location'] = $d->location;
                $data['date'] = $d->date;
                $data['time'] = $d->time;
                $data['slot'] = $d->slot;
                $data['status'] = $d->status;

                foreach($d->employee as $value){
                    $emp = [];
                    $emp['emp_id']= $value['id'];
                    $emp['firstname']= $value['firstname'];
                    $emp['lastname']= $value['lastname'];
                    $data['emp'][] = $emp;
                }

                foreach($d->property as $value){
                    $emp = [];
                    $emp['address']= $value['address'];
                    $emp['lat']= $value['lat'];
                    $emp['long']= $value['long'];
                    $data['property'][] = $emp;
                }

                foreach($d->report as $value){
                    $emp = [];

                    $emp['report_type']= $value['report_name'];
                    $data['report_type'][] = $emp;
                }
                $EmpArray[$d->employee_id][$d->slot][] = $data;
            }
        //dd($reportSql_data);
        if(count($EmpArray) > 0):
            $response = ['status' =>true,'message' =>'Record Found!','data'=> $EmpArray];
        else :
            $response = [ 'status' =>false, 'message' =>'No Record Found!'];
        endif;
        return response($response);
    }




    public function ReportsLocationFilterincident(Request $request){
        $response = array('status'=>false,'message'=>'Something wrong');

        $reportSql = Reportslog::with('employee','property','report');
        if ($request->search != null) {
			$reportSql = $reportSql->where(function($query) use ($request) {
                $query->where('location', 'Like', "%{$request->search}%");
			});
        }
        $reportSql->where('date', $request->current_date);
        $reportSql->where('report_type_id','=', $request->report_type_id);
        $reportSql->Where('client_id', $request->client_id);
        $reportSql_data  = $reportSql->get();
 
        //dd($reportSql_data);
        if(count($reportSql_data) > 0):
            $response = ['status' =>true,'message' =>'Record Found!', 'data'=> $reportSql_data];
        else :
            $response = ['status' =>false,'message' =>'No Record Found!'];
        endif;
        return response($response);
    }
    
    
    /*
    * store invoices here
    */
    public function SaveInvoices(){
        $response = ['status'=>false, 'message'=>'something wrong' ];
        $shifts = Shift::with(['property.client','employee'])
        ->whereHas( 'property.client',function($q) use ($request) {
            $q->whereIn('id', $request->client_id);
        })->where('added_to', 'LIKE', "%".$request->current_date."%")->get()->toArray();
       //dd($shifts);

        $total_amount = [];
        if(count($shifts) > 0 ):
           
            foreach($shifts as $shift):
                $paid_hours = [];
                // $paid_hours['hours'] = $shfit['paid_hours'];
                $paid_hours['current_date'] =  $request->current_date;
                $paid_hours['id'] =  $shift['property']['client']['id'];
                $sum = $shift['employee']['pay_rate']*$shift['paid_hours'];      
                $paid_hours['pay_rate']= $sum;
                
                $invoices = new Invoices();
                $invoices->invoice_amount =  $sum;
                $invoices->client_id = $shift['property']['client']['id'];
                $invoices->status = 'pending';
                $invoices->date_posted = $request->current_date;
                $invoices->save();
               
                $total_amount[] =$paid_hours;              
            endforeach;

            //dd($total_amount);
            if($invoices){
                $response = ['status'=>true, 'message'=>'Your Invoices','data'=>$total_amount ];
            }
            $response = ['status'=>true, 'message'=>'Your Invoices','data'=>$total_amount ];
        else:
            $response = ['status'=>false, 'message'=>'Today No update', 'data'=>$total_amount];
        endif;       
        return response($response);

    }

  
    /**
     *  Invoices
     *  @ client id by get client all property
     *  @ property id by getshift id by get all employee
     *  @ check property id in shift
     */

   
    public function ClientPropertyEmployeeInvoices(Request $request){

        $response = ['status'=>false, 'message'=>'something wrong' ];
        $invoices = Invoices::with('client')
                    ->where('client_id','=',$request->client_id)
                    ->whereDate('created_at',Carbon::today())
                    ->get()
                    ->toArray();
     
        if(count($invoices) > 0 ):
            $response = ['status'=>true, 'message'=>'Your Invoices','data'=>$invoices ];
        else:
            $response = ['status'=>false, 'message'=>'Today No update'];
        endif;       
        return response($response);
    }

    
    /****
     *  Client Report with property 
     *  show info by hourly and incident 
     */
    public function ClientReports(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];
            
                $properties = ClientProperties::
                        with(['shift' => function($q) use($request) {
                            $q->where('added_to','LIKE', "%".$request->date."%");
                        },'shift.employee'])
                        ->where('client_id', $request->client_id)
                        ->get()->toArray();

            $employe_ids = [];
            $main_array = [];
           
            foreach($properties as $main):             
                foreach($main['shift'] as $shift):
                        $emp = [];                
                        $emp = $shift['employee']['id'];
                        $employe_ids[] = $emp;               
                endforeach;

                if(count($employe_ids) > 0):
                    $property = [];
                    $property['property_id'] = $main['id'];
                    $property['client_id'] = $main['client_id'];
                    $property['name'] = $main['name'];
                    $property['address'] = $main['address'];
                    $property['lat'] = $main['lat'];
                    $property['long'] = $main['long']; 

                    
                        $property['hourly'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$request->date)
                        ->where('report_type_id','=', 1)                        
                        ->whereIn('employee_id', $employe_ids)             
                        ->where('property_id','=',$main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray(); 

                        $property['incident'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$request->date)
                        ->where('report_type_id','=', 2)
                        ->whereIn('employee_id', $employe_ids)
                        ->where('property_id','=', $main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray();

                    

                    if(count($property['hourly'])> 0 || count($property['incident']) > 0):
                        $main_array[] = $property; 
                    endif;     
                else :
                    $response = [ 'status' =>false, 'message' =>'No Record Found!'];
                endif;      
                      
            endforeach; 
            if(count($main_array) > 0):
                $response = [ 'status' =>true,'message' =>'Record Found!','data'=> $main_array ];
            else :
                $response = [ 'status' =>false, 'message' =>'No Record Found!'];
            endif;   
               
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /***
     * report filter 
     */

     public function ReportFilter(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];
            if($request->current_date != null ){
                $properties = ClientProperties::
                        with(['shift' => function($q) use($request) {
                            $q->where('added_to',$request->current_date);
                        },'shift.employee'=> function($q) use($request){
                            $q->where('firstname','LIKE', "%{$request->search}%");
                            $q->orWhere('lastname','LIKE', "%{$request->search}%");
                        }])
                        ->where('client_id', $request->client_id)
                        ->get()
                        ->toArray();
            }else if($request->end_date != null || $request->start_date != null){
                $properties = ClientProperties::
                    with(['shift' => function($q) use($request) {
                        $q->whereBetween('week_of',[$request->start_date,$request->end_date]);
                    },'shift.employee'=> function($q) use($request){
                        $q->where('firstname','LIKE', "%{$request->search}%");
                        $q->orWhere('lastname','LIKE', "%{$request->search}%");
                    }])
                    ->where('client_id', $request->client_id)
                ->get()->toArray();
            }

            $employe_ids = [];
            $main_array = [];
           
            foreach($properties as $main):             
                foreach($main['shift'] as $shift):
                        $emp = [];                
                        $emp = $shift['employee']['id'];
                        $employe_ids[] = $emp;               
                endforeach;

                if(count($employe_ids) > 0):
                    $property = [];
                    $property['property_id'] = $main['id'];
                    $property['client_id'] = $main['client_id'];
                    $property['name'] = $main['name'];
                    $property['address'] = $main['address'];
                    $property['lat'] = $main['lat'];
                    $property['long'] = $main['long']; 

                    if($request->current_date != null ){
                        $property['hourly'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$request->current_date)
                        ->where('report_type_id','=', 1)                        
                        ->whereIn('employee_id', $employe_ids)             
                        ->where('property_id','=',$main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray(); 

                    }else if($request->end_date != null || $request->start_date != null){
                        $property['hourly'] = Reportslog::with('employee','property','report','subReport')
                        ->whereBetween('date',[$request->start_date, $request->end_date])
                        ->where('report_type_id','=', 1)                        
                        ->whereIn('employee_id', $employe_ids)             
                        ->where('property_id','=',$main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray(); 
                    }


                    if($request->current_date != null ){
                        $property['incident'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$request->current_date)
                        ->where('report_type_id','=', 2)
                        ->whereIn('employee_id', $employe_ids)
                        ->where('property_id','=', $main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray();

                    }else if($request->end_date != null || $request->start_date != null){
                        $property['incident'] = Reportslog::with('employee','property','report','subReport')
                        ->whereBetween('date',[$request->start_date, $request->end_date])
                        ->where('report_type_id','=', 2)
                        ->whereIn('employee_id', $employe_ids)
                        ->where('property_id','=', $main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray();
                    }

                    if(count($property['hourly'])> 0 || count($property['incident']) > 0):
                        $main_array[] = $property; 
                    endif;     
                else :
                    $response = [ 'status' =>false, 'message' =>'No Record Found!'];
                endif;      
                      
            endforeach; 
            if(count($main_array) > 0):
                $response = [ 'status' =>true,'message' =>'Record Found!','data'=> $main_array ];
            else :
                $response = [ 'status' =>false, 'message' =>'No Record Found!'];
            endif;   
               
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

   
    
   

    /**
     * CLIENT GET SINGLE INCIDENT VIEW
     */
    public function ClientSingleIncidentView($reportlog_id){
        
        $incidentview = Reportslog::with('client','property','report','subReport')
						->where('id',$reportlog_id)
						->first()->toArray();

        if(count($incidentview) > 0){
            $response = ['status' =>true,'message'=>'All Report','incidentview' =>$incidentview];
        }else{
            $response = ['status' =>false,'message'=>'No Record Found!'];
        }
        return response($response); 

    }


    /**
     * VIEW HOURLY DATA AT CLIENT SIDE
     */
    public function viewHourlyReportModel($client_id){
        //dd(Carbon::today()->toDateString());
        $reportSQL = Reportslog::with('client','property','report','subReport');
        $reportSQL->where('report_type_id',1);
        $reportSQL->where('client_id', $client_id);
        $reportSQL->whereDate('date', Carbon::today()->toDateString());
        $report = $reportSQL->get()->toArray();
               
        if(count($report) > 0){
            $response = ['status' =>true,'message'=>'All Report','data' =>$report];
        }else{
            $response = ['status' =>false,'message'=>'No Record Found!'];
        }
        return response($response);
    }






}
