<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\Searchers\ScheduleSearch\ScheduleSearch as ScheduleSearch;

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
	
	static protected function date_of_the_week($a_date)
	{
		return date('N', strtotime($a_date));
	}
	
    static public function search_available(Request $request)
	{
		//get the validator for the search
		$validator = static::validateRequestAppointmentAvailableSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Adds the day of the week to the criteria
		$days_of_the_week = array();
		$date = $request["date_from"];
		
		while (strtotime($date) <= strtotime( $request["date_to"] ) )
		{
			if( in_array( static::date_of_the_week($date), $days_of_the_week ) )
				break;
				
			array_push( $days_of_the_week, static::date_of_the_week($date) );			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		$request["day_of_the_week"] = $days_of_the_week;
		
		//return response()->json( [ "msg" => $request->toArray() ], 200);
		
		//Get the schedule within the criteria
		$schedule = ScheduleSearch::apply( $request );	

		$appointment_filter = new Request([
			"clinic_id" => $request["clinic_id"],
			"hcp_id" => $request["hcp_id"],
			"statuses_id" => [ self::APPOINTMENT_PENDING, self::APPOINTMENT_COMPLETE ],
			"date_range" => [ $request["date_from"], $request["date_to"] ],
			"schedules_id" => array_map(function($element) { return $element['id']; }, json_decode( $schedule, true ))
		]);
		
		$schedule = json_decode( $schedule, true );
		$schedule = array_combine( array_map(function($element) { return $element['id']; }, $schedule), $schedule );
		
		//Gets the taken appointments
		$taken_appointments = json_decode( AppointmentSearch::apply( $appointment_filter ), true );		
		$taken_appointments = array_combine( array_map(function($element) { return date('Y-m-d', strtotime($element['appointment_date'])).'-'.$element['clinic_appointment_schedule_id']; }, $taken_appointments), $taken_appointments); 
		
		//Creates a response with the clinic calendar and the taken appointments		
	
		$date = $request["date_from"];
		$available_appointments = array();
		
		while (strtotime($date) <= strtotime( $request["date_to"] ) )
		{			
			if( isset( $schedule[ static::date_of_the_week($date) ] ) )
				array_push( $available_appointments, array_merge( $schedule[ static::date_of_the_week($date) ], ["appointment_date" => $date ]  ) );
				
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		return response()->json( [ "schedule" => $schedule, "available_appointments" => $available_appointments, "taken_appointments" => $taken_appointments ], 200);
	}
	
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
