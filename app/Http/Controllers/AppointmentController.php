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
	const APPOINTMENT_PENDING = 1;
	const APPOINTMENT_COMPLETE = 3;
	
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

	static protected function validateRequestAppointmentAvailableSearch(Request $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request->all(), 
								[
									"hcp_id" => "required|integer",
									"clinic_id" => "required|integer",
									"date_from" => "required|date|date_format:Y-m-d",
									"date_to" => "required|date|date_format:Y-m-d",
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
	
    static public function search_available(Request $request)
	{
		//get the validator for the search
		$validator = static::validateRequestAppointmentAvailableSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Adds the day of the week to the criteria
		$request["day_of_the_week"] = [ min( date('N', $request["date_from"]), date('N', $request["date_to"]) ), max( date('N', $request["date_from"]), date('N', $request["date_to"]) ) ];
		
		//Get the schedule within the criteria
		$schedule = ScheduleSearch::apply( $request );	
		
		return response()->json( [ "msg" => $schedule ], 200);
/*		
		$appointment_filter = [
			"clinic_id" => $request["clinic_id"],
			"hcp_id" => $request["hcp_id"],
			"status" => [ self::APPOINTMENT_PENDING, self::APPONTMENT_COMPLETE ],
			"date_range" => [ $request["date_from"], $request["date_to"] ],
			"schedule_ids" => array_map(create_function('$o', 'return $o->id;'), $schedule)
		];
		
		return $appointment_filter;
		
		//Gets the taken appointments
		$taken_appointments = AppointmentSearch::apply([
			"clinic_id" => $request["clinic_id"],
			"hcp_id" => $request["hcp_id"],
			"status" => [ self::APPOINTMENT_PENDING, self::APPONTMENT_COMPLETE ],
			"date_range" => [ $request["date_from"], $request["date_to"] ],
			"schedule_ids" => array_map(create_function('$o', 'return $o->id;'), $schedule)
		]);

		return $taken_appointments;
*/		
	}
	
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
