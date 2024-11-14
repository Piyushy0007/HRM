<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetInstruction;

class ClientWebForgetController extends Controller
{

    
    use SendsPasswordResetEmails;
    
  

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

    function password_generate($chars)
    {
        $data = '1234567890ABCDEFGHIJKdfh64LMNOP5a5sf90QRSTUVWX133YZabcefghijkGH546lmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }

    public function sendResetLinkEmail(Request $request)
    {
       
        // Validate user input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255' ],
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->errors()]);
        }

        $generatedRandomPassword = $this->password_generate(12);
        $getEmail = $this->credentials($request);
        // $employee = Client::where('email','=', $getEmail)->get();
        // $employee->password = Hash::make($generatedRandomPassword);
        // $employee->plain_password = $generatedRandomPassword;
        // $employee->save();

        $update_data = ['password' => Hash::make($generatedRandomPassword),'plain_password'=>$generatedRandomPassword];
        $user = Client::where('email', $getEmail)->update($update_data);
        $newuser = Client::where('email', $getEmail)->first();
       
        $when = now()->addMinutes(1);
        Mail::to($getEmail)->later($when, new ResetInstruction($newuser));

        return response('Client Image updated!', 204);

        // // Attempt to send the password reset email to user.  
        // $response = $this->broker('clients')->sendResetLink(
        //     $this->credentials($request)
        // );
        
        // //dd($this->SendsPasswordResetEmails());
        // // After attempting to send the link, we can examine the response to see 
        // // the message we need to show to the user and then send out a 
        // // proper response.
        
        // return $response == Password::RESET_LINK_SENT
        //             ? $this->sendResetLinkResponse($request, $response)
        //             : $this->sendResetLinkFailedResponse($request, $response);
    }
    /**
     * Send the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */
    // protected function sendResetLinkResponse(Request $request, $response)
    // {
        
    //     // On success, a string $response is returned with value of RESET_LINK_SENT 
    //     // from the Password facade (the default is "passwords.sent") 
    //     // Laravel trans() function translates this response to the text  
    //     // designated in resources/lang/en/passwords.php

    //     return response()->json(['status'=>false,"message" => trans($response) ]);   
    // }
    /**
     * Send the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {        
        return response()->json(['error' => ["message" => trans($response)] ], 422);    
    }



     /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    // public function sendResetLinkEmail(Request $request)
    // {
    //     $this->validateEmail($request);

    //     // We will send the password reset link to this user. Once we have attempted
    //     // to send the link, we will examine the response then see the message we
    //     // need to show to the user. Finally, we'll send out a proper response.
    //     $response = $this->broker()->sendResetLink(
    //         $this->credentials($request)
    //     );

    //     return $response == Password::RESET_LINK_SENT
    //                 ? $this->sendResetLinkResponse($request, $response)
    //                 : $this->sendResetLinkFailedResponse($request, $response);
    // }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
                    ? new JsonResponse(['message' => trans($response)], 200)
                    : back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // protected function sendResetLinkFailedResponse(Request $request, $response)
    // {
    //     if ($request->wantsJson()) {
    //         throw ValidationException::withMessages([
    //             'email' => [trans($response)],
    //         ]);
    //     }

    //     return back()
    //             ->withInput($request->only('email'))
    //             ->withErrors(['email' => trans($response)]);
    // }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('clients');
    }

}