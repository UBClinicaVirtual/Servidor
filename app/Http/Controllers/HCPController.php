<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HCP as HCP;
use App\Speciality as Speciality;

use App\Http\Controllers\AppointmentController as AppointmentController;
use App\Http\Controllers\MedicalRecordController as MedicalRecordController;
use App\Searchers\HCPSearch\HCPSearch as HCPSearch;

class HCPController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HCP Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the HCP
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
	
	protected function validateRequestHCPSearch( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"clinic_id" => "integer",								
									"speciality_id" => "integer",								
								]		
								);		
	}
		
	public function search(Request $request )
	{			
		//get the validator for the search
		$validator = $this->validateRequestHCPSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return response()->json( [ "hcps" => HCPSearch::apply( $request ) ], 200);
	}			

	protected function validateProfileRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"hcp.first_name" => "required|string|min:3",
									"hcp.last_name" => "required|string|min:3",
									"hcp.address" => "string|min:3",
									"hcp.phone" => "string|min:3",
									"hcp.birth_date" => "required|date|date_format:Y-m-d",
									"hcp.gender_id" => "required|integer",
									"hcp.identification_number" => "required|string|min:3",
									"hcp.register_number" => "required|string|min:3",
								]		
								);		
	}
	
	public function add_specialities( HCP $hcp, array $specialities )
	{
		$hcp->specialities()->sync( $specialities );
	}
	
	protected function get_hcp_from_user( $user )
	{
		$hcp = $user->hcp()->first();
			
		if( !$hcp )
		{
			//create a new HCP based on the user id and the user data from the request
			$hcp = new HCP();
								
			//Forces the id of the HCP
			$hcp->user_id = $user->id;
		}	
		
		return $hcp;
	}
	
	public function get_profile(Request $request )
	{
		$hcp = Auth::guard('api')->user()->hcp()->first();
		
		return response()->json([ 'hcp' => array_merge( $hcp->toArray(), [ 'specialities' => $hcp->specialities()->get()] )], 200);
	}
	
	public function update_profile(Request $request )
	{

		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Searchs for the HCP of the logged user		
		$hcp = $this->get_hcp_from_user( Auth::guard('api')->user() );
		
		//Updates the fields
		$hcp->first_name = $request["hcp"]["first_name"];
		$hcp->last_name = $request["hcp"]['last_name'];
		$hcp->gender_id = $request["hcp"]['gender_id'];
		$hcp->birth_date = $request["hcp"]['birth_date'];
		$hcp->address = $request["hcp"]['address'];
		$hcp->phone = $request["hcp"]['phone'];
		$hcp->identification_number = $request["hcp"]["identification_number"];
		$hcp->register_number = $request["hcp"]["register_number"];
		$hcp->save();
		
		// Adds all the specialities sent
		if( $request->has('specialities') )		
			$this->add_specialities( $hcp, $request['specialities'] );
					
		return $this->get_profile( $request );
	}
	
	public function search_appointments( Request $request){
		$request['hcp_id'] = Auth::guard('api')->user()->hcp()->first()->id;
		return response()->json(['appointments' => AppointmentController::search( $request ) ], 200);
	}
	
	public function search_medical_records( Request $request){
		$request['hcp_id'] = Auth::guard('api')->user()->hcp()->first()->id;
		return response()->json(['medical_records' => MedicalRecordController::search( $request ) ], 200);
	}
}
