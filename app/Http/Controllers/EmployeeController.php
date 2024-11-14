<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Shift;
use App\Timeoff;
use App\Employeemessage;
use App\Models\ShiftEmployee;
use App\Admin;
use App\Clockin;
use App\Notifications;
use App\Message;
use App\MessageGroup;
use App\Models\ReportAlert;
use App\Models\EmployeeClient;
use App\EmployeePosition;
use App\ClientAlertReport;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Mail;
use App\Mail\SignInInstruction;
use App\Mail\SignInInstructionuser;
use App\Mail\SendReminder;
use App\Mail\SendMessage;
use App\Mail\EmployeResetPass;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Hash;
use App\Mail\TimeOffRequestStatus;
use App\Notifications\WholeAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use JWTAuth;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $search = $request->get('kw');
        $hasEmail = $request->get('has_email');
        $userRole = $request->get('userRole');
        $userid = $request->get('userid');
        //$user = Auth::user();
        
        //$user->id
        if(@$userRole=='client'){
            //get community clients data
            $getemployeelist = EmployeeClient::where('client_id', $userid)->get();
            $getclientList = array();
            foreach($getemployeelist as $getemployeelistval){
                $getclientList[] = $getemployeelistval->employee_id;
            }

            $employee = EmployeePosition::with(['employee' => function($q) use ($search, $hasEmail, $userid, $getclientList) {
                if ( $hasEmail == 'both'  ) {
                    $q->where('added_by',$userid)
                    ->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                        $qq->where('firstname', 'like', "%{$search}%")
                           ->orWhere('lastname', 'like', "%{$search}%");
                    });
                } else if ( $hasEmail ) {
                    $q->where('email', '!=', NULL)
                    ->where('added_by',$userid)
                    ->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                    $qq->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%");
                    });
                } else if ( !$hasEmail ) {
                    $q->where('email', '=', NULL)
                    ->where('added_by',$userid)
                    ->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                        $qq->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%");
                    });
                }
            }])
            ->groupBy('employee_id')
            ->orderBy('employee_id', 'desc'); 
        }else { 
            $employee = EmployeePosition::with(['employee' => function($q) use ($search, $hasEmail) {
                if ( $hasEmail == 'both'  ) {
                    $q->where('added_by',0)
                     ->where(function($qq) use ($search) {
                        $qq->where('firstname', 'like', "%{$search}%")
                           ->orWhere('lastname', 'like', "%{$search}%");
                    });
                } else if ( $hasEmail ) {
                    $q->where('email', '!=', NULL)
                      ->where('added_by',0)
                      ->where(function($qq) use ($search) {
                        $qq->where('firstname', 'like', "%{$search}%")
                           ->orWhere('lastname', 'like', "%{$search}%");
                      });
                } else if ( !$hasEmail ) {
                    $q->where('email', '=', NULL)
                      ->where('added_by',0)
                      ->where(function($qq) use ($search) {
                        $qq->where('firstname', 'like', "%{$search}%")
                           ->orWhere('lastname', 'like', "%{$search}%");
                      });
                }
            }])
            ->groupBy('employee_id')
            ->orderBy('employee_id', 'desc'); 
        }


        if ( $request->get('position_id') && $request->get('position_id') != '' ) {
            $aw = [];
            foreach ($employee->where('position_id', $request->get('position_id'))->get() as $value) {
                if($value->employee == null) continue;
                $aw[] = $value;
            }
            //dd($aw);
        return $aw;
        }
       
        // workaround 
        $aw = [];
        foreach ($employee->get() as $value) {
            if($value->employee == null) continue;
            $aw[] = $value;
        }
        return $aw;
    }


    // SEND LIST OF ADMIN
    public function get_list_of_all_Admin(){
        
        $response = ['status'=>false,'message'=>'Something wrong'];
        $admin = Admin::all();

        if(count($admin) > 0):
            $response = ['status'=>true, 'message'=>'Admin List','admin'=>$admin, ];
        else:
            $response = ['status'=>false, 'message'=>'No Admin'];
        endif;        
        return $response;
    }
    
    public function get_list_of_employee(Request $request){

        $employee = EmployeePosition::with('employee')->where('position_id',$request['position_id'])->get()->toArray();
        $employee_list = [];
        foreach($employee as $empl):
           if($empl['employee'] != null):
                $employee_list[] = $empl['employee'];
           endif;
        endforeach;
        if(count($employee_list) > 0):
            $response = ['status'=>true, 'employee'=>$employee_list, 'message'=>'Employee List'];
        else:
            $response = ['status'=>false, 'message'=>'No Employee'];
        endif;
        
        return $response;

    }

    
    /**
     * GENERATE PASSWORD
     */
    function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKdfh64LMNOP5a5sf90QRSTUVWX133YZabcefghijkGH546lmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //$user = Auth::user();

       // Just generate random password which will then be sent to the user
        // thru email which requires a new password, etc.
        $generatedRandomPassword = $this->password_generate(12);
        // $generatedRandomPassword = bin2hex( random_bytes(16) );
      
        if($employee_image =$request->file('employee_image')):
            $request->employee_image  =  Storage::disk('public')->put('employee_image',$employee_image);
        else:
            $filename= 'logo.png';
            $request->employee_image = 'employee_image/' . $filename;
        endif;
       
        // Note:
        if(($request->get('enable_security_officer')=='undefined')) {
            $getmethod = 0;
        }else{
            $getmethod = $request->get('enable_security_officer');
        }

        if($request->get('phone')=='undefined'){ $phone = ''; }else { $phone = $request->get('phone'); }
        if($request->get('phone2')=='undefined'){ $phone2 = ''; }else { $phone2 = $request->get('phone2'); }
        if($request->get('address')=='undefined'){ $address = ''; }else { $address = $request->get('address'); }
        if($request->get('address2')=='undefined'){ $address2 = ''; }else { $address2 = $request->get('address2'); }
        if($request->get('city')=='undefined'){ $city = ''; }else { $city = $request->get('city'); }
        if($request->get('state')=='undefined'){ $state = ''; }else { $state = $request->get('state'); }
        if($request->get('zip')=='undefined'){ $zip = ''; }else { $zip = $request->get('zip'); }
        if($request->get('comments')=='undefined'){ $comments = ''; }else { $comments = $request->get('comments'); }

        //  - validate
        $payload = [
            "firstname" => $request->get('firstname'),
            "lastname"  => $request->get('lastname'),
            "phone"     => $phone,
            "phone2"    => $phone2,
            "email"     => $request->get('email'),
            "address"   => $address,
            "address2"  => $address2,
            "city"      => $city,
            "state"     => $state,
            "zip"       => $zip,
            "max_weekly_hours" => $request->get('max_weekly_hours'),
            "max_weekly_days"  => $request->get('max_weekly_days'),
            "max_day_hours"    => $request->get('max_day_hours'),
            "max_day_shifts"   => $request->get('max_day_shifts'),
            "pay_rate"  => $request->get('pay_rate'),
            "hired_date" => $request->get('hired_date'),
            "priority_group" => $request->get('priority_group'),
            'comment' =>$comments,
            "enable_screen_reader" => $request->get('enable_screen_reader') || false,
            "enable_security_officer" => $getmethod || false,
            "password" => Hash::make($generatedRandomPassword),
            "plain_password" => $generatedRandomPassword,
            "employee_image"=>$request->employee_image,
            "added_by"=> $request->get('added_by')
        ];
       
        try {
            $employee = Employee::create( $payload );
            // dd($employee);
            if ($employee) {
                // Note:
                // not the efficient way, search how to insert multiple in MySQL
                // Add positions
                if($request->positions){
                    $pos =  explode(',', $request->positions);               
                    if ($pos && count($pos) > 0 ) {
                        foreach ($pos as $v) {
                            $position = EmployeePosition::create([
                                'employee_id' => $employee->id,
                                'position_id' => $v
                            ]);
                        }
                    } 
                }else{
                    $position = EmployeePosition::create([
                        'employee_id' => $employee->id,
                        'position_id' => 16
                    ]);
                }
                // Note:
                // --- unecnrypted password will be send thru email here ----
                // - user should verify thru email
                // - then be redirected to change password, etc
                $when = now()->addMinutes(1);
                if($getmethod==1){
                    Mail::to($request->email)->later($when, new SignInInstructionuser($employee));
                }else {
                    Mail::to($request->email)->later($when, new SignInInstruction($employee));
                }
                
                return response()->json(['data' => Employee::find($employee->id)]);
                //return response('', 201)->header('Location', url("/api/employees/{$employee->id}"));
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response('The e-mail or employee_no you input existed already!', 500);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
        // dd($request->file('employee_image'));
        $employee = Employee::findOrFail($request['id']);

        if($request['enable_security_officer']=='1'){
            $explode_position =  explode(',', $request->positions);
            // echo "<pre>"; print_r($explode_position); 
            // echo  $employee->id;
            $employee->position()->forceDelete();
            foreach ($explode_position as $v) {
                $position = EmployeePosition::create([
                    'employee_id' => $employee->id,
                    'position_id' => $v
                ]);
            }
            //die();
        }

        if($request['enable_security_officer']=='1'){
            $employee->enable_security_officer = 1;
        }else if($request['enable_security_officer']=='0'){
            $employee->enable_security_officer = 0;
        }
        if($request['plain_password']!=""){
            $employee->password = Hash::make($request['plain_password']);
            $employee->plain_password = $request['plain_password'];
        }
        $employee->firstname = $request['firstname'];
        $employee->lastname = $request['lastname'];
        $employee->save();

        //
        if($request['added_by']!="" && $request['added_by']!='undefined'){
            $emplyereport = new EmployeeClient();
            $emplyereport->client_id = $request['added_by'];
            $emplyereport->employee_id = $request['id'];
            $emplyereport->save();
        }

        dd($request['enable_security_officer']);
        // Note: should scan everything then remove all then re-add again


        if ( $explode_position && count($explode_position) > 0  ) {
            //$employee->position()->forceDelete();
            $employee->update( $request->except(['position', 'positions']) );
            if($employee_image =$request->file('employee_image')):
                $request->employee_image  =  Storage::disk('public')->put('employee_image',$employee_image);
            else:
                unset($request->employee_image);
            endif;
            $employee->employee_image = $request->employee_image;
            //$employee->enable_security_officer = $request->enable_security_officer;
            $employee->save();

            // foreach ($explode_position as $v) {
               
            //     $position = EmployeePosition::create([
            //         'employee_id' => $employee->id,
            //         'position_id' => $v.',',
            //     ]);
            // }
            // return response('Employee updated!', 204);
        }else {
            if($employee_image =$request->file('employee_image')):
                $request->employee_image  =  Storage::disk('public')->put('employee_image',$employee_image);
            else:
                unset($request->employee_image);
            endif;
            $employee->employee_image = $request->employee_image;
            $employee->save();
        }

        

        return response('Error', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
          
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $employee = Employee::find($request['id']);
            $employee->delete();
            if($employee):            
                $response = ['status'=>true,'message'=>'Record Deleted'];
            else:
                $response = ['status'=>false,'message'=>'Sorry, Records not Deleted!'];
            endif;
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
        
    }

    /**
     * Show all deleted employee (soft deletion)
     * 
     * @return [type] [description]
     */
    public function showTrashed(Request $request)
    {
      // return Employee::onlyTrashed()->get();
      $search = $request->get('kw');
      $employee = EmployeePosition::with(['employee' => function($q) use ($search) {
          $q->where(function($qq) use ($search) {
            $qq->where('firstname', 'like', "%{$search}%")
               ->orWhere('lastname', 'like', "%{$search}%");
          })
          ->whereNotNull('deleted_at')
          ->onlyTrashed()
          ->orderBy('deleted_at', 'desc');
      }])
      ->groupBy('employee_id')
      // ->orderBy('created_at', 'desc');
      ->orderBy('employee_id', 'desc');


      if ( $request->get('position_id') && $request->get('position_id') != '' ) {
          $aw = [];
          foreach ($employee->where('position_id', $request->get('position_id'))->get() as $value) {
              if($value->employee == null) continue;
              $aw[] = $value;
          }
          return $aw;
      } 
      // workaround fuck
      $aw = [];
      foreach ($employee->get() as $value) {
          if($value->employee == null) continue;
          $aw[] = $value;
      }
      return $aw;
    }


    public function showAlertData($userId)
    {
        $getemployeelist = Employee::where('added_by', $userId)->get();
        $getlist = array();
        $getEmpname = array();
        foreach($getemployeelist as $getemployeelistval){
            $getlist[] = $getemployeelistval->id;
            //$getEmpname[$getemployeelistval->id]= $getemployeelistval->firstname.' '.$getemployeelistval->lastname;
        }

        $getreportlist = ClientAlertReport::where('client_id', $userId)->get();
        $getreportListdata = array();
        foreach($getreportlist as $getreportlistval){
            $getreportListdata[] = $getreportlistval->report_id;
        }
        $employeedata = ReportAlert::whereIn('employee_id',$getlist)->get();
        foreach($employeedata as $employeedataval){
            $getreportListdata[] = $employeedataval->id;
        }

        $employee = ReportAlert::whereIn('id',$getreportListdata)->orderBy('created_at', 'desc')->get();
        // workaround
        $aw = [];
        foreach ($employee as $key=>$value) {
            $getemployeeName = Employee::where('id', $value->employee_id)->first();
            //if($value->employee == null) continue;
            if(!empty($getemployeeName)){
                $value->empname = $getemployeeName->firstname.' '.$getemployeeName->lastname;
            }else {
                $value->empname = '';
            }
            $getimage = explode(',',$value->data_images);
            $value->dataImageval = $getimage;
            $aw[] = $value;
        }
        return $aw;
    }

    public function showAllAlertData()
    {
        // Get all employees
        $getemployeelist = Employee::all(); // Fetch all employees without filtering by userId
        $getlist = array();
        foreach ($getemployeelist as $getemployeelistval) {
            $getlist[] = $getemployeelistval->id;
        }
    
        // Get all client alert reports
        $getreportlist = ClientAlertReport::all(); // Fetch all client alert reports
        $getreportListdata = array();
        foreach ($getreportlist as $getreportlistval) {
            $getreportListdata[] = $getreportlistval->report_id;
        }
    
        // Get employee data
        $employeedata = ReportAlert::whereIn('employee_id', $getlist)->get();
        foreach ($employeedata as $employeedataval) {
            $getreportListdata[] = $employeedataval->id;
        }
    
        // Fetch all report alerts and include employee names
        $employee = ReportAlert::whereIn('id', $getreportListdata)->orderBy('created_at', 'desc')->get();
    
        // Prepare the final data
        $aw = [];
        foreach ($employee as $key => $value) {
            $getemployeeName = Employee::where('id', $value->employee_id)->first();
    
            // Add employee name if exists, else empty string
            if (!empty($getemployeeName)) {
                $value->empname = $getemployeeName->firstname . ' ' . $getemployeeName->lastname;
            } else {
                $value->empname = '';
            }
    
            // Process image data
            $getimage = explode(',', $value->data_images);
            $value->dataImageval = $getimage;
            $aw[] = $value;
        }
    
        return $aw;
    }
    


    /**
     * Search deleted employee
     * 
     * @return [type] [description]
     */
    public function searchTrashed(Request $request)
    {
      // return Employee::onlyTrashed()->get();
      $search = explode(" ",$request->get('kw'));
      $employee = EmployeePosition::with(['employee' => function($q) use ($search) {
          $q->where(function($qq) use ($search) {
              
            foreach($search as $s) {
                $qq->where('firstname', 'like', "%{$s}%")
                ->orWhere('lastname', 'like', "%{$s}%");
            }
            // $qq->where('firstname', 'like', "%{$search}%")
            //    ->orWhere('lastname', 'like', "%{$search}%");
          })
          ->whereNotNull('deleted_at')
          ->onlyTrashed()
          ->orderBy('deleted_at', 'desc');
      }])
      ->groupBy('employee_id')
      ->orderBy('employee_id', 'desc');


      if ( $request->get('position_id') && $request->get('position_id') != '' ) {
          $aw = [];
          foreach ($employee->where('position_id', $request->get('position_id'))->get() as $value) {
              if($value->employee == null) continue;
              $aw[] = $value;
          }
          return $aw;
      }
      // workaround fuck
      $aw = [];
      foreach ($employee->get() as $value) {
          if($value->employee == null) continue;
          $aw[] = $value;
      }
      return $aw; 
    }


    /**
     * Restore trashed
     *
     * @param  int  $id
     * @return [type] [description]
     */
    public function restoreTrashed($id)
    {
        $employee = Employee::onlyTrashed()->find($id);

        if (!$employee) {
            return response('Employee not found!', 404);
        }

        $employee->restore();
        return response('', 204);
    }

    /**
     * Search Employee
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return [type]     [description]
     */
    public function search(Request $request)
    {
        // Note: use scout here
        $search = explode(" ",$request->get('kw'));
        $hasEmail = $request->get('has_email');
        $userRole = $request->get('userRole');
        $userid = $request->get('userid');
        if(@$userRole=='client'){
            //get community clients data
            $getemployeelist = EmployeeClient::where('client_id', $userid)->get();
            $getclientList = array();
            foreach($getemployeelist as $getemployeelistval){
                $getclientList[] = $getemployeelistval->employee_id;
            }

            $employee = EmployeePosition::with(['employee' => function($q) use ($search, $hasEmail, $userid, $getclientList) {
                if ( $hasEmail == 'both'  ) {
                    $q->where('added_by',$userid)
                    //->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                    });
                } else if ( $hasEmail ) {
                    $q->where('email', '!=', NULL)
                    ->where('added_by',$userid)
                    //->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                    });
                } else if ( !$hasEmail ) {
                    $q->where('email', '=', NULL)
                    ->where('added_by',$userid)
                    //->orWhereIn('id',$getclientList)
                    ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                    });
                }
            }])
            ->groupBy('employee_id')
            ->orderBy('employee_id', 'desc'); 
        }else { 
            $employee = EmployeePosition::with(['employee' => function($q) use ($search, $hasEmail) {
                if ( $hasEmail == 'both'  ) {
                    $q->where('added_by',0)
                     ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                    });
                } else if ( $hasEmail ) {
                    $q->where('email', '!=', NULL)
                      ->where('added_by',0)
                      ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                      });
                } else if ( !$hasEmail ) {
                    $q->where('email', '=', NULL)
                      ->where('added_by',0)
                      ->where(function($qq) use ($search) {
                        foreach($search as $s) {
                            $qq->where('firstname', 'like', "%{$s}%")
                            ->orWhere('lastname', 'like', "%{$s}%");
                        }
                      });
                }
            }])
            ->groupBy('employee_id')
            ->orderBy('employee_id', 'desc'); 
        }
        // $employee = EmployeePosition::with(['employee' => function($q) use ($search, $hasEmail) {
        //     if ( $hasEmail == 'both' ) {
        //         $q->where(function($qq) use ($search) {
        //             foreach($search as $s) {
        //                 $qq->where('firstname', 'like', "%{$s}%")
        //                 ->orWhere('lastname', 'like', "%{$s}%");
        //             }
        //         });
        //     } else if ( $hasEmail ) {
        //         $q->where('email', '!=', NULL)
        //           ->where(function($qq) use ($search) {
        //             foreach($search as $s) {
        //                 $qq->where('firstname', 'like', "%{$s}%")
        //                 ->orWhere('lastname', 'like', "%{$s}%");
        //             }
        //           });
        //     } else if ( !$hasEmail ) {
        //         $q->where('email', '=', NULL)
        //           ->where(function($qq) use ($search) {
        //             foreach($search as $s) {
        //                 $qq->where('firstname', 'like', "%{$s}%")
        //                 ->orWhere('lastname', 'like', "%{$s}%");
        //             }
        //           });
        //     }
        // }])
        // ->groupBy('employee_id')
        // ->orderBy('employee_id', 'desc');

        // if position param is present then it'll be added to the where clause
        if ( $request->get('position_id') && $request->get('position_id') != '' ) {
            $aw = [];
            foreach ($employee->where('position_id', $request->get('position_id'))->get() as $value) {
                if($value->employee == null) continue;
                $aw[] = $value;
            }
            return $aw;
        }

        // workaround fuck
        $aw = [];
        foreach ($employee->get() as $value) {
            if($value->employee == null) continue;
            $aw[] = $value;
        }
        return $aw;
    }

    /**
     * Get the previous and next record based on the given id
     * 
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getPrevNextRecord($id)
    {
            /*if ( $hasEmail == 'both' ) {
        $q->where(function($qq) use ($search) {
            $qq->where('firstname', 'like', "%{$search}%")
               ->orWhere('lastname', 'like', "%{$search}%");
        });*/
        /*->groupBy('employee_id')
        ->orderBy('created_at', 'desc');*/
        // awts
        $employee = Employee::find($id);
        return [
            'first' => $employee->first(),
            'prev' => $employee->prev(),
            'next' => $employee->next(),
            'last' => $employee->orderBy('id', 'desc')->first(),
        ];
    }

    /**
     * Get Employee and its position(s)
     * Note:
     *  - route messed up -_-, lots of misunderstandings happened
     *  - this should be under the resources
     *  - remap later
     * 
     * @return [type] [description]
     */
    public function getEmployees()
    {
        return Employee::orderBy('id', 'desc')
                    ->get();
    }

    public function getPosEmployees()
    {
        return Employee::where('enable_security_officer', 1)->orderBy('id', 'desc')
                    ->get();
    }

    public function getSearchedEmployee(Request $request)
    {
        $search = $request->get('kw');
        return Employee::orderBy('id', 'desc')
                    ->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->get();
    }


    /**
     * Get employee's position(s)
     * 
     * @param  int $empId
     * @return \Illuminate\Http\Response
     */
    public function getPositions($empId)
    {
        return Employee::find($empId)->position;
    }

    /**
     * Update Employee Positions
     *
     * @param  int $empId
     * @return \Illuminate\Http\Response
     */
    public function updateEmployeePositions($empId, Request $request)
    {
        $employee = Employee::find($empId);
        if ( $request->get('positions') && count($request->get('positions')) > 0 ) {          
            // just simply flush all the employee's position inside the database, and replace it with
            // the requested payload
            $employee->position()->forceDelete();
            foreach ($request->get('positions') as $v) {
                $position = EmployeePosition::firstOrCreate([
                    'employee_id' => $empId,
                    'position_id' => $v
                ]);
            }
            return response('Position(s) updated!', 204);
        }

        return response('Error', 500);
    }

    public function updatePublish($empId, Request $request)
    {
        $employee = Employee::find($empId);
        $employee->new_schedule_published = $request->get('arr_new_schedule_published');
        $employee->published_shift_is_changed = $request->get('arr_published_shift_is_changed');

        if ( $employee->save() ) {
            return response('Updated!', 204);
        }
        return response('Error', 500);
    }

    public function sendSignInInstructionToAll(Request $request) {
        $employee = Employee::where('last_login_at', NULL)->get();
        
        $data = [];
        $when = now()->addMinutes(1);

        foreach($employee as $emp) {
            $data[] = ['name' => $emp->firstname. " " . $emp->lastname, 'email' => $emp->email];
            Mail::to($emp->email)
            ->later($when, new SignInInstruction($emp));
        }

        if(true) {
            return response($data, 200);
        }

        return response('Error', 500);
    }

    public function sendSignInInstructionToEmployee(Request $request) {

        try {
            $ids = $request->input('ids');
            $employee = Employee::whereIn('id', $ids)->get();
    
            if(count($employee) == 0) {
                return response('Error', 500);
            }
    
            $data = [];
    
            $when = now()->addMinutes(1);
    
            foreach($employee as $emp) {
                $data[] = ['name' => $emp->firstname. " " . $emp->lastname, 'email' => $emp->email];
                Mail::to($emp->email)
                ->later($when, new SignInInstruction($emp));
            }
    
            return response($data, 200);    
        }
        catch (QueryException $e) {
            return response('Error', 500);
        }

    }

    public function sendMessage(Request $request) {
        try {
            $ids = $request->input('id');
            $subject = $request->input('subject');
            $message = $request->input('message');
            $employee = Employee::whereIn('id', $ids)->get();
  
            if(count($employee) == 0) {
                return response('Error', 500);
            }
    
            $data = [];
    
            $when = now()->addMinutes(1);
            foreach($employee as $emp) {

                $create_groupdata = DB::table('message_group')->updateOrInsert([
                    'group_name' => $subject,
                    'user_type' => "App\\Admin",
                    'user_id' => 1,
                    'created_at'=>  date('Y-m-d H:i:s'),
                    'updated_at'=>  date('Y-m-d H:i:s'),
                ]);
                
                $create_group = DB::table('message_group')->orderBy('id', 'desc')->first();
                $group_id = $create_group->id;
                
                $create_groupdata = DB::table('message_group_user')->updateOrInsert([
                    'group_id' => $group_id,
                    'groupable_type' => "App\\Admin",
                    'groupable_id' => 1,
                    'created_at'=>  date('Y-m-d H:i:s'),
                    'updated_at'=>  date('Y-m-d H:i:s'),
                ]);

                $create_groupdata = DB::table('message_group_user')->updateOrInsert([
                    'group_id' => $group_id,
                    'groupable_type' => "App\Employee",
                    'groupable_id' => $emp->id,
                    'created_at'=>  date('Y-m-d H:i:s'),
                    'updated_at'=>  date('Y-m-d H:i:s'),
                ]);

                $message = Message::create([
                    'group_id' =>$group_id,
                    'message' => $message,
                    'messageable_id' =>  1,
                    'messageable_type'=> 'App\\Admin',
                    'from' => 'admin',
                    'message_type' => 'text',
                    'send_time'=>  date('H:i:sa'),
                ]);
                
                if($emp->deviceToken!=""){ 
                      $curl = curl_init();
                      $token = $emp->deviceToken;
                      $message = "Admin has sent you message";
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

                $data[] = ['name' => $emp->firstname. " " . $emp->lastname, 'email' => $emp->email];
                Mail::to($emp->email)->later($when, new SendMessage($emp, ['subject' => $subject, 'message' => $message]));
            }
    
            return response($data, 200);    
        }
        catch (QueryException $e) {
            return response('Error', 500);
        }
    }

    public function sendReminder(Request $request) {
       
        try {
            $ids       = $request->input('id');
            $beginDate = $request->input('beginDate');
            $endDate   = $request->input('endDate');
            $subject   = $request->input('subject');
            $comment   = $request->input('comment');

            $getDatesFromRange = function($start, $end, $format = 'Y-m-d') { 
                $array = array(); 
 
                $interval = new \DateInterval('P1D'); 
              
                $realEnd = new \DateTime($end); 
                $realEnd->add($interval); 
              
                $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd); 
 
                foreach($period as $date) {                  
                    $array[] = $date->format($format);  
                } 
                return $array; 
            };
            
            $datesRange = $getDatesFromRange($beginDate, $endDate);

            $employee = Employee::whereIn('id', $ids)->with(['shifts'])->get();
                           
            $sent = [];
            $sentNot = [];
    
            $when = now()->addMinutes(1);
    
            foreach($employee as $emp) {
                $dates = [];
                foreach($emp->shifts as $shift) 
                {
                    $dates = array_merge($dates, explode("~", $shift->added_to));
                }
                
                if(count(array_intersect($dates, $datesRange)) > 0 ) {
                    $sent[] = $emp;
                } else {
                    $sentNot[] = $emp; 
                }

            }

            foreach($sent as $s) {
                Mail::to($s->email)->later($when, new SendReminder($s, ['beginDate' => $beginDate, 'endDate' => $endDate, 'subject' => $subject, 'comment' => $comment ]));
            }    
            return response(['sent' => $sent, 'sentNot' => $sentNot], 200);    
        }
        catch (QueryException $e) {
            return response('Error', 500);
        }
    }

    public function restoreDeleted(Request $request) {

        $ids = explode(",", $request->input('id'));

        $data = [];
        foreach($ids as $id) {
            $employee = Employee::onlyTrashed()->find($id);
            if($employee) {
                $employee->restore();
                $data[] = ['name' => $employee->firstname. " " . $employee->lastname, 'email' => $employee->email];
            }  
        }        
        return response($data);
    }


    public function isThisCurrentShift($shift){

        if($shift['clockin'] != null && $shift['clockin']['shiftaction'] == true){
            return  true;
        }
        $current_time = date('H:i:s');
        
        if($current_time <=  $shift['start']  && $current_time  >= $shift['end']  ):
            return  true;
        endif;
        return false;
       
    }


    /**
     * SEND HERE THE CURRENT SHIFT 
     */

    public function onNow(Request $request) {
        $response=['status'=>false,'message'=>'Something Went Wrong']; 

        $employee = $this->TodaydateShifIsExist();
        $SHIFT = [];
        foreach($employee  as  $shiftdata):
            if($this->isThisCurrentShift($shiftdata)){
                $SHIFT[] = $shiftdata;
            }                           
        endforeach;
       //dd($SHIFT);
        if(count($SHIFT) > 0){
            $response = ['status'=>true, 'message'=>'Today shift','data'=>$SHIFT];
        }else{
            $response = ['status'=>false, 'message'=>'Your shift has not started'];
        }
        return response($response);
    }


    
    public function checktimeoffwithshift($timeoff, &$shift,&$emp,$i){
        
        $current_date = Carbon::now()->toDateString();
        $timeoff_dates  = array_merge(explode("~", $timeoff['time_off']));

        if(in_array($current_date, $timeoff_dates)){
         
            if($timeoff['whole_day'] == '1'){
                array_splice($emp['shifts'],$i);
                return false;
            }else{
          
                $is_start =false;
                $is_end = false;
                if($timeoff['from_time'] <= $emp[$i]['start'] && $timeoff['to_time'] >= $emp[$i]['start'] ){
                    $is_start =true;
                }

                if($timeoff['from_time'] <=$emp[$i]['end'] && $timeoff['to_time'] >= $emp[$i]['end'] ){
                    $is_end = true;
                }

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

    /***
     * CHECK HERE TODAY SHIFT EXIST ORT NOT
     * 
     */
    public function  TodaydateShifIsExist(){
         
        $response=['status'=>false,'message'=>'Something Wrong']; 

        $current_date = Carbon::now()->toDateString();
        $data = [];  

        $shifts = Shift::with(['employee','position','property',
                'employee.timeoff'=>function($q){
                    $q->where('time_off', 'LIKE',"%".Carbon::today()->toDateString()."%");
                    $q->where('status', 'approved');
                },'logs'
            ])
            ->whereDate('added_to', 'LIKE',"%".Carbon::today()->toDateString()."%")
            ->whereNull('deleted_at')
            ->get()->toArray();
        
        foreach($shifts as &$shift):
            for($i =0; $i < count($shifts); $i++):
                $shifts[$i]['clockin'] = $this->GetClockInOutByEmployeeId($shifts[$i]['id'],$current_date,$shifts[$i]['start'],$shifts[$i]['end']);
                foreach($shift['employee']['timeoff'] as &$timeoff):
                    $this->checktimeoffwithshift($timeoff,$shifts[$i],$shifts,$i);
                endforeach;
            endfor;         
        endforeach;
        return $shifts;                  
    }



    public function GetClockInOutByEmployeeId($shift_id,$current_date,$check_start,$check_end){
       
        $clockin = Clockin::where('shift_id',$shift_id);
        if ($shift_id != null) {
			$clockin->where('shift_id',$shift_id);
		}
        if ($shift_id != null) {
			$clockin->orWhereDate('check_date',$current_date);
		}

        if ($check_start != null) {
			$clockin->where('check_start',$check_start);
		}

        if ($check_end != null) {
			$clockin->where('check_end',$check_end);
		}

        $clockinout = $clockin->first();
        //dd($this->getEloquentSqlWithBindings($clockin)); 
        return $clockinout; 
    }

    /***
     * 
     * SEND HERE USER SHIFT ACTIVE LATE
     */
    public function onLater(Request $request) {
        $response=['status'=>false,'message'=>'Something Went Wrong']; 

        $employee = $this->TodaydateShifIsExist();

        if($employee){
            $response = ['status'=>true, 'message'=>'Today shift','data'=>$employee];
        }else{
            $response = ['status'=>false, 'message'=>'Your shift has not started'];
        }
        return response($response);
    }


    public function bulkEdit(Request $request) {

        $data = [];

        if($request->input("max_weekly_hours") != '')
        $data['max_weekly_hours'] = $request->input("max_weekly_hours");

        if($request->input("max_weekly_days") != '')
        $data['max_weekly_days']  = $request->input("max_weekly_days");

        if($request->input("max_day_hours") != '')
        $data['max_day_hours']    = $request->input("max_day_hours");

        if($request->input("max_day_shifts") != '')
        $data['max_day_shifts']   = $request->input("max_day_shifts");

        if($request->input("city") != '')
        $data['city']             = $request->input("city");

        if($request->input("state") != '')
        $data['state']            = $request->input("state");

        if($request->input("zip") != '')
        $data['zip']              = $request->input("zip");

        if($request->input("hired_date") != '')
        $data['hired_date']       = $request->input("hired_date");

        if($request->input("priority_group") != '')
        $data['priority_group']   = $request->input("priority_group");

        if($request->input("pay_rate") != '')
        $data['pay_rate']         = $request->input("pay_rate");

        // $data['alert_date']       = $request->input("alert_date");
        // $data['cf_1']             = $request->input("cf_1");
        // $data['cf_2']             = $request->input("cf_2");
        // $data['comments']         = $request->input("comments");

        $ids = $request->input('ids');

        foreach($ids as $id) {
            $emp = Employee::where('id', $id)->first();
            if($emp) {
                $emp->update($data);
            }
        }

        return response(['status' => 'success']);
    }

    public function bulkDeletedEdit(Request $request) {

        $data = [];

        if($request->input("max_weekly_hours") != '')
        $data['max_weekly_hours'] = $request->input("max_weekly_hours");

        if($request->input("max_weekly_days") != '')
        $data['max_weekly_days']  = $request->input("max_weekly_days");

        if($request->input("max_day_hours") != '')
        $data['max_day_hours']    = $request->input("max_day_hours");

        if($request->input("max_day_shifts") != '')
        $data['max_day_shifts']   = $request->input("max_day_shifts");

        if($request->input("city") != '')
        $data['city']             = $request->input("city");

        if($request->input("state") != '')
        $data['state']            = $request->input("state");

        if($request->input("zip") != '')
        $data['zip']              = $request->input("zip");

        if($request->input("hired_date") != '')
        $data['hired_date']       = $request->input("hired_date");

        if($request->input("priority_group") != '')
        $data['priority_group']   = $request->input("priority_group");

        if($request->input("pay_rate") != '')
        $data['pay_rate']         = $request->input("pay_rate");

        // $data['alert_date']       = $request->input("alert_date");
        // $data['cf_1']             = $request->input("cf_1");
        // $data['cf_2']             = $request->input("cf_2");
        // $data['comments']         = $request->input("comments");

        $ids = $request->input('ids');

        foreach($ids as $id) {
            $emp = Employee::where('id', $id)->onlyTrashed()->first();
            if($emp) {
                $emp->update($data);
            }
        }

        return response(['status' => 'success']);
    }


    function getPositiondata(Request $request, $id) {
        $emp = Employee::where('id', $id)->with('position', 'position.position')->first();
        return response(['status' => 'success', 'data' => $emp]);
    }

    function savePositiondata(Request $request) {

        $alert_date = $request->input('alert_date');
        $pay_rate = $request->input('pay_rate');
        $comment = $request->input('comment');

        $employee = Employee::where('id', $request->input('id'))->first();
        $employee->alert_date = $alert_date;
        $employee->pay_rate   = $pay_rate;
        $employee->comment    = $comment;
        $employee->save();

        $position = $request->input('position');
        foreach($position as $p) {
            $emp = EmployeePosition::where('id', $p['id'])->first();
            if($emp) {
                $emp->pay_rate = $p['pay_rate'];
                $emp->alter_date = $p['alter_date'];
                $emp->preference = $p['preference'];
                $emp->save();
            }
        }
        return response(['status' => 'success', 'messgae' => "Update Successfully"]);
    }


    /*
    * Show time off request 
    */
    function ShowTimeOffRequestDetail(Request $request) {
       
        $Timeoff =  Timeoff::with('employee')->get();
        $Timeoff_data = $Timeoff->toArray();
        $time_off =array();
        foreach ($Timeoff_data as $key => $off) {
            $dates =  explode("~", $off['time_off']);
            $time_off_temp = [];
            $time_off_temp['timeoff_id'] =  $off['id'];
            $time_off_temp['employee_id'] =  $off['employee_id'];
            $time_off_temp['time_off'] =  $dates;
            $time_off_temp['description'] = $off['description'];
            $time_off_temp['status'] = $off['status'];
            $time_off_temp['firstname'] = $off['employee']['firstname'];
            $time_off_temp['lastname'] =$off['employee']['lastname'];
            $time_off_temp['email'] = $off['employee']['email'];
            $time_off_temp['created_at'] = $off['created_at'];
            $time_off_temp['updated_at'] = $off['updated_at'];           
            $time_off[] = $time_off_temp;
        }     
        return $time_off;
 
    }
   
    /*
    * Time Off Status Change by admin
    */
    public function ChangeTimeOffStatus(Request $request){
        $response = ['status'=>false,'message'=>'Something wrong'];
        $update = ['status'=>$request['status']];
        
        $updated =  Timeoff::where('id',$request->get('id'))-> update($update);
        if ($updated) {

            // SEND HERE NOTIFICATION
            $employee = Employee::where('email',$request->input('email'))->first();
            
            $app_id = '04ad3658-d35a-4910-bdd0-2d53a56207e2';
            $subject ='Time Off request';
            $message = 'Your account '.$request['status']; 
            if($employee['employee_player_id'] != ''):
                // send Notification by custom method
                $changetimeoff = new WholeAppNotification();
                $changetimeoff->PushNotification($app_id,$employee['employee_player_id'],$subject,$message);

                // INSERT NOTIFICATION
                $notification = [
                    'employee_id'=>$employee['id'],
                    'subject'=> $subject,
                    'description'=> $message,
                ];
                Notifications::create($notification);
            endif;
           
            //Mail::to($request->email)->later($when, new TimeOffRequestStatus($update)); 
            $response= ['status'=>true, 'message'=>'Your request is approved'];            
        }else{
            $response= [ 'status'=>false,'message'=>'Youir request not approved']; 
        }
        return response($response);
    }


    /**
    * get message by employee id   
    */
    public function GetMessage($employee_id){
       /// dd('dsgxdfh');
        return Employeemessage::where('employee_id',$employee_id)->get();
    }

    /**
    * send message by admin by id 
    */
    public function SendMessageByadmin(Request $request)
    {    
        $response = ['status'=>false, 'message'=>'Something wrong' ];

        if($request->employee_id == '' &&  $request->employee_id == NULL):
            $response = ['status'=>false, 'message'=>'Parameter missing'];
        else:
            $send_message = new Employeemessage([
                'employee_id'=>$request->employee_id,           
                'message'=>$request->message,
            ]);
            $send_message->save();
            if($send_message):
                $response =['status'=>true,'message'=>"Your message is send",'data'=>$send_message ];
            else :
                $response =['status'=>false, 'message'=>"Your message not send. Pleass check!"];
            endif;
        endif;  
        return response($response);
    }


    /****
     * GET TimeOFF STATUS
     *  
     */
    public function GetTimeOffStatus(Request $request){
        $response = ['status' =>false,'message' =>'Something wrong'];
        $Timeoff =  Timeoff::with('employee')->orderBy('created_at', 'DESC')->where('status','=',$request->get('status'))->get();
        $Timeoff_data = $Timeoff->toArray();
        $time_off =[];
        foreach ($Timeoff_data as $key => $off) {
            $dates =  explode("~", $off['time_off']);
            $time_off_temp = [];
            $time_off_temp['timeoff_id'] =  $off['id'];
            $time_off_temp['employee_id'] =  $off['employee_id'];
            $time_off_temp['time_off'] =  $dates;
            $time_off_temp['description'] = $off['description'];
            $time_off_temp['status'] = $off['status'];
            $time_off_temp['firstname'] = $off['employee']['firstname'];
            $time_off_temp['lastname'] =$off['employee']['lastname'];
            $time_off_temp['email'] = $off['employee']['email'];
            $time_off_temp['created_at'] = $off['created_at'];
            $time_off_temp['updated_at'] = $off['updated_at'];           
            $time_off[] = $time_off_temp;
        }     
        if(count($time_off) > 0){
            $response = ['status' =>true,'message' =>'All Records','data'=>$time_off];
        }else{
            $response = ['status' =>false,'message' =>'No Record Found!'];
        }
        return response($response); 
    }


    /****
     * GET Time OFF DESC ORDER 
     *  
     */
    public function TimeOFFOrderDescData(Request $request){
        $response = ['status' =>false,'message' =>'Something wrong'];
        $Timeoff =  Timeoff::with('employee')->orderBy('created_at', 'DESC')->get();
        $Timeoff_data = $Timeoff->toArray();
        $time_off =[];
        foreach ($Timeoff_data as $key => $off) {
            $dates =  explode("~", $off['time_off']);
            $time_off_temp = [];
            $time_off_temp['timeoff_id'] =  $off['id'];
            $time_off_temp['employee_id'] =  $off['employee_id'];
            $time_off_temp['time_off'] =  $dates;
            $time_off_temp['description'] = $off['description'];
            $time_off_temp['status'] = $off['status'];
            $time_off_temp['firstname'] = $off['employee']['firstname'];
            $time_off_temp['lastname'] =$off['employee']['lastname'];
            $time_off_temp['email'] = $off['employee']['email'];
            $time_off_temp['created_at'] = $off['created_at'];
            $time_off_temp['updated_at'] = $off['updated_at'];           
            $time_off[] = $time_off_temp;
        } 
       
        if(!empty($time_off)){
            $response = ['status' =>true, 'message' =>'All Records', 'data'=>$time_off];
        }else{
            $response = ['status' =>false,'message' =>'No Record Found!'];
        }
        return response($response); 
    }

    /**
     *  Timeoff Request ASC
     */

    public function TimeOFFOrderASCData(Request $request){
        $response = ['status' =>false,'message' =>'Something wrong'];

        $Timeoff =  Timeoff::with('employee')->orderBy('created_at', 'ASC')->get();
        $Timeoff_data = $Timeoff->toArray();
        $time_off =[];
        foreach ($Timeoff_data as $key => $off) {
            $dates =  explode("~", $off['time_off']);
            $time_off_temp = [];
            $time_off_temp['timeoff_id'] =  $off['id'];
            $time_off_temp['employee_id'] =  $off['employee_id'];
            $time_off_temp['time_off'] =  $dates;
            $time_off_temp['description'] = $off['description'];
            $time_off_temp['status'] = $off['status'];
            $time_off_temp['firstname'] = $off['employee']['firstname'];
            $time_off_temp['lastname'] =$off['employee']['lastname'];
            $time_off_temp['email'] = $off['employee']['email'];
            $time_off_temp['created_at'] = $off['created_at'];
            $time_off_temp['updated_at'] = $off['updated_at'];           
            $time_off[] = $time_off_temp;
        } 
       
        if(!empty($time_off)){
            $response = [ 'status' =>true,'message' =>'All Records','data'=>$time_off];
        }else{
            $response = ['status' =>false, 'message' =>'No Record Found!'];
        }
        return response($response); 
    }
    

    /*
    **  TimeOFFFilter 
    */
    public function TimeOFFFilter(Request $request){

        $response = ['status' =>false,'message' =>'Something wrong'];
        $TimeoffSQl =  Timeoff::with('employee');

        if($request->status != null) {
            $TimeoffSQl->where('status', $request->status);
        }  

        if($request->requested != null) {
            $TimeoffSQl->orderBy('created_at', $request->requested);
        }
        $timedata  = $TimeoffSQl->get();
        
        //dd($data);
        $Timeoff_data = $timedata->toArray();
        $time_off =array();

        foreach ($Timeoff_data as $key => $off) {
            $dates =  explode("~", $off['time_off']);
            $time_off_temp = [];
            $time_off_temp['timeoff_id'] =  $off['id'];
            $time_off_temp['employee_id'] =  $off['employee_id'];
            $time_off_temp['time_off'] =  $dates;
            $time_off_temp['description'] = $off['description'];
            $time_off_temp['status'] = $off['status'];
            $time_off_temp['firstname'] = $off['employee']['firstname'];
            $time_off_temp['lastname'] =$off['employee']['lastname'];
            $time_off_temp['email'] = $off['employee']['email'];
            $time_off_temp['created_at'] = $off['created_at'];
            $time_off_temp['updated_at'] = $off['updated_at'];           
            $time_off[] = $time_off_temp;
        } 

        if(!empty($time_off)){
            $response = ['status' =>true, 'message' =>'All Records','data'=>$time_off];
        }else{
            $response = ['status' =>false, 'message' =>'No Record Found!'];
        }
        return response($response); 
    }


    
    /***
     *  CHNAGE EMAIL ADDRESS  IN EMPLOYEE EDIT
     * 
     */
    public function ChangeEmployeeEmails(Request $request){
        try{
            $check_email = Employee::where('email',$request->email)->first();
            if ($check_email === null):
                $data = ['email'=>$request->email];
                $update_employee = Employee::where('id',$request->employee_id)->update($data);
                if($update_employee):
                    $response = ['status'=>true,'message'=>'Email Updated Successfully!'];
                else:
                    $response = ['status'=>false,'message'=>'Email not update Successfully'];
                endif;
            else:
                $response = ['status'=>false,'message'=>'This email already exist!'];
            endif;
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

    /****
     * RESET PASSWORD AND PRINT 
     * 
     */
    public function ResetPassAndPrint($employee_id){
        try{
            $response = ['status'=>false,'message'=>'Something wrong'];

            $password = $this->password_rand_string(16);
            $update_password = [
                'password'=> Hash::make($password),
                'plain_password'=>$password
            ];

            $data = ['password' =>$password];
            $update_employee= Employee::where('id',$employee_id)->update($update_password);
            if($update_employee):
                $user_email =  Employee::where('id',$employee_id)->first();
                $when = now()->addMinutes(1);
                Mail::to($user_email->email)->later($when, new EmployeResetPass([$data])); 
                
                $response = ['status'=>true,'message'=>'ResetPass And Print','data'=>$password];
            else:
                $response = ['status'=>false,'message'=>'Error in resetpass print'];
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


    /**
     * EMPLOYEE SCHEDULES DETAILS 
     */
    public function EmployeMonthSchedule(Request $request){
        try{

            $response=['status'=>false,'message'=>'something wrong'];    
            // GET EMPLOYE SHIFT DETAIL WITH DATE AND TIME
            $employee =  ShiftEmployee::with(['employee', 'shift','shift.property','timeoff'])
                ->Where(function($q){
                        $q->whereHas('shift',function($query) {
                            $query->whereBetween('added_to', [Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth()]);  
                        });
                    })
                ->where('employee_id', $request['employee_id'])
                ->get()
               ->toArray();               
           //dd($this->getEloquentSqlWithBindings($employee));   
        if(count($employee) > 0):         
          
            $send_schedule = [];
            $send_timeoff = []; 
            $listing = [];         
            // send here the user shift data
            foreach ($employee as $key => $emp):
                    //  GET HERE SHIFT ADDED TO                        
                    $dates =  explode("~", $emp['shift']['added_to']);
                    //SHIFT SCHEDULE 
                    for($i = 0; $i < count($dates); $i++) {
                        $send_schedule[$dates[$i]] = [
                                'start_time' => date("H:i:s", strtotime($emp['shift']['start'])),
                                'end_time' => date("H:i:s", strtotime($emp['shift']['end'])),
                                'lunch_start' => date("H:i:s", strtotime($emp['shift']['lunch_start'])),
                                'lunch_end' => date("H:i:s", strtotime($emp['shift']['lunch_end'])),
                                'break_start' => date("H:i:s", strtotime($emp['shift']['break_start'])),
                                'break_end' => date("H:i:s", strtotime($emp['shift']['break_end'])),
                                'property' => $emp['shift']['property']
                        ];
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
    
            $timeoff_key = [];
            foreach($send_timeoff as $key=>$schedule):
                if($schedule['whole_day'] == 'true'):
                    $timeoff_key[] = $key;
                endif;
            endforeach;
                     
            foreach($send_schedule as $key=>$schedule):

                if(!in_array($key, $timeoff_key)):
                   $listing[$key] = $schedule; 
                endif;
            endforeach;
           
                $response =['status'=>true,'message'=>'All schedule','send_schedule'=>$send_schedule,'send_timeoff' =>$send_timeoff,'listing'=>$listing];             
            else:
                $response = ['status'=>false,'message'=>'No schedule Found'];     
            endif;

            return response($response);

        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }



    /*
    * Show time off request 
    */
    function EmployeeTimeOffRequestDetails($employee_id) {
        try{
       
            $timeoff =  Timeoff::with('employee')
                    ->where('employee_id',$employee_id)->get()->toArray();
            $time_off = [];
            foreach ($timeoff as $key => $off) {
                
                $time_off_temp = [];
                $time_off_temp['timeoff_id'] = $off['id'];
                $time_off_temp['employee_id'] = $off['employee_id'];
                $time_off_temp['time_off'] =  explode("~", $off['time_off']);
                $time_off_temp['description'] = $off['description'];
                $time_off_temp['status'] = $off['status'];
                $time_off_temp['whole_day'] = $off['whole_day'];
                $time_off_temp['from_time'] = $off['from_time'];              
                $time_off_temp['to_time'] = $off['to_time']; 
                $time_off_temp['created_at'] = $off['created_at'];
                $time_off_temp['updated_at'] = $off['updated_at'];
                $time_off_temp['employee'] = $off['employee'];         
                $time_off[] = $time_off_temp;
            }  
            if(count($time_off) > 0):
                $response = ['status'=>true,'message'=>'Timeoff Schedule  ','data'=>$time_off];
            else:
                $response = ['status'=>false,'message'=>'No timeoff schedule'];
            endif;  
            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }




}
