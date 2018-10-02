<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patient as Patient;

class PatientController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Patient Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the patient
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
        $this->middleware('guest', ['except' => [ 'get_profile','update_profile', 'search']]);
    }
	
	public function search(Request $request )
	{			
		return response()->json( [ "msg" => "unimplemented method" ], 403);
	}

	/*
	| validate the request has at least a name and a identification number ( DNI )
	*/
	protected function validateProfileRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"name" => "required|string|min:3",
									"identification_number" => "required|string|min:3",
								]		
								);		
	}
	
	/*
	| gets the patient from the user profile
	| TODO: change to use the eloquent relationship
	*/
	public function _get_patient_from_user( $user )
	{
		$patient = Patient::where( 'id', $user->id )->first();
			
		if( !$patient )
		{
			//create a new Patient based on the user id and the user data from the request
			$patient = new Patient();
								
			//Forces the id of the Patient
			$patient->id = $user->id;
		}	
		
		return $patient;
	}
	
	public function get_profile(Request $request )
	{
		return Auth::guard('api')->user();
	}
	
	public function update_profile(Request $request )
	{
		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the patient profile from the user
		$patient = $this->_get_patient_from_user( Auth::guard('api')->user() );
		
		//update the fields for the patient
		$patient->name = $request["name"];
		$patient->identification_number = $request["identification_number"];
		$patient->save();
		
		//Returns the updated profile
		return response()->json(['patient' => $patient ], 201);
	}	
}
