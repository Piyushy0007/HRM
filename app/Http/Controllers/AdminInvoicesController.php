<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Mail\NewClient;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Client;
use App\ClientProperties;
use App\Invoices;
use Carbon\Carbon;
use JWTFactory;
use JWTAuth;
use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientInvoiceExport;



class AdminInvoicesController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        //$invoices_data = Invoices::with('client')->get()->toArray();
        // $invoices = [];
        // foreach($invoices_data as $invoice):
        //     $inv['Invoice Amount'] = $invoice['invoice_amount'];
        //     $inv['Client Name'] = $invoice['client']['contactname'];
        //     $inv['Invoice Number'] = $invoice['id'];
        //     $inv['Status'] = $invoice['status'];
        //     $inv['Date Posted'] = $invoice['date_posted'];
        //    $invoices[] = $inv;

        // endforeach;
        // $file_name = time().'invoices.xlsx';
        // $sata = Excel::download(new ClientInvoiceExport, 'invoices.xlsx');
        // return $sata;
        return Excel::download(new ClientInvoiceExport, 'invoices.xlsx');
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request) 
    {   
        $response= ['status'=>false,'message'=>'Something Wrong'];

        $files = $request->file('file');
        $fileD  = fopen($files,"r");
        $column=fgetcsv($fileD);
       
        while(!feof($fileD)){
            [$invoiceamount,$status]=fgetcsv($fileD);
            
            if($invoiceamount !='' &&  $status != ''){
                $inserted_data=[
                    'invoice_amount'=>$invoiceamount ?? '',
                    'client_id'=>$request['client_id'],
                    'status' => $status ?? '',
                    'date_posted' =>Carbon::now()->toDateString()
                ];                
                $invoice  = Invoices::create($inserted_data);
                if($invoice){
                    $response = ['status'=>true,'message'=>'File import Sucessfuly'];  
                }else{
                    $response = ['status'=>false,'message'=>'File Not Import'];  
                }
            }else{
                $response = ['status'=>false,'message'=>'File Not Import! please check file'];  
            }         
        }
        return response($response);             
    }



}
