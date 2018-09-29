<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HCP as HCP;
use App\Speciality as Speciality;

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
        $this->middleware('guest', ['except' => ['update_profile', 'search']]);
    }
	
	public function search(Request $request )
	{			
		return response()->json( [ "msg" => "unimplemented method" ], 403);
	}			

	protected function validateProfileRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"name" => "required|string|min:3",
									"registration_number" => "required|string|min:3",
									"identification_number" => "required|string|min:3",
								]		
								);		
	}
	
	public function update_profile(Request $request )
	{

		//get the validator for the creation
		$validator = $this->validateProfileRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Searchs for the HCP of the logged user
		$user = Auth::guard('api')->user();
		
		$hcp = HCP::where( 'id', $user->id )->first();
			
		if( !$hcp )
		{
			//create a new HCP based on the user id and the user data from the request
			$hcp = new HCP();
								
			//Forces the id of the HCP
			$hcp->id = $user->id;
		}
		
		//Updates the fields
		$hcp->name = $request["name"];
		$hcp->registration_number = $request["registration_number"];
		$hcp->identification_number = $request["identification_number"];
		$hcp->save();
		
		// Adds all the specialities sent
		if( $request->has('specialities') )
		{	
			$specialities = $request['specialities'];
			
			foreach( $specialities as $speciality_id )
			{
				//Only adds the non existant specialities
				if( !$hcp->specialities()->where('id_speciality', $speciality_id )->exists() )
				{
					$speciality = Speciality::where('id', $speciality_id )->first();			
					$hcp->specialities()->save( $speciality );
				}
			}
		}		
		
		return response()->json([ 'hcp' => ['hcp' => $hcp, 'specialities' => $hcp->specialities()->get() ] ], 201);
	}
}
