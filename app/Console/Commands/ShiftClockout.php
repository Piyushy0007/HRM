<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Employee;
use App\Models\Clockin;
use App\Models\DetectShift;
use App\Models\ShiftEmployee;

class ShiftClockout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shift:clockout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Your Shift is close. please clockout';

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
        $ShiftwithEmpoloyee = $this->TodayShift();
        
        $current_time = date('H:i:m');
        foreach($ShiftwithEmpoloyee as $shiftemp):
            if($shiftemp['end'] <= $current_time ):
                
                $clockin_id = Clockin::where('employee_id', $shiftemp['employee']['id'])
                ->where('shiftaction','=','true')
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->orderByDesc('id')
                ->groupBy('employee_id')
                ->first();
                   
                $update =[
                    'employee_id' => $shiftemp['employee']['id'],
                    'shift_id' =>$shiftemp['id'],
                    'shiftaction' =>'false',                       
                ];
        
                // dd($clockin_id);
                $check_clock_in_id = null;
        
                if($clockin_id != ''):
                    
                    $clockin = Clockin::where('id', $check_clock_in_id)->update($update);
                    $DetectShift = new DetectShift();
                    $DetectShift->employee_id = $shiftemp['employee']['id'];
                    $DetectShift->clock_out_time = date('H:i:sa');
                    $DetectShift->shift_id = $shiftemp['id'];
                    $DetectShift->checked_id = $clockin_id->id;
                    $DetectShift->onShift = 'false';
                    $DetectShift->date = date('Y-m-d');
                    $DetectShift->save();
                    $this->info('shift:clockout Command Run successfully!');
                endif;                             
            endif;
        endforeach;       

    }


    public function TodayShift(){
       return  $shifts = Shift::with('employee')->where('added_to', 'LIKE', "%".$this->ReturnCurrentDate()."%")->get()->toArray();
    }

    public function ReturnCurrentDate(){
        $date = Carbon::now();
       return  $current_date = $date->toDateString();
    }

}
