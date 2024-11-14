<?php

namespace App\Http\Controllers;

use App\Message;
use App\MessageGroup;
use App\Models\Shift;
use App\Employee;
use App\Client;
use App\Group;
use App\GroupUser;
use App\EmployeePosition;
use App\Admin;
use App\Mesagelastseen;
use App\Mail\MessageSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Carbon\Carbon;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
               
        // Conditions:
        // If any of the ff. are selected, the conditions are as follows:
        // - for 'everyone' and 'all-managers, ignore other selected options, this is to avoid additional queries
        // - if any 'pos-{posId}' has been selected and mixed with any employees, make sure to make distinction, to avoid
        //   duplicate sending of message of the same recipient(s)
        $recipients = $request->get('recipients');

        //dd($recipients);
        $arrRecipientsEmail = []; // This will store the e-mail that will recieved the e-mail]\
        $arrclientId = [];
        $admin = [];
        foreach ($recipients as $recipient) {
            if ( $recipient === 'everyone' ) {
                $arrRecipientsEmail = []; // reset
                $employee = Employee::select('id')
                                ->whereNotNull('id')
                                ->get();
                foreach ($employee as $v) $arrRecipientsEmail[] = $v->id;
                // $arrRecipientsEmail[] = $employee;
                //dd($arrRecipientsEmail);
                break;
            
            } else if ( $recipient === 'all-managers' ) {
                $arrRecipientsEmail = []; // reset
                $employee = Employee::select('id')
                                ->whereNotNull('id')
                                ->where('role', 'manager')->get();
                foreach ($employee as $v) $arrRecipientsEmail[] = $v->id;
                //dd($arrRecipientsEmail);
                // $arrRecipientsEmail[] = $employee;
                break;
            // For picking manually
            } else if ( strpos($recipient, 'pos-') !== false ) {
                $employee = DB::table('tblmq_employee_positions as ep')
                                ->select('e.id')
                                ->join('tblm_employee as e', 'ep.employee_id', '=', 'e.id')
                                    ->where('ep.position_id', explode('pos-', $recipient)[1])
                                    ->whereNotNull('e.id') // since there are employees that has not setup their email yet
                                    ->get();
                if ( count($employee) > 0 ) {
                    foreach ($employee as $v) $arrRecipientsEmail[] = $v->id;
                    //dd($arrRecipientsEmail);
                    continue;
                }
            } else if ( strpos($recipient, 'emp-') !== false ) {
                $employee = Employee::select('id')
                                ->whereNotNull('id')
                                ->where('id', explode('emp-', $recipient)[1])
                                ->get();
                foreach ($employee as $v) $arrRecipientsEmail[] = $v->id;
                //dd($arrRecipientsEmail);
                // $arrRecipientsEmail[] = $employee;
                continue;
            } else {
                // echo 'naboang ka?';
                // break;
            }
        }

        /**
         * Insert Admin
         *  
         * */
        $adminID = [];
        foreach ($recipients as $recipient) {
            if ( $recipient === 'everyone' ) {
                $adminID = []; // reset
                $admins = Admin::select('id')
                                ->whereNotNull('id')
                                ->get();
                foreach ($admins as $v) $adminID[] = $v->id;
                break;
            }else if( $recipient === 'all-admins'){
                $admin = []; // reset
                $admins = Admin::select('id')
                                ->whereNotNull('id')
                                ->get();
                foreach ($admins as $v) $adminID[] = $v->id;
                break;
            }else if ( strpos($recipient, 'admin-') !== false ) {
                $admins = Admin::select('id')
                                ->whereNotNull('id')
                                ->where('id', explode('admin-', $recipient)[1])
                                ->get();
                foreach ($admins as $v) $adminID[] = $v->id;
                continue;
            }
        }
       

        $arrclientId = [];
        foreach ($recipients as $recipient) {
            if ( $recipient === 'everyone' ) {
                $arrclientId = []; // reset
                $client = Client::whereNotNull('id')->get();
                foreach ($client as $v) $arrclientId[] = $v->id;
               //dd($arrclientId);
                break;            
            
            }  else if ( strpos($recipient, 'client-') !== false ) {
                $client = Client::select('id')
                                ->whereNotNull('id')
                                ->where('id', explode('client-', $recipient)[1])
                                ->get();
                foreach ($client as $v) $arrclientId[] = $v->id;
                //dd($arrRecipientsEmail);
                // $arrRecipientsEmail[] = $employee;
                continue;
            } else {
                // echo 'naboang ka?';
                // break;
            }
        }

        $admin = Admin::find($request->user_id);
    
        $create_group = new Group();
        $create_group->group_name =$request->subject;
        $create_group->user_type =$request->user_type;
        $create_group->user_id =$request->user_id;
        $create_group->save();
        $admin->groups()->save($create_group);

        $group_id = '';
        if(!empty($create_group)):
            $group_id  = $create_group->id;
        endif;
        $admin = Admin::find($request->user_id);
        $group_user = new GroupUser();
        $group_user->group_id = $group_id;
        $group_user->groupable_id  = $request->user_id;
        $group_user->groupable_type =$request->user_type;
        $group_user->save();
        $admin->groupuser()->save($group_user);
       
        /**Insert here admin */
        foreach (array_unique($adminID) as $recipientEmail):
            $admin = Admin::find($recipientEmail);
            $group_user = new GroupUser();
            $group_user->group_id = $group_id;
            $group_user->groupable_id  = $recipientEmail;
            $group_user->groupable_type =$request->user_type;
            $group_user->save();
            $admin->groupuser()->save($group_user);
        endforeach;

        foreach (array_unique($arrRecipientsEmail) as $recipientEmail):
            $employee = Employee::find($recipientEmail);
            $group_user = new GroupUser();
            $group_user->group_id = $group_id;
            $group_user->groupable_id = $recipientEmail;
            $group_user->groupable_type =$request->user_type;
            $group_user->save();
            $employee->groupuser()->save($group_user);
        endforeach;

      
         
        // ///  MESSAGE SEND TO CLIENT
        foreach (array_unique($arrclientId) as $recipientEmail):
            $client = Client::find($recipientEmail);
            $group_user = new GroupUser();
            $group_user->group_id = $group_id;
            $group_user->groupable_id = $recipientEmail;
            $group_user->groupable_type =$request->user_type;
            $group_user->save();
            $client->groupuser()->save($group_user);
        endforeach;

        $message = new Message([
            'group_id' =>$group_id,
            'message' => $request->message,
            'messageable_id' =>  $request->user_id,
            'messageable_type'=> $request->user_type,
            'from' => 'admin',
            'message_type' => 'text',
            'send_time'=>  date('H:i:sa'),
        ]);
        $message->save();
        $admin->messages()->save($message);

        $lastseen = $this->updatelastSeen($group_id,$request->user_id,$request->user_type);
        $admin->seen()->save($lastseen);
        

    }

    function updatelastSeen($group_id,$seen_id,$seen_type){
       // dd($seen_type);
        $lastseen= Mesagelastseen::where([
            'group_id' =>$group_id,
            'seenable_id'=>$seen_id,
            'seenable_type'=>$seen_type
        ])
        ->first();
        //dd($lastseen);
        if($lastseen != null):
            $lastseen->last_view_time =date('Y:m:d H:i:sa');
            $lastseen->update();
        else:
            $lastseen = new Mesagelastseen([
                'group_id' =>$group_id,
                'seenable_id'=>$seen_id,
                'seenable_id'=>$seen_type,
                'last_view_time'=> date('Y:m:d H:i:sa'),
            ]);
            $lastseen->save();
        endif;
        return $lastseen;
    }

    // function sortByOrder($a, $b) {
	// 	return $a['id'] - $b['id'];
	// }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        $response = ['status'=>false,'message'=>'Something Wrong'];

        $messages = Message::where('is_admin','!=', 1)
                ->groupBy('userable_id')
                ->groupBy('userable_type')
                ->orderBy('id','desc')
                ->get()
                ->toArray();
       // dd($messages);
        foreach($messages as &$msg) {
            if($msg['userable_type'] == 'client') {
                $msg['user'] = Client::where('id', $msg['userable_id'])->first()->toArray();
            }
            else if($msg['userable_type']  == 'employee') {
                $msg['user'] = Employee::where('id', $msg['userable_id'])->first()->toArray();
            }
        }
  
        //dd($messages);
        if(count($messages)> 0):
            $response = ['status'=>true,'message'=>'All Messages', 'data'=>$messages ];
        else:
            $response = ['status'=>false,'message'=>'No Message Found!'];
        endif;
        return response($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($userable_id)
    {
        $response = ['status'=>false,'message'=>'Something Wrong'];
        $is_admin = 1;
        $message = Message::where('userable_id','=',$userable_id)->update(['is_admin'=>$is_admin]);  
        if($message):
            $response = ['status'=>true,'message'=>'Record Deleted'];
        else:
            $response = ['status'=>false,'message'=>'Sorry, records not deleted!'];
        endif;
        return response($response);
    }

    /***
     * Return id my message
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function GetSingleEmployeeChat(Request $request,$userable_id,$type)
    {
        // dd($type);
        $response = ['status'=>false,'message'=>'Something Wrong'];
        if($userable_id == '' && $userable_id == NULL):
            $response = ['status'=>false,'message'=>'Requested parameter is wrong'];
        else:
            $message = Message::with($type)
                        ->where('userable_id','=', $userable_id)
                        ->where('userable_type','=',$type)
                        ->get()
                        ->toArray();
           //dd($message);
            $main_array = [];
           foreach($message as $messg):                
                if($messg['message_type'] == 'text'){
                    $messg['id'] = $messg['id'];
                    $messg['subject'] = $messg['subject']; 
                    $messg['message'] = $messg['message']; 
                    $messg['userable_id'] = $messg['userable_id']; 
                    $messg['userable_type'] = $messg['userable_type']; 
                    $messg['send_time'] = $messg['send_time'];
                    $messg['from'] = $messg['from']; 
                    if($type == 'client') 
                    {
                        $messg['user'] = Client::where('id', $messg['userable_id'])->first()->toArray();
                    }else if($type == 'employee'){
                        $messg['user'] = Employee::where('id', $messg['userable_id'])->first()->toArray();
                    }
                }else if($messg['message_type'] == 'image'){

                    $messg['id'] = $messg['id'];
                    $messg['subject'] = $messg['subject']; 
                    $messg['message'] = explode(',',$messg['message']); 
                    $messg['userable_id'] = $messg['userable_id']; 
                    $messg['userable_type'] = $messg['userable_type']; 
                    $messg['send_time'] = $messg['send_time'];
                    $messg['from'] = $messg['from'];
                    if($type == 'client') 
                    {
                        $messg['user'] = Client::where('id', $messg['userable_id'])->first()->toArray();
                    }else if($type == 'employee'){
                        $messg['user'] = Employee::where('id', $messg['userable_id'])->first()->toArray();
                    }
                }         
                $main_array[] = $messg;
           endforeach;
            if(count($main_array) > 0):
                $response = ['status'=>true,'message'=>'All Messages', 'data'=>$main_array];
            else:
                $response = ['status'=>false,'message'=>'No Message Found!'];
            endif;
        endif;
        return response($response);
                
    }


    /***
     * Admin send message to Single employee
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function ChatWithSingleEmployee(Request $request){

        $response = ['status'=>false,'message'=>'Something Wrong'];
        //dd($request->all());
        $images=[];
        $files   = $request->file('files');
        if($files != ''):
            $store_with_paths  =[];
            $full_path = [];
            foreach($files as $file){
                //dd($file->getClientOriginalName());
                $name = $file->getClientOriginalName();
                //$name = time().'.png';
                $store_with_paths[] =  Storage::disk('public')->put('messages',$file);
                // $store_with_paths[] = Storage::url('public/messages/'.$name);
                $images[]=$name;
                
            }
        endif;
       // dd($full_path);
        if(!empty($images)){
            $all_images = implode(",",$store_with_paths);
            $send_message = new Message([
                'userable_id'=>$request->userable_id,
                'userable_type'=>$request->userable_type,            
                'message'=>$all_images,
                'subject'=>$request->subject,
                'message_type'=>'image',
                'from'=>'admin',
                'send_time'=>date('H:i:sa')
            ]);
            $send_message->save();
            if($send_message):
                $response = ['status'=>true,'message'=>'All Messages', 'data'=>$send_message ];
            else:
                $response = ['status'=>false,'message'=>'No Message Found!'];
            endif;
        }

        if(!empty($request->message)){            
            $send_message = new Message([
                'userable_id'=>$request->userable_id,
                'userable_type'=>$request->userable_type,           
                'message'=>$request->message,
                'subject'=>$request->subject,
                'message_type'=>'text',
                'from'=>'admin',
                'send_time'=>date('H:i:sa')
            ]);
            $send_message->save();
            if($send_message):
                $response = ['status'=>true,'message'=>'All Messages', 'data'=>$send_message ];
            else:
                $response = ['status'=>false,'message'=>'No Message Found!'];
            endif;
        }
        return response($response);        
    }


    /****
     *  DELETE MULTIPLE RECORD FROM DATABASE
     * @MULTIPLE IDD
     * @parameter employees_ids
     */

    public function DeleteMutipleMessageByAdmin(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $is_admin = 1;
            $message = Message::whereIn('userable_id',$request['ids'])
                        ->where('userable_type',$request['type'])
                        ->update(['is_admin'=>$is_admin]);  
            if($message):
                $response = ['status'=>true,'message'=>'Record Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, records not deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }

    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    /***
     * GET GROUP HERE
     */
    public function GetGroups(Request $request){ 
        try{
            
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $groups = Group::with(['user','GroupUser.groupable'])
                    ->where(function($q) use($request){
                        $q->whereHas('GroupUser',function($q) use($request){
                            $q->where('groupable_id','=',$request['user_id']);
                            $q->Where('groupable_type','=',$request['user_type']);
                           //$q->Where('groupable_type','=',$request['user_type']);
                        });
                    })
                    ->get()
                    ->toArray();

            usort($groups, function ($item1, $item2) {
                return $item2['id'] <=> $item1['id'];
            });

            //echo "<pre>"; print_r($groups); die();
            
            // if(!empty($groups)):
            //     $response = ['status'=>true,'message'=>'Record Found','data'=>$groups];
            // else:
            //     $response = ['status'=>false,'message'=>'No Record Found!'];
            // endif;

            $main_array =[];
            foreach($groups  as $group):
                $groups_ar=[];
                $groups_ar['groups'] = $group;
                //$lastseen = Mesagelastseen::where('group_id',$group['id'])->where('seenable_id',$request['user_id'])->where('seenable_type',$request['user_type'])->first();
                   
                    //if($lastseen == null) {
                        $messages_count = Message::where('group_id',$group['id'])->where('messageable_type','App\Employee')->where('seen','0')->count();
                    // } else {
                    //     $messages_count = Message::where('group_id',$group['id'])->where('created_at','>',$lastseen['last_view_time'])->count();
                    // }
                    $groups_ar['messages_count'] =$messages_count;
                    $main_array[] =$groups_ar;
            endforeach;
           
        
            if(count($main_array)> 0):
                $response = ['status'=>true,'message'=>'Record Found','data'=>$main_array];
            else:
                $response = ['status'=>false,'message'=>'No Record Found!'];
            endif;
            return response($response);
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /***
     * GROUP ID BY GET USER AND CLIENT 
     */

    public function GetMessageByGroupId($group_id,$groupable_id){
        try{
           
            $groupable_type= 'App\Admin';
            $response = ['status'=>false,'message'=>'Something Wrong'];
            
            $message = Group::with('GroupUser.groupable','message.messageable')
            ->where('id',$group_id)
            ->first()
            ->toArray();
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
            $getallMsg =  Message::where('group_id',$group_id)->where('messageable_type','App\Employee')->get(); 
            
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


    /**
     * SEND MESSAGE IN GROUP
     */
    
    public function background_clock_out_notification(Request $request){
        $employeeshowdata = Employee::where('id','=',15)->update(['background_check'=>date('Y-m-d H:i:s')]); 
        
        $newmessage = "You are out of the boundry, current latitude: ".$request['currentLatitude'].' and Longitude: '.$request['currentLongitude'].' and user Id: '.$request['user_id'];

        $emplshowdata = Employee::where('id','=',15)->update(['background_data'=>$newmessage]); 

        $emp = Employee::where('id',$request['user_id'])->first();

            if($emp->deviceToken!=""){
              $curl = curl_init();
              $token = $emp->deviceToken;
              $message = "You are out of the boundry, current latitude: ".$request['currentLatitude'].' and Longitude: '.$request['currentLongitude'];
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
        $response = ['status'=>true,'message'=>$message];
    }

    public function background_reminder_notification(Request $request){
        
        $shifts = Shift::whereDate('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
            ->whereNull('deleted_at')
            ->get()->toArray();
        $getemployList = array();
        foreach($shifts as $valshifts){
            $getemployList[] = $valshifts['employee_id'];
        }
        $getemployList = array_unique($getemployList);
        $message = "Time to CheckIn";

        foreach($getemployList as $key=>$value){
            $emp = Employee::where('id',$value)->first();
            if($emp->deviceToken!=""){
                $curl = curl_init();
                $token = $emp->deviceToken;
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
        }
        $response = ['status'=>true,'message'=>$message];
    }


    public function background_reportreminder_notification(Request $request){
        
        $shifts = Shift::whereDate('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
            ->whereNull('deleted_at')
            ->get()->toArray();
        $getemployList = array();
        foreach($shifts as $valshifts){
            $getemployList[] = $valshifts['employee_id'];
        }
        $getemployList = array_unique($getemployList);
        $message = "Time to submit a report";

        // foreach($getemployList as $key=>$value){
        //     $emp = Employee::where('id',$value)->first();
        //     if($emp->deviceToken!=""){
        //         $curl = curl_init();
        //         $token = $emp->deviceToken;
        //         curl_setopt_array($curl, array(
        //           CURLOPT_URL => "http://3.142.74.2:7889/callpushNotification",
        //           CURLOPT_RETURNTRANSFER => true,
        //           CURLOPT_ENCODING => "",
        //           CURLOPT_MAXREDIRS => 10,
        //           CURLOPT_TIMEOUT => 30,
        //           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //           CURLOPT_CUSTOMREQUEST => "POST",
        //           CURLOPT_POSTFIELDS => '{"message": "'.$message.'", "token": "'.$token.'"}',
        //           CURLOPT_HTTPHEADER => array(
        //             "Content-Type: application/json"
        //           ),
        //         ));
        //         $response = curl_exec($curl);
        //         $err = curl_error($curl);
        //         curl_close($curl);
        //     }
        // }
        $response = ['status'=>true,'message'=>$message];
    }


    public function SendMessageinGroupAdmin(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

                $images=[];
                $files   = $request->file('files');
                if(!empty($files)):
                    $store_with_paths  =[];
                    foreach($files as $file):
                        $name = $file->getClientOriginalName();
                        $store_with_paths =  Storage::disk('public')->put('messages',$file);
                    
                        $admin = Admin::find($request->user_id);
                        $message =new Message([
                            'group_id' =>$request['group_id'],
                            'message' =>$store_with_paths,
                            'messageable_id' =>$request['user_id'],
                            'messageable_type' =>$request['user_type'],
                            'send_time' => date('H:i:sa'),
                            'message_type' =>'image',
                            'from' =>$request['from'],
                        ]);
                        $message->save(); 
                        $admin->messages()->save($message);

                        $lastseen = $this->updatelastSeen($request['group_id'],$request['user_id'],$request['user_type']);
                        $admin->seen()->save($lastseen);

                        if($message):
                            $response = ['status'=>true,'message'=>'Message Send','data'=>$message];
                        else:
                            $response = ['status'=>false,'message'=>'Message Not Send'];
                        endif;
                    endforeach;
                endif;
                if(!empty($request['message'])):
                    $admin = Admin::find($request->user_id);
                    $message =new Message([
                        'group_id' =>$request['group_id'],
                        'message' =>$request['message'],
                        'messageable_id' =>$request['user_id'],
                        'messageable_type' =>$request['user_type'],
                        'send_time' => date('H:i:sa'),
                        'message_type' =>'text',
                        'from' =>$request['from'],
                    ]);
                    $message->save(); 
                    $admin->messages()->save($message);
                    $getmsggroup = DB::table('message_group')->where('id',$request['group_id'])->first();
                    $emp = Employee::where('id',$getmsggroup->user_id)->first();
                    if($emp && $emp->deviceToken!=""){
                          $curl = curl_init();
                          $token = $emp->deviceToken;
                          $message = "Admin has sent you message on -".$getmsggroup->group_name;
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

                     //die();
                    $lastseen = $this->updatelastSeen($request['group_id'],$request['user_id'],$request['user_type']);
                    $admin->seen()->save($lastseen);
                   
                        if($message):
                            $response = ['status'=>true,'message'=>'Message Send','data'=>$message];
                        else:
                            $response = ['status'=>false,'message'=>'Message Not Send'];
                        endif;
                endif;
                    
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }  
    }


    /**
     * DELETE MESSAGE BY ADMIN 
     * 
     */

     public function DeleteMessageByAdmin(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $message = Message::where('group_id',$request['group_id'])->delete(); 
           
            if($message):
                $response = ['status'=>true,'message'=>'Record Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /***
     * SELECT ALL GROUP TO DELETE
     */
    public function SelectAllGroupDelete(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $group_ids = $request['group_ids'];
            $deleted_records = Group::whereIn('id',$group_ids);
            $deleted_records->delete();
           
            if($deleted_records):
                $grouuser = GroupUser::whereIn('group_id', $group_ids);
                $grouuser->delete();

                $message = Message::whereIn('group_id', $group_ids);
                $message->delete();

                $response = ['status'=>true,'message'=>'Records Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 

    }

    /**
     * DELETE SINGLE GROUP
     */
    public function DeleteSingleGroup(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $group = Group::where('id','=',$request['group_id']);
            $group->delete();
            if($group):
                $grouuser = GroupUser::where('group_id', $request['group_id']);
                $grouuser->delete();

                $message = Message::where('group_id', $request['group_id']);
                $message->delete();
                $response = ['status'=>true,'message'=>'Records Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }



    /**
     *  SEND GROUP ADMIN MESSAGE COUNT
     **/
    public function adminGroupMessageCount(Request $request){
        try{
            
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $groups = Group::with(['user','GroupUser.groupable'])
                    ->where(function($q) use($request){
                        $q->whereHas('GroupUser',function($q) use($request){
                            $q->where('groupable_id','=',$request['admin_id']);
                            $q->Where('groupable_type','=',$request['userable_type']);
                        });
                    })
                    ->get()
                    ->toArray();
                                
                $messages_count =0;
                $lastseen = [];
                foreach($groups as $group_id):
                    $lastseen[$group_id['id']] = Mesagelastseen::where('group_id',$group_id['id'])->where('seenable_id','=',$request['admin_id'])->where('seenable_type',$request['userable_type'])->first();
                endforeach;
              
                if(count($lastseen) > 0):
                    foreach($lastseen as $group_id => $value):
                        if($value == null) {
                            $messages_count += Message::where('group_id',$group_id)->count();
                        } else {
                            $messages_count += Message::where('group_id',$group_id)->where('created_at','>',$value->last_view_time)->count();
                        }
                        //dd($this->getEloquentSqlWithBindings($messages_count));
                    endforeach;
                endif;
            //    dd($messages_count);
            
            $messages_count = Message::where('messageable_type','App\Employee')->where('seen',0)->count();

            if($messages_count >0):
                $response = ['status'=>true,'message'=>'Mesage count','data'=>$messages_count];
            else:
                $response = ['status'=>false,'message'=>'Empty'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }



}
