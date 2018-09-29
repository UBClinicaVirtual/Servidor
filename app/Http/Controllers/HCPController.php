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
		
		$HCP = HCP::where( 'id', $user->id )->first();
			
		if( $HCP )
		{
			//TODO update the fields for the HCP
			$HCP->save();
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
								
			$hcp->id = $user->id;
			$hcp->specialities()->save( new Speciality( [ "name" => "Prueba", "active" => 1 ] ) );
			$hcp->specialities()->save( new Speciality( [ "name" => "Prueba2", "active" => 1 ] ) );
		}				
		
		return response()->json(['hcp' => $hcp, 'specialities' => $hcp->specialities() ], 201);
	}
}
