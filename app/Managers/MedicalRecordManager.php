<?php

namespace App\Managers;

use Validator;
use Illuminate\Support\Facades\DB;
use App\Searchers\MedicalRecordSearch\MedicalRecordSearch as MedicalRecordSearch;

class MedicalRecordManager
{
	protected function validateRequestMedicalRecordSearch(array $request)
	{		
		return Validator::make(	$request, 
								[
									"patient_id" => "integer",
									"hcp_id" => "integer",
									"clinic_id" => "integer",
								]		
								);
	}
	
    public function search(array $request)
	{
		//get the validator for the search
		$validator = static::validateRequestMedicalRecordSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return MedicalRecordSearch::apply_filters( $request );	
	}
}
