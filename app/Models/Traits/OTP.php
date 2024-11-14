<?php

namespace App\Models\Traits;

use Illuminate\Http\Request;
use App\Services\TwilloSms;
use App\Models\Auth\User;
use App\Notifications\Frontend\UserNotification;

/**
 * Trait OTP.
 * App\Models\Traits\OTP
 */
trait OTP
{
    public function showVerifyOTPForm() {

        if ( !$this->validateSession('otp_process') ) {
            return back()->with('flash_danger', 'Invalid Access!');
        }

        return view('frontend.auth.verify_otp');
    }

    public function validateSession(string $key) {
        return session()->has($key);
    }

    public function verifyOTP(Request $request) {
        if ( !$this->validateSession('otp_process') || !method_exists($this, session()->get('otp_process')) ) {
            return back()->with('flash_danger', 'Invalid Access!');
        }

        return $this->{session()->get('otp_process')}($request);

    }

   

    public function resendOTP()
    {
        if ( !session()->get('otp_process')
            || !method_exists($this, "resendOTP_".session()->get('otp_process'))
        ) {
            return back()->with('flash_danger', 'Invalid Access!');
        }

        $otp_process = "resendOTP_".session()->get('otp_process');

        $user = $this->{$otp_process}();

        if ( $this->sendSMS($user['dailCode'], $user['mobile'], 'mob_otp', $user['process']) ) {
            return back()->with('flash_success', 'OTP resent to your mobile number, please check!');
        }

        return back()->with('flash_danger', 'OTP failed! Try again');
    }  



    public function sendSMS($dailCode, $mobile, $otp_session, $process) {

        $otp = mt_rand(100000,999999);
        $message = "Fastglobalpay OTP for {$process} is {$otp}";
        $mobile = "+{$dailCode}{$mobile}";

        session()->put($otp_session, $otp);

        $TwilloSms = new TwilloSms();
        $response = $TwilloSms->sendSMS($mobile, $message);

        return $response ? true : false;
    }  

    public function getVerifyOTPForm() {
        return redirect()->route('frontend.auth.verifyOTP')->with('flash_success', 'OTP sent to your mobile number, please check!');
    }

}
