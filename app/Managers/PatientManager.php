<?php

namespace App\Managers;

use Validator;

use App\User as User;
use App\Patient as Patient;

use App\Managers\AppointmentManager as AppointmentManager;
use App\Managers\MedicalRecordManager as MedicalRecordManager;

class PatientManager
{
    /*
    |--------------------------------------------------------------------------
    | Patient Manager
    |--------------------------------------------------------------------------
    |
    | This manager handles the functions aviable for the users with the patient
	| profile.
    |
    */
	
	public function search(array $request )
	{			
		return response()->json( [ "msg" => "unimplemented method" ], 403);
	}

	/*
	| validate the request has at least a name and a identification number ( DNI )
	*/
	protected function validateProfileRequest( array $request )
	{
		return Validator::make(	$request, 
								[
									"patient.first_name" => "required|string|min:3",
									"patient.last_name" => "required|string|min:3",
									"patient.address" => "string|min:3",
									"patient.phone" => "string|min:3",
									"patient.birth_date" => "required|date|date_format:Y-m-d",
									"patient.gender_id" => "required|integer",
									"patient.identification_number" => "required|string|min:3",
								]		
								);		
	}
	
	/*
	| gets the patient from the user profile
	| TODO: change to use the eloquent relationship
	*/
	protected function get_patient_from_user( User $user )
	{
		$patient = $user->patient()->first();
			
		if( !$patient )
		{
			//create a new Patient based on the user id and the user data from the request
			$patient = new Patient();
								
			//Forces the id of the Patient
			$patient->user_id = $user->id;
		}	
		
		return $patient;
	}
	
	public function get_profile( User $user, array $request )
	{		
		return response()->json(['patient' => $user->patient()->first() ], 200);
	}
	
	public function update_profile( User $user, array $request )
	{
		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the patient profile from the user
		$patient = $this->get_patient_from_user( $user );
		
		//update the fields for the patient
		$patient->first_name = $request["patient"]["first_name"];
		$patient->last_name = $request["patient"]['last_name'];
		$patient->gender_id = $request["patient"]['gender_id'];
		$patient->birth_date = $request["patient"]['birth_date'];
		$patient->address = $request["patient"]['address'];
		$patient->phone = $request["patient"]['phone'];
		$patient->identification_number = $request["patient"]["identification_number"];
		$patient->save();
		
		//Returns the updated profile
		return response()->json(['patient' => $patient ], 201);
	}

	public function search_appointments( User $user, array $request){
		$request['patient_id'] = $user->patient()->first()->id;
		
		return response()->json(['appointments' => ( new AppointmentManager() )->search( $request ) ], 200);
	}	
	
	public function search_medical_records( User $user, array $request){
		$request['patient_id'] = $user->patient()->first()->id;
		
		return response()->json(['medical_records' => ( new MedicalRecordManager() )->search( $request ) ], 200);
	}		
}
