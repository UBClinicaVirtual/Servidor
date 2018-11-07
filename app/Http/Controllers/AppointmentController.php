<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\AppointmentStatus;

class AppointmentController extends Controller
{
	static protected function validateRequestAppointmentSearch(Request $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
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
		$validator = static::validateRequestAppointmentSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return AppointmentSearch::apply( $request );	
	}
	
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
