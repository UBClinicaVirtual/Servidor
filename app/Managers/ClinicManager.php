<?php
 
namespace App\Managers;

use Validator;
use App\Searchers\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Clinic as Clinic;

use App\HCP as HCP;
use App\Speciality as Speciality;

use App\Managers\AppointmentManager as AppointmentManager;
use App\Managers\MedicalRecordManager as MedicalRecordManager;

class ClinicManager
{
    /*
    |--------------------------------------------------------------------------
    | Clinic Manager
    |--------------------------------------------------------------------------
    |
    | This manager handles the functions aviable for the users with the clinic
	| profile.
    |
    */
	
	protected function validateRequestClinicSearch(array $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request, 
								[
									"business_name" => "string|min:3",
								]		
								);
	}
	
	protected function validateProfileRequest( array $request )
	{
		return Validator::make(	$request, 
								[
									"clinic.business_name" => "required|string|min:3",
									"clinic.business_number" => "required|string|min:8",
									"clinic.address" => "required|string|min:3",
									"clinic.phone" => "required|string|min:3",
								]		
								);		
	}
	
	public function search(array $request )
	{		
		//get the validator for the search
		$validator = $this->validateRequestClinicSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent
		$clinics = ClinicSearch::apply_filters( $request );
		
		return response()->json( [ "clinics" => $clinics ], 200);
	}
	
	public function get_profile( User $user, array $request )
	{
		$clinic = $user->clinic()->firstOrFail();
		
		return response()->json(['clinic' => array_merge( 
															$clinic->toArray(), 
															[ "hcp_specialities" => $clinic->hcp_specialities()->get() ],
															[ "hcps" => $clinic->hcps()->get() ],
															[ "specialities" => $clinic->specialities()->get() ]
															
														)], 200);
	}

	protected function get_clinic_profile( $user )
	{
		$clinic = $user->clinic()->first();
			
		if( !$clinic )
		{
			//create a new Clinic based on the user id and the user data from the request
			$clinic = new Clinic();
								
			//Forces the id of the Clinic
			$clinic->user_id = $user->id;
		}	
		
		return $clinic;	
	}
	
	public function update_profile( User $user, array $request )
	{
		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Gets the clinic for the user profile
		$clinic = $this->get_clinic_profile( $user );
				
		//Updates the fields of the clinic
		$clinic->business_name = $request["clinic"]['business_name'];		
		$clinic->business_number = $request["clinic"]['business_number'];		
		$clinic->address = $request["clinic"]['address'];		
		$clinic->phone = $request["clinic"]['phone'];		
		$clinic->save();		
		
		//return the updated clinic
		return $this->get_profile( $request );
	}
	
	/*
	* Adds to the current clinic the list of hcp and specialities
	*/
	public function add_hcp_specialities( User $user, array $request)
	{
		$clinic = $user->clinic()->firstOrFail();
				
		foreach( $request['hcp_specialities_id'] as $hcp_speciality_id )
			$clinic->clinic_hcp_specialities()->create( [ "hcp_speciality_id" => $hcp_speciality_id ] );
		
		$clinic->save();
		
		return $this->get_profile( $request );
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter
	*/
	public function search_hcp_specialities(array $request)
	{
		return response()->json(['hcps' => [] ], 200);
	}	
	
	/*
	* Adds to the current clinic schedule a hcp and speciality
	*/
	public function add_schedule( User $user, array $request)
	{
		$clinic = $user->clinic()->firstOrFail();

		$hcps = $request['hcps'];

		foreach( $hcps as $hcp )
		{
			$specialities = $hcp['specialities'];

			$hcp = HCP::findOrFail( $hcp['hcp_id'] );

			foreach( $scpecialities as $speciality )
			{
				$day_of_the_week = $speciality['day_of_the_week'];
				$speciality = Speciality::findOrFail( $speciality['id_speciality'] );				
			}

		}
		
		return response()->json(['schedule' => $clinic->hcps()->get() ], 200);
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter in the schedule
	*/
	public function search_schedule(array $request)
	{
		return response()->json(['schedule' => [] ], 200);
	}
	
	/*
	* Gets all the appointmens in the current clinic
	*/
	
	public function search_appointments( User $user, array $request){
		$request['clinic_id'] = $user->clinic()->first()->id;
		
		return response()->json(['appointments' => ( new AppointmentManager())->search( $request ) ], 200);
	}		
	
	/*
	* Gets all the medical records in the current clinic
	*/
	
	public function search_medical_records( User $user, array $request){
		$request['clinic_id'] = $user->clinic()->first()->id;
		
		return response()->json(['medical_records' => ( new MedicalRecordManager() )-> search( $request ) ], 200);
	}	
}
