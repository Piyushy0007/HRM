<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications;
use JWTFactory;
use JWTAuth;
use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;

class EmployeeNotification extends Controller
{

    public function allNotication($employee_id){

        $response=['status'=>false,'message'=>'Something Went Wrong']; 
        
        $notification = Notifications::with('employee')->where('employee_id',$employee_id)->get()->toArray();

        if(count($notification)> 0){
            $response = ['status'=>true, 'message'=>'All Notification','data'=>$notification];
        }else{
            $response = ['status'=>false, 'message'=>'No any Notification'];
        }
        return response($response);
    }

   
    // UPDATE NOTIFICATION 
    public function updateNotication(Request $request){

        $response=['status'=>false,'message'=>'Something Went Wrong']; 
        
        $data = ['seen'=>1];
        $notification = Notifications::where('id',$request['id'])->update($data);

        if($notification){
            $response = ['status'=>true, 'message'=>'Notifaction seen',];
        }else{
            $response = ['status'=>false, 'message'=>'No notification'];
        }
        return response($response);
    }



}
