<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Broadcasting\BroadcastController as BaseBroadcastController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class BroadcastController extends Controller
{
    public function __construct()
    {
        //
    }

    public function authenticate(Request $request) {
        $user = Auth::user();
        if ($request->hasSession()) {
            $request->session()->reflash();
        }        
        return Broadcast::auth($request);
    }

    
}
