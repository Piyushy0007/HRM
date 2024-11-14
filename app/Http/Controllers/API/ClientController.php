<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use Auth;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;


class ClientController extends Controller{
      
    public function __construct(){
        $this->middleware('guest:client')->except('logout');
    }


    /**
    * Client login api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function signIn(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }else{

            $client = Client::where('email', $request->email)->first();
            if ($client) {
                if (Hash::check($request->password, $client->password)) {
                    $token = $client->createToken('Access Client Backend',['client'])->accessToken;
                    $client->remember_token = $token;
                    $client->save();
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


      /**
    * Client Signup api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function ClientSignup(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tblm_clients',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()){
            return response(['errors'=>$validator->errors()->all()], 422);
        }else{
            $request['password']=Hash::make($request['password']);
            $request['remember_token'] = Str::random(100);
            $request->role ='client';
            $client = Client::create($request->toArray());
            $token = $client->createToken('Access Client Backend',['client'])->accessToken;
            $response = ['remember_token' => $token];
            return response($response, 200);
        }
    }

    /**
    * Client Logout api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function logout (Request $request) {

        $token = $request->client()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }



    /**
    * Client View Profile api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function ViewClientprofile($client_id){

        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null){
            $response['error']='Authorization failed';
        }else{
            $client = client::where('id',$client_id)->get();
            if($client){
                $response['message']   = "Client fetech successfully";
                $response['success']   = true;
                $response['data']     = $client;  
            }else{
                $response['message'] = "Sorry not found";
            } 
            
        }
        return response($response, 200);
    }



    /**
    * Client Edit Profile api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function  EditClientprofile(Request $request,$client_id){

        $response = array('error'=>'false', 'message'=>'Some error occure', 'data'=>array() );
        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);

        if($header == "" || $header == null){
            $response['error']='Authorization failed';
        }else{

            if($client_id =='' && $client_id == Null){
                $response['error'] ='Some request parameters are missing please try again';
            }else{
                // det here the record by the client id 
                $client = client::where('id',$client_id)->get();
                if($client){
                    $response['message']   = "Client fetech successfully";
                    $response['success']   = true;
                    $response['data']     = $client;  
                }else{
                    $response['message'] = "Sorry not found";
                }  
            }
        }
        return response($response, 200);
    }


    /**
    * Client Update Profile api .
    *
    * @param  int  
    * @return \Illuminate\Http\Response
    */
    public function  UpdateClientprofile(Request $request, $client_id){

        $response = array('error'=>'false', 'message'=>'Some error occure', 'data'=>array() );
        $header_raw   = $request->header('Authorization');
        $header_raw_2   = str_replace('Bearer ', '', $header_raw);
        $header_raw_3   = str_replace(':', '', $header_raw_2);
        $header = trim($header_raw_3);
        
        if($header == "" || $header == null){
            $response['error']='Authorization failed';
        }else{

            if($client_id =='' && $client_id == Null){
                $response['error'] ='Some request parameters are missing please try again';
            }else{
                $update = Client::where('id',$client_id)->update($request->all());
                if($update){
                    $response['message']   = "Client updated successfully";
                    $response['success']   = true;
                    $response['data']    = $update;  
                }else{
                    $response['message'] = "Client not updated";
                } 
            }       
         
        }
        return response($response, 200);
    }





// END HERE CLASS
}
