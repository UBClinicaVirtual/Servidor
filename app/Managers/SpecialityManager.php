<?php

namespace App\Managers;

use Auth;
use Validator;
use App\Searchers\SpecialitySearch\SpecialitySearch as SpecialitySearch;
use App\Speciality as Speciality;

class SpecialityManager
{
    /*
    |--------------------------------------------------------------------------
    | Speciality Manager
    |--------------------------------------------------------------------------
    |
    | This manager handles the functions for the specialities
    |
    */
	
	protected function validateCreationRequest( array $request )
	{
		return Validator::make(	$request, 
								[
									"name" => "required|string|min:3"
								]		
								);		
	}	
	
	protected function validateUpdateRequest( array $request )
	{
		return Validator::make(	$request, 
								[
									"name" => "required|string|min:3",
								]		
								);		
	}	
	
	protected function validateSearchRequest( array $request )
	{
		return Validator::make(	$request, 
								[
									"name" => "string|min:3",
								]		
								);
	}
	
	public function create(array $request)
	{
		//get the validator for the creation
		$validator = $this->validateCreationRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		$speciality = Speciality::create(["name" => $request["name"]]);	
		return response()->json( $speciality, 201);
	}	
	
	public function speciality(array $request, Speciality $speciality)
	{
		return response()->json( $speciality, 201);
	}
	
	public function update(array $request, Speciality $speciality)
	{	
		//get the validator for the update of a Speciality
		$validator = $this->validateUpdateRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		$speciality->name = $request["name"];		
		
		$speciality->save();
		
		return response()->json( $speciality, 201);
	}
	
	public function search(array $request)
	{
		//get the validator for the update of a Speciality
		$validator = $this->validateSearchRequest( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
			
		//Get all the records that match the filter sent	
		return response()->json( [ "specialities" => SpecialitySearch::apply( $request ) ], 200);
	}	
}
