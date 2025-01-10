<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    //

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
       // dd($recipients);
        $arrRecipientsEmail = []; // This will store the e-mail that will recieved the e-mail]\
        $arrclientId = [];
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

        //dd($arrRecipientsEmail);
        foreach (array_unique($arrRecipientsEmail) as $recipientEmail) {
            // dd($recipientEmail);
            //Mail::to($recipientEmail)->send(new MessageSend( $request->all() ));
            $message = new Message();
            $message->userable_id =$recipientEmail;
            $message->userable_type ='employee';
            $message->subject = $request->subject;
            $message->message = $request->message;
            $message->from = 'admin';
            $message->message_type = 'text';
            $message->send_time = date('H:i:sa');
            $message->save();         
        }        

        ///  MESSAGE SEND TO CLIENT
      
        foreach (array_unique($arrclientId) as $recipientEmail) {
            
            //dd($recipientEmail);
            $message = new Message();
            $message->userable_id =$recipientEmail;
            $message->userable_type ='client';
            $message->subject = $request->subject;
            $message->message = $request->message;
            $message->from = 'admin';
            $message->message_type = 'text';
            $message->send_time = date('H:i:sa');
            $message->save();           
        }
    }


    function sortByOrder($a, $b) {
		return $a['id'] - $b['id'];
	}

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


































//     public function store(Request $request)
//     {
     
//         // clean this up
//         // format it to [date, date, 0, date, date, date, 0]; example
//         $selectedDate = $request->get('week_of');
//         $start = Carbon::create( $selectedDate )->startOfWeek()->format('Y-m-d');
//         $end = Carbon::create( $selectedDate )->endOfWeek()->format('Y-m-d');

//         // This will provide the interval period based on the supplied data
//         $period = CarbonPeriod::create($start, $end);

//         // Iterate over the period
//         $date = [];
//         foreach ($period as $v) $date[] = $v->format('Y-m-d');

//         $shiftDateAddedTo = $request->get('added_to');
//         // dd($shiftDateAddedTo);
//         // This will compare the date value inside $selectedDate, which will then be
//         // compared if the date is valid on that specific period or not. If not, it'll
//         // return a value of 0
//         // Note:
//         //  - take note, this is actually intended for "added_to" field inside the shift
//         //    table, which will be subject for removal in favor of "ShiftDate" model
//         //  - this whole thing is actually unnecessary,
//         $validDatePeriod = [];
//         for ($i = 0; $i < count($date); $i++) {
//             if (in_array($date[$i], $shiftDateAddedTo)) {
//                 array_push($validDatePeriod, $date[$i]);
//             } else {
//                 array_push($validDatePeriod, 0);
//             }
//         }
//         /**
//          * get here client id by client property
//          * which properties is assign to employee 
//          */
//         $requestBody = [
//             "week_of" => $request->get('week_of'),
//             "start" => $request->get('start'),
//             "end" => $request->get('end'),
//             "description" => $request->get('description'),
//             "notify_affected_employees" => $request->get('notify_affected_employees') || false,
//             "paid_hours" => (int) $request->get('paid_hours'),
//             "position_id" => $request->get('position'),
//             "added_to" => implode('~', $validDatePeriod), // subject for removal
//             "color" => $request->input('color'),
//             "client_property_id"=>$request->get('property_id'),
//             "lunch_start"=>$request->get('lunch_start'),
//             "lunch_end"=>$request->get('lunch_end'),
//             "break_start"=>$request->get('break_start'),
//             "break_end"=>$request->get('break_end'),
//         ];
     
//         //dd($request->all());
//         // Note:
//         // - if being added to the same shift_id and position, should only add to ShiftEmployee
//         // - should update as well the 'added_to' column
//         // - should update start, end accordingly
//         // 
//         // 
//         // Okay naman diay, ang problem lang if kung mag.add ug new schedule, dapat mabutang rajud xa dadtos ubos.. fuck this
//         // $checkExistenceOfShift = Shift::selectRaw('id, count(id) as counter')
//         //                                 ->where('week_of', $request->get('week_of'))
//         //                                 ->where('position_id', $request->get('position'))
//         //                                 ->where('start', $request->get('start')) // ?? or ang end?
//         //                                 ->first();

//         // if existing already
// //dd($validDatePeriod);
        
//         $checkExistenceOfShift = $this->checkExistenceOfShift('2021-05-27',$request->position,$request->start,$request->end,$employees);
       
       
//         if ( $checkExistenceOfShift->counter > 0 ) {
//             // Update added to
//             // Note:
//             //  - update, tblmq_shift_added_to has been uploaded, so "added_to" will then be removed (08-24)
//             if ( $request->get('employees') && count($request->get('employees')) > 0 ) {
//                 foreach ($request->get('employees') as $v) {
//                     ShiftEmployee::create([
//                         'shift_id' => $checkExistenceOfShift->id,
//                         'employee_id' => $v,
//                     ]);
//                 }
//                 // Supply "ShiftDate"
//                 for ($i = 0; $i < count($date); $i++) {
//                     if (in_array($date[$i], $shiftDateAddedTo)) {
//                         ShiftDate::create([
//                             'date' => $date[$i],
//                             'shift_id' => $checkExistenceOfShift->id
//                         ]);
//                     }
//                 }

//                 return response()->json(['data' => Shift::find($checkExistenceOfShift->id)]);

//                 // return response('', 201)
//                 //     ->header('Location', url("/api/shifts/{$checkExistenceOfShift->id}"));
//             }
//             return response()->json(['data' => '', 'message' => 'Error: At least (1) one employee is required!']);
//         }

//         // if not existing
//         $shift = Shift::create( $requestBody );
    
//         if ( $shift ) {
//             if ( $request->get('employees') && count($request->get('employees')) > 0 ) {
//                 foreach ($request->get('employees') as $v) {
//                     ShiftEmployee::create([
//                         'shift_id' => $shift->id,
//                         'employee_id' => $v,
//                     ]);
//                 }
//                 // Supply "ShiftDate"
//                 for ($i = 0; $i < count($date); $i++) {
//                     if (in_array($date[$i], $shiftDateAddedTo)) {
//                         ShiftDate::create([
//                             'date' => $date[$i],
//                             'shift_id' => $shift->id
//                         ]);
//                     }
//                 }
//                 // return response('', 201)->header('Location', url("/api/shifts/{$shift->id}"));
//                 return response()->json(['data' => Shift::find($shift->id)]);
//             }
//             return response()->json(['message'=>'At least (1) one employee is required!']);
//            // return response('Error: At least (1) one employee is required!', 500);
//         }
//         return response('Error', 500);
//     }





    /*
    * employee week off schedule
    */
    public function TimeOffRequest(Request $request){
        try{
            $response =  ['status'=>false,'message'=>'Something wrong'];
            // time off schedule request 
            $time_off_date = json_decode($request['time_off']);
           
                // if(count($time_off_date) >0 ){
                //     $response =['status'=> false,'message'=>'Please select at least one date'];
                // }else{
                   
                // // check here user has already shift or not 
                // $employee = Employee::where('id', $request['employee_id'])->with(['shifts'])->get();

                // $olddates = [];
                // foreach($employee as $emp):          
                //     foreach($emp->shifts as $shift):
                //         $olddates = explode("~", $shift->added_to);            
                //     endforeach;
                // endforeach;
                
                // // CHECK DIFFERENCE DATE 
                // $reverse_diff = array_diff($time_off_date, $olddates);
                // IMPLODE HERE NEW DATES
                //$implode_diffrent = implode('~', $reverse_diff);
                
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
                    $response =['status'=> true,'message'=>'Your time off Request is send','response'=>$Timeoff];
                else:
                    $response = ['status'=>false, 'message'=>'Your time off request not send'];
                endif;
            
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }

    }




    
    public function detectClockInEmployeeposition(Request $request){

        $response = array('status'=>false,'message'=>'Something Wrong');
        $clockin_id = Clockin::whereDate('created_at','=',Carbon::now())
                 ->orderByDesc('id')
                 ->groupBy('employee_id')
                 ->first();
        $value = null;
        if($clockin_id != ''){
            $value = $clockin_id->id;
        }
        $DetectShift = new DetectShift([
            'employee_id' => $request['employee_id'],
            'clock_in_time'=>$request['start_time'],
            'shift_id' =>$request['shift_id'],
            'checked_id' =>$value,
            'onShift' =>'false',
            'date'=> date('Y-m-d'),
        ]);
            //dd($DetectShift);
        if($DetectShift->save()){
            $response  = [ 'status' =>true,'message'=>'You are in your location','data'=>$DetectShift];
        }else{
            $response  = ['status' =>false,'message'=>'You are not in your location'];
        }           
        return response($response);
       
    }

    public function DetectClockOutEmployeePosition(Request $request){
        $response=array('status'=>false,'message'=>'Something Wrong');
        $clockin_id = Clockin::whereDate('created_at','=',Carbon::now())
                ->orderByDesc('id')
                ->groupBy('employee_id')
                ->first();
        $value = null;
        if($clockin_id != ''){
            $value = $clockin_id->id;
        }
    
        $DetectShift = new DetectShift([
            'employee_id' => $request['employee_id'],
            'clock_out_time'=>$request['end_time'],
            'shift_id' =>$request['shift_id'],
            'checked_id' => $value,
            'onShift' =>'false',
            'date'=> date('Y-m-d'),
        ]);
        //dd($DetectShift);
        if($DetectShift->save()){
            $response  =['status' =>true, 'message'=>'Your are out off location', 'data'=>$DetectShift];
        }else{
            $response  = ['status' =>false, 'message'=>'something wrong'];
        }
        return response($response);
    }



    public function CurrentShiftProperty($employee_id){

        $shift = $this->TodaydateShifIsExist($employee_id);
        $property = [];
        if($shift):     

            foreach($shift as $emp):
                foreach($emp['shifts'] as $shiftdata):
                    //dd($shiftdata);
                    $current_time = date('H:i:m');
                    if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                       return  $property[] = $shiftdata['property'];
                    endif;
                endforeach;
            endforeach;           
        endif;
    }


    public function CurrentShiftReturn($employee_id){

        $shift = $this->TodaydateShifIsExist($employee_id);
        $SHIFT = [];
        if($shift):      

            foreach($shift as $emp):
                foreach($emp['shifts'] as $shiftdata):
                    //dd($shiftdata);
                    $current_time = date('H:i:m');
                    if($current_time >=  $shiftdata['start']  && $current_time  <= $shiftdata['end']  ):
                       return  $SHIFT[] = $shiftdata;
                    endif;
                endforeach;
            endforeach;           
        endif;
    }

     // ROUTE FOR ALL LOCATION
    // Route::get('locations',[EmployeeController::class,'LocationName']);
    public function LocationName(){
        // dd('sdgfds');
         $properties = ClientProperties::all();
         if(count($properties) > 0):
             $response = ['status' =>true,'message' =>'Record Found!','data'=> $properties];
         else :
             $response = ['status' =>false, 'message' =>'No Record Found!'];
         endif;
         return response($response);
     }
 

     // CLOCK IN/OUT LOG INSERT
    // Route::post('detectin', [EmployeeController::class,'detectClockInEmployeeposition']);
    // Route::post('detectout', [EmployeeController::class,'DetectClockOutEmployeePosition']);
    // // Employee message send
    // Route::get('getmessage/{id}',[EmployeeController::class,'fetchMessages']);
    // Route::post('message',[EmployeeController::class,'MessageByEmployee']);


    /*
    * PHONE NUMBER VERIFICATION CREATE
    *  SEND HERE THE RANDOM VERIFICATION CODE
    */
     //Route::post('send-sms', [EmployeeController::class,'sendOtp']);
    public function sendOtp(Request $request){
        return response()->json([
            'message' => 'Mobile Number dont exist. Please check your number you have entered',
         ], 201);
}

 /*
 * End Here create otp
* PHONE NUMBER VERIFICATION CREATE
*  SEND HERE THE RANDOM VERIFICATION CODE
*/



/*
* PHONE NUMBER VERIFICATION
* BY TWILLO
*/
//Route::post('verify', [EmployeeController::class,'verifyOtp']);
public function verifyOtp(Request $request){   
    return response()->json([
       'message' => 'Request token missing access denied!',
    ], 201);

}

/*
* End here the verification  code
* PHONE NUMBER VERIFICATION
* BY TWILLO
*/



public function ClientGetMessage($client_id,$type){
    //dd($employee_id);
       $response = ['status'=>false,'message'=>'Something Wrong'];
       if($client_id == '' && $client_id == NULL):
           $response = ['status'=>false,'message'=>'Requested parameter is wrong'];
       else:
           $message = Message::with($type)
                       ->where('userable_id','=', $client_id)
                       ->where('userable_type','=', $type)
                       ->get()
                       ->toArray();
                       //dd($message);
           $main_array = [];
           foreach($message as $msg):
               $message_array = [];
               if($msg['message_type'] == 'image'){
                   $message_array['id'] =$msg['id'];
                   $message_array['userable_id'] =$msg['userable_id'];
                   $message_array['userable_type'] =$msg['userable_type'];                          
                   $message_array['subject'] =$msg['subject'];
                   $message_array['from'] =$msg['from'];                   
                   $message_array['send_time'] =$msg['send_time'];
                   $message_array['message_type'] =$msg['message_type'];
                   $message_array['message'] =explode("," ,$msg['message']);
                   $message_array['user'] =$msg['client'];

               }else if($msg['message_type'] == 'text'){
                   $message_array['id'] =$msg['id'];
                   $message_array['userable_id'] =$msg['userable_id'];
                   $message_array['userable_type'] =$msg['userable_type'];                          
                   $message_array['subject'] =$msg['subject'];
                   $message_array['from'] =$msg['from'];                   
                   $message_array['send_time'] =$msg['send_time'];
                   $message_array['message_type'] =$msg['message_type'];
                   $message_array['message'] = $msg['message'];
                   $message_array['user'] =$msg['client'];
               }
               $main_array[] = $message_array;
               
           endforeach;
           if(count($main_array) > 0):
               $response = ['status'=>true,'message'=>'All Messages', 'data'=>$main_array];
           else:
               $response = ['status'=>false,'message'=>'No Message Found!'];
           endif;
       endif;
       return response($response);
   }

   public function AdminGetOfficerLogs(Request $request){
    // dd(Carbon::today()->toDateString());
     $response = ['status'=>false,'message'=>'Something wrong'];
     $officelogs = Clockin::with('employee','shift','detectShift')->whereDate('created_at', Carbon::today()->toDateString())->get();
     $logs = $officelogs->toArray();
     dd($logs);
     $logarray = array();
     foreach($logs as $log){
        //dd($log);
         $emp =[];
         $emp['firstname']  = $log['employee']['firstname'];
         $emp['lastname']  = $log['employee']['lastname'];
         $emp['start']  = $log['shift']['start'];
         $emp['end']  = $log['shift']['end'];
         $emp['lunch_start']  = $log['shift']['lunch_start'];
         $emp['lunch_end']  = $log['shift']['lunch_end'];
         $emp['break_start']  = $log['shift']['break_start'];
         $emp['break_end']  = $log['shift']['break_end'];
        
         foreach($log['detect_shift'] as $logrp){
             // dd($logrp);
             $logmdata = [];
             $logmdata['clock_in_time'] = $logrp['clock_in_time'];
             $logmdata['clock_out_time']=$logrp['clock_out_time'];
             $logmdata['date']=$logrp['date'];
             $emp['perimeter'][] = $logmdata;
         }
         $logarray[] = $emp;
     }
     if(count($logarray) > 0){
         $response = ['status' =>true,'message'=>'Today officer logs','data' =>$logarray];
     }else{
         $response = ['status' =>false, 'message'=>'Today no officer logs'];
     }        
     return response($response);
 }
 


 public function MonthSchedule($employee_id){
    try{

    $response=['status'=>false,'message'=>'something wrong']; 
    $date = Carbon::today();
   
    // GET EMPLOYE SHIFT DETAIL WITH DATE AND TIME
    $employee = ShiftEmployee::with(['employee', 'shift','shift.property','timeoff'])
            ->Where(function($q){
                    $q->whereHas('shift',function($query) {
                        $query->whereBetween('added_to', [Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()]);  
                    });
                })
            ->where('employee_id', $employee_id)
            ->get()
           ->toArray();               
       //dd($this->getEloquentSqlWithBindings($employee));  
       //dd($employee);
    if(count($employee) > 0):         
      
        $send_schedule = [];
        $send_timeoff = []; 
        $listing = [];         
        // send here the user shift data
        foreach ($employee as $key => &$emp):
          
                //  GET HERE SHIFT ADDED TO                        
                $dates =  explode("~", $emp['shift']['added_to']);
                //SHIFT SCHEDULE 
                for($i = 0; $i < count($dates); $i++) {
                    $send_schedule[$dates[$i]] =$emp['shift'];
                    // $send_schedule[$dates[$i]] = [
                    //     'shift'=> $emp['shift']
                    //         // 'start_time' => date("H:i:s", strtotime($emp['shift']['start'])),
                    //         // 'end_time' => date("H:i:s", strtotime($emp['shift']['end'])),
                    //         // 'lunch_start' => date("H:i:s", strtotime($emp['shift']['lunch_start'])),
                    //         // 'lunch_end' => date("H:i:s", strtotime($emp['shift']['lunch_end'])),
                    //         // 'break_start' => date("H:i:s", strtotime($emp['shift']['break_start'])),
                    //         // 'break_end' => date("H:i:s", strtotime($emp['shift']['break_end'])),
                    //         // 'property' => $emp['shift']['property']
                    // ];
                }

            // TIME OFF SCHEDULE
            foreach($emp['timeoff'] as $time):
                $timeofed = explode('~',$time['time_off']);
                $timeoff = array_unique($timeofed);

                for($i = 0; $i < count($timeoff); $i++) {
                    $send_timeoff[$timeoff[$i]] = [
                        'description' => $time['description'],                  
                        'status' =>  $time['status'],
                        'whole_day'=> $time['whole_day'],
                        'from_time'=> $time['from_time'],
                        'to_time'=> $time['to_time'],
                    ];
                }                  
            endforeach;                         
        endforeach;
       dd($send_schedule);
        $timeoff_key = [];
        foreach($send_timeoff as $key=>$schedule):
            if($schedule['whole_day'] == 'true'):
                $timeoff_key[] = $key;
            endif;
        endforeach;
        //dd($timeoff_key);
       
        foreach($send_schedule as $key=>$schedule):
            //dd($timeoff_key);
            if(!in_array($key, $timeoff_key)):
               $listing[$key] = $schedule; 
            endif;
        endforeach;
        
            $response =['status'=>true,'message'=>'Monthly schedule','send_schedule'=>$send_schedule,'send_timeoff' =>$send_timeoff,'listing'=>$listing];             
        else:
            $response = ['status'=>false,'message'=>'This month No schedule'];     
        endif;

        return response($response);
    }catch (\Exception $e) {
        return response()->json(['error'=>$e->getMessage()]);   
    }
   }





    /*
   * check here employee distance
   */
  public function getDistance($employee_lat, $employee_long, $property_lat, $property_long) {
    $earth_radius = 100;

    $dLat = deg2rad($property_lat - $employee_lat);
    $dLon = deg2rad($property_long - $employee_long);

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($employee_lat)) * cos(deg2rad($property_lat)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;

    return $d;
  }




  
    /*
    * check here employee start time
    */

    // public function detect_clock_button($start_time, $end_time, $current_time,$employee_id,$latitude,$longitude){
    //     $date = Carbon::now();
    //     $current_date = $date->toDateString();
    //     // CURRENT TIME
    //     $current_time = $date->toTimeString();

    //     if($start_time <= $current_time){

    //         $clockin = new Clockin([
    //                 'employee_id' => $employee_id,
    //                 'start_time'=>$start_time,
    //                 'latitude'=>$latitude,
    //                 'longitude'=>$longitude,
    //                 'shiftaction'=> 'true',
    //             ]);
    //         $clockin->save();
    //         $clockin->id;

    //         $DetectShift = new DetectShift([
    //                 'employee_id' => $employee_id,
    //                 'checking_id' => $clockin->id,
    //                 'clock_in_time'=> $current_time,
    //                 'onShift' =>'true',
    //                 'date'=> $current_date,
    //             ]);
    //         $DetectShift->save();

    //         $response=['status'=>true,'message'=>'Check in on time, on property','data'=>$DetectShift, 'clockin'=>$clockin];

    //     }else if($end_time >= $current_time){
    //         $clockin = ['employee_id' => $employee_id, 'shiftaction'=> false];
    //         $clockin =Clockin::where('employee_id',$employee_id)->update($clockin);

    //         $DetectShift = new DetectShift([
    //                 'employee_id' => $employee_id,
    //                 'checking_id' => $clockin,
    //                 'clock_out_time'=>$current_time,
    //                 'onShift' =>false,
    //                 'date'=> $current_date,
    //             ]);
    //         $DetectShift->save();
    //         $response = ['status'=>false,'message'=>'Check in early, not on property'];
    //     }
    //     return response($response, 200);
    // }


    // public function get_list_of_employee(Request $request){

    //     // $week_of = $request->get('created_date');
    //     // //dd($week_of);
    //     // $shift = Shift::with('employee')->whereDate('added_to','LIKE', "%".$week_of."%")->get();
    //     // //dd($this->getEloquentSqlWithBindings($shift));
    //     // $already_shift = $shift->toArray();
    //     // //dd($already_shift);
    //     // $employeeIds = [];
    //     // foreach($already_shift as $value){
    //     //     foreach($value['employee'] as $emp){
    //     //         $employeeIds[] = $emp['id'];   
    //     //     } 
    //     // }
    //     //dd($employeeIds);
    //     // $employee = Employee::whereNotIn('id', $employeeIds)->get();

    //     $employee = EmployeePosition::with('employee')->where('position_id',$request['position_id'])->get()->toArray();
    //     $employee_list = [];
    //     foreach($employee as $empl):
    //        if($empl['employee'] != null):
    //             $employee_list[] = $empl['employee'];
    //        endif;
    //     endforeach;
    //     if(count($employee_list) > 0):
    //         $response = ['status'=>true, 'employee'=>$employee_list, 'message'=>'Employee List'];
    //     else:
    //         $response = ['status'=>false, 'message'=>'No Employee'];
    //     endif;
        
    //   // dd($employee_list);
    //     //$employee = Employee::get();
    //     return $response;

    // }





    // public function store(Request $request)
    // {
    //     try{
    
    //         // format it to [date, date, 0, date, date, date, 0]; example
    //         $selectedDate = $request->week_of;

    //         /**
    //          *  $selectedDate VARIABLE  BY CREATE WEEK START AND END WEEK  
    //          */
    //         $start = Carbon::create( $selectedDate )->startOfWeek()->format('Y-m-d');
    //         $end = Carbon::create( $selectedDate )->endOfWeek()->format('Y-m-d');

    //         // This will provide the interval period based on the supplied data
    //         $period = CarbonPeriod::create($start, $end);

    //         // Iterate over the period
    //         $date = [];
    //         foreach ($period as $v) $date[] = $v->format('Y-m-d');
            
    //         /**
    //          * SHIFT DATA SELECTED BY ADMIN GET HERE
    //          *  ADDED_TO DATES
    //          */
    //         $shiftDateAddedTo = $request->added_to;
            
    //         $validDatePeriod = [];
    //         for ($i = 0; $i < count($date); $i++) {
    //             if (in_array($date[$i], $shiftDateAddedTo)) {
    //                 array_push($validDatePeriod, $date[$i]);
    //             } else {
    //                 array_push($validDatePeriod, 0);
    //             }
    //         }

    //         //dd($validDatePeriod);

    //         $checkExistenceOfShift = $this->checkExistenceOfShift($validDatePeriod,$request->position,$request->start,$request->end,$request->employees);
           
    //         //dd($checkExistenceOfShift);
    //         if($checkExistenceOfShift){
    //             return response()->json(['status'=>false,'message'=>'Shift already exist', 'data'=>$checkExistenceOfShift]);
    //         }else{
        

    //              /***
    //              *  GET ADD SHIFT FIELD DATA
    //              *  CREATE AN ARRAY TO INSERT IN SHIFT TABLE
    //              ***/
    //             $requestBody = [
    //                 "week_of" => $request->week_of,
    //                 "start"   => $request->start,
    //                 "end"     => $request->end,
    //                 "description" => $request->description,
    //                 "notify_affected_employees" => $request->notify_affected_employees|| false,
    //                 "paid_hours" => (int) $request->paid_hours,
    //                 "position_id" => $request->position,
    //                 "added_to" => $validDatePeriod, // subject for removal
    //                 "color" => $request->color||null,
    //                 "client_property_id" => $request->property_id,
    //                 "lunch_start" => $request->lunch_start,
    //                 "lunch_end" => $request->lunch_end,
    //                 "break_start" => $request->break_start,
    //                 "break_end" => $request->break_end,
    //             ];

    //             /**
    //              *  IF SHIFT IS NOT EXISTING 
    //              *  CREATE NEW SHIFT FOR EMPLOYEES
    //              **/
    //             $shift = Shift::create($requestBody);
            
    //             if($shift):
    //                 if ( $request->employees && count($request->employees) > 0 ) {
    //                     foreach ($request->employees as $v) {
    //                         Shift::create(['shift_id' => $shift->id,'employee_id' => $v]);
    //                     }
    //                     // Supply "ShiftDate"
    //                     for ($i = 0; $i < count($date); $i++) {
    //                         if (in_array($date[$i], $shiftDateAddedTo)) {
    //                             ShiftDate::create(['date' => $date[$i],'shift_id' => $shift->id]);
    //                         }
    //                     }
    //                     // return response('', 201)->header('Location', url("/api/shifts/{$shift->id}"));
    //                     return response()->json(['status'=>true,'message'=>'New shift added successfully','data' => Shift::find($shift->id)]);
    //                 }
    //             // else:
    //             //     return response()->json(['status'=>false,'message'=>'Shift Not Created Successfully']);
    //             endif;
    //         }

    //     }catch (\Exception $e) {
    //         return response()->json(['error'=>$e->getMessage()]);   
    //     }
    // }




    // public function checkExistenceOfShift($dates,$position,$start_time,$end_time,$employees){
       
    //     try{ 
        
    //         $shift_data_query = Shift::with('employee')
    //             ->whereBetween('start',[$start_time,$end_time]) // ?? or ang end?
    //             ->orWhereBetween('end',[$start_time,$end_time])
    //             ->where(function($query) use($dates){
    //                 foreach($dates as $date):
    //                     $query->orWhere('added_to', 'LIKE',"%".$date."%");
    //                 endforeach;
    //             });
               
    //         $shift_data = $shift_data_query->get()->toArray();
          
    //         if (count($shift_data) > 0 ):
                
    //             $shift_emp_ids = [];
    //             foreach($shift_data as $shift):                 
    //                 foreach($shift['employee'] as $shift_emp):
    //                     $shift_emp_ids[] = $shift_emp['id'];
    //                 endforeach;
    //             endforeach;
              
    //             $matched_value = array_intersect($shift_emp_ids,$employees);
    //             if(count($matched_value) > 0):
    //                return Employee::whereIn('id',$matched_value)->get();
    //             endif;
    //         else: 
    //              return false;              
    //         endif;           
            
    //     }catch (\Exception $e) {
    //         return false;
    //     }
    // }

    // public function showsingle(Request $request, $shift_id)
    // {
    //     // $Shift = shift::with('employee')->find($id);
    //     $Shift = ShiftEmployee::with('shift','shift.property','employee')
    //             //->where('shift_id', '=', '$id')
    //             ->when($shift_id, function ($query, $shift_id) {
    //                 return $query->where('shift_id', $shift_id);
    //             })
    //             ->get();
    //     return response()->json(['data'=> $Shift]);
    // }

    // public function index(Request $request)
    // {
      
    //     // - first capture the date and calculate it's start and end of week value
    //     // - compare it to added_to if it has the value or not, if it has, then display, otherwise leave off
    //     // - distribute the value to it's specific location
    //     // $shift = Shift::with('position')
    //     //             ->where('week_of', $request->get('selectedDate'))
    //     //             ->orderBy('position_id');
    //                 // ->groupBy('position_id');
    //                 // ->orderBy('created_at', 'desc');
    //     // return $shift->get();

    //     $shift = Shift::with('position')
    //     ->where('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
    //     //->where('added_to', $request->get('selectedDate'))
    //     ->orderBy('position_id');

      

    //     // group concat

    //     /*if ( $request->get('position_id') && $request->get('position_id') != '' ) {
    //         return $shift->where('position_id', $request->get('position_id'))
    //                      ->get();
    //     }

    //     return $shift->get();*/

    //     if ($request->get('position_id') && $request->get('position_id') != '' ) {
    //         // return $employee->where('position_id', $request->get('position_id'))->get();
    //         $aw = [];
    //         foreach ($shift->where('position_id', $request->get('position_id'))->get() as $value) {
    //             if($value->position == null) continue;
    //             $aw[] = $value;
    //         }
    //         return $aw;
    //     }
    //     // return $employee->get();

    //     // workaround fuck
    //     $aw = [];
    //     foreach ($shift->get() as $value) {
    //         if($value->position == null) continue;
    //         $aw[] = $value;
    //     }
    //     return $aw;




                        //     $start = Carbon::now()->startOfWeek()->format('Y-m-d');
                //     $end = Carbon::now()->endOfWeek()->format('Y-m-d');

                //     $shifts = Shift::with('position','employee','property')
                //             ->whereBetween('added_to', [$start,$end])
                            
                //             ->orderBy('created_at', 'desc')
                //             ->get()
                //             ->toArray();

                //     $main_array = [];
                //     foreach($shifts as $shift):
                //         $main_array[$shift['added_to']][] = $shift;
                //     endforeach;
                //   return $main_array;
       
    // }


     /***
     * CHECK HERE TODAY SHIFT EXIST ORT NOT
     * 
     */
    // public function  TodaydateShifIsExist($employee_id){
         
    //     $response=['status'=>false,'message'=>'Something Wrong']; 

    //     $current_date = Carbon::now()->toDateString();
    //     $data = [];  

    //     $employee = Employee::with(['shifts'=>function($q){
    //             $q->where('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%");
    //         },'shifts.property'
    //         ,'timeoff'=>function($q){
    //             $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
    //             $q->where('status', 'approved');
    //             //$q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
    //         }
    //     ])
    //     ->where('id', $employee_id)
    //     ->get()
    //     ->toArray();           
        
    //     foreach($employee as &$emp):
            
    //         for($i =0; $i < count($emp['shifts']); $i++):

    //             $emp['shifts'][$i]['clockin'] = $this->GetClockInOutByEmployeeId($employee_id,$emp['shifts'][$i]['id'],$current_date,$emp['shifts'][$i]['start'],$emp['shifts'][$i]['end']);
                
    //             foreach($emp['timeoff'] as &$timeoff):
                    
    //                 $this->checktimeoffwithshift($timeoff,$emp['shifts'][$i],$emp,$i);
    //             endforeach;
    //         endfor;         
    //     endforeach;
    //     return $employee;                  
    // }



    public function checktimeoffwithshift($timeoff, &$shift,&$emp,$i){
        
        // dd($timeoff,$shift,$emp,$i);
        $current_date = Carbon::now()->toDateString();
        $timeoff_dates  = array_merge(explode("~", $timeoff['time_off']));

        if(in_array($current_date, $timeoff_dates)){
         
            if($timeoff['whole_day'] == '1'){
                array_splice($emp['shifts'],$i);
                return false;
            }else{
            // dd($emp['shifts'][$i]['start']);
                //dd($emp['shifts'][$i]['start'],$emp['shifts'][$i]['end'],$timeoff['from_time'],$timeoff['to_time']);
               
                $is_start =false;
                $is_end = false;
                if($timeoff['from_time'] <= $emp[$i]['start'] && $timeoff['to_time'] >= $emp[$i]['start'] ){
                    $is_start =true;
                }

                if($timeoff['from_time'] <=$emp[$i]['end'] && $timeoff['to_time'] >= $emp[$i]['end'] ){
                    $is_end = true;
                }

               // dd($is_start,$is_end);

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


    public function isThisCurrentShift($shift){

        if($shift['clockin'] != null && $shift['clockin']['shiftaction'] == true){
            return  true;
        }
        $current_time = date('H:i:m');

        if($current_time >=  $shift['start']  && $current_time  <= $shift['end']  ):
            return  true;
        endif;
        return false;
       
    }

    // public function MonthSchedule($employee_id){
    //     try{

    //     $response=['status'=>false,'message'=>'something wrong']; 
    //     $date = Carbon::today();

    //     //dd(date('Y-m-d', strtotime(date('Y-m')." -3 month")));
         
    //     //$start = date("Y-m-d", strtotime("first day of previous month"));
    //     $threemonthstart = date('Y-m-d', strtotime(date('Y-m')." -3 month"));
    //     $currentend = Carbon::now()->endOfMonth()->toDateString() ;
    //     $shifts = Shift::with(['employee'=>function($q) use($employee_id){
    //         $q->where('id', $employee_id);
    //     },'property',
    //         'employee.timeoff'=>function($q){
    //             $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
    //             $q->where('status', 'approved');
    //         }
    //     ])
    //     ->whereBetween('added_to', [$threemonthstart,$currentend ])
    //     ->where('employee_id',$employee_id)
    //     ->get()->toArray();
        
    //     // $employee = Employee::with(['shifts'=>function($q) use($threemonthstart,$currentend){
    //     //    // $q->whereBetween('added_to', [Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()]);
    //     //     //$q->whereBetween('added_to', [ date("Y-n-j", strtotime("first day of previous month")),Carbon::now()->endOfMonth()->toDateString()]);
    //     //     $q->whereBetween('added_to', [$threemonthstart,$currentend ]);
    //     // },'shifts.property'
    //     // ,'timeoff'])
    //     // ->where('id', $employee_id)
    //     // ->get()
    //     // ->toArray();

    //     //dd($this->getEloquentSqlWithBindings($employee));
        
    //     if(count($employee) > 0):         
    //         $send_schedule = [];
    //         $send_timeoff = []; 
    //         $listing = [];    
               
    //         // send here the user shift data
    //          foreach ($employee as $key => $emp):              
    //             foreach($emp['shifts'] as $shift):
    //                 $dates =  explode("~", $shift['added_to']);
    //                 //DATE BY SHIFT DETAILS 
    //                 for($i = 0; $i < count($dates); $i++) {
    //                     if($threemonthstart <= $dates[$i] ){
    //                         $send_schedule[$dates[$i]][] = $shift;
    //                     }                       
    //                 }
    //             endforeach;  
                              
    //             // TIME OFF SCHEDULE
    //             foreach($emp['timeoff'] as $time):
    //                 $timeofed = explode('~',$time['time_off']);
    //                 $timeoff = array_unique($timeofed);

    //                 for($i = 0; $i < count($timeoff); $i++) {
    //                     $send_timeoff[$timeoff[$i]] = $time;
    //                 }                  
    //             endforeach;                         
    //         endforeach;
           
    //         // CHECK TIME OFF IS APPROVED OR NOT 
    //         $timeoff_key = [];
    //         foreach($send_timeoff as $key=>$schedule):
    //             if($schedule['whole_day'] == 'true'):
    //                 $timeoff_key[] = $key;
    //             endif;
    //         endforeach;
           
    //         // IF TRUE REURN THE DATE OFF HOLIDAY
    //         foreach($send_schedule as $key=>$schedule):
    //             //dd($timeoff_key);
    //             if(!in_array($key, $timeoff_key)):
    //                $listing[$key] = $schedule; 
    //             endif;
    //         endforeach;

    //             if(count($send_schedule) > 0 || count($listing) > 0  || count($send_timeoff) > 0  ):
                                             
    //                 $response =['status'=>true,'message'=>'Monthly schedule','send_schedule'=>$send_schedule,'send_timeoff' =>$send_timeoff,'listing'=>$listing];             
    //             else:
    //                 $response = ['status'=>false,'message'=>'This month No schedule']; 
    //             endif;
    //         else:
    //             $response = ['status'=>false,'message'=>'This month No schedule'];     
    //         endif;

    //         return response($response);
    //     }catch (\Exception $e) {
    //         return response()->json(['error'=>$e->getMessage()]);   
    //     }
   	// }





    //    /**
	//  * @ download report pdf
	//  * 
	//  */
	// public function ReportPdfdownload(Request $request){

	// 	$response = ['status'=>false,'message'=>'Something wrong'];

	// 	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
    //     // $pdf = PDF::loadView('pdfview', $data);
    //     // return $pdf->download('Nicesnippets.pdf');

	// 	// check here report type  ID 1
	// 	if($request->report_type_id == 1){
	// 		$reportSQL = Reportslog::with('employee','property','report');
	// 		$reportSQL->where('report_type_id', $request->report_type_id);
	// 		$reportSQL->where('employee_id', $request->employee_id);
	// 		$report1 = $reportSQL->get()->toArray();

	// 		$report_one_array = [];
	// 		$report_one_array['id'] = $request->id;
	// 		foreach($report1 as $report):
	// 				$reptarray['date'] = date('d/m/Y',strtotime($report['date']));
	// 				$reptarray['time'] = date('h:i A',strtotime($report['time']));
	// 				$reptarray['description'] = $report['description'];
	// 				$report_one_array['table'][] = $reptarray;
	// 			foreach($report['employee'] as $employee):
	// 					$report_one_array['officer_name'] = $employee['firstname'].' '.$employee['lastname'];
	// 			endforeach;
	// 			foreach($report['report'] as $repname):
	// 				$report_one_array['title'] = $repname['report_name'];
	// 			endforeach;
	// 		endforeach;
		
	// 		$pdf = PDF::loadView('pdfview',$report_one_array);
	// 		return $pdf->download('Nicesnippets.pdf');
        	
		
	// 	// check here report type id 2		
	// 	}else if($request->report_type_id == 2){
	// 		$report2 = Reportslog::with('employee','property','report','subReport')
	// 					->where('id',$request->id)
	// 					->first();
			
	// 		$report_two_array = []; 
			
	// 		$report_two_array['id'] = $report2['id'];
	// 		$report_two_array['description'] = $report2['description'];
	// 		foreach($report2['employee'] as $employee):
	// 			$report_two_array['officer_name'] = $employee['firstname'].' '.$employee['lastname'];
	// 		endforeach;

	// 		foreach($report2['report'] as $repname):
	// 			$report_two_array['title'] = $repname['report_name'];
	// 		endforeach;
	// 		$report_two_array['report_type_name'] = $report2['subReport']['report_type_name'];
	// 		$pdf = PDF::loadView('pdf.incident',$report_two_array);
	// 		return $pdf->download('file.pdf');
	// 	}  	
	
	// }




    

    
}
