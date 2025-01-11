<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\ShiftEmployee;
use App\Models\Employee;
use App\Shift;
use App\Reportslog;
use App\ReportType;
use App\ReportSubType; 
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends Controller
{
	/**
	 * [shift description]
	 * 
	 * @param  Request $request [description]
	 * @param  String  $type    i.e. 'shifts'
	 */

	 
    public function shift (Request $request, $type)
    {
		if ($request->get('reportType') && $request->get('reportType') == 'position') {
			
			$startDate = $request->get('start');
			$endDate = $request->get('end');
			$position = $request->get('position');

			$query = Shift::with(['employee', 'position'])->whereBetween('added_to', [$startDate, $endDate]);
			if($position != '') {
				$query->whereHas('position', function($q) use ($position) {
					return $q->where('id', $position);
				});
			}

			$shifts = $query->get()->toArray();

			usort($shifts, function ($a, $b) {
				return strcmp($a['position']['position'], $b['position']['position']);
			});

			$data = [];
			$total_shifts = 0;
			$total_hours = 0;
			$total_cost   = 0;

			foreach($shifts as $shift) {

				foreach($shift['employee'] as $emp) 
				{
					$temp = [];
					$temp['position'] = $shift['position']['position'];
					$temp['empolyee'] = $emp['firstname']." ".$emp['lastname'];
					$temp['shifts']   = count(explode('~',$shift['added_to']));
				 // $temp['hours']    = abs(number_format((strtotime($shift['end']) - strtotime($shift['start']))/3600, 2));
					$temp['hours']    = $shift['paid_hours'];
					$temp['payrate']  = $emp['pay_rate'];
					$temp['cost']     = $temp['hours'] * $temp['payrate'] * $temp['shifts'];

					$data[] = $temp;

					$total_shifts += $temp['shifts'];
					$total_cost   += $temp['cost'];
					$total_hours  += $temp['hours'];
				}
			} 

			$prePosition = '';

			foreach($data as &$d) {
				if($d['position'] != $prePosition) {
					$prePosition = $d['position'];
				} else {
					$d['position'] = '';
				}
			}

			return ['data' => $data, 'meta' => [
				'total_shifts' => $total_shifts,
				'total_hours'  => $total_hours,
				'total_cost'   => $total_cost
			]];
		}
		else {

			// $startDate = $request->get('start');
			// $endDate = $request->get('end');
			// $position = $request->get('position');


			// $shifts = Shift::with(['employee', 'position'])->whereBetween('added_to', [$startDate, $endDate])->get()->toArray();

			// usort($shifts, function ($a, $b) {
			// 	return strcmp($a['position']['position'], $b['position']['position']);
			// });

			// $data = [];
			// $total_shifts = 0;
			// $total_hours = 0;
			// $total_cost   = 0;

			// foreach($shifts as $shift) {

			// 	foreach($shift['employee'] as $emp) 
			// 	{
			// 		$temp = [];
			// 		$temp['position'] = $shift['position']['position'];
			// 		$temp['empolyee'] = $emp['firstname']." ".$emp['lastname'];
			// 		$temp['shifts']   = count(explode('~',$shift['added_to']));
			// 	 // $temp['hours']  =  abs(number_format((strtotime($shift['end']) - strtotime($shift['start']))/3600, 2));
			// 		$temp['hours']    = $shift['paid_hours'];
			// 		$temp['payrate']  = $emp['pay_rate'];
			// 		$temp['cost']     = $temp['hours'] * $temp['payrate'] * $temp['shifts'];

			// 		$data[] = $temp;

			// 		$total_shifts += $temp['shifts'];
			// 		$total_cost   += $temp['cost'];
			// 		$total_hours  += $temp['hours'];
			// 	}
			// } 

			// $prePosition = '';

			// foreach($data as &$d) {
			// 	if($d['position'] != $prePosition) {
			// 		$prePosition = $d['position'];
			// 	} else {
			// 		$d['position'] = '';
			// 	}
			// }


			// return ['data' => $data, 'meta' => [
			// 	'total_shifts' => $total_shifts,
			// 	'total_hours'  => $total_hours,
			// 	'total_cost'   => $total_cost
			// ]];
			return $this->genreport($request);
		}	
	}
	

	public function exportfilebyposition(Request $request) {
		
		$number = $request->number;
		$name = $request->name;
		$is_shifts = $request->shifts;
		$hours = $request->hours;
		$payrate = $request->payrate;
		$totalpay = $request->totalpay;
		$firstname = $request->firstname;
		$lastname = $request->lastname;

		$isIncludePayRateAndCosts = $request->isIncludePayRateAndCosts;
		if($isIncludePayRateAndCosts) {
			$payrate = true;
		}
		
		$position = $request->position;

		$b = explode("/", $request->beginDate);
		$e = explode("/", $request->endDate);

		$startDate = $b[2]."-".$b[0]."-".$b[1];
		$endDate = $e[2]."-".$e[0]."-".$e[1];
		$position = $request->get('position');

		$query = Shift::with(['employee', 'position'])->whereBetween('added_to', [$startDate, $endDate]);
		
		if($position != '' && $position != 'All Positions') {
			$query->whereHas('position', function($q) use ($position) {
				return $q->where('id', $position);
			});
		}

		$shifts = $query->get()->toArray();

		usort($shifts, function ($a, $b) {
			return strcmp($a['position']['position'], $b['position']['position']);
		});

		$data = [];
		$total_shifts = 0;
		$total_hours = 0;
		$total_cost   = 0;

		foreach($shifts as $shift) {

			foreach($shift['employee'] as $emp) 
			{
				$temp = [];
				$temp['position'] = $shift['position']['position'];
				if($name) $temp['name'] = $emp['firstname']." ".$emp['lastname'];
				if($firstname) $temp['firstname'] = $emp['firstname'];
				if($lastname) $temp['lastname'] = $emp['lastname'];

				$shift_number = count(explode('~',$shift['added_to']));
				if($is_shifts) $temp['shifts']   = $shift_number;
				if($hours) $temp['hours']    = $shift['paid_hours'];
				if($payrate) $temp['payrate']  = $emp['pay_rate'];

				$cost = $shift['paid_hours'] * $emp['pay_rate'] * $shift_number;
				if($totalpay) $temp['cost'] = $cost;

				$data[] = $temp;

				$total_shifts += $shift_number;
				$total_cost   += $cost;
				$total_hours  += $shift['paid_hours'];
			}
		} 

		// $prePosition = '';

		// foreach($data as &$d) {
		// 	if($d['position'] != $prePosition) {
		// 		$prePosition = $d['position'];
		// 	} else {
		// 		$d['position'] = '';
		// 	}
		// }

	
		return ['data' => $data, 'meta' => [
			'total_shifts' => $total_shifts,
			'total_hours'  => $total_hours,
			'total_cost'   => $total_cost
		]];


	}

	public function exportfilebyemployee(Request $request) {
		
		$number = $request->number;
		$name = $request->name;
		$is_shifts = $request->shifts;
		$hours = $request->hours;
		$payrate = $request->payrate;
		$totalpay = $request->totalpay;
		$firstname = $request->firstname;
		$lastname = $request->lastname;
		$is_hire_date = $request->hire_date;

		$isIncludePayRateAndCosts = $request->isIncludePayRateAndCosts;

		if($isIncludePayRateAndCosts) {
			$payrate = true;
		}
		$position = $request->position;

		$b = explode("/", $request->beginDate);
		$e = explode("/", $request->endDate);

		$startDate = $b[2]."-".$b[0]."-".$b[1];
		$endDate = $e[2]."-".$e[0]."-".$e[1];
		$position = $request->get('position');

		$query = Shift::with(['employee', 'position'])->whereBetween('added_to', [$startDate, $endDate]);
		if($position != '' && $position != 'All Positions') {
			$query->whereHas('position', function($q) use ($position) {
				return $q->where('id', $position);
			});
		}

		$shifts = $query->get()->toArray();

		$data = [];
		$total_shifts = 0;
		$total_hours = 0;
		$total_cost   = 0;

		foreach($shifts as $shift) {

			foreach($shift['employee'] as $emp) 
			{
				$temp = [];
				$temp['emp_id'] = $emp['id'];
				$temp['position'] = $shift['position']['position'];
				if($name) $temp['name'] = $emp['firstname']." ".$emp['lastname'];
				if($firstname) $temp['firstname'] = $emp['firstname'];
				if($lastname) $temp['lastname'] = $emp['lastname'];
				if($is_hire_date) $temp['hire_date'] = $emp['hired_date'];

				$shift_number = count(explode('~',$shift['added_to']));
				if($is_shifts) $temp['shifts']   = $shift_number;

				if($hours) $temp['hours']    = $shift['paid_hours'];
				if($payrate) $temp['payrate']  = $emp['pay_rate'];

				$cost = $shift['paid_hours'] * $emp['pay_rate'] * $shift_number;
				if($totalpay) $temp['cost'] = $cost;

				$data[] = $temp;

				$total_shifts += $shift_number;
				$total_cost   += $cost;
				$total_hours  += $shift['paid_hours'];
			}
		} 

		$finalData = [];

		$isAva = function($d, $finalData) {
			for($i = 0; $i < count($finalData); $i++) {

				$f = $finalData[$i];

				if($f['emp_id'] == $d['emp_id']) {
					return $i;
				}
			}

			return false;
		};

		foreach($data as $d) {

			// dd($d);
			$index = $isAva($d, $finalData);
			
			if($index === false) {
				$finalData[] = $d;
			} else {

				$preD = $finalData[$index];
				// dd($preD, $d);
				// $preD['position'] = $shift['position']['position'];
			

				if($is_shifts) $finalData[$index]['shifts']   = $preD['shifts'] + $d['shifts'];
				if($hours) $finalData[$index]['hours']     = $preD['hours'] + $d['hours'];
				if($totalpay) $finalData[$index]['cost']   = $preD['cost'] + $d['cost'];
			}
		}

        foreach ($finalData as &$d) {
			unset($d['emp_id']);
        }

		return ['data' => $finalData, 'meta' => [
			'total_shifts' => $total_shifts,
			'total_hours'  => $total_hours,
			'total_cost'   => $total_cost
		]];
	}


	private function genreport($request) {
			$startDate = $request->get('start');
			$endDate = $request->get('end');
			$position = $request->get('position');

    		// Report type is "position"
    		// if ( $request->get('reportType') && $request->get('reportType') == 'position' ) {
			// 	$queryReport = DB::table('tblmq_shift_employee as se')
			// 						->select(
			// 							DB::raw(
			// 								"p.position,
        	// 								GROUP_CONCAT( DISTINCT CONCAT(e.firstname, ' ', e.lastname) ) as employee,

			// 								COUNT(se.employee_id) as total_shifts,
			// 								ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600), 2 ) as total_hours,
			// 								e.pay_rate,
			// 								ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600) * e.pay_rate, 2) as cost

        	// 								")
			// 						)
			// 						->join('tblm_employee as e', 'se.employee_id', '=', 'e.id')
			// 						->join('tblmq_shift_added_to as sat', 'se.shift_id', '=', 'sat.shift_id')
			// 						->join('tblm_shifts as s', 'se.shift_id', '=', 's.id')
			// 						->join('tblm_positions as p', 's.position_id', '=', 'p.id')
			// 							->whereBetween('sat.date', [$startDate, $endDate])
			// 							->groupBy('p.id');

			// 	return $queryReport->get();

			// 	$queryMeta = DB::table('tblmq_shift_employee as se')
			// 							->select(
			// 								DB::raw(
			// 									'COUNT(se.employee_id) as total_shifts,
			// 									ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600), 2 ) as total_hours')
			// 							)
			// 							->join('tblm_employee as e', 'se.employee_id', '=', 'e.id')
			// 							->join('tblmq_shift_added_to as sat', 'se.shift_id', '=', 'sat.shift_id')
			// 							->join('tblm_shifts as s', 'se.shift_id', '=', 's.id')
			// 								->whereBetween('sat.date', [$startDate, $endDate]);

			// 	// If "position" is present, then filter it out accordingly
			// 	if ( $position != '' || $position != 'All Positions' ) {
			// 		$metaResult = $queryMeta->where('s.position_id', $position)->first();
			// 		return [
			// 					'data' => $queryReport->where('s.position_id', $position)->get(),
			// 					'meta' => [
			// 						'total_shifts' => $metaResult->total_shifts,
			// 						'total_hours' => $metaResult->total_hours,
			// 					]
			// 				];
			// 	}

			// 	return [
			// 		'data' => $queryReport->get(),
			// 		'meta' => [
			// 			'total_shifts' => $queryMeta->first()->total_shifts,
			// 			'total_hours' => $queryMeta->first()->total_hours
			// 		]
			// 	];
    		// }

			$queryReport = DB::table('tblmq_shift_employee as se')
								->select(
									DB::raw(
										'CONCAT(e.firstname, " ", e.lastname) as name,
										p.position,
										COUNT(se.employee_id) as total_shifts,
										ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600), 2 ) as total_hours,
										e.pay_rate,
										ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600) * e.pay_rate, 2) as cost')
								)
								->join('tblm_employee as e', 'se.employee_id', '=', 'e.id')
								->join('tblmq_shift_added_to as sat', 'se.shift_id', '=', 'sat.shift_id')
								->join('tblm_shifts as s', 'se.shift_id', '=', 's.id')
								->join('tblm_positions as p', 's.position_id', '=', 'p.id')
									->whereBetween('sat.date', [$startDate, $endDate])
									->groupBy('se.employee_id');

			$queryMeta = DB::table('tblmq_shift_employee as se')
									->select(
										DB::raw(
											'COUNT(se.employee_id) as total_shifts,
											ROUND( (sum(time_to_sec(TIMEDIFF(s.end, s.start))) / 3600), 2 ) as total_hours')
									)
									->join('tblm_employee as e', 'se.employee_id', '=', 'e.id')
									->join('tblmq_shift_added_to as sat', 'se.shift_id', '=', 'sat.shift_id')
									->join('tblm_shifts as s', 'se.shift_id', '=', 's.id')
										->whereBetween('sat.date', [$startDate, $endDate]);

			// If "position" is present, then filter it out accordingly
			if ( $position != '' ) {
				$metaResult = $queryMeta->where('s.position_id', $position)->first();
				return [
							'data' => $queryReport->where('s.position_id', $position)->get(),
							'meta' => [
								'total_shifts' => $metaResult->total_shifts,
								'total_hours' => $metaResult->total_hours,
							]
						];
			}

			return [
				'data' => $queryReport->get()->toArray(),
				'meta' => [
					'total_shifts' => $queryMeta->first()->total_shifts,
					'total_hours' => $queryMeta->first()->total_hours
				]
			];
	}

	function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }


	function sortByOrder($a, $b) {
		return $a['id'] - $b['id'];
	}
	
	

	public function OfficerlogsUnderReports(Request $request){
		$response = ['status'=>false,'message'=>'Something wrong'];
		
		$reports = Reportslog::with('employee','property','report')
					->where('report_type_id','=',1)
					->groupBy('employee_id')
					->groupBy('date')
					->orderBy('id','DESC')
					->get();
	// print_r($reports);exit;
		$report2 = Reportslog::with('employee','property','report')
					->where(function ($query) {
						$query->where('report_type_id','=',2);	
					})
					->orderBy('id','DESC')
					->get();

		$ARRAY1 = $reports->toArray();
		$ARRAY2 = $report2->toArray();

		$newAry = (array_merge($ARRAY1,$ARRAY2));
		usort($newAry, [$this,'sortByOrder']);
		
		$logarray = array();
		foreach($newAry as $log){
			$report = [];
			$report['id'] = $log['id'];
			$report['description'] = $log['description'];
			$report['report_type_id'] = $log['report_type_id'];
			$report['date'] = $log['created_at'];
			$report['time'] = $log['time'];
			$report['report_image'] = $log['report_image'];
			$report['slot'] = $log['slot'];
			$report['status'] = $log['status'];
			foreach($log['employee'] as $emp){
				$report['employee'][] = $emp;
			}
			foreach($log['property'] as $pro){
				$report['property'][] = $pro;
			}
			foreach($log['report'] as $rep){
				$report['report'][] = $rep;
			}
			$logarray[] = $report;
		}
		if(count($logarray) > 0){
            $response = [ 'status' =>true,'message'=>'All Report','data' =>array_reverse($logarray)];
        }else{
            $response = ['status' =>false,'message'=>'No Record Found!'];
        }        
        return response($response);
	}
	

	// SINGLE REPORT VIEW
	public function SingleViewReport(Request $request){
		$response =array('status'=>false,'message'=>'Something wrong');

		// CHECK HERE  REPORT ID 1
		if($request->report_type_id == 1){
			$reportSQL = Reportslog::with('employee','property','report');
			
				$reportSQL->where('report_type_id', $request->report_type_id);
				$reportSQL->where('employee_id', $request->employee_id);
				$reportSQL->where('date', $request->date);
				$report = $reportSQL->get();
				
				if(count($report) > 0){
					$response = ['status' =>true,'message'=>'All Report','data' =>$report];
				}else{
					$response = ['status' =>false,'message'=>'No Record Found!'];
				}   
				
		// CHECK HERE REPORT ID 2		
		}else if($request->report_type_id == 2){
			$report = Reportslog::with('employee','property','report',)
						->where('id',$request->id)
						->first();
			if($report){
				$response = ['status' =>true,'message'=>'All Report','data' =>$report];
			}else{
				$response = ['status' =>false,'message'=>'No Record Found!'];
			}   		
		}       
        return response($response);
	}


	// SINGLE REPORT EDIT
	public function SingleEditReport(request $request,$id){
		try{
			$response =array('status'=>false,'message'=>'Something wrong');

			$report = Reportslog::with('employee','property','report')->where('id',$id)->first();
			if($report){
				$response = ['status' =>true,'message'=>'Today officer logs', 'data' =>$report];
			}else{
				$response = ['status' =>false,'message'=>'Today no officer logs'];
			}        
			return response($response);
		}catch (\Exception $e) {
			return response()->json(['error'=>$e->getMessage()]);   
		} 
	}


	// UPDATE REPORT
	public function UpdateSingleReport(Request $request){
		$response =['status'=>false,'message'=>'Something wrong'];
		
		
		if($request->report_type_id == 1){
			$reportdata = $request->data;
				$arry = [];
				foreach($reportdata as $key =>$value){
					//dd($value);
					$update = Reportslog::find($value['id']);
					$update->description = $value['description'];
					$update->save();
					$arry[] = $update;
				}		
				if($arry){
					$response = ['status' =>true,'message'=>'Records updated!','data' =>$arry];
				}else{
					$response = ['status' =>false,'message'=>'Records Not updated!'];
				} 
			
		}else if($request->report_type_id == 2){
			$update = [
				'description' =>$request->description,
				'report_subtype_id'=> $request->subtype,
			];

			$updatated_report = Reportslog::where('id','=',$request->id)->update($update);
			if($updatated_report){
				$response = ['status' =>true,'message'=>'Records updated!','data' =>$updatated_report];
			}else{
				$response = ['status' =>false,'message'=>'Records Not updated!'];
			} 
		}
        return response($response);
	}


	


	/** REPORT FILTER */
	public function filterreports(Request $request){
		try{
			$response = ['status'=>false,'message'=>'Something wrong'];

			$reports = Reportslog::with(['employee','property','report']);
			if ($request->search != null) {
				$reports = $reports->where(function($q) use($request){
					$q->whereHas('property',function($query) use ($request) {
						$query->where('address', 'Like', "%{$request->search}%");
					})->orWhereHas('employee', function( $query ) use ( $request ){
						$query->where('firstname', 'Like', "%{$request->search}%");
						$query->orWhere('lastname','Like', "%{$request->search}%");
					})->orWhereHas('report', function( $query ) use ( $request ){
						$query->where('report_name','Like', "%{$request->search}%");
					});
				});		
			}
			
			if($request->range_date != null ) {
				$reports->where('date', date('Y-m-d',strtotime($request->range_date)));
			}
				
			$report_data  = $reports->get();
			//dd($this->getEloquentSqlWithBindings($reports));
			if(count($report_data) > 0):
				$response = ['status' =>true,'message' =>'All Records!','data' => $report_data];
			else:
				$response =['status' =>false,'message' =>'No Records Found!'];
			endif;
			return response($response);
		}catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
	}

	
	//  GET HERE ALL REPORT TYPE 
    public function AdminGetAllSubTypeReport(Request $request){
		try{
			$ReportSubType = ReportSubType::all();
			if(count($ReportSubType) > 0 ){
				$response = ['status' =>true, 'message'=> 'All SubType Report','data'=> $ReportSubType];
			}else{
				$response = ['status' =>false,'message' =>'No Record Found!'];
			}
			return response($response);
		}catch (\Exception $e) {
			return response()->json(['error'=>$e->getMessage()]);   
		} 
    }


	public function ApproveAllReport(Request $request){
		try{
			$response = ['status'=> false,'message'=>'Something wrong'];
			
			$data =  ['status'=>'approved'];	
			$update = Reportslog::where(['employee_id'=>$request->employee_id, 'date'=> date('Y-m-d',strtotime($request->date))])->update($data);
			if($update){
				$response = ['status' =>true,'message'=>'Records updated!','data' =>$update];
				// SEND HERE NOTIFICATION
				$employee = Employee::where('id',$request->employee_id)->first();
            
				$player_id = '38dc8333-d533-4a10-8b21-f2f7db87600a';
				$subject = "Report Approve";
				$message = "Report Approve"; 
				if($employee['employee_player_id'] != ''):   
					// send Notification by custom method
					$this->PushNotification($player_id,$employee['employee_player_id'],$subject,$message);
				endif;

			}else{
				$response = ['status' =>false,'message'=>'Records Not updated!'];
			} 
			return response($response);
		}catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
	}

	public function ApproveReport(Request $request){
		$response = ['status'=> false,'message'=>'Something wrong'];
		//dd($request);
		$id  =  $request->id;

		if(is_array($id)){
			$arry =[];
			foreach($id as $key =>$value){

				$update = Reportslog::find($value);
				$update->status = 'approved';
				$update->save();
				$arry[] = $update;
			}	
			//dd($this->getEloquentSqlWithBindings($update));
			if($arry){
				$response = ['status' =>true,'message'=>'Records updated!','data' =>$arry];
			}else{
				$response = ['status' =>false,'message'=>'Records Not updated!'];
			} 
		}else if($id) {
			
			$data =  ['status'=>'approved'];
			$update = Reportslog::where('id','=',$id)->update($data);

			if($update){
				$response = ['status' =>true,'message'=>'Records updated!','data' =>$update];
			}else{
				$response = ['status' =>false,'message'=>'Records Not updated!'];
			} 
		}
		return response($response);
	}


	

	/***
	 * DOWNLOAD PDF FOR HOURY
	 *  HOURLY PDF
	 * @REPORT 
	 */
	public function hourlyPdfReport(Request $request){
		$reportSQL = Reportslog::with('employee','property','report');
		$reportSQL->where('report_type_id', $request->report_type_id);
		$reportSQL->where('employee_id', $request->employee_id);
		$report1 = $reportSQL->get()->toArray();

		$report_one_array = [];
		$report_one_array['id'] = $request->id;
		foreach($report1 as $report):
				$reptarray['date'] = date('d/m/Y',strtotime($report['date']));
				$reptarray['time'] = date('h:i A',strtotime($report['time']));
				$reptarray['description'] = $report['description'];
				$report_one_array['table'][] = $reptarray;
			foreach($report['employee'] as $employee):
					$report_one_array['officer_name'] = $employee['firstname'].' '.$employee['lastname'];
			endforeach;
			foreach($report['report'] as $repname):
				$report_one_array['title'] = $repname['report_name'];
			endforeach;
		endforeach;
	    
		$pdf = PDF::loadView('pdfview',$report_one_array);
		return $pdf->download('Nicesnippets1.pdf');

	}

	public function hourlyPdfReportdata($client_id,$currentdate,$enddate){
		if($enddate==1){
			$enddate = null;
		}
		if($currentdate==1){
			$currentdate = date('Y-m-d');
		}
		if($currentdate != null && $enddate != null){
            $properties = ClientProperties::
                with(['shift' => function($q) use($currentdate,$enddate) {
                    $q->whereBetween('week_of',[$currentdate,$enddate]);
                },'shift.employee'])
                ->where('client_id', $client_id)
            ->get()->toArray();
        }else if($currentdate != null){
            $properties = ClientProperties::
                    with(['shift' => function($q) use($currentdate) {
                        $q->where('added_to',$currentdate);
                    },'shift.employee'])
                    ->where('client_id', $client_id)
                    ->get()
                    ->toArray();
        }

        $employe_ids = [];
        $main_array = [];
        foreach($properties as $main):             
                foreach($main['shift'] as $shift):
                        $employe_ids[] = $shift['employee']['id'];               
                endforeach;

                if(count($employe_ids) > 0):
                    $property = [];
                    $property['property_id'] = $main['id'];
                    $property['client_id'] = $main['client_id'];
                    $property['name'] = $main['name'];
                    $property['address'] = $main['address'];
                    $property['lat'] = $main['lat'];
                    $property['long'] = $main['long']; 

                    if($currentdate != null && $enddate != null){
                        $property['hourly'] = Reportslog::with('employee','property','report','subReport')
                        ->whereBetween('date',[$currentdate, $enddate])
                        ->where('report_type_id','=', 1)                        
                        ->whereIn('employee_id', $employe_ids)             
                        ->where('property_id','=',$main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray(); 
                    }else if($currentdate != null ){
                        $property['hourly'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$currentdate)
                        ->where('report_type_id','=', 1)                        
                        ->whereIn('employee_id', $employe_ids)             
                        ->where('property_id','=',$main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray(); 

                    }


                    if($currentdate != null && $enddate != null){
                        $property['incident'] = Reportslog::with('employee','property','report','subReport')
                        ->whereBetween('date',[$currentdate, $enddate])
                        ->where('report_type_id','=', 2)
                        ->whereIn('employee_id', $employe_ids)
                        ->where('property_id','=', $main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray();
                    }else if($currentdate != null ){
                        $property['incident'] = Reportslog::with('employee','property','report','subReport')
                        ->whereDate('date',$currentdate)
                        ->where('report_type_id','=', 2)
                        ->whereIn('employee_id', $employe_ids)
                        ->where('property_id','=', $main['id'])
                        ->orderBy('time')
                        ->get()
                        ->toArray();

                    }

                    if(count($property['hourly'])> 0 || count($property['incident']) > 0):
                        $main_array[] = $property; 
                    endif;     
                
                endif;      
                      
            endforeach; 
        //echo "<pre>";print_r($main_array); //die();
		$pdf = PDF::loadView('pdfview', ['main_array' => $main_array]);
		return $pdf->download('HourlyReport.pdf');
		die();
	}

	public function adminhourlyPdfReportdata($emplyee_id,$typeid,$date){
		if($date==1){
			$date = date('Y-m-d');
		}
		$getnewdate =  explode('T',$date);
		$date = $getnewdate[0];
        $property = [];   
        $main_array = [];
        $getlog = Reportslog::with('employee','property','report','subReport')
        ->whereDate('date',$date)
        ->where('report_type_id','=', $typeid)                        
        ->where('employee_id', $emplyee_id)
        ->orderBy('time')
        ->get()
        ->toArray(); 

        if($typeid==2){
        	$property['incident'] = $getlog;
        	$property['hourly'] = [];
        }else {
        	$property['hourly'] = $getlog;
        	$property['incident'] = [];
        }
        $main_array[] = $property; 
        //echo "<pre>";print_r($property); die();
        if($typeid==2){
			$pdf = PDF::loadView('adminpdfview1', ['main_array' => $main_array]);
			return $pdf->download('IncidentReport.pdf');
		}else {
			$pdf = PDF::loadView('adminpdfview', ['main_array' => $main_array]);
			return $pdf->download('HourlyReport.pdf');
		}
		
		die();
	}

	/*****
	 * DOWNLOAD REPORT FOR INCIDENT
	 *  INCIDENT PDF
	 * @REPORT 
	 */
	public function incidentPdfDownload(Request $request){
		$report2 = Reportslog::with('employee','property','report','subReport')
					->where('id',$request->id)
					->first();
		
		$report_two_array = []; 
		
		$report_two_array['id'] = $report2['id'];
		$report_two_array['description'] = $report2['description'];
		foreach($report2['employee'] as $employee):
			$report_two_array['officer_name'] = $employee['firstname'].' '.$employee['lastname'];
		endforeach;

		foreach($report2['report'] as $repname):
			$report_two_array['title'] = $repname['report_name'];
		endforeach;
		$report_two_array['report_type_name'] = $report2['subReport']['report_type_name'];
		$pdf = PDF::loadView('pdf.incident',$report_two_array);
		return $pdf->download('file.pdf');
	}




	public function trashedOfficerlogsReports(){

		$response =['status'=>false,'message'=>'Something wrong'];
		
		$reports = Reportslog::with('employee','property','report')
					->where('report_type_id','=',1)
					->groupBy('employee_id')
					->groupBy('date')
					->orderBy('id','DESC')
					->onlyTrashed()
					->get();
	
		$report2 = Reportslog::with('employee','property','report')
					->where(function ($query) {
						$query->where('report_type_id','=',2);	
					})
					->orderBy('id','DESC')
					->onlyTrashed()
					->get();

		$ARRAY1 = $reports->toArray();
		$ARRAY2 = $report2->toArray();

		$newAry = array_merge($ARRAY1,$ARRAY2);
		usort($newAry, [$this,'sortByOrder']);
		
		$logarray = array();
		foreach($newAry as $log){
			//dd($log);
			$report = [];
			$report['id'] = $log['id'];
			$report['description'] = $log['description'];
			$report['report_type_id'] = $log['report_type_id'];
			$report['date'] = $log['created_at'];
			$report['time'] = $log['time'];
			$report['report_image'] = $log['report_image'];
			$report['slot'] = $log['slot'];
			$report['status'] = $log['status'];
			foreach($log['employee'] as $emp){
				$report['employee'][] = $emp;
			}
			foreach($log['property'] as $pro){
				$report['property'][] = $pro;
			}
			foreach($log['report'] as $rep){
				$report['report'][] = $rep;
			}
			$logarray[] = $report;
		}
		if(count($logarray) > 0){
            $response = [ 'status' =>true,'message'=>'All Trashed Report','data' =>$logarray];
        }else{
            $response = ['status' =>false,'message'=>'No Record Found!'];
        }        
        return response($response);
	}


}
