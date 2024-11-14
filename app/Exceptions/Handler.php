<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Auth;
use Illuminate\Support\Arr;
use Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
       //dd($exception);
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json( [
                'status'=>false,
                'message' => $exception->getMessage(),
            ], 405 );
        }
    
        return parent::render($request, $exception);
    }
    
    
   

    protected function unauthenticated($request, AuthenticationException $exception )
    {

        $class = get_class($exception);
        //dd($exception->guards());
         switch($class) {
            case 'Illuminate\Auth\AuthenticationException':
                $guard = Arr::get($exception->guards(), 0);
               
                switch ($guard) {
                    case "users":
                        $login = "login";
                    break;
                    case "clients":
                        $login = "client_login";
                    break;
                    
                    default:
                        $login = 'login';
                    break;
              }   
              return redirect()->route($login);
         }

        if ($request->expectsJson()) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
       
    }
}
