<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patient as Patient;

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
        $this->middleware('guest', ['except' => ['update_profile', 'search']]);
    }
	
	public function search(Request $request )
	{			
		return response()->json( [ "msg" => "unimplemented method" ], 403);
	}			
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();
		
		$patient = Patient::where( 'id', $user->id )->first();
			
		if( $patient )
		{
			//TODO update the fields for the patient
			$patient->save();
		}
		else
		{
			//create a new patient based on the user id and the user data from the request
			$patient = Patient::create([
											'id' => $user->id,
										]);
		}				
		
		return response()->json(['patient' => $patient ], 201);
	}	
}
