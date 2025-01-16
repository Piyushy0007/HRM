<?php
 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\EmployeeParkingTicket;
use App\Http\Controllers\API\EmployeeMessageController;
use App\Http\Controllers\Web\ClientWebController;
use App\Http\Controllers\AdminMainController;
use App\Http\Controllers\EmployeeController as EmployeeController2;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminParkingTicket;
use App\Http\Controllers\Web\ClientWebTypeController;
use App\Http\Controllers\Web\WebClientMessageController;
use App\Http\Controllers\Web\ClientWebForgetController;
use App\Http\Controllers\Web\ClientWebResetPasswordController;
use App\Http\Controllers\WorkreferencesController;
use App\Http\Controllers\AdminInvoicesController;
use App\Http\Controllers\API\BroadcastController;
use App\Http\Controllers\API\EmployeeNotification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ClientRequestController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;


// ******************  ROUTE FOR CLIENT DASHBOARD ***************** //

// echo url()->current(); die();
// if(url()->current()=='http://staging.admin.trakastar.com/' || url()->current()=='http://staging.admin.trakastar.com'){
//     //echo url()->current();
//    return redirect('/login');
    Route::redirect('', '/login');
//     header('Location: http://staging.admin.trakastar.com/login');
//     exit;
// }
// if(url()->current()=='http://admin.trakastar.com/' || url()->current()=='http://admin.trakastar.com'){
//     //echo url()->current();
//    // return redirect('/login');
    // Route::redirect('', '/login');
//     header('Location: http://admin.trakastar.com/login');
//     exit;
// }

Route::post('clientregister', [ClientWebController::class,'register']);
Route::post('client-login', [ClientWebController::class,'clientLogin']);
Route::post('client-reset', [ClientWebForgetController::class,'sendResetLinkEmail']);
Route::get('password/reset/{token}',[ClientWebForgetController::class,'showLinkRequestForm']); 
Route::post('password/reset', [ClientWebResetPasswordController::class,'reset']);

Route::get('testemail',[EmployeeController::class,'testalertreport']);


Route::get('hourlyreportdownloaddata/{id}/{startdate}/{endate}',[ReportController::class,'hourlyPdfReportdata']);
Route::get('adminhourlyPdfReportdata/{employee_id}/{type_id}/{date}',[ReportController::class,'adminhourlyPdfReportdata']);


