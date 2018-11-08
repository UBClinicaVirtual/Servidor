<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\Searchers\ScheduleSearch\ScheduleSearch as ScheduleSearch;
use App\ClinicAppointmentSchedule as Schedule;
use App\Appointment as Appointment;
use App\Patient as Patient;

use App\AppointmentStatus;

class AppointmentController extends Controller
{
	const APPOINTMENT_PENDING = 1;
	const APPOINTMENT_CANCELLED = 2;
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

	protected function validateRequestAppointmentAvailableSearch(Request $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request->all(), 
								[
									"clinic_id" => "integer",
									"hcp_id" => "integer",
									"speciality_id" => "required|integer",									
									"date_from" => "required|date|date_format:Y-m-d",
									"date_to" => "required|date|date_format:Y-m-d",
								]		
								);
	}
	
	protected function validateRequestTakeAppointment(Request $request)
	{		
		return Validator::make(	$request->all(), 
								[
									"clinic_appointment_schedule_id" => "required|integer",									
									"appointment_date" => "required|date|date_format:Y-m-d",									
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
	
	protected function day_of_the_week($a_date)
	{
		return date('N', strtotime($a_date));
	}
	
	protected function get_schedules_of_day( $schedules, $a_day_of_the_week )
	{
		return array_filter($schedules, function( $value ) use($a_day_of_the_week){ return intval($value["day_of_the_week"]) == intval($a_day_of_the_week); } );
	}
	
	protected function get_schedule(Request $request)
	{
		//Adds the day of the week to the criteria
		$days_of_the_week = array();
		$date = $request["date_from"];
		
		while (strtotime($date) <= strtotime( $request["date_to"] ) )
		{
			if( in_array( $this->day_of_the_week($date), $days_of_the_week ) )
				break;
				
			array_push( $days_of_the_week, $this->day_of_the_week($date) );			
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		$request["day_of_the_week"] = $days_of_the_week;
				
		//Get the schedule within the criteria		
		return json_decode( ScheduleSearch::apply( $request ), true );
	}
	
	protected function get_taken_appointments(Request $request, $schedules )
	{
		$appointment_filter = new Request([
			"statuses_id" => [ self::APPOINTMENT_PENDING, self::APPOINTMENT_COMPLETE ],
			"date_range" => [ $request["date_from"], $request["date_to"] ],
			"schedules_id" => array_map(function($element) { return $element['id']; }, $schedules )
		]);
		
		//Gets the taken appointments
		$taken_appointments = json_decode( AppointmentSearch::apply( $appointment_filter ), true );	
		
		return array_combine( array_map(function($element) { return date('Y-m-d', strtotime($element['appointment_date'])).'-'.$element['clinic_appointment_schedule_id']; }, $taken_appointments), $taken_appointments); 	
	}
	
    public function search_available(Request $request)
	{
		//get the validator for the search
		$validator = $this->validateRequestAppointmentAvailableSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the clinic schedule with the criteria
		$schedules = $this->get_schedule( $request );
		
		//Gets the taken appointments of the clinic schedule
		$taken_appointments = $this->get_taken_appointments( $request, $schedules );
		
		//Creates a response with the clinic calendar and the taken appointments
		$date = $request["date_from"];
		$available_appointments = array();
		
		while (strtotime($date) <= strtotime( $request["date_to"] ) )
		{						
			//Get the schedules for that day
			$schedules_of_day = $this->get_schedules_of_day( $schedules, $this->day_of_the_week($date) );
			
			foreach( $schedules_of_day as $schedule )
			{
							
				//If the schedule dont have a appointment associated, 
				if( !array_key_exists( $date.'-'.$schedule["id"], $taken_appointments ) )
					array_push( $available_appointments, array_merge( $schedule, [
																		"appointment_date" => $date, 
																		"appointment_hour" => date('H:i', strtotime($schedule['appointment_hour'] )) 
																		] ) );					
			}
				
			$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		
		return response()->json( 
								[ 	
									"available_appointments" => $available_appointments, 
								], 200);
	}
	
	public function take_appointment(Request $request)
	{
		$patient = Auth::guard('api')->user()->patient()->firstOrFail();
		
		//get the validator for the appointment to be taken
		$validator = $this->validateRequestTakeAppointment( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);		
		
		//Gets the schedule to make an appointment
		$schedule = Schedule::findOrFail( $request["clinic_appointment_schedule_id"] );		
		
		//Validates the date of the week to make the appointment
		if( intval( $this->day_of_the_week( $request["appointment_date"] ) ) != intval( $schedule["day_of_the_week"] ) )
			return response()->json( [ "msg" => "Invalid date ".$request["appointment_date"]." for the schedule ".$request["clinic_appointment_schedule_id"]  ], 403);		
		
		//Creates the appointment date		
		$appointment_date = $request["appointment_date"]." ".date('H:i:00', strtotime($schedule["appointment_hour"]));		
		
		//Creates a new appointment for the date		
		$appointment = Appointment::firstOrNew( [
									"clinic_appointment_schedule_id" => $request["clinic_appointment_schedule_id"],
									"appointment_date" => $appointment_date,
									"appointment_status_id" => [ self::APPOINTMENT_PENDING, self::APPOINTMENT_COMPLETE ],
									]);
		if( $appointment->id > 0)
			return response()->json( [ "msg" => 'an appointment was already taken for the date '. $appointment_date ], 403);					
		
		// loads the fields for the appoinment
		$appointment->appointment_date = $appointment_date;
		$appointment->clinic_appointment_schedule_id = $request["clinic_appointment_schedule_id"];
		$appointment->patient_id = $patient->id;
		$appointment->appointment_status_id = self::APPOINTMENT_PENDING;
		
		$appointment->save();
		
		//Returns the created appointment
		return response()->json(['appointment' => $appointment ], 200);
		
	}
		
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
