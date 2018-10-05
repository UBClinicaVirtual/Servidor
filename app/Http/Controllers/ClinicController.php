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
        $this->middleware('guest', ['except' => [ 'add_hcpspecialities', 'get_profile', 'update_profile', 'search']]);
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
	
	/*
	* Adds to the current clinic the list of hcp and specialities
	*/
	public function add_hcpspecialities( Request $request)
	{
		$clinic = Auth::guard('api')->user()->clinic()->firstOrFail();
/*		
		$hcp = HCP::findOrFail(4);
		$speciality = Speciality::findOrFail(1);
		
		$clinic->hcps()->attach( $hcp, [ 'id_speciality' => 1 ]);
*/		
		return response()->json(['clinic' => $clinic->hcps()->get() ], 200);
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter
	*/
	public function search_hcpspecialities(Request $request)
	{
		return response()->json(['hcps' => [] ], 200);
	}	
	
	/*
	* Adds to the current clinic schedule a hcp and speciality
	*/
	public function add_schedule( Request $request)
	{
		$clinic = Auth::guard('api')->user()->clinic()->firstOrFail();
/*		
		$hcp = HCP::findOrFail(4);
		$speciality = Speciality::findOrFail(1);
		
		$clinic->hcps()->attach( $hcp, [ 'id_speciality' => 1 ]);
*/
		return response()->json(['schedule' => $clinic->hcps()->get() ], 200);
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter in the schedule
	*/
	public function search_schedule(Request $request)
	{
		return response()->json(['schedule' => [] ], 200);
	}
}
