<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patient as Patient;

use App\Http\Controllers\AppointmentController as AppointmentController;

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
        $this->middleware('auth:api');
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
	protected function get_patient_from_user( $user )
	{
		$patient = $user->patient()->first();
			
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
		return response()->json(['patient' => Auth::guard('api')->user()->patient()->first() ], 200);
	}
	
	public function update_profile(Request $request )
	{
		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the patient profile from the user
		$patient = $this->get_patient_from_user( Auth::guard('api')->user() );
		
		//update the fields for the patient
		$patient->name = $request["name"];
		$patient->identification_number = $request["identification_number"];
		$patient->save();
		
		//Returns the updated profile
		return response()->json(['patient' => $patient ], 201);
	}

	public function search_appointments( Request $request){
		return response()->json(['appointments' => AppointmentController::search( $request ) ], 200);
	}	
}
