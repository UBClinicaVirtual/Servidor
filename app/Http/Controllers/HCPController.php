<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HCP as HCP;
use App\Speciality as Speciality;

use App\Http\Controllers\AppointmentController as AppointmentController;

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
	
	public function search(Request $request )
	{			
		return response()->json( [ "msg" => "unimplemented method" ], 403);
	}			

	protected function validateProfileRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"first_name" => "required|string|min:3",
									"last_name" => "required|string|min:3",
									"address" => "string|min:3",
									"phone" => "string|min:3",
									"birth_date" => "required|date|date_format:Y-m-d",
									"gender_id" => "required|integer",
									"identification_number" => "required|string|min:3",
									"register_number" => "required|string|min:3",
								]		
								);		
	}
	
	public function add_specialities( HCP $hcp, array $specialities )
	{
	/*
		foreach( $specialities as $speciality_id )
		{
			//Only adds the non existant specialities
			if( !$hcp->specialities()->where('speciality_id', $speciality_id )->exists() )
			{
				$speciality = Speciality::where('id', $speciality_id )->first();			
				$hcp->specialities()->save( $speciality );
			}
		}	
	*/
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
		$hcp->first_name = $request["first_name"];
		$hcp->last_name = $request['last_name'];
		$hcp->gender_id = $request['gender_id'];
		$hcp->birth_date = $request['birth_date'];
		$hcp->address = $request['address'];
		$hcp->phone = $request['phone'];
		$hcp->identification_number = $request["identification_number"];
		$hcp->register_number = $request["register_number"];
		$hcp->save();
		
		// Adds all the specialities sent
		if( $request->has('specialities') )		
			$this->add_specialities( $hcp, $request['specialities'] );
					
		return $this->get_profile( $request );
	}
	
	public function search_appointments( Request $request){
		return response()->json(['appointments' => AppointmentController::search( $request ) ], 200);
	}
}
