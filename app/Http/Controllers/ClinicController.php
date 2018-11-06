<?php
 
namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Searchers\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Clinic as Clinic;

use App\HCP as HCP;
use App\Speciality as Speciality;

use App\Http\Controllers\AppointmentController as AppointmentController;
use App\Http\Controllers\MedicalRecordController as MedicalRecordController;

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
									"business_name" => "string|min:3",
								]		
								);
	}
	
	protected function validateProfileRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"business_name" => "required|string|min:3",
									"business_number" => "required|string|min:8",
									"address" => "required|string|min:3",
									"phone" => "required|string|min:3",
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
		$clinic = Auth::guard('api')->user()->clinic()->firstOrFail();
		
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
	
	public function update_profile(Request $request )
	{
		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Gets the clinic for the user profile
		$clinic = $this->get_clinic_profile( Auth::guard('api')->user() );
				
		//Updates the fields of the clinic
		$clinic->business_name = $request['business_name'];		
		$clinic->business_number = $request['business_number'];		
		$clinic->address = $request['address'];		
		$clinic->phone = $request['phone'];		
		$clinic->save();		
		
		//return the updated clinic
		return $this->get_profile( $request );
	}
	
	/*
	* Adds to the current clinic the list of hcp and specialities
	*/
	public function add_hcp_specialities( Request $request)
	{
		$clinic = Auth::guard('api')->user()->clinic()->firstOrFail();
				
		foreach( $request['hcp_specialities_id'] as $hcp_speciality_id )
			$clinic->clinic_hcp_specialities()->create( [ "hcp_speciality_id" => $hcp_speciality_id ] );
		
		$clinic->save();
		
		return $this->get_profile( $request );
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter
	*/
	public function search_hcp_specialities(Request $request)
	{
		return response()->json(['hcps' => [] ], 200);
	}	
	
	/*
	* Adds to the current clinic schedule a hcp and speciality
	*/
	public function add_schedule( Request $request)
	{
		$clinic = Auth::guard('api')->user()->clinic()->firstOrFail();

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
	public function search_schedule(Request $request)
	{
		return response()->json(['schedule' => [] ], 200);
	}
	
	/*
	* Gets all the appointmens in the current clinic
	*/
	
	public function search_appointments( Request $request){
		$request['clinic_id'] = Auth::guard('api')->user()->clinic()->first()->id;
		
		return response()->json(['appointments' => AppointmentController::search( $request ) ], 200);
	}		
	
	/*
	* Gets all the medical records in the current clinic
	*/
	
	public function search_medical_records( Request $request){
		$request['clinic_id'] = Auth::guard('api')->user()->clinic()->first()->id;
		
		return response()->json(['medical_records' => MedicalRecordController::search( $request ) ], 200);
	}	
}
