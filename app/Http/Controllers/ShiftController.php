<?php

namespace App\Http\Controllers;

use App\Shift;
use App\ShiftDate;
use App\ShiftEmployee;
use App\Employee;
use App\ClientProperties;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\UserSetting;
use App\Notifications;
use App\Position;
use Illuminate\Support\Facades\Storage;
use App\Notifications\WholeAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\App;


class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {  
        // - first capture the date and calculate it's start and end of week value
        // - compare it to added_to if it has the value or not, if it has, then display, otherwise leave off
        // - distribute the value to it's specific location
        //echo $request->get('client_id');
        if($request->get('client_id') && $request->get('client_id')!=""){
            $empNewFile = Employee::where('added_by',$request->get('client_id'))->get();
            $getnewarr = array();
            foreach($empNewFile as $empNewFiledata){
                $getnewarr[] = $empNewFiledata->id;
            }
            //echo "<pre>"; print_r($getnewarr);
            $shifts = Shift::with('position','employee','property')
                ->where('week_of', $request->get('selectedDate'))
                ->whereIn('employee_id',$getnewarr)
                ->orderBy('position_id');
                //->orderBy('created_at', 'desc');

        }else {
            $shifts = Shift::with('position','employee','property')
                ->where('week_of', $request->get('selectedDate'))
                ->orderBy('position_id');
                //->orderBy('created_at', 'desc');
        }

        // $shifts = Shift::with('position','employee','property')
        // ->where('week_of', $request->get('selectedDate'))
        // ->orderBy('position_id');

      
        if ($request->get('position_id') && $request->get('position_id') != '' ) {
            $aw = [];
            foreach ($shifts->where('position_id', $request->get('position_id'))->get() as $value) {
                if($value->position == null) continue;
                $aw[] = $value;
            }
            return $aw;
        }
       
        // workaround fuck
        $aw = [];
        foreach ($shifts->get() as $value) {
            if($value->position == null) continue;
            $aw[] = $value->toArray();
        }

        $selectedDate = $request->get('selectedDate');
        $start = Carbon::create( $selectedDate )->startOfWeek()->format('Y-m-d');
        $end = Carbon::create( $selectedDate )->endOfWeek()->format('Y-m-d');

        // This will provide the interval period based on the supplied data
        $period = CarbonPeriod::create($start, $end);

          // Iterate over the period
        $dates = [];
        $position_array = [];
        foreach ($period as $v) $dates[] = $v->format('Y-m-d');
        
        while(count($aw) > 0 ):
            $shift = array_shift($aw);
            $position_array[$shift['position_id']][$shift['added_to']][] = $shift;
        endwhile;

        foreach($position_array as &$position):
                foreach($dates as $date):
                    if(isset($position[$date]) == false):
                        $position[$date] = [];
                    endif;
                endforeach;
        endforeach;
          
       
        //  $main_array = [];
         
