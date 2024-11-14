<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invoices;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\ShiftEmployee;
use App\Models\Shift;
use Carbon\Carbon;

class ClientInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily Invoices to all client Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       $shifts = Shift::with(['property.client','employee'])
            ->where('added_to', 'LIKE', "%".$this->ReturnCurrentDate()."%")
            ->get()->toArray();

        
            $sum =0;
            $client_id = [];
            foreach($shifts as $shift):                
                $sum += $shift['employee']['pay_rate']*$shift['paid_hours'];
            endforeach;
          
                $invoices = new Invoices();
                $invoices->invoice_amount =  $sum;
                $invoices->client_id = $shift['property']['client']['id'];
                $invoices->status = 'overdue';
                $invoices->date_posted = Carbon::now()->toDateString();
                $invoices->save(); 

       
        $status = "overdue";
       Invoices::where('status', $status)->update(['cron_status'=>'1']);
       $this->info('Client:invoice Command Run successfully!');
    }

    // public function ReturnAllClientId(){
    //     return Client::pluck('id');
    // }

    public function ReturnCurrentDate(){
        $date = Carbon::now();
       return  $current_date = $date->toDateString();
    }


    


}
