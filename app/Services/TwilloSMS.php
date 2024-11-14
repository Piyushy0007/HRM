<?php
namespace App\Services;

use Twilio\Rest\Client;

class TwilloSms 
{
    private $account_id;
    private $auth_token;
    private $twilio_number;

    public function __construct() {
        $this->account_id = env('TWILIO_ACCOUNT_SID');
        $this->auth_token = env('TWILIO_AUTH_TOKEN');
        $this->twilio_number  = env('TWILIO_NUMBER');
    }

    public function sendSMS($mobile, $sms) {
        $client = new Client($this->account_id, $this->auth_token);
        return $client->messages->create(
            $mobile,
            array(
                'from' => $this->twilio_number,
                'body' => $sms
            )
        );
    }
   
}

