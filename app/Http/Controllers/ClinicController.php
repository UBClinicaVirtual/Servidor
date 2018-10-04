<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Searchers\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Clinic as Clinic;

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
        $this->middleware('auth:api');
    }
	
	protected function validateRequestClinicSearch(Request $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request->all(), 
								[
									"business_name" => "string|min:3"
								]		
								);
	}
	
	public function search(Request $request )
	{		
		//get the validator for the search
		$validator = $this->validateRequestClinicSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent
		$clinics = ClinicSearch::apply( $request );
		
		return response()->json( [ "clinics" => $clinics ], 200);
	}
	
	public function get_profile(Request $request )
	{
		return response()->json(['clinic' => Auth::guard('api')->user()->clinic()->first() ], 200);
	}

	public function _get_clinic_profile( $user )
	{
		$clinic = $user->clinic()->first();
			
		if( !$clinic )
		{
			//create a new Clinic based on the user id and the user data from the request
			$clinic = new Clinic();
								
			//Forces the id of the Clinic
			$clinic->id = $user->id;
		}	
		
		return $clinic;	
	}
	
	public function update_profile(Request $request )
	{
		//Gets the clinic for the user profile
		$clinic = $this->_get_clinic_profile( Auth::guard('api')->user() );
				
		//Updates the fields of the clinic
		$clinic->business_name = $request['business_name'];		
		$clinic->save();		
		
		//return the updated clinic
		return response()->json(['clinic' => $clinic ], 201);
	}
}
