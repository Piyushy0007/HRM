<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\Reportslog;
use App\Models\ReportSubType;
use App\Parking;
use App\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Carbon\Carbon;
use DB;

class ClientWebTypeController extends Controller
{
    /*  Function for get report list with date,report typpe and client id */
    public function responseData($data,$msg,$status)
    {
        $response = 
            ['status'=>$status,'message'=>$msg, 'data'=>$data];
        return response($response);
    }
    public function dataWithReportType(Request $request)
    {
        try {
            // dd($request->all());
            $report= Reportslog::with('employee','property','report')
                ->where(function($q) use ($request) {
                    $q->where('report_type_id','=', $request->report_type_id)
                    ->Where('client_id', $request->client_id)
                    ->where('date',$request->current_date);
                });
            $data = $report->get(); 

            $EmpArray = [];

            foreach($data as $key=>$d) {
                if(!isset($EmpArray[$d->employee_id])) {
                    $EmpArray[$d->employee_id] = [];
                }

                if(!isset($EmpArray[$d->employee_id][$d->slot])) {
                    $EmpArray[$d->employee_id][$d->slot] = [];
                }

                $data =[]; 
                $data['id'] = $d->id;
                $data['description'] = $d->description;
                $data['location'] = $d->location;
                $data['date'] = $d->date;
                $data['time'] = $d->time;
                $data['slot'] = $d->slot;
                $data['status'] = $d->status;

                foreach($d->employee as $value){
                    $emp = [];
                    $emp['firstname']= $value['firstname'];
                    $emp['lastname']= $value['lastname'];
                    $data['emp'][] = $emp;
                }

                foreach($d->property as $value){
                    $emp = [];
                    $emp['address']= $value['address'];
                    $emp['lat']= $value['lat'];
                    $emp['long']= $value['long'];
                    $data['property'][] = $emp;
                }

                foreach($d->report as $value){
                    $emp = [];

                    $emp['report_type']= $value['report_name'];
                    $data['report_type'][] = $emp;
                }
                $EmpArray[$d->employee_id][$d->slot][] = $data;
            }
             
            if(count($EmpArray) > 0):
                $response =['status' =>true, 'message' =>'Record Found!', 'data'=> $EmpArray];
            else :
                $response = ['status' =>false,'message' =>'No Record Found!'];
            endif;
        return response($response);

        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);
                
        }
        
    }


    /**
     * Client get property ids by parking ticket report
     * @client
     * @parking
     */

    public function ClientPropertyParkingTicket(Request $request){
        try {
           
            $response = ['status'=>false,'message'=>'something wrong'];
            $properties = ClientProperties::where('client_id','=',$request['client_id'])->get();
            
            $main_array = [];
            foreach($properties as $prop):
                $property= [];
                $property['property'] = $prop;
                $property['parking'] = Parking::with('vechiclemake','vechiclemodel','vechicletype')
                        ->where('client_id','=',$request['client_id'])
                        ->where('property_id', '=', $prop['id'])
                        ->whereDate('created_at','=',date('Y-m-d',strtotime($request['current_date'])))
                        ->get()
                        ->toArray();
                if(!empty($property['parking'])):
                    $main_array[] = $property; 
                else:
                    $response = ['status'=>false,'message'=>'No Record Found!'];
                endif;
            endforeach;
            
            if($main_array):
                $response = ['status'=>true,'message'=>'All Records', 'data'=>$main_array];
            else:
                $response = ['status'=>false,'message'=>'No record found'];
            endif;
            return response()->json($response);
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

    public function ClientFilterParkingTickets(Request $request){
        try {
            $response = ['status'=>false,'message'=>'something wrong'];
           
                //$properties_ids = $properties = ClientProperties::where('client_id','=',$request['client_id'])->pluck('id');
                $properties = ClientProperties::where('client_id','=',$request['client_id'])->get();
                $main_array = [];
                
                $property['parking'] = [];
                foreach($properties as $prop):
                    $property= [];
                    $property['property'] = $prop;    
                    
                    $result = Parking::with('vechiclemake','vechiclemodel','vechicletype')
                        ->leftJoin('tblm_vehicle_make',function($join) use($request){
                            $join->on('tblm_vehicle_make.id' ,'=','tblm_parking.vehicle_make');
                        })
                        ->leftJoin('tblm_vehicle_model',function($join) use($request){
                            $join->on('tblm_vehicle_model.id' ,'=','tblm_parking.vehicle_model');
                        })
                        ->leftJoin('tblm_vehicle_type',function($join) use($request){
                            $join->on('tblm_vehicle_type.id' ,'=','tblm_parking.vehicle_type');
                        })
                        ->whereDate('tblm_parking.created_at','=', $request->current_date)
                        ->where('client_id','=',$request->client_id)
                        ->where('property_id', '=', $prop['id'])
                        ->where(function($q) use ($request) {
                            $q->where(function($query) use ($request){
                                    $query->where('licence_plate','LIKE', "%{$request->search}%");
                                })
                            ->orWhere(function($query) use ($request) {
                                    $query->where('color','LIKE', "%{$request->search}%");
                                })
                            ->orWhere('tblm_vehicle_make.vehicle_make_name','LIKE',"%{$request->search}%")
                            ->orWhere('tblm_vehicle_model.vehicle_model_name','LIKE',"%{$request->search}%")
                            ->orWhere('tblm_vehicle_type.vehicle_type_name','LIKE',"%{$request->search}%");
                            })
                           
                    ->get()
                    ->toArray();
                    
                    //dd($result);
                    $property['parking'] = $result;
                    
                    if(!empty($property['parking'])):
                        $main_array[] = $property; 
                    else:
                        $response = ['status'=>false,'message'=>'No Record Found!'];
                    endif;
                   
                endforeach;
                //dd($main_array);
                if(count($main_array)>0):
                    $response = ['status'=>true,'message'=>'All Records', 'data'=>$main_array];
                else:
                    $response = ['status'=>false,'message'=>'No Record Found!'];
                endif;
            
            return response()->json($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);
                
        }
    }



    /***
     * Single parking view 
     */

    public function  ClientSingleParkingView($parking_id){
        try {
            $response = ['status'=>false,'message'=>'something wrong'];
            $single_parking = parking::with('vechiclemake','vechiclemodel','vechicletype')
                ->where('id', '=', $parking_id)
                ->orderBy('id', 'DESC')
                ->get();  
            if(count($single_parking) > 0):
                $response = ['status'=>true,'message'=>'All Records', 'data'=>$single_parking];
            else:
                $response = ['status'=>false,'message'=>'No record found'];
            endif;
            
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }


    /**
     * invoice filter 
     */
    public function ClientInvoiceFilter(Request $request){
        try{
            $response = ['status'=>false,'message'=>'something wrong'];
            $invoices = Invoices::with('client');
                                  
            if ($request->search != null) {
                    $invoices->where(function($q) use ($request) {
                        $q->where(function($query) use ($request){
                            $query->where('invoice_amount','LIKE', "%{$request->search}%");
                        })
                        ->orWhere(function($query) use ($request) {
                            $query->where('id','LIKE', "%{$request->search}%");
                        })
                        ->orWhere(function($q) use($request){
                            $q->whereHas('client',function($query) use ($request) {
                                $query->where('clientname', 'Like', "%{$request->search}%"); 
                            });
                        });
                    });
                // $invoices->where('invoice_amount','Like', "%{$request->search}%")
                // ->orWhere(function($q) use($request){
                //     $q->whereHas('client',function($query) use ($request) {
                //         $query->where('clientname', 'Like', "%{$request->search}%");  
                //     });
                // });                
            }

            if($request->data != null && $request->data !="alldata") {
                if($request->data === "today"){
                    $invoices->whereDate('created_at',Carbon::today());

                }else if($request->data === "yesterday"){
                   // dd(Carbon::yesterday());
                    $invoices->whereDate('created_at',Carbon::yesterday());

                }else if($request->data === "weekly"){
                    $invoices->whereBetween('created_at',[ 
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);

                }else if($request->data === "monthly"){
                    $invoices->whereBetween('created_at',[ 
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ]);

                }else if($request->data === "yearly"){
                    $invoices->whereBetween('created_at',[
                        Carbon::now()->startOfYear(),
                        Carbon::now()->endOfYear(),
                    ]);
                }
            }

            if($request->client_id != null ) {
                $invoices->where('client_id','=',$request->client_id);
            }

            if($request->status != null &&  $request->status !="all") {
                $invoices->where('status', '=', $request->status);
            }			
           $invoice_data  = $invoices->get();
           //dd($this->getEloquentSqlWithBindings($invoices));
            if(count($invoice_data) > 0):
                $response = ['status' =>true,'message' =>'All Records!','data' => $invoice_data];
            else:
                $response =['status' =>false,'message' =>'No Records Found!'];
            endif;
            return response($response);
            
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }

    }


    
}
