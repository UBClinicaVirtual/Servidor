<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Searchers\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Clinic as Clinic;

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
        $this->middleware('guest', ['except' => ['update_profile', 'search']]);
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
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();
		
		//TODO: remove global classnames
		$clinic = Clinic::where( 'id', $user->id )->first();
			
		if( $clinic )
		{
			//i update the fields for the existing clinic
			$clinic->business_name = $request['business_name'];
			
			$clinic->save();
		}
		else
		{
			//create a new clinic based on the user id and the user data from the request
			$clinic = Clinic::create([
											'id' => $user->id,
											'business_name' => $request['business_name'],
										]);
		}				
		
		return response()->json(['clinic' => $clinic ], 201);
	}
}
