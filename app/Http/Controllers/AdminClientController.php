<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Mail\NewClient;
use App\Mail\NewAdmin;
use App\Mail\AdminClientreset;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Client;
use App\Admin;
use App\ClientProperties;
use App\ClientAlertReport;

use App\DetectShift;
use App\Clockin;
use App\Shift;
use Carbon\Carbon;
use JWTFactory;
use JWTAuth;
use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;


class AdminClientController extends Controller
{
    
    /*
    * GET ALL CLIENT
    */
    public function  AdminGetAllClient(){
        $properties = Client::with(['property'])->orderBy('clientname')->get();
        return $properties;      
    }

    public function  AdminGetAllAdmin(){
        $properties = Admin::where('role','admin')->orderBy('id')->get();
        return $properties;      
    }

    /****
     * GET CLIENT BY CLIENT STATUS
     *  
     */
    public function AdminGetStatusClient(Request $request){
        $response = array('status' =>false,'message' =>'Something wrong',);
        $status  = Client::with(['property'])->orderBy('clientname')->where('status','=',$request->get('status'))->get();
        if(count($status) > 0){
            $response = ['status' =>true,'message' =>'All Records','data'=>$status];
        }else{
            $response = ['status' =>false,'message' =>'No Record Found!'];
        }
        return response($response); 
    }


    /****
     * GET CLIENT BY CLIENT STATUS
     *  
     */
    public function AdminGetIClientNameAddressClient(Request $request){
        $response = array('status' =>false,'message' =>'Something wrong');
        $term = $request->get('search');
        $search  = Client::with(['property'])
        ->where('clientname', 'LIKE', "%{$term}%") 
        ->orWhere('address', 'LIKE',  "%{$term}%")
        ->where('status','active')
        ->orderBy('clientname')
        ->get();
       
        if(count($search) > 0){
            $response = array(
                    'status' =>true,
                    'message' =>'All Records',
                    'data'=>$search,
                );
        }else{
            $response = array(
                'status' =>false,
                'message' =>'No Record Found!',
            );
        }
        return response($response); 
    }



    /****
     * GET PROPERTIES STATUS
     *  
     */
    public function AdminGetProperties(Request $request){
        $response = array('status' =>false,'message' =>'Something wrong',);
        $status  = ClientProperties::where('status','=',$request->get('status'))->get();
        if(count($status) > 0){
            $response = array(
                    'status' =>true,
                    'message' =>'All Records',
                    'data'=>$status,
                );
        }else{
            $response = array(
                'status' =>false,
                'message' =>'No Record Found!',
            );
        }
        return response($response); 
    }


    /****
     * GET PROPERTIES search 
     *  
     */
    public function AdminGetIPropertiesAddressClient(Request $request){
        $response = array('status' =>false,'message' =>'Something wrong');
        $term = $request->get('search');
        $search  =  ClientProperties::with('client')->where('name', 'LIKE', "%{$term}%") 
        ->orWhere('address', 'LIKE', "%{$term}%") 
        ->get();
       
        if(count($search) > 0){
            $response = array(
                    'status' =>true,
                    'message' =>'All Records',
                    'data'=>$search,
                );
        }else{
            $response = array(
                'status' =>false,
                'message' =>'No Record Found!',
            );
        }
        return response($response); 
    }

    /**
     *  Admin Client Location Search 
     */
    public function AdminGetAddressLocation(Request $request){
        try{

            $response = ['status' =>false,'message' =>'Something wrong'];
            $term = $request->get('search');
            $search  = Client::with(['property'])
            ->where('address', 'LIKE', "%{$term}%")
            ->orderBy('clientname')
            ->get();
        
            if(count($search) > 0){
                $response = [ 'status' =>true,'message' =>'All Records','data'=>$search];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * Properties Location
     */

    public function AdminGetpropertiesLocation(Request $request){
        try{
            $response = array('status' =>false,'message' =>'Something wrong');
            $term = $request->get('search');
            $search  =ClientProperties::with('client')->orderBy('name')->where('address', 'LIKE', "%{$term}%")->get();
        
            if(count($search) > 0){
                $response = [ 'status' =>true, 'message' =>'All Records', 'data'=>$search];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }


    /*
    * Properties
    */
    public function GetAllProperties(){
        try{

            $response = ['status'=>false,'message'=>'SomeThing Wrong'];

            $properties =  ClientProperties::all();
            if(count($properties) > 0 ):
                $response =[ 'status' =>true,'message' =>'All Properties','data' => $properties];
            else:
                $response = ['status' =>false, 'message' =>'No Record Found!',];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * PROPERTIES GET ONLY FOR ADMIN DASHBOARD CLIENT ICON  MENU PROPERTIES 
    */
    public function AdminClientUnderProperties(Request $request){
        try{
            $response =  array('status'=>false,'message'=>'Problem In code');
        
            $properties = ClientProperties::with('client')->get();
            if(count($properties) > 0 ){
                $response = [ 'status' =>true,'message' =>'Your properties fetch successfully','data' => $properties];
            }else{
                $response = ['status' =>false,'message' =>'No Record Found!'];
            }        
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }


       
    /*
    * Get Client And Client Property
    */
    public function ClientIdByClientProperty($clientId){
        try{
            $response = ['status'=>false,'message'=>'Problem In code'];
            if(!empty($clientId)){
                $properties = Client::Where('id',$clientId)->with(['property'])->first();
                
                if($properties){
                    $response = ['status' =>true,'message' =>'Your Information fetch successfully','data' => $properties];
                }else{
                    $response = ['status' =>false, 'message' =>'Your Information not fetch successfully'];
                }
            }else{
                $response = ['status' =>false,'message' =>'Client has no properties'];
            }
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }
    
    /*
    * Update Client 
    *
    */
    public function updateClient(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];
            
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
             
            $profile = Client::find($request['id']);
            $profile->clientname =$request->clientname;
            $profile->contactname =$request->contactname;
            $profile->job_title =$request->job_title;
            $profile->address = $request->address;
            $profile->state = $request->state;            
            $profile->phone = $request->phone;
            $profile->email = $request->email;
            $profile->zip = $request->zip;
            $profile->police_email = $request->police_email;
            $profile->website = $request->website;
            $profile->client_image = $request->client_image;
            $profile->client_background_logo =$request->client_background_logo;
            if($request->password!=""){
               $profile->password = Hash::make($request->password);
            }
            $profile->save();
            if($request->password!=""){
                $data = [
                    'contactname'=> $request->contactname,
                    'address'=> $request->address,
                    'clientname'=> $request->clientname,
                    'email'=> $request->email,
                    'password'=> $request->password,
                    'phone'=> $request->phone,
                ];
                // $when = now()->addMinutes(1);
                Mail::to($request->email)->send(new NewClient($data));  
            }                    
            if($profile) :
                $response = ['status'=> true, 'message'=>'Client Update Successfully', 'data'=>$profile];    
            else :
                $response = ['status'=> false, 'message'=>'Client Not Update Successfully'];
            endif;
           
        return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }


     /*
    * Update Client property
    *
    */
    public function updateClientProperty(Request $request, $property_id){
        try{
            $response =  ['status'=>false,'message'=>'Something wrong'];
            
            $property = ClientProperties::find($property_id);
            //dd($request['coordinates']);
            if($property){
                $update = array(
                    'name'=>$request['name'],
                    'address'=>$request['address'],
                    'state'=>$request['state'],
                    'lat'=> $request['lat'],
                    'long'=>$request['long'],
                    'coordinates'=> ($request['coordinates'] == null) ? '' :$request['coordinates'],
                );
                $updated =  ClientProperties::where('id',$property_id)-> update($update);
                if($update){
                    $response = ['status'=>true,'message'=>'Property Updated','data'=>$updated];
                }else{
                    $response = ['status'=>false,'message'=>'Not Updated'];
                }                
            }else{
                $response = ['status'=>false,'message'=>'Property Donot exist'];
            }   
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }  
    }


    /** 
     * GENERATE RANDOM PASSWORD
     * 
    */
    function password_rand_string($length) {
        $chars = "abcdefg@@###%%%hijkl^^^mno&&&&pqrstuvw****xyzABCDEFGHIJK!#LMNOPQRSTUVW@XYZ01!!!23456@789";
        return substr(str_shuffle($chars),0,$length);
    }

    function password_rand_string1($length) {
        $chars = "abcdefghijklmnopqrstuvwxyz1234567890";
        return substr(str_shuffle($chars),0,$length);
    }


    /*
    * Admin Add New Client
    */
    public function AdminAddNewClient(Request $request){
        try{
            
            $response = ['status' => false,'message'=>'Something wrong'];
            // Validate requested data
            $validator = Validator::make($request->all(), [
                'contactname' => 'required|string',
                'address' => 'required|string',
                'clientname' => 'required|string',
                'email'=> 'required|string|email|unique:tblm_clients',
                'phone'=>'required',

            ]);
            if ($validator->fails()) :
                $response = ['status' => false,'error'=>$validator->errors()];           
            else :    
                $check_email = Client::where('email', $request->email)->first();
                $PASSWORD = $this->password_rand_string(16);
                if (!$check_email) : 

                    if($client_image =$request->file('client_image')):
                        $request->client_image  =  Storage::disk('public')->put('client_profile',$client_image);
                    endif;

                    if($client_logo =$request->file('client_background_logo')):
                        $request->client_background_logo =  Storage::disk('public')->put('client_bg_logo',$client_logo); 
                    endif;
                   
                    $client = new Client();
                    $client->contactname = ucfirst($request->contactname);
                    $client->address = $request->address;
                    $client->state = $request->state;                    
                    $client->job_title = $request->job_title;
                    $client->clientname = ucfirst($request->clientname);
                    $client->email = strtolower($request->email);
                    $client->zip = $request->zip;
                    $client->police_email = strtolower($request->police_email);
                    $client->password = Hash::make($PASSWORD);
                    $client->phone = $request->phone;
                    $client->website = $request->website;
                    $client->client_image = $request->client_image;
                    $client->client_background_logo = $request->client_background_logo;
                    $client->save();

                    if($client) :
                        $data = [
                            'contactname'=> $request->contactname,
                            'address'=> $request->address,
                            'clientname'=> $request->clientname,
                            'email'=> $request->email,
                            'password'=> $PASSWORD,
                            'phone'=> $request->phone,
                        ];
                        // $when = now()->addMinutes(1);
                        Mail::to($request->email)->send(new NewClient($data));              
                        $response = [ 'status' => true, 'message' =>'Added successfully! Check all detail in Emails','data' => $client];                
                    else :
                        $response = ['status' => false,'message' =>'New Client Not added Successfully. Please check your credential!'];                
                    endif;            
                else :
                    $response = ['status' => false, 'message' =>'Client already exist on this mail !'];           
                endif;  
            endif;     
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }    
    }


    /*
    * Admin Add New Admin
    */
    public function AdminAddNewAdmin(Request $request){
        try{
            $response = ['status' => false,'message'=>'Something wrong'];
            // Validate requested data
            $validator = Validator::make($request->all(), [
                'email'=> 'required|string|email|unique:tblmadminusers',
            ]);
            if ($validator->fails()) :
                $response = ['status' => false,'error'=>$validator->errors()];           
            else :    
                $check_email = Admin::where('email', $request->email)->first();
                $PASSWORD = $this->password_rand_string1(8);
                if (!$check_email) :
                    $client = new Admin();
                    $client->firstname = ucfirst($request->clientname);
                    $client->email = strtolower($request->email);
                    $client->password = Hash::make($PASSWORD);
                    $client->lastname = $request->phone;
                    $client->role = 'admin';
                    $client->save();

                    $data = [
                        'contactname'=> $request->clientname,
                        'email'=> $request->email,
                        'password'=> $PASSWORD,
                        'phone'=> $request->phone,
                    ];
                    
                    Mail::to($request->email)->send(new NewAdmin($data));  

                    $response = [ 'status' => true, 'message' =>'Added successfully! Check all detail in Emails','data' => $client];          
                else :
                    $response = ['status' => false, 'message' =>'Admin already exist on this mail !'];           
                endif;  
            endif;     
            return response($response); 
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }    
    }


    /*
    * Add new client property
    */
    public function AdminAddNewProperty(Request $request){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'address' => 'required|string',
                'lat' => 'required',
                'long' => 'required',
            ]);
            if ($validator->fails()) {
                $response = ['status' => false,'message'=>'Fill Required fields','error'=>$validator->errors()];           
            }else{
                $getclientproperty  = ClientProperties::where('client_id','=',$request->client_id)->first();
                if(!empty($getclientproperty)){
                    $response = ['status' =>false, 'message'=>'Property already added for this Community!'];
                }else {
                    $property = new ClientProperties();
                    $property->client_id = $request->client_id;
                    $property->name = ucfirst($request->name);
                    $property->address = $request->address;
                    $property->state = $request->state;
                    $property->lat = $request->lat;
                    $property->long = $request->long;
                    $property->coordinates = $request->coordinates == null ? '' : json_encode($request->coordinates);
                    $property->status = 'active';
                    $property->save();
                    
                    if($property){
                        $response = ['status' =>true,'message'=>'New property added Successfully!','data'=>$property];
                    }else{
                        $response = ['status' =>false, 'message'=>'New property not added Successfully!'];
                    }
                }
            }           
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /*
    * Add new client report
    */
    public function AdminAddClientReport(Request $request){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
           // echo 'client_id'.$request->client_id;
           // echo 'report_id'.$request->report_id;
            $getclientproperty  = ClientAlertReport::where('client_id','=',$request->client_id)->where('report_id','=',$request->report_id)->first();
            //echo "<pre>"; print_r($getclientproperty); die();
            if(!empty($getclientproperty)){
                $response = ['status' =>false, 'message'=>'Report is  already added for this Community!'];
            }else {
                $property = new ClientAlertReport();
                $property->client_id = $request->client_id;
                $property->report_id = $request->report_id;
                $property->save();
                
                if($property){
                    $response = ['status' =>true,'message'=>'Report is added successfully!','data'=>$property];
                }else{
                    $response = ['status' =>false, 'message'=>'Report not added successfully!'];
                }
            }      
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /*
    * View client and properties
    */
    Public function ViewSingleClient($client_id){
        try{
            if(!empty($client_id)){
                $properties = Client::Where('id',$client_id )->with(['property'])->first();
                if($properties){
                    return response($properties);
                }else{
                    return response('Error', 200);
                }
            }else{
                return response('Error', 500);
            }
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /*
    *  Delete client and client property
    */
    public function deleteClient($client_id){
        try{
            $response =['status'=>true,'message'=>'already deleted'];

            $client = Client::findOrFail($client_id);
            $client->delete();
            
            if($client){
               
                $response =['status' =>true,'message'=>'Client Deleted Successfully'];  
            }else{
                $response = ['status' =>false,'message'=>'Client Not Deleted Successfully']; 
            }                
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    /*
    *  Delete client and client property
    */
    public function Admindeleteadmin($client_id){
        try{
            $response =['status'=>true,'message'=>'already deleted'];

            $client = Admin::findOrFail($client_id);
            $client->delete();
            
            if($client){
               
                $response =['status' =>true,'message'=>'Admin Deleted Successfully'];  
            }else{
                $response = ['status' =>false,'message'=>'Admin can not be Deleted']; 
            }                
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /*
    *  DELETE SINGLE PROPERTIES
    */
    public function DeleteSingleproperties($property_id){
        try{
            $response =['status'=>false,'message'=>'Something wrong'];
            $properties = ClientProperties::find($property_id);
            $properties->delete();
            if($properties){
                $response = ['status' =>true,'message'=>'Property Deleted Successfully'];  
            }else{
                $response = ['status' =>false,'message'=>'Property Not Deleted Successfully']; 
            }             
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    
    /*
     *  ADMIN OFFICER LOGS CHECK CURRENT DATE RECORDS
     *     
    */
    public function AdminGetOfficerLogs(Request $request){
       
        $response = ['status'=>false,'message'=>'Something wrong'];
        $officelogs = Clockin::with('employee','shift','detectShift')->whereDate('created_at', Carbon::today()->toDateString())->get();
        $logs = $officelogs->toArray();
        
        if(count($logs) > 0){
            $response = ['status' =>true,'message'=>'Today officer logs','data' =>$logs];
        }else{
            $response = ['status' =>false, 'message'=>'Today no officer logs'];
        }        
        return response($response);
    }
    

    /**
    ** AdminClientFilter 
    */

    public function AdminClientFilter(Request $request){
        $response = array('status' =>false,'message' =>'Something wrong');

        $propertiesSQl = Client::with(['property'])
                        ->where(function($q) use ($request) {
                                $q->where('clientname', 'like', "%{$request->search}%")
                                ->orWhere('address', 'like', "%{$request->search}%");
                        });
        //dd($this->getEloquentSqlWithBindings($propertiesSQl));
            if($request->status != null) {
                $propertiesSQl->where('status', $request->status);
            }

            if($request->location != null) {
                $propertiesSQl->Where('address', 'Like', "%{$request->location}%");
            } 

            $propertiesSQl->orderby('clientname');
            $data = $propertiesSQl->get();
       
        if(count($data) > 0){
            $response = ['status' =>true, 'message' =>'All Records', 'data'=>$data];
        }else{
            $response = ['status' =>false,'message' =>'No Record Found!'];
        }
        return response($response);

    }

    /*
    * AdminFilterProperty 
    */

    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    public function AdminFilterProperty(Request $request){
        $response =  array('status'=>false,'message'=>'Something wrong');
    
        $propertiesSQl = ClientProperties::with('client')
                        ->where(function($q) use ($request) {
                                $q->where('name', 'Like', "%{$request->search}%")
                                ->orWhere('address', 'Like', "%{$request->search}%");
                        });
               // dd($this->getEloquentSqlWithBindings($propertiesSQl));
            if($request->status != null) {
                $propertiesSQl->where('status', $request->status);
            }      
            
            if($request->location != null) {
                $propertiesSQl->Where('address', 'Like', "%{$request->location}%");
            }
            $propertiesSQl->orderBy('name');
            $properties = $propertiesSQl->get();

            if(count($properties) > 0 ){
                $response = ['status' =>true,'message' =>'Your properties fetech successfully','data' => $properties];
            }else{
                $response =['status' =>false,'message' =>'No Record Found!'];
            }       
        return response($response);
    }


    //  filterofficerlogs
    public function FilterOfficerLogs(Request $request){
        $response = ['status'=>false,'message'=>'Something wrong'];

        $officelogs = Clockin::with('employee','shift','detectShift');
        if ($request->search != null) {
			$officelogs = $officelogs->whereHas('employee',function($query) use ($request) {
                $query->where('firstname', 'Like', "%{$request->search}%");
                $query->orWhere('lastname','Like', "%{$request->search}%");
			});
		}
        $officelogs->whereDate('created_at', Carbon::today())->get();
        $officelogs_data  = $officelogs->get();
       
        $logs = $officelogs_data->toArray();
        
        if(count($logs) > 0){
            $response = ['status' =>true,'message'=>'Today officer logs','data' =>$logs];
        }else{
            $response = ['status' =>false,'message'=>'Today no officer logs'];
        }        
        return response($response);

    }


    /**
     *  @INVOICES 
     * @Admin can check the invoices
     */

    public function AdminCheckAllInvoices(Request $request){
        $response = ['status'=>false, 'message'=>'something wrong' ];
        $shifts = Shift::with('property.client','employee')->where('added_to', 'LIKE', "%".$request->current_date."%")->get()->toArray();
        //dd($shifts);
    }


    
    /***
     * GET ONLY PROPERTIES LOCATION
     */
    public function GetOnlyCLientLocation(){
        try{
            $response = ['status'=>true,'message'=>'Something Wrong'];
            $client_location = Client::whereNotNull('state')->groupBy('state')->pluck('state');      
            if(count($client_location) > 0):
                $response = ['status' =>true,'message'=>'All Location','data' =>$client_location];
            else:
                $response = ['status' =>false,'message'=>'No Records Found'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 

    }


     /***
     * GET ONLY CLIENT PROPERTY LOCATION
     */
    public function GetOnlyPropertiesLocation(){
        try{
            $response = ['status'=>true,'message'=>'Something Wrong'];
            $property_location = ClientProperties::whereNotNull('state')->groupBy('state')->pluck('state');

            if(count($property_location) >0):
                $response = ['status' =>true,'message'=>'All Location','data' =>$property_location];
            else:
                $response = ['status' =>false,'message'=>'No Records Found'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }


    /***
     *  GET TRASHED CLIENT RECORDS
     * 
     */
    public function  GetClienttrashedRecords(){
        $response = ['status'=>true,'message'=>'Something Wrong'];

        $clients = Client::with(['property'])->orderBy('clientname')->onlyTrashed()->get()->toArray();
        if(count($clients) > 0):
            $response = ['status' =>true,'message'=>'Trashed Client','client' =>$clients];
        else:
            $response = ['status' =>false,'message'=>'No Trashed Client'];
        endif;

        return response($response);

    }

    /***
     *  GET TRASHED CLIENT RECORDS
     * 
     */
    public function  GetClientPropertiestrashedRecords(){
        $response = ['status'=>true,'message'=>'Something Wrong'];

        $ClientProperties = ClientProperties::with('client')->onlyTrashed()->get()->toArray();
        if(count($ClientProperties) > 0):
            $response = ['status' =>true,'message'=>'Trashed Properties','properties' =>$ClientProperties];
        else:
            $response = ['status' =>false,'message'=>'No Trashed Properties'];
        endif;

        return response($response);

    }



    function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKdfh64LMNOP5a5sf90QRSTUVWX133YZabcefghijkGH546lmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }


    /**
     * 
     * ADMIN RESET CLIENT PASSWORD
     */
    public function AdminResetClientPassword(Request $request){
        $response = ['status'=>true,'message'=>'Something Wrong'];

        $password = $this->password_generate(9);
        $update_data = ['password' => Hash::make($password)];

        $check_user = Client::where('email', $request->email)->first();

        
        if ($check_user) :
            // UPDATE HERE USERT PASSWORD
            $user = Client::where('email', $request->email)->update($update_data);

            $data = [
                'clientname'=> $check_user->clientname,
                'email'=> $request->email,
                'password'=> $password,
            ];
          
            Mail::to($request->email)->send(new AdminClientreset($data));

            return response()->json(['status' => true, 'message' => 'Your Password is send by email Please check email!']);
        else :
            return response()->json(['status' => false, 'message' => 'User Dont exist on this email. Please Check!']);
        endif;

        return response($response);

    }



}
