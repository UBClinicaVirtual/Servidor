<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Speciality as Speciality;

class SpecialityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Speciality Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions for the specialities
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
	
	protected function validateCreationRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"name" => "required|string|min:3"
								]		
								);		
	}	
	
	protected function validateUpdateRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"name" => "required|string|min:3",
									"active" => "required|integer",
								]		
								);		
	}	
	
	protected function validateSearchRequest( Request $request )
	{
		return Validator::make(	$request->all(), 
								[
									"name" => "string|min:3",
									"active" => "integer",
								]		
								);		
	}
	
	public function create(Request $request)
	{
		//get the validator for the creation
		$validator = $this->validateCreationRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		$speciality = Speciality::create(["name" => $request["name"]]);	
		return response()->json( $speciality, 201);
	}	
	
	public function speciality(Request $request, Speciality $speciality)
	{
		return response()->json( $speciality, 201);
	}
	
	public function update(Request $request, Speciality $speciality)
	{	
		//get the validator for the update of a Speciality
		$validator = $this->validateUpdateRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		$speciality->name = $request["name"];		
		$speciality->active = $request["active"];
		
		$speciality->save();
		
		return response()->json( $speciality, 201);
	}
	
	public function search(Request $request)
	{
		//get the validator for the update of a Speciality
		$validator = $this->validateSearchRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		return response()->json( ["specialities" => Speciality::active()->get() ], 201);
	}	
}