Route::group([
    //'middleware' => 'auth:clients',
   // 'middleware' =>['assign.guard:clients','jwt.auth']
], function ($router) {
  
    Route::get('getproperty/{id}', [ClientWebController::class,'GetOurProperties']);
    Route::post('clientlogout', [ClientWebController::class,'Clientlogout']);
    Route::post('addproperty', [ClientWebController::class,'AddClientProperties']);
    Route::get('viewproperty/{id}', [ClientWebController::class,'ClientViewProperties']);
    Route::post('updateproperty', [ClientWebController::class,'UpdateClientProperties']);
    Route::get('deleteproperty/{id}', [ClientWebController::class,'DeleteProperty']);
    Route::get('viewprofile/{id}',[ClientWebController::class,'ClientProfileView']);
    Route::post('updateprofile',[ClientWebController::class,'UpdateClientProfile']);
    Route::post('clienthourlyreport',[ClientWebController::class,'ClientHourlyReport']);
    Route::post('clientincidentreport',[ClientWebController::class,'ClientIncidentReport']);
    Route::post('clientreports',[ClientWebController::class,'ClientReports']);

    Route::get('subtypes',[ClientWebController::class,'GetAllReportSubType']);
    Route::post('clientmessagetoemployee',[ClientWebController::class,'ClientSendMessageToAssignEmployee']);
    //Route for get client record with report type and date
    Route::post('reports-data',[ClientWebController::class,'dataWithReportType']);
    Route::post('hourlyfilter',[ClientWebController::class,'ReportsLocationFilterHourly']);
    Route::post('incidentfilter',[ClientWebController::class,'ReportsLocationFilterincident']);
    Route::post('invoices',[ClientWebController::class,'ClientPropertyEmployeeInvoices']);
    //Route::post('invoicesfilter',[ClientWebController::class,'ClientInvoicesFilter']);
    
    Route::post('reportfilter',[ClientWebController::class,'ReportFilter']);
    Route::get('Singleinvoicereport/{id}',[ClientWebController::class,'ClientSingleIncidentView']);
    Route::get('Singlehourlyreport/{id}',[ClientWebController::class,'viewHourlyReportModel']);


    Route::post('parkingreport',[ClientWebTypeController::class,'ClientPropertyParkingTicket']);
    Route::post('filtertickets',[ClientWebTypeController::class,'ClientFilterParkingTickets']);
    Route::get('viewparking/{id}',[ClientWebTypeController::class,'ClientSingleParkingView']);
    Route::post('invoicefilter',[ClientWebTypeController::class,'ClientInvoiceFilter']);
    
//employee document

Route::post('/employees/{employee_id}/joining-letter', [EmployeeDocumentController::class, 'postJoiningLetter']);
// Route::post('/employee/{id}/performance-document', [EmployeeDocumentController::class, 'postPerformanceDocument']);
// // Route::post('/employee/{id}/salary-slip', [EmployeeDocumentController::class, 'postSalarySlip']);
//     Route::get('employee/{id}/joining-letter', [EmployeeDocumentController::class, 'getJoiningLetter']);
// Route::get('employee/{id}/performance-documents', [EmployeeDocumentController::class, 'getPerformanceDocuments']);
// // Route::get('employee/{id}/salary-slip/{month}', [EmployeeDocumentController::class, 'getSalarySlip']);

    // CLIENT MESSAGE 
    Route::post('creategroup',[WebClientMessageController::class,'ClientCreateMessageGroup']); 
    Route::post('cgroups',[WebClientMessageController::class,'GetGroups']);
    Route::get('cgroupmessage/{group_id}/{user_id}',[WebClientMessageController::class,'GetGroupUserAndMesages']);
    Route::post('cgroupmessage',[WebClientMessageController::class,'SendMesageInGroup']);
    Route::post('messagedeletebyclient',[WebClientMessageController::class,'MessageDeleteByClient']);
    Route::post('cdeleteselectedgroup',[WebClientMessageController::class,'DeleteMultipleGroup']);
    Route::post('cdeletsinglegroup',[WebClientMessageController::class,'DeleteGroup']);
    Route::post('cmessagecount',[WebClientMessageController::class,'ClientMessageCount']);


   
});
// Route::group([
//     'middleware' => 'api'
// ], function ($router) {
// // MAKE THIS OFF
//    
//     Route::get('password/reset/{token}', 'Web\ClientWebResetPasswordController@showResetForm');
//     Route::post('password/reset', 'Web\ClientWebResetPasswordController@reset');
// });
//********************** END ROUTE FOR CLIENT DASHBOARD ****************//

// Note:
//  - secure route (middleware)
//*************************** API FOR ADMIN SECTION *************************//
Route::post('/adminlogin', [AdminMainController::class,'AdminLogin']);

//Route::post('addadmin1',[AdminClientController::class,'AdminAddNewAdmin']);

Route::group([
    //'middleware' => 'auth:users',
   // 'middleware'=> ['assign.guard:users','jwt.auth']
], function ($router) {
    Route::post('logout', [AdminMainController::class,'Adminlogout']);
        // schedules
    Route::post('/schedules/exportfile', [ShiftController::class,'schedulesExport']);
    Route::post('/schedules/changelayout', [ShiftController::class,'changelayout']);
    Route::post('/schedules/getlayout', [ShiftController::class,'getlayout']);

    // Positions
    // Route::resource('positions', \App\Http\Controllers\PositionController::class);
    Route::get('positions',  [PositionController::class,'index']);
    Route::post('/positions', [PositionController::class,'create']);
    Route::post('/positions/{id}', [PositionController::class,'update']);
    Route::get('/positions/{id}', [PositionController::class,'destroy']);
    Route::get('/positions-trashed', [PositionController::class,'showTrashed']);
    Route::delete('/positions-restore/{id}', [PositionController::class,'restoreTrashed']);
    Route::patch('/position-sort', [PositionController::class,'sort']);
    

    // Employee
    Route::get('/employees/on_now', [EmployeeController2::class,'onNow']);
    Route::get('/employees/on_later', [EmployeeController2::class,'onLater']);
    Route::post('/employees/register_face', [EmployeeController2::class,'registerFace']);
    Route::get('/employees/getEmployeesWithEmbeddings', [EmployeeController2::class,'getEmployeesWithEmbeddings']);
    
    //attendence
    Route::post('/attendance', [AttendanceController::class, 'markAttendance']);
    Route::get('/attendance/monthly/{employeeId}', [AttendanceController::class, 'getMonthlyAttendance']);
    Route::post('/attendances', [AttendanceController::class, 'getAttendancesByDateRange']);
    
    Route::get('/attendanceSummary', [AttendanceController::class, 'getAttendanceSummary']);
    Route::post('/attendance', [AttendanceController::class, 'markedAttendance']);


    //LeaveRequest
    Route::post('/employee/{employeeId}/LeaveRequest', [LeaveRequestController::class, 'create']);
    Route::get('/employee/{employeeId}/LeaveRequests', [LeaveRequestController::class, 'show']);
    Route::put('/LeaveRequest/{leaveRequestId}/Status', [LeaveRequestController::class, 'updateStatus']);
    Route::get('/leave-requests', [LeaveRequestController::class, 'getLeaveRequestsByDateRange']);


//expirence     

Route::post('employees/{employeeId}/experience', [ExperienceController::class, 'addExperience']);

// client_request 

Route::post('/ClientRequestsWithFiles', [ClientRequestController::class, 'createWithFiles']);
Route::put('/ClientRequests/{id}/Status', [ClientRequestController::class, 'updateStatus']);

//jobs

Route::post('/jobs', [JobController::class, 'store']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job_number}', [JobController::class, 'getJobByNumber']);

//candidates
Route::post('/apply-job', [JobApplicationController::class, 'applyForJob']);
Route::get('/applicant/{id}', [JobApplicationController::class, 'getApplicationWithJobDetails']);
Route::get('/applications/job/{jobNumber}', [JobApplicationController::class, 'getApplicationsByJobNumber']);

//role
Route::get('/roles', [RoleController::class, 'getAllRoles']);

//salaries
Route::get('/salaries', [SalaryController::class, 'index']);
Route::post('calculate-tds', [SalaryController::class, 'calculateTds']);





    //Route::resource('employees', EmployeeController2::class);
    Route::get('employees', [EmployeeController2::class,'index']);
    Route::post('addemployee', [EmployeeController2::class,'store']);
    Route::post('employees', [EmployeeController2::class,'update']);
    Route::post('deletemployees', [EmployeeController2::class,'destroy']);

    Route::get('testchkapi/{date}/{position}/{start_time}/{end_time}', [ShiftController::class,'checkExistenceOfShift']);


    Route::get('/employees-trashed', [EmployeeController2::class,'showTrashed']);

    Route::get('/employees-alert/{id}', [EmployeeController2::class,'showAlertData']);
    Route::get('/employees-alert-all', [EmployeeController2::class,'showAllAlertData']);
    

    Route::delete('/employees-restore/{id}', [EmployeeController2::class,'restoreTrashed']);
    Route::get('/employee-search', [EmployeeController2::class,'search']);
    Route::get('/employee-trashed-search', [EmployeeController2::class,'searchTrashed']);
    Route::get('/employee/{id}/prevnextrecord', [EmployeeController2::class,'getPrevNextRecord']);

    Route::get('/employeess', [EmployeeController2::class,'getEmployees']);
    Route::get('/positionemployeess', [EmployeeController2::class,'getPosEmployees']);
    Route::get('/employeess-search', [EmployeeController2::class,'getSearchedEmployee']);

    Route::get('/employee/{id}/positions', [EmployeeController2::class,'getPositions']);
    Route::patch('/employee/{id}/positions', [EmployeeController2::class,'updateEmployeePositions']);

    Route::get('/employee/{id}/positiondata', [EmployeeController2::class,'getPositiondata']);
    Route::post('/employee/{id}/positiondata', [EmployeeController2::class,'savePositiondata']);

    //
    Route::post('/employee/send-signin-instruction-to-all', [EmployeeController2::class,'sendSignInInstructionToAll']);
    Route::post('/employee/send-signin-instruction-to-employee', [EmployeeController2::class,'sendSignInInstructionToEmployee']);
    Route::post('/employees/send-reminder', [EmployeeController2::class,'sendReminder']);
    Route::post('/employees/send-message', [EmployeeController2::class,'sendMessage']);
    Route::post('/employees/employee-bulk-edit', [EmployeeController2::class,'bulkEdit']);
    Route::post('/employees/delete-bulk-edit', [EmployeeController2::class,'bulkDeletedEdit']);
    Route::delete('/employees-restore-selected', [EmployeeController2::class,'restoreDeleted']);

    //EMPLOYEE EMAIL CHANGE
    Route::post('/changemail', [EmployeeController2::class,'ChangeEmployeeEmails']);
    Route::get('/resetpassprint/{id}', [EmployeeController2::class,'ResetPassAndPrint']);
    Route::post('empschedule/{id}', [EmployeeController2::class,'EmployeMonthSchedule']);
    

    // Published Schedule
    Route::patch('/employee/{id}/publish', [EmployeeController2::class,'updatePublish']);
    //Route::get('employeelist',[EmployeeController2::class,'get_list_of_employee']);
    Route::post('employeelist',[EmployeeController2::class,'get_list_of_employee']); 
    Route::get('adminlist',[EmployeeController2::class,'get_list_of_all_Admin']); 

        

    // Shifts
    Route::get('shifts', [ShiftController::class,'index']);
    Route::get('shift/{id}/employee', [ShiftController::class,'showsingle']);
    Route::post('shifts', [ShiftController::class,'store']);
    // Route::resource('shift.employee', ShiftEmployeeController::class)->only('index');
    Route::get('shift.employee', [ShiftEmployeeController::class,'index']);
    Route::post('editshift', [ShiftController::class,'update']);
    Route::get('deleteshift/{id}', [ShiftController::class,'destroy']);
    

    // Message
    // Route::resource('messages', MessageController::class)->only('store');
    //Route::post('messages', [MessageController::class,'GetGroups']);
    Route::post('groups', [MessageController::class,'GetGroups']);
    Route::post('messages', [MessageController::class,'store']);
    Route::get('groupuser/{group_id}/{groupable_id}', [MessageController::class,'GetMessageByGroupId']);
    Route::post('sgroupmsg', [MessageController::class,'SendMessageinGroupAdmin']);
    

    Route::post('messagedelete',[MessageController::class,'DeleteMessageByAdmin']);
    Route::post('deletegroup',[MessageController::class,'SelectAllGroupDelete']);
    Route::post('singlegroupdelete',[MessageController::class,'DeleteSingleGroup']);
    
    
    Route::get('message/{userable_id}/{type}', [MessageController::class,'GetSingleEmployeeChat']);
    Route::any('adminmessage',[MessageController::class,'ChatWithSingleEmployee']);
    Route::post('messagedelete/{id}/{type}', [MessageController::class,'destroy']);
    Route::post('messagedeleteall',[MessageController::class,'DeleteMutipleMessageByAdmin']);
    Route::post('adminmessagecount',[MessageController::class,'adminGroupMessageCount']);
    

    Route::get('report/{type}', [ReportController::class,'shift']);
    Route::post('report/exportfile/byemployee', [ReportController::class,'exportfilebyemployee']);
    Route::post('report/exportfile/byposition', [ReportController::class,'exportfilebyposition']);

    Route::get('allreport', [ReportController::class,'OfficerlogsUnderReports']);
    Route::post('viewreport', [ReportController::class,'SingleViewReport']);
    Route::get('editreport/{id}', [ReportController::class,'SingleEditReport']);
    Route::post('updatereport', [ReportController::class,'UpdateSingleReport']);
    Route::post('filterreports', [ReportController::class,'filterreports']);
    Route::post('approveallreport',[ReportController::class,'ApproveAllReport']);
    Route::post('approvereport',[ReportController::class,'ApproveReport']);
    Route::get('allsubtypereport',[ReportController::class,'AdminGetAllSubTypeReport']);
    Route::post('hourlyreportdownload',[ReportController::class,'hourlyPdfReport']);
    Route::post('incidentdownload',[ReportController::class,'incidentPdfDownload']);
    
    //Route::post('filterreports', [ReportController::class,'filterreports']);


    // Client 
    Route::get('client',[AdminClientController::class,'AdminGetAllClient']);
    Route::get('admin',[AdminClientController::class,'AdminGetAllAdmin']);
    Route::get('property',[AdminClientController::class,'GetAllProperties']);
    Route::get('properties/{id}',[AdminClientController::class,'ClientIdByClientProperty']);
    Route::get('allproperties',[AdminClientController::class,'AdminClientUnderProperties']);
    Route::get('propertielocation',[AdminClientController::class,'GetOnlyPropertiesLocation']);    
    Route::post('updateclient',[AdminClientController::class,'updateClient']);
    Route::post('updateproperties/{id}',[AdminClientController::class,'updateClientProperty']);
    Route::post('clientstatus',[AdminClientController::class,'AdminGetStatusClient']);
    Route::post('clientsearch',[AdminClientController::class,'AdminGetIClientNameAddressClient']);
    Route::post('propertiesstatus',[AdminClientController::class,'AdminGetProperties']);
    Route::post('propertysearch',[AdminClientController::class,'AdminGetIPropertiesAddressClient']);
    Route::post('clientlocation',[AdminClientController::class,'AdminGetAddressLocation']);
    Route::post('propertieslocation',[AdminClientController::class,'AdminGetpropertiesLocation']);
    Route::post('filterclient',[AdminClientController::class,'AdminClientFilter']);
    Route::post('filterproperty',[AdminClientController::class,'AdminFilterProperty']);

    /// GET TRASHED RECORDS 
    Route::get('clienttrashed',[AdminClientController::class,'GetClienttrashedRecords']);
    Route::get('propertiestrashed',[AdminClientController::class,'GetClientPropertiestrashedRecords']);
    Route::get('reporttrashed', [ReportController::class,'trashedOfficerlogsReports']);
    Route::get('trashedshift', [ShiftController::class,'trashedShift']);
  
    
    // admin create client  delete
    Route::post('addclient',[AdminClientController::class,'AdminAddNewClient']);
    Route::post('addadmin',[AdminClientController::class,'AdminAddNewAdmin']);
    Route::post('adminaddproperty',[AdminClientController::class,'AdminAddNewProperty']);
    Route::get('singleView/{id}',[AdminClientController::class,'ViewSingleClient']);
    Route::get('propertydelete/{id}',[AdminClientController::class,'DeleteSingleproperties']);
    Route::get('Delete/{id}',[AdminClientController::class,'deleteClient']);
    Route::get('deleteadmin/{id}',[AdminClientController::class,'Admindeleteadmin']);
    Route::get('officerlogs',[AdminClientController::class,'AdminGetOfficerLogs']);
    Route::post('filterofficerlogs',[AdminClientController::class,'FilterOfficerLogs']);
    Route::get('aclientLocation',[AdminClientController::class,'GetOnlyCLientLocation']);
    Route::post('clientreset',[AdminClientController::class,'AdminResetClientPassword']);
    Route::post('adminaddclientreport',[AdminClientController::class,'AdminAddClientReport']);

    // Admin Time Off Request 
    Route::get('showtimeoff',[EmployeeController2::class,'ShowTimeOffRequestDetail']);
    Route::post('updatetimeoff',[EmployeeController2::class,'ChangeTimeOffStatus']);
    Route::post('timeoffstatus',[EmployeeController2::class,'GetTimeOffStatus']);
    Route::get('timeoffdesc',[EmployeeController2::class,'TimeOFFOrderDescData']);
    Route::get('timeoffasc',[EmployeeController2::class,'TimeOFFOrderASCData']);
    Route::Post('filtertimeoff',[EmployeeController2::class,'TimeOFFFilter']);
    Route::get('employeetimeoff/{employee_id}',[EmployeeController2::class,'EmployeeTimeOffRequestDetails']);


    // admin send message and parking details
    // Route::get('messages/{employee_id}',[EmployeeController::class,'GetMessage']);
    // Route::post('messages','EmployeeController@SendMessageByadmin');
    Route::get('allparking', [AdminParkingTicket::class,'index']);
    Route::get('viewparking/{id}', [AdminParkingTicket::class,'view']);
    Route::post('editparking/{id}', [AdminParkingTicket::class,'edit']);
    Route::post('updateparking', [AdminParkingTicket::class,'update']);
    Route::get('deleteparking/{id}', [AdminParkingTicket::class,'destroy']);
    Route::get('vehicleMake', [AdminParkingTicket::class,'GetAllvehicleMake']);
    Route::get('vehicleModel', [AdminParkingTicket::class,'GetAllVehicleModel']);
    Route::get('vehicleType', [AdminParkingTicket::class,'GetAllVehicleType']);
    Route::post('approveparking/{id}', [AdminParkingTicket::class,'ApproveParkingTicket']);

    //ADMIN INVOICES
    Route::post('admininvoices', [AdminClientController::class,'AdminCheckAllInvoices']);

    //ROUTE FOR PREFERENCES
    Route::get('preference/{employee_id}', [WorkreferencesController::class,'index']);
    Route::post('addpreference', [WorkreferencesController::class,'create']);
    Route::post('updatepreference/{id}', [WorkreferencesController::class,'update']);
    Route::get('deletepreference/{id}', [WorkreferencesController::class,'destroy']);


    // INVOICES
    Route::get('export', [AdminInvoicesController::class,'export']);
    Route::post('import', [AdminInvoicesController::class,'import']);
    //Route::get('importExportView', 'AdminInvoicesController@importExportView');

}); 

// ************************ END HERE ADMIN ROUTES *****************************************// 


//***************************** API ROUTES For Employee Mobile ****************************//
Route::post('/login', [EmployeeController::class, 'login']);
Route::post('/register', [EmployeeController::class, 'register']);
// PASSWORD RESET FUNCTIONALITY
Route::post('verifycode',[EmployeeController::class,'VerifictionCodeSendByEmail']);
Route::post('matchotp',[EmployeeController::class,'matchOTP']);
Route::post('forgetpasword',[EmployeeController::class,'ForgetPassword']);
/*Route::group([
    'middleware'=> ['assign.guard:employee','jwt.auth']
], function ($router) {*/
    Route::post('/broadcasting/aut', [BroadcastController::class,'authenticate']);

    Route::post('/emplogout', [EmployeeController::class, 'logout']);
    Route::post('/refresh', [EmployeeController::class, 'refresh']);
    Route::get('/user-profile', [EmployeeController::class, 'userProfile']);    
    
    Route::post('background_clock_out_notification', [MessageController::class,'background_clock_out_notification']);

    Route::get('background_reminder_notification', [MessageController::class,'background_reminder_notification']);

    Route::get('background_reportreminder_notification', [MessageController::class,'background_reportreminder_notification']);

    
    Route::get('checkshift/{employee_id}', [EmployeeController::class,'TodaydateShifIsExist']);
    Route::post('user-shift', [EmployeeController::class,'send_shift_start_end_details']);
    Route::post('clock-in', [EmployeeController::class,'clock_in']); 
    Route::post('clock-out', [EmployeeController::class,'clock_out']);
    Route::post('logs', [EmployeeController::class,'employee_logs_details']); 
    Route::post('timeoff', [EmployeeController::class,'TimeOffRequest']);

     // CLOCK IN/OUT LOG INSERT
     Route::post('detectin', [EmployeeController::class,'detectClockInEmployeeposition']);
     Route::post('detectout', [EmployeeController::class,'DetectClockOutEmployeePosition']);
 
    Route::get('monthschedule/{id}', [EmployeeController::class,'MonthSchedule']);
    Route::get('monthschedule/{id}/{type}', [EmployeeController::class,'MonthSchedule']);
    Route::get('dayshiftdata/{id}', [EmployeeController::class,'dayshiftdata']);
    
    Route::get('timeOffSchedules/{id}', [EmployeeController::class,'TimeOffScheduleslog']); 

    Route::get('lastshiftentry/{id}', [EmployeeController::class,'LastShiftEntry']);  

    //OneSignal Notification
    Route::post('playerid',[EmployeeController::class,'OneApiSignalEmployeePLayerId']);

    // ROUTES FOR PARKING TICKECT 
    Route::post('addticket',[EmployeeParkingTicket::class,'create']);
    Route::get('ticketInfo',[EmployeeParkingTicket::class,'CreateTicketInfomation']);
    Route::get('ticket/{employee_id}',[EmployeeParkingTicket::class,'GetParkingTicketByEmployeeId']);
    Route::post('updateticket/{parking_id}',[EmployeeParkingTicket::class,'update']);

    // Route::get('allparking', [AdminParkingTicket::class,'index']);

    // ROUTE FOR EMPOLYEE ADD REPORT
    Route::post('addreport',[EmployeeController::class,'EmployeeAddReport']);
    Route::post('addalertreport',[EmployeeController::class,'addalertreport']);
    
    Route::post('updatereport/{report_id}',[EmployeeController::class,'EmployeeUpdateReport']);
    Route::get('hourlyreport/{id}',[EmployeeController::class,'GETHourlyReport']);
    Route::get('incidentreport/{employee_id}',[EmployeeController::class,'GetIncidentReport']);

    // ROUTE FOR GET ROUTE TYPE
    Route::get('allsubtype',[EmployeeController::class,'GetAllReportSubType']);
   
    Route::post('password',[PasswordResetController::class,'create']);

    //EMPLOYEE MESSAGE 
    Route::get('alladminlist',[EmployeeMessageController::class,'allAdminList']); 
    Route::post('employeeCreatethread',[EmployeeMessageController::class,'EmployeeCreateThread']);
    Route::post('getgroups',[EmployeeMessageController::class,'index']); 
    Route::get('emessages/{group_id}/{groupable_id}',[EmployeeMessageController::class,'GetGroupMemberAndMesages']);
    Route::post('esendmessage',[EmployeeMessageController::class,'EmployeeSendMessageInGroup']);
    
    // EMPLOYEE SHIFT BY PROPERTY LOCATION
    Route::get('employeepropertylocation/{employee_id}',[EmployeeController::class,'ShowAssignShitClientPropertyLocation']);
    Route::get('propertylocation/{employee_id}',[EmployeeController::class,'ShowCurrentShiftPropertyLocation']);
    // Route::get('RepeatHourlyReport/{id}/{sid}/{date}', [EmployeeController::class,'RepeatHourlyReport']);

    Route::post('cancelrequest',[EmployeeController::class,'CancelRequest']);
    Route::post('onbreak',[EmployeeController::class,'OnBreak']);
    Route::post('ontravel',[EmployeeController::class,'onTravel']);

    Route::post('delete_employee', [EmployeeController::class,'destroy']);

    Route::get('travelproperty/{employee_id}',[EmployeeController::class,'travelProperty']);
    Route::post('forcedClockOut',[EmployeeController::class,'forcedClockOut']);
    Route::post('Checkerrorinapi',[EmployeeController::class,'CheckErrorInAPI']);

    Route::get('allNotication/{id}',[EmployeeNotification::class,'allNotication']);
    Route::post('updateNotication',[EmployeeNotification::class,'updateNotication']);

//});
//************************************ END EMPLOYEE ROUTES ********************************//