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
        $this->middleware('guest', ['except' => ['update_profile', 'search']]);
    }
	
	public function search(Request $request )
	{
		//TODO refactor to have a validateSearchRequest function
		if( 3 > strlen( $request['business_name'] ) ) 
			return response()->json( [ "msg" => "business_name is not present or has less than 3 characters" ], 403);
		
		// get the records with a name like the sent
		$clinics = \App\Clinic::business_name( $request['business_name'] )->get();
		
		return response()->json( [ "clinics" => $clinics ], 200);
	}
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();
		
		//TODO: remove global classnames
		$clinic = \App\Clinic::where( 'id', $user->id )->first();
			
		if( $clinic )
		{
			//i update the fields for the existing clinic
			$clinic->business_name = $request['business_name'];
			
			$clinic->save();
		}
		else
		{
			//create a new clinic based on the user id and the user data from the request
			$clinic = \App\Clinic::create([
											'id' => $user->id,
											'business_name' => $request['business_name'],
										]);
		}				
		
		return response()->json(['clinic' => $clinic ], 201);
	}
}
