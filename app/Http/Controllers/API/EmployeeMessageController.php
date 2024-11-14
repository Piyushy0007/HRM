<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;
use App\Employee;
use App\Client;
use App\Group;
use App\GroupUser;
use App\Admin;
use App\Mesagelastseen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use File;

class EmployeeMessageController extends Controller
{
    //
    /**
     * EMPLOYEE ID BY GET GROUPS
     */
    public function index(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $group = Group::with(['user','GroupUser.groupable'])
            ->where(function($q) use($request){
                $q->whereHas('GroupUser',function($q) use($request){
                    $q->where('groupable_id','=',$request['user_id']);
                    $q->Where('groupable_type','=',$request['user_type']);
                });
            })
            ->orderBy('created_at','DESC')
            ->get()
            ->toArray();


            foreach($group as $key=>$groupval){
                $messages_count = Message::where('group_id',$groupval['id'])->where('messageable_type','App\Admin')->where('seen','0')->count();
                $group[$key]['messages_count'] = $messages_count;
            }

            if($group):
                $response = ['status'=>true,'message'=>'Record Found','data'=>$group];
            else:
                $response = ['status'=>false,'message'=>'No Record Found!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


     /****
     *  EMPLOYEE CREATE MESSAGE GROUP
     * 
     */
    Public function EmployeeCreateThread(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            //$admin_id  = $request->admin_id;

            $recipients = $request->get('recipients');
           
            // GET HERE ALL ADMINS WITH IDS
            $adminID = [];
            if ( $recipients === 'all-admins' ) {
                $admins = Admin::whereNotNull('id')->get();
                foreach ($admins as $v) $adminID[] = $v->id;
            }          
           

            $employee = Employee::find($request->user_id);
            $create_group = new Group();
            $create_group->group_name =$request->subject;
            $create_group->user_type =$request->user_type;
            $create_group->user_id =$request->user_id;
            $create_group->save();
            $employee->groups()->save($create_group);

            $group_id = '';
            if(!empty($create_group)):
                $group_id  = $create_group->id;
            endif;
            // INSERT ADMIN HERE

            foreach (array_unique($adminID) as $recipientEmail):
                $admin = Admin::find($recipientEmail);
                $group_user = new GroupUser();
                $group_user->group_id = $group_id;
                $group_user->groupable_id  = $recipientEmail;
                $group_user->groupable_type ='admin';
                $group_user->save();
                $admin->groupuser()->save($group_user);
            endforeach;
            // $admin = Admin::find(1);
            // $group_user = new GroupUser();
            // $group_user->group_id = $group_id;
            // $group_user->groupable_id = '1';
            // $group_user->groupable_type = 'admin';
            // $group_user->save();
            // $admin->groupuser()->save($group_user);
            // END ADMIN HERE

            // INSERT Employee HERE
            $group_user = new GroupUser();
            $group_user->group_id = $group_id;
            $group_user->groupable_id = $request->user_id;
            $group_user->groupable_type = $request->user_type;
            $group_user->save();
            $employee->groupuser()->save($group_user);
            // END Employee HERE

            $message = new Message();
            $message->group_id = $group_id;
            $message->message = $request->message;
            $message->messageable_id =  $request->user_id;
            $message->messageable_type = $request->user_type;
            $message->from = 'employee';
            $message->message_type = 'text';
            $message->send_time = date('H:i:sa');
            $message->save();
            $employee->messages()->save($message);
            
            $lastseen = $this->updatelastSeen($group_id,$request->user_id,$request->user_type);
            $employee->seen()->save($lastseen);
            $user_data = Employee::find($request->user_id)->first();
            if($message){
                $response = ['status'=>true,'message'=>'Group Created succesfully','data'=>$create_group,'user'=>$user_data];
            }else{
                $response = ['status'=>false,'message'=>'group Not created'];
            }
            return response($response);

        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }  
    }


    
    function updatelastSeen($group_id,$seen_id,$seen_type){
        $lastseen= Mesagelastseen::where([
            'group_id' =>$group_id,
            'seenable_id'=>$seen_id,
            'seenable_type'=>$seen_type
        ])
        ->first();

        if($lastseen != null):
            $lastseen->group_id = $group_id;
            $lastseen->seenable_id =$seen_id;
            $lastseen->seenable_type =$seen_type;
            $lastseen->last_view_time =date('Y:m:d H:i:sa');
            $lastseen->save();
        else:
       
            $lastseen = new Mesagelastseen([
                'group_id' =>$group_id,
                'seenable_id'=>$seen_id,
                'seenable_type'=>$seen_type,
                'last_view_time'=> date('Y:m:d H:i:sa'),
            ]);
            $lastseen->save();
        endif;
        return $lastseen;
    }

   

     /***
     * GROUP ID BY GET GROUP MEMBER AND MESSAGES 
     */
    public function GetGroupMemberAndMesages($group_id,$groupable_id){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $message = Group::with('GroupUser.groupable','message.messageable')
            ->where('id',$group_id)
            ->first()
            ->toArray();

            $groupable_type = "App\Employee";
            $main_data = [];
                for($i = 0; $i< count($message['group_user']); $i++): 
                    $user = $message['group_user'][$i];
                    if($user['group_id'] == $group_id && $user['groupable_id'] == $groupable_id &&  $user['groupable_type'] == $groupable_type ):              
                        unset($user);
                    else:
                      $main_data[] =$user;  
                    endif;
                endfor;
            $message['group_user'] = $main_data;

            $getallMsg =  Message::where('group_id',$group_id)->where('messageable_type','App\Admin')->get(); 
            
            foreach($getallMsg as $getallMsgval){
                $employeeshowdata = Message::where('id','=',$getallMsgval->id)->update(['seen'=>1]); 
            }

            if(count($message) > 0):
                $response = ['status'=>true,'message'=>'Record Found','data'=>$message];
            else:
                $response = ['status'=>false,'message'=>'No Record Found!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }  
    }


    /***
     * EMPLOYEESEND MESSAGE IN GROUP
     */
    public function EmployeeSendMessageInGroup(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $messages = [];
                                
            if($request->input('image')): 
                
                $photo = $request->input('image');
                $name = uniqid().time().'.png';
                
                $store_with_paths =  Storage::disk('public')->put('messages/'.$name, base64_decode($photo));
                  
                $employee = Employee::find($request->employee_id);
                $message =new Message([
                    'group_id' =>$request['group_id'],
                    'message' => 'messages/'.$name,
                    'messageable_id' =>$request['employee_id'],
                    'messageable_type' =>$request['userable_type'],
                    'send_time' => date('H:i:sa'),
                    'message_type' =>'image',
                    'from' =>$request['from'],
                ]);
                $message->save(); 
                $employee->messages()->save($message);

                $lastseen = $this->updatelastSeen($request['group_id'],$request['employee_id'],$request['userable_type']);
                $employee->seen()->save($lastseen);

                $messages[] = $message;
            endif;
          
            if(!empty($request['message'])):
                $employee = Employee::find($request->employee_id);
                $message =new Message([
                    'group_id' => $request['group_id'],
                    'message' => $request['message'],
                    'messageable_id' =>$request['employee_id'],
                    'messageable_type' =>$request['userable_type'],
                    'send_time' => date('H:i:sa'),
                    'message_type' => 'text',
                    'from' => $request['from'],
                ]);
                $message->save();
                $employee->messages()->save($message);
                $lastseen = $this->updatelastSeen($request['group_id'],$request['employee_id'],$request['userable_type']);
                $employee->seen()->save($lastseen);

                if($message):
                    $messages[] = $message;
                endif;
            endif;
            $response = ['status'=> true, 'message'=>"Message send", 'data'=>$messages];

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


     // SEND LIST OF ADMIN
     public function allAdminList(){
        
        $response = ['status'=>false,'message'=>'Something wrong'];
        $admin = Admin::all();

        if(count($admin) > 0):
            $response = ['status'=>true, 'message'=>'Admin List','admin'=>$admin, ];
        else:
            $response = ['status'=>false, 'message'=>'No Admin'];
        endif;        
        return $response;
    }


    



}
