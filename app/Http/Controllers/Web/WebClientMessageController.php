<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Message;
use App\Employee;
use App\Client;
use App\Group;
use App\GroupUser;
use App\Admin;
use App\Mesagelastseen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class WebClientMessageController extends Controller{

    public function ClientCreateMessageGroup(Request $request){
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
                // $employee = Employee::select('id')
                //                 ->whereNotNull('id')
                //                 ->where('id', explode('emp-', $recipient)[1])
                //                 ->get();
                // foreach ($employee as $v) $arrRecipientsEmail[] = $v->id;
                // //dd($arrRecipientsEmail);
                // // $arrRecipientsEmail[] = $employee;
                // continue;
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
            
            }  else if ( strpos($recipient, 'emp-') !== false ) {
                $client = Employee::select('id')
                                ->whereNotNull('id')
                                ->where('id', explode('emp-', $recipient)[1])
                                ->get();
                foreach ($client as $v) $arrclientId[] = $v->id;
                //dd($arrRecipientsEmail);
                //$arrRecipientsEmail[] = $employee;
                continue;
            } else {
                // echo 'naboang ka?';
                // break;
            }
        }

        $client = Client::find($request->user_id);
           
        $create_group = new Group();
        $create_group->group_name =$request->subject;
        $create_group->user_type =$request->user_type;
        $create_group->user_id =$request->user_id;
        $create_group->save();
        $client->groups()->save($create_group);

        $group_id = '';
        if(!empty($create_group)):
            $group_id  = $create_group->id;
        endif;
        // INSERT ADMIN HERE
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

        foreach (array_unique($arrclientId) as $recipientEmail):
            $useremp = Employee::find($recipientEmail);
            $group_user = new GroupUser();
            $group_user->group_id = $group_id;
            $group_user->groupable_id  = $recipientEmail;
            $group_user->groupable_type =$request->user_type;
            $group_user->save();
            $useremp->groupuser()->save($group_user);
        endforeach;


        // $admin = Admin::find(1);
        // $group_user = new GroupUser();
        // $group_user->group_id = $group_id;
        // $group_user->groupable_id = '1';
        // $group_user->groupable_type = 'admin';
        // $group_user->save();
        // $admin->groupuser()->save($group_user);
        // END ADMIN HERE

        // CLIENT INSERT HERE
        $group_user = new GroupUser();
        $group_user->group_id = $group_id;
        $group_user->groupable_id = $request->user_id;
        $group_user->groupable_type = 'admin';
        $group_user->save();
        $client->groupuser()->save($group_user);
        //END CLIENT HERE
        
        $message = new Message();
        $message->group_id =$group_id;
        $message->message = $request->message;
        $message->messageable_id = $request->user_id;
        $message->messageable_type = $request->user_type;
        $message->from = 'client';
        $message->message_type = 'text';
        $message->send_time = date('H:i:sa');
        $message->save();
        $client->messages()->save($message); 

        //dd($group_id);
        $lastseen = $this->updatelastSeen($group_id,$request->user_id,$request->user_type);
        $client->seen()->save($lastseen);
    }

    function updatelastSeen($group_id,$seen_id,$seen_type){
       
        $lastseen= Mesagelastseen::where([
            'group_id' =>$group_id,
            'seenable_id'=>$seen_id,
            'seenable_type'=>$seen_type
        ])
        ->first();

        if($lastseen != null):
            $lastseen->last_view_time =date('Y:m:d H:i:sa');
            $lastseen->save();
        else:
       
            $lastseen = new Mesagelastseen([
                'group_id' =>$group_id,
                'seenable_id' => $seen_id,
                'seenable_type'=> $seen_type,
                'last_view_time'=> date('Y:m:d H:i:sa'),
            ]);
            $lastseen->save();
        endif;
        return $lastseen;
    }

    /**
     * GET GROUP BY TYPE
     */
    public function GetGroups(Request $request){
        try{
            
            $response = ['status'=>false,'message'=>'Something Wrong'];

            if($request['user_type']=='App\\Client'){
                $getUserList = Employee::select('id')
                                ->where('added_by','=',$request['user_id'])
                                ->get()->toArray();
                $getnewar = array();
                $getnewar[] = $request['user_id'];
                foreach($getUserList as $getUserListval){
                    $getnewar[] = $getUserListval['id'];
                }
                //echo "<pre>"; print_r($getnewar);die();
                $groups = Group::with(['user','GroupUser.groupable'])->whereIn('user_id',$getnewar)
                            ->get()
                            ->toArray();
            }else {
        
                $groups = Group::with(['user','GroupUser.groupable'])
                    ->where(function($q) use($request){
                        $q->whereHas('GroupUser',function($q) use($request){
                            $q->where('groupable_id','=',$request['user_id']);
                            $q->Where('groupable_type','=',$request['user_type']);
                        });
                    })
                    ->get()
                    ->toArray();
            }
           
            
            $main_array =[];
            foreach($groups  as $group):
                $groups_ar=[];
                $groups_ar['groups'] = $group;
                $lastseen = Mesagelastseen::where('group_id',$group['id'])->where('seenable_id',$request['user_id'])->where('seenable_type',$request['user_type'])->first();
                   
                    if($lastseen == null) {
                        $messages_count = Message::where('group_id',$group['id'])->count();
                    } else {
                        $messages_count = Message::where('group_id',$group['id'])->where('created_at','>',$lastseen['last_view_time'])->count();
                    }
                    $groups_ar['messages_count'] =$messages_count;
                    $main_array[] =$groups_ar;
            endforeach;
           
        
            if(count($main_array)> 0):
                $response = ['status'=>true,'message'=>'Record Found','data'=>$main_array];
            else:
                $response = ['status'=>false,'message'=>'No Record Found!'];
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
     * GROUP ID BY GET GROUP MEMBER AND MESSAGES 
     */
    public function GetGroupUserAndMesages($group_id,$user_id){
        try{

            
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $message = Group::with('GroupUser.groupable','message.messageable')
            ->where('id',$group_id)
            ->first()
            ->toArray();
                   
            $client = Client::find($user_id);
            $lastseen = $this->updatelastSeen($group_id,$user_id,'App\Client');
            $client->seen()->save($lastseen);

            $groupable_type = "App\Client";
            $main_data = [];
                for($i = 0; $i< count($message['group_user']); $i++): 
                    $user = $message['group_user'][$i];
                    if($user['group_id'] == $group_id && $user['groupable_id'] == $user_id &&  $user['groupable_type'] == $groupable_type ):              
                        unset($user);
                    else:
                      $main_data[] =$user;  
                    endif;
                endfor;
            $message['group_user'] = $main_data;


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
    public function SendMesageInGroup(Request $request){
        try{
            //dd($request->all());
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $files   = $request->file('files');
                if(!empty($files)):
                    foreach($files as $file):

                        $name = $file->getClientOriginalName();
                        $store_with_paths =  Storage::disk('public')->put('messages',$file);

                        $client = Client::find($request->user_id);
                        $message =new Message([
                            'group_id' =>$request['group_id'],
                            'message' => $store_with_paths,
                            'messageable_id' =>$request['user_id'],
                            'messageable_type' =>$request['user_type'],
                            'send_time' => date('H:i:sa'),
                            'message_type' =>'image',
                            'from' =>$request['from'],
                        ]);
                        $message->save(); 
                        $client->messages()->save($message);

                        $lastseen = $this->updatelastSeen($request['group_id'],$request['user_id'],$request['user_type']);
                        $client->seen()->save($lastseen);

                        if($message):
                            $response = ['status'=>true,'message'=>'Message Send','data'=>$message];
                        else:
                            $response = ['status'=>false,'message'=>'Message Not Send'];
                        endif;
                    endforeach;
                endif;
                if(!empty($request['message'])):
                    $client = Client::find($request->user_id);
                    $message =new Message([
                        'group_id' => $request['group_id'],
                        'message' => $request['message'],
                        'messageable_id' =>$request['user_id'],
                        'messageable_type' =>$request['user_type'],
                        'send_time' => date('H:i:sa'),
                        'message_type' => 'text',
                        'from' => $request['from'],
                    ]);
                    $message->save();
                    $client->messages()->save($message);
                    $lastseen = $this->updatelastSeen($request['group_id'],$request['user_id'],$request['user_type']);
                    $client->seen()->save($lastseen);
                  
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
     *  DELETE MESSAGE BY CLIENT
     * 
     */
    public function MessageDeleteByClient(Request $request){
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


    /**
     * DELETE MULTIPLE GROUPS
     */
    public function DeleteMultipleGroup(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            
            $group_delete = Group::whereIn('id',$request['group_ids']);
            $group_delete->delete();

            if($group_delete):
                $group_user = GroupUser::whereIn('group_id',$request['group_ids']);
                $group_user->delete();
                $message = Message::whereIn('group_id',$request['group_ids']);
                $message->delete();

                $response = ['status'=>true,'message'=>'Records Deleted!'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }



    /**
     * DELETE GROUP
     */

    public function DeleteGroup(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            
            $group_delete = Group::where('id',$request['group_id']);
            $group_delete->delete();

            if($group_delete):
                $group_user = GroupUser::where('group_id',$request['group_id']);
                $group_user->delete();
                $message = Message::where('group_id',$request['group_id']);
                $message->delete();

                $response = ['status'=>true,'message'=>'Records Deleted!'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }

    }


    /**
     * DELETE  MESSAGE
     */

     public function DeleteSingleMessage(Request $request){
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $message  = Message::where('id',$request['message_id']);
            $message->delete();

            if($message):
                $response = ['status'=>true,'message'=>'Records Deleted!'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records Not Deleted!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }



    /**
     * get group message count
     */
    public function ClientMessageCount(Request $request){
        try{
            
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $groups = Group::with(['user','GroupUser.groupable'])
                    ->where(function($q) use($request){
                        $q->whereHas('GroupUser',function($q) use($request){
                            $q->where('groupable_id','=',$request['user_id']);
                            $q->Where('groupable_type','=',$request['user_type']);
                        });
                    })
                    ->get()
                    ->toArray();
                                
                $messages_count =0;
                $lastseen = [];
                foreach($groups as $group_id):
                    $lastseen[$group_id['id']] = Mesagelastseen::where('group_id',$group_id['id'])->where('seenable_id','=',$request['user_id'])->where('seenable_type',$request['user_type'])->first();
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