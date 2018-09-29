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
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();
		
		$hcp = HCP::where( 'id', $user->id )->first();
			
		if( $hcp )
		{
			//TODO update the fields for the HCP
			$hcp->save();
		}
		else
		{
			//create a new HCP based on the user id and the user data from the request
			$hcp = HCP::create([
									'id' => $user->id,
									'name' => $user->name,
									'registration_number' => 'rn',
									'identification_number' => 'in',
								]);
								
			//Forces the id of the HCP
			$hcp->id = $user->id;
		}
		
		// Adds all the specialities sent
		if( $request->has('specialities') )
		{	
			$specialities = $request['specialities'];
			
			foreach( $specialities as $speciality_id )
			{
				$speciality = Speciality::where('id', $speciality_id )->first();			
				$hcp->specialities()->save( $speciality );
			}
		}		
		
		return response()->json([ 'hcp' => ['hcp' => $hcp, 'specialities' => $hcp->specialities()->get() ] ], 201);
	}
}
