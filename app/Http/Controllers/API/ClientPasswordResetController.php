<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Notifications\ClientPasswordResetRequest;
use App\Notifications\ClientPasswordResetSuccess;
use App\Models\Client;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ClientPasswordResetController extends Controller
{
    //

    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $Client = Client::where('email', $request->email)->first();
        if (!$Client)
            return response()->json([
                'message' => 'We cant find a client with that e-mail address.'
            ], 404);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $Client->email],
            [
                'email' => $Client->email,
                'token' => Str::random(40)
             ]
        );
        if ($Client && $passwordReset)
            $Client->notify(
                new ClientPasswordResetRequest($passwordReset->token)
            );
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return response()->json($passwordReset);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] Client object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        $Client = Client::where('email', $passwordReset->email)->first();
        if (!$Client)
            return response()->json([
                'message' => 'We cant find a Client with that e-mail address.'
            ], 404);
        $Client->password = bcrypt($request->password);
        $Client->save();
        $passwordReset->delete();
        $Client->notify(new ClientPasswordResetSuccess($passwordReset));
        return response()->json($Client);
    }

}