        //  foreach($aw as $shift):
        //     $main_array[$shift['added_to']][] = $shift;
        // endforeach;
         return $position_array;

       
       
    }


    /***
     *  GET TRASHED SHIFT
     * 
     */
    public function trashedShift()
    {
        $response = ['status'=>false,'message'=>'Something wrong'];

        $shift = Shift::with('position')->orderBy('position_id')->onlyTrashed()->get();
        if(count($shift) > 0):
            $response = [ 'status' =>true,'message'=>'All Trashed shift','data' =>$shift];
        else:
            $response = [ 'status' =>false,'message'=>'No Trashed shift'];
        endif;

        return response($response);
    }


    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    /**
     *  CHECK SHIFT EXIST FOR THIS USER OR NOT 
     *  BY IMPLOYEE ID
     * 
    */
    public function checkExistenceOfShift($dates,$start_time,$end_time,$employees){
       
        $shift_data_query = Shift::
            where(function($query) use($start_time,$end_time){
                $query->where(function($query) use($start_time) {
                    $query->where('start','<=', $start_time)->where('end', '>=', $start_time);
                });
                $query->orWhere(function($query) use($end_time) {
                    $query->where('start','<=', $end_time)->where('end', '>=', $end_time);
                });
            })           
            ->where(function($query) use($dates){
                foreach($dates as $date):
                    if(!empty($date)){
                        $query->orWhere('added_to', $date);
                    }
                endforeach;
            });
            
        // dd($this->getEloquentSqlWithBindings($shift_data_query));
        // dd($shift_data_query);

        $shift_data = $shift_data_query->get()->toArray();
        
        if (count($shift_data) > 0 ):
           
            $shift_emp_ids = [];                                 
                foreach($shift_data as $shift_emp):
                    $shift_emp_ids[] = $shift_emp['employee_id'];
                endforeach;
            
            $matched_value = array_intersect($shift_emp_ids,$employees);
            if(count($matched_value) > 0):
                return Employee::whereIn('id',$matched_value)->get();
            endif;
        else: 
                return false;              
        endif;           
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // format it to [date, date, 0, date, date, date, 0]; example
        $selectedDate = $request->week_of;

        /**
         *  $selectedDate VARIABLE  BY CREATE WEEK START AND END WEEK  
         */
        $start = Carbon::create( $selectedDate )->startOfWeek()->format('Y-m-d');
        $end = Carbon::create( $selectedDate )->endOfWeek()->format('Y-m-d');

        // This will provide the interval period based on the supplied data
        $period = CarbonPeriod::create($start, $end);

        // Iterate over the period
        $date = [];
        foreach ($period as $v) $date[] = $v->format('Y-m-d');
                    
        /**
         * SHIFT DATA SELECTED BY ADMIN GET HERE
         *  ADDED_TO DATES
         */
        $shiftDateAddedTo = $request->added_to;
        
        $validDatePeriod = [];
        for ($i = 0; $i < count($date); $i++) {
            if (in_array($date[$i], $shiftDateAddedTo)) {
                array_push($validDatePeriod, $date[$i]);
            } else {
                array_push($validDatePeriod, 0);
            }
        }

        $checkExistenceOfShift = $this->checkExistenceOfShift($validDatePeriod,$request->start,$request->end,$request->employees);
        
        
        if($checkExistenceOfShift):
            return response()->json(['status'=>false,'message'=>'Shift already exist', 'data'=>$checkExistenceOfShift]);
        else:
    
            /***
             *  GET ADD SHIFT FIELD DATA
             *  CREATE AN ARRAY TO INSERT IN SHIFT TABLE
             **/
            $shift = [];
            //Iterate over the period
            $addDate = 0;
            if(strtotime($request->start) > strtotime($request->end)){
                $addDate = 1;
            }
            if ( $request->employees && count($request->employees) > 0 ) :
                foreach ($request->employees as $emp_id) :
                    foreach ($request->client as $client_id) :
                        for ($i = 0; $i < count($date); $i++) :
                            if (in_array($date[$i], $shiftDateAddedTo)) {
                                $added_form = $date[$i];
                                if($addDate==1){
                                    $added_form = date('Y-m-d', strtotime($date[$i] . ' +1 day'));
                                }
                                $requestBody = [
                                    "week_of" => $request->week_of,
                                    "start"   => $request->start,
                                    "end"     => $request->end,
                                    "description" => $request->description,
                                    "lunch_time" => $request->lunch_time,
                                    "break_time" => $request->break_time,
                                    "notify_affected_employees" => $request->notify_affected_employees|| false,
                                    "paid_hours" => (int) $request->paid_hours,
                                    "position_id" => $request->position,
                                    "added_to" => $date[$i], // subject for removal
                                    "added_form" => $added_form,
                                    "color" => $request->color,
                                    //"client_property_id" => $request->property_id,
                                    "client_property_id" => $client_id,
                                    "lunch_start" => $request->lunch_start,
                                    "lunch_end" => $request->lunch_end,
                                    "break_start" => $request->break_start,
                                    "break_end" => $request->break_end,
                                    "employee_id"=>$emp_id
                                ];
                                $emp = Employee::where('id',$emp_id)->first();
                                // if($emp->deviceToken!=""){
                                //       $curl = curl_init();
                                //       $token = $emp->deviceToken;
                                //       $message = "Admin has created your shift, please login on app to check";
                                //       curl_setopt_array($curl, array(
                                //       CURLOPT_URL => "http://3.142.74.2:7889/callpushNotification",
                                //       CURLOPT_RETURNTRANSFER => true,
                                //       CURLOPT_ENCODING => "",
                                //       CURLOPT_MAXREDIRS => 10,
                                //       CURLOPT_TIMEOUT => 30,
                                //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                //       CURLOPT_CUSTOMREQUEST => "POST",
                                //       CURLOPT_POSTFIELDS => '{"message": "'.$message.'", "token": "'.$token.'"}',
                                //       CURLOPT_HTTPHEADER => array(
                                //         "Content-Type: application/json"
                                //       ),
                                //     ));
                                //     $response = curl_exec($curl);
                                //     $err = curl_error($curl);
                                //     curl_close($curl);
                                // }
                                $shift[] = Shift::create($requestBody);
                            } // END HERE FIRST IF 
                        endfor;   
                    endforeach; 
                endforeach; 

                $employees = Employee::whereIn('id',[$request->employees])->get();
                foreach($employees as $employee):
                    
                    $app_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
                    $subject = "New Shift";
                    $message = "New Shift Added";  
                    if($employee['employee_player_id'] != ''):  
                        // send Notification by custom method
                        //$shiftnotification = new WholeAppNotification();
                        //$shiftnotification->PushNotification($app_id,$employee['employee_player_id'],$subject,$message);
                        // INSERT NOTIFICATION
                    $notification = [
                        'employee_id'=>$employee['id'],
                        'subject'=> $subject,
                        'description'=> $message,
                    ];
                    Notifications::create($notification);
                    endif;
                endforeach;
                
                if(count($shift) > 0):
                   
                    return response()->json(['status'=>true,'message'=>'New shift added successfully','data' => $shift]);
                else:
                    return response()->json(['status'=>false,'message'=>'Shift Not added successfully']);
                endif;
            else:
                return response()->json(['status'=>false,'message'=>'You have not select any employee']);
            endif;

        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */ 
    public function show(Shift $shift)
    {
        return $shift;
    }

    public function showsingle(Request $request, $shift_id)
    {
        $Shift = shift::with('position','employee','property')->find($shift_id);
        return response()->json(['data'=> $Shift]);
    }




    public function checkShiftForUpdateUserShiftTime($date,$start_time,$end_time,$employee_id,$shift_id){
              
        //dd($date,$start_time,$end_time,$employee_id);
        $shift_data_query = Shift::where('id', '!=',$shift_id)
            ->where(function($query) use($start_time,$end_time ){
                $query->where(function($query) use($start_time) {
                    $query->where('start','<=', $start_time)->where('end', '>=', $start_time);
                });
                $query->orWhere(function($query) use($end_time) {
                    $query->where('start','<=', $end_time)->where('end', '>=', $end_time);
                });
            })->where(function($query) use($date){
                $query->orWhere('added_to', $date);
            })->where(function($query) use($employee_id){
                $query->orWhere('employee_id', $employee_id);
            });

        //dd($this->getEloquentSqlWithBindings($shift_data_query));
        $shift_data = $shift_data_query->get()->toArray();
        //dd($shift_data);
        if (count($shift_data) > 0 ):
            return $shift_data;
        else: 
            return false;              
        endif;           
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $response =['status'=>false,'message'=>'Something wrong'];


        $check_shift = $this->checkShiftForUpdateUserShiftTime($request['added_to'],$request['start'],$request['end'],$request['employee_id'],$request['id']);
        if($check_shift):
            return response()->json(['status'=>false,'message'=>'Shift already exist', 'data'=>$check_shift]);
        else:
            $input = $request->all();
            $shiftupdate = Shift::where('id',$request['id'])->update($input);

            // SEND HERE NOTIFICATION
            $employee = Employee::where('id',$request['employee_id'])->first();
            
            $player_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
            $subject = "Update Shift";
            $message = "Shift Time chnage";
            if($employee['employee_player_id'] != ''):
                // send Notification by custom method
                $shiftnotification = new WholeAppNotification();
                $shiftnotification->PushNotification($player_id,$employee['employee_player_id'],$subject,$message);
                // INSERT NOTIFICATION
                $notification = [
                    'employee_id'=>$employee['id'],
                    'subject'=> $subject,
                    'description'=> $message,
                ];
                Notifications::create($notification);
            endif;

            if($shiftupdate):
                $response = ['status' =>true, 'message' =>'Shift updated!'];
            else:
                $response = ['status' =>false, 'message' =>'Shift not updated!'];
            endif;
        endif;

        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response =['status'=>false,'message'=>'Something wrong'];
        $shift_info = Shift::where('id',$id)->first();
        $shiftdelete = Shift::where('id',$id)->delete();

        if($shiftdelete):
             // SEND HERE NOTIFICATION
            
            $employee = Employee::where('id',$shift_info->employee_id)->first();
            

            $player_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
            $subject = "Shift Delete";
            $message = "Your Shift Deleted";
            if($employee['employee_player_id'] != ''):
                // send Notification by custom method
                $shiftnotification = new WholeAppNotification();
                $shiftnotification->PushNotification($player_id,$employee['employee_player_id'],$subject,$message);
                $notification = [
                    'employee_id'=>$employee['id'],
                    'subject'=> $subject,
                    'description'=> $message,
                ];
                Notifications::create($notification);
            endif;
            $response = ['status' =>true, 'message' =>'Shift deleted!'];
        else:
            $response = ['status' =>false, 'message' =>'Shift not deleted!'];
        endif;
        return response($response);
    }

    public function schedulesExport(Request $request) 
    {
        $dates = $request->input('CheckedNames');
        $data = [];

        $timeDiff = function($s, $e) {
            $checkTime = strtotime($s);
            $loginTime = strtotime($e);
            $diff = $checkTime - $loginTime;

            $hour = number_format($diff/3600, 2);
            return $hour;
        };

        $isPosition = $request->input('position');

        $emps = Employee::with(['shifts' => function ($q) use ($dates, $isPosition) {
                if ($isPosition != 0) {
                    return $q->whereHas('position', function($sq) use ($isPosition){
                        return $sq->where('id', $isPosition);
                    })
                    ->where(function ($sq) use ($dates) {
                        foreach ($dates as $date) {
                             $sq->orWhere('added_to', "like", "%".$date."%");
                        }
                        return $sq;
                    });
                } else {
                    foreach ($dates as $date) {
                         $q->orWhere('added_to', "like", "%".$date."%");
                    }

                    return $q;
                }
        }, 'shifts.position'])->has('shifts', '>', 0)->distinct('id')->get()->toArray();

        foreach($emps as $emp) {

            if (count($emp['shifts']) > 0) {

                $tempdata = [];

                if ($request->input('firstname') == true) {
                    $tempdata['firstname'] = $emp['firstname'];
                }

                if ($request->input('lastname') == true) {
                    $tempdata['lastname'] = $emp['lastname'];
                }

                if ($request->input('name') == true) {
                    $tempdata['name'] = $emp['firstname'] . " " . $emp['lastname'];
                }

                if ($request->input('payrate') == true) {
                    $tempdata['payrate'] = $emp['pay_rate'];
                }
            
                foreach ($dates as $date) {
                    $tempdata[$date] = $emp['shifts'][0]['paid_hours'];
                }
            
                $data[] = $tempdata;
            }
        }

        return response($data);
    }
 
    public function changelayout(Request $request) {
        $printview = $request->input('printview');
        $screenview = $request->input('screenview');
        $user_id = $request->input('user_id');

        $printviewModel = UserSetting::where('user_id', $user_id)->where('meta_key', 'printview')->first();

        if($printviewModel != null) {
            $printviewModel->meta_value = \json_encode($printview);
            $printviewModel->save();
        } else {
            $printviewModel =  UserSetting::create(['user_id' => $user_id, 'meta_key' => 'printview', 'meta_value' => json_encode($printview)]);
        }

        
        $screenviewModel = UserSetting::where('user_id', $user_id)->where('meta_key', 'screenview')->first();
        if($screenviewModel) {
            $screenviewModel->meta_value = \json_encode($screenview);
            $screenviewModel->save();
        } else {
            UserSetting::create(['user_id' => $user_id, 'meta_key' => 'screenview', 'meta_value' => json_encode($screenview)]);
        }

        return response(['status' => 'success']);
    }

    public function getlayout(Request $request){
        $user_id = $request->input('user_id');
        $printviewModel = UserSetting::where('user_id', $user_id)->where('meta_key', 'printview')->first();
        $screenviewModel = UserSetting::where('user_id', $user_id)->where('meta_key', 'screenview')->first();

        return response(['status' => 'success', 'data' => ['printview' => $printviewModel,  'screenview' => $screenviewModel]]);
    }

   

}
