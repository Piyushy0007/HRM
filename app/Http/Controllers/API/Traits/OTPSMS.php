<?php 
namespace App\Http\Controllers\API\Traits;

use Illuminate\Http\Request;
use App\Services\TwilloSms;
use App\Models\Employee;
use Twilio\Rest\Client;
use Twilio\VersionInfo;
use Twilio\Http\Client as HttpClient;
use Twilio\Http\CurlClient;


/**
 * Trait OTP.
 * App\Models\Traits\OTP
 */
trait OTPSMS
{

    public function sendSMS($otp, $dailCode, $mobile, $process) {

        $message = "Htc-local OTP for {$process} is {$otp}";
        $mobile = "+{$dailCode}{$mobile}";

       // dd($mobile);
        $TwilloSms = new TwilloSms();
        $response = $TwilloSms->sendSMS($mobile, $message);

        return $response ? true : false;
    }


    public function sendEmailOTP($user, $otp, $process = 'reset password') {
        $data = [
            'subject_lang_key' => 'reset_password',
            'message_line' => ["Your {$process} OTP is {$otp}"]
        ];

        $user->notify(new UserNotification($data));
    }


}