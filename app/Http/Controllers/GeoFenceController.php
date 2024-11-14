<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Salman\GeoFence\Service\GeoFenceCalculator;
 use  GeoFence;

class GeoFenceController extends Controller
{

   
    public function distance()
	{
	    $d_calculator = new GeoFenceCalculator();

	    $distance = $d_calculator->CalculateDistance('38.199020', '-77.969658', '37.090240', '-95.712891');

	    return $distance;
	}

	// public function distance()
	// {
	//         $distance = GeoFence::CalculateDistance('38.199020', '-77.969658', '37.090240', '-95.712891');
	//         return $distance;
	// }
	public function index(){
		 return view('client.map');

	}

	


}
