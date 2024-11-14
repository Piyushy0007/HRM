<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Closure;
use JWTAuth;
use Exception;

class Authenticate extends Middleware
{


    // public function handle($request, Closure $next)
    // {
    //     //dd($request);
    //     try {
    //         $user = JWTAuth::toUser($request->input('access_token'));
    //     } catch (Exception $e) {
    //         if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
    //             return response()->json(['error'=>'Token is Invalid']);
    //         }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
    //             return response()->json(['error'=>'Token is Expired']);
    //         }else{
    //             return response()->json(['error'=>'Something is wrong']);
    //         }
    //     }
    //     return $next($request);
    // }
   
   
    protected function redirectTo($request)
    {
        //dd($request->expectsJson() );
        if (! $request->expectsJson()) {
            //return('message');
            return route('login');
        }
    }
}
