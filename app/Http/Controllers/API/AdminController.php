<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use App\Models\Admin;
use App\Models\Client;
use App\Models\clientProperties;
use Carbon\Carbon;

class AdminController extends Controller{
  
    public function __construct(){
        //$this->middleware('guest:admin');
        //$this->middleware('auth.basic');
    }


    /**
    * Display admin login api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */ 
    public function adminLogin(Request $request){
      // check here validation 
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // if validation failed
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }else{
            // check admin exist or not
            $admin = Admin::where('email', $request->email)->first();
            if ($admin) {
                if (Hash::check($request->password, $admin->password)) { 
                    $token = $admin->createToken('Access Admin Backend',['admin'])->accessToken;
                    $admin->remember_token = $token;
                    $admin->save();
                    $response = ['token' => $token];
                    return response($response, 200);
                } else {
                    
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            } else {
                $response = ["message" =>'User does not exist'];
                return response($response, 422);
            }
        }
    }
    // end here admin login


    /**
     * Display admin here all client  .
     *
     * @param  int  
     * @return \Illuminate\Http\Response
     */
    public function AdminGetAllClient(Request $request){
        $response  = array('status' =>false, 'error'=>'some error', 'message'=>'','data'=>'');
        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null)
        {
            $response['error']='Authorization failed';
        }else{
            $client = Client::get();
            if($client){
                $response = array(
                    'status'=> true,
                    'message' =>'Data fetech successfully',
                    'data'=>$client
                );
            }else{
                $response = array(
                  'status'=>false,
                  'error'=>'Data does not exist '

                );
            }
            
        }
        return response($response, 200);
    }


    /**
    * CLIENT ID BY CLIENT INFO.
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */ 
    public function AdminCheckClientInfo($client_id){
        $response  = array('status' =>false, 'error'=>'some error', 'message'=>'','data'=>'');

        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null)
        {
            $response['error']='Authorization failed';
        }else{

            if($client_id == '' && $client_id == Null){
                $response['error'] = 'Some request parameters are missing please try again'; 
            }else{
                // GET HERE THE CLIENT BY CLIENT ID 
                $client = Client::find($client_id);
                // CHECK HERE IF CLIENT EXIST OR NOT 
                if($client){
                    $response = array(
                        'status'=> true,
                        'message'=>'Data fetech successfully',
                        'data'=> $client
                    );
                }else{
                    $response = array(
                        'status'=> false,
                        'error'=> 'Data not fetech successfully'
                    );
                }          
            }
        }     
        return response($response, 200);
    }



    /**
    * Get here client id by client property id .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */ 
    public function AdminClientIdByClientProperty($client_id){
        $response  = array('status' =>false, 'error'=>'some error', 'message'=>'','data'=>'');
        
        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null)
        {
            $response['error']='Authorization failed';
        }else{
            if($client_id =='' && $client_id ==Null){
                $response['error'] ='Some request parameters are missing please try again';
            }else{          
                // client id by get client properties 
                $client_propeties = clientProperties::where('client_id',$client_id)->first();
                if(!empty($client_propeties)){
                    $response = array(
                        'status' => true,
                        'message'=>'Data fetech successfully',
                        'data'=>$client_propeties,
                    );
                }else{
                    $response = array(
                        'status' => false,
                        'message'=>'Sorry can not found',
                    );
                }        
            }
        }
        return response($response, 200);
    }


    /**
    * Update here client id by client property .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */ 
    public function AdminSendClientIdByClientProperty(Request $request, $client_id){
        $response  = array('status' =>false, 'error'=>'some error', 'message'=>'','data'=>'');

        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null)
        {
            $response['error']='Authorization failed';
        }else{

            if($client_id =='' && $client_id ==Null){
                $response['error'] ='Some request parameters are missing please try again';
            }else{  
                // check here the form validation 
                $validator = Validator::make($request->all(), [
                    // 'title' => 'required',
                    // 'description' => 'required',
                ]);
                // return here the validation error 
                if ($validator->fails())
                {
                    return response(['errors'=>$validator->errors()->all()], 422);
                }else{
                    $update  = clientProperties::where('client_id',$client_id)->update($request->all());
                    if($update){
                        $response  = array(
                            'status' =>true,
                            'message'=>'Data Updated Successfully ',
                            'data'=>$update
                        );
                    }else{
                        $response  = array(
                            'status' =>false,
                            'error'=>'Data not updated successfully',
                        );
                    }
                }     
            }
        }
        return response($response,200);
    }



}
