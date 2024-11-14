<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;
use App\Admin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\QueryException;
use Exception;
use JWTFactory;
use JWTAuth;
use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminMainController extends Controller
{
    protected $redirectTo = '';
    
    protected function redirectTo($request)
    {
        return route('adminlogin');
    }
       
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['logout', 'register']]);
        
    // }
    protected function guard()
	{
	    return Auth::guard('users');
	}

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'status'=>true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->guard('users')->factory()->getTTL() * 3600, 
            'user' => auth()->guard('users')->user()
        ]);
    }

    /**
     * check the token in Header.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function bearerToken()
    {
        $header = $this->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }


    public function AdminRegister(Request $request)
    {

       // Validate requested data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:tblm_employee',
            'password' => 'required|string|min:6',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }     
     
        $user = Admin::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
        
    }

	/**
	* admin web login check infomation and redirect 
	*/
	public function AdminLogin(Request $request){
        //dd("reach");
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $user = Admin::where('email', $request->email)->first();
        if ($user) {
        
            if (Hash::check($request->password, $user->password)) {

                if (! $token = auth()->guard('users')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return response()->json(['error' => 'Unauthoriz'], 401);
                }
                return $this->createNewToken($token);
            } else {
               
                $response = ["message" => "Password is incorrrect"];
                return response($response, 422);
            }

        } else {
            $response = ["message" =>'Email is not found'];
            return response($response, 422);
        }
        
    }

    /**
    * admin Logout
    */
    public function Adminlogout(Request $request) {
        auth()->guard('users')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->guard('users')->refresh());
    }


    /**
     * Invalidate the token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidate() {
        return auth()->guard('users')->invalidate();
    }


}
