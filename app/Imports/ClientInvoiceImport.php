<?php

namespace App\Imports;

use App\Invoices;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class ClientInvoiceImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       
        $date = Carbon::now();
        $current_date = $date->toDateString();
//dd($collection);
        return new Invoices([
            'invoice_amount' => $collection['invoice_amount'],
            'client_id' => $collection['client_id'], 
            'cron_status' => 'pending',
            'date_posted' =>$current_date,
        ]);
       
    }
    
}
