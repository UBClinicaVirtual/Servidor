<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicalRecordController extends Controller
{
	static protected function validateRequestMedicalRecordSearch(Request $request)
	{		
		return Validator::make(	$request->all(), 
								[
									"patient_id" => "integer",
									"hcp_id" => "integer",
									"clinic_id" => "integer",
								]		
								);
	}
	
    static public function search(Request $request)
	{
		//get the validator for the search
		$validator = static::validateRequestMedicalRecordSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return MedicalRecordSearch::apply( $request );	
	}
}
