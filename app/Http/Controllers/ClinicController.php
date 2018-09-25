<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClinicController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Clinic Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the clinic
	| profile.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['update_profile']]);
    }
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();
		
		//TODO: remove global classnames
		$clinic = \App\Clinic::where( 'id', $user->id )->first();
			
		if( $clinic )
		{
			//i update the fields for the existing clinic
		}
		else
		{
			$clinic = \App\Clinic::create([
											'id' => $user->id,
											'business_name' => $request['business_name'],
										]);
		}				
		
		return response()->json(['clinic' => $clinic ], 201);
	}
}
