<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class EmployeeAlertReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "A-HR Report";
        $getarr = $this->data['data_images'];
        $getEmail = explode(' , ',$getarr);
        if(sizeof($getEmail)==1){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->subject($subject);
        }else if(sizeof($getEmail)==2){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->subject($subject);
        }else if(sizeof($getEmail)==3){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->subject($subject);
        }else if(sizeof($getEmail)==4){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->subject($subject);
        }else if(sizeof($getEmail)==5){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->subject($subject);
        }else if(sizeof($getEmail)==6){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->attach($getEmail[5])->subject($subject);
        }else if(sizeof($getEmail)==7){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->attach($getEmail[5])->attach($getEmail[6])->subject($subject);
        }else if(sizeof($getEmail)==8){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->attach($getEmail[5])->attach($getEmail[6])->attach($getEmail[7])->subject($subject);
        }else if(sizeof($getEmail)==9){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->attach($getEmail[5])->attach($getEmail[6])->attach($getEmail[7])->attach($getEmail[8])->subject($subject);
        }else if(sizeof($getEmail)==10){
            return $this->markdown('emails.employeeReport',['data' => $this->data])->attach($getEmail[0])->attach($getEmail[1])->attach($getEmail[2])->attach($getEmail[3])->attach($getEmail[4])->attach($getEmail[5])->attach($getEmail[6])->attach($getEmail[7])->attach($getEmail[8])->attach($getEmail[9])->subject($subject);
        }else {
             return $this->markdown('emails.employeeReport',['data' => $this->data])->subject($subject);
        }
    }

    // public function attachments()
    // {
    //     $getarr = $this->data['data_images'];
    //     if($getarr){
    //         $getEmail = explode(' , ',$getarr);
    //         // foreach($getEmail as $key=>$value){
                
    //         // }
    //         return [
    //                 Attachment::fromPath($value),
    //             ];
    //     }
    // }
}