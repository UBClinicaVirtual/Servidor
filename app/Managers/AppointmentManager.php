<?php

namespace App\Managers;

use Validator;
use App\User as User;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\Searchers\ScheduleSearch\ScheduleSearch as ScheduleSearch;
use App\Searchers\MedicalRecordSearch\MedicalRecordSearch as MedicalRecordSearch;
use App\ClinicAppointmentSchedule as Schedule;
use App\Appointment as Appointment;
use App\Patient as Patient;
use App\PatientMedicalRecord as PatientMedicalRecord;
use App\AppointmentStatus;

class AppointmentManager 
{
	const APPOINTMENT_PENDING = 1;
	const APPOINTMENT_CANCELLED = 2;
	const APPOINTMENT_COMPLETE = 3;
	
	protected function validateRequestAppointmentSearch(array $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request, 
								[
									"patient_id" => "integer",
									"hcp_id" => "integer",
									"clinic_id" => "integer",
								]		
								);
	}

	protected function validateRequestAppointmentAvailableSearch(array $request)
	{
		/*
			TODO: this should have in consideration searching by many another fields
			like HCP Speciality, appointment disponibility, etc.
		*/
		
		return Validator::make(	$request, 
								[
									"clinic_id" => "integer",
									"hcp_id" => "integer",
									"speciality_id" => "required|integer",									
									"date_from" => "required|date|date_format:Y-m-d",
									"date_to" => "required|date|date_format:Y-m-d",
								]		
								);
	}
	
	protected function validateRequestTakeAppointment(array $request)
	{		
		return Validator::make(	$request, 
								[
									"clinic_appointment_schedule_id" => "required|integer",									
									"appointment_date" => "required|date|date_format:Y-m-d",									
								]		
								);
	}	
	
	protected function validateRequestCancelAppointment(array $request)
	{		
		return Validator::make(	$request, 
								[
									"appointment_id" => "required|integer",									
								]		
								);
	}	
	
	protected function validateRequestAddRecord(array $request)
	{		
		return Validator::make(	$request, 
								[
									"appointment_id" => "required|integer",									
									"description" => "required|string|max:1024",									
								]		
								);
	}
	
    public function search(array $request)
	{
		//get the validator for the search
		$validator = $this->validateRequestAppointmentSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return AppointmentSearch::apply_filters( $request );	
	}	
	
	protected function day_of_the_week($a_date)
	{
		return date('N', strtotime($a_date));
	}
	
	protected function get_schedules_of_day( $schedules, $a_day_of_the_week )
	{
		return array_filter($schedules, function( $value ) use($a_day_of_the_week){ return intval($value["day_of_the_week"]) == intval($a_day_of_the_week); } );
	}
	
	protected function get_schedule(array $request)
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
		return json_decode( ScheduleSearch::apply_filters( $request ), true );
	}
	
	protected function get_taken_appointments(array $request, $schedules )
	{
		$appointment_filter = [
			"statuses_id" => [ self::APPOINTMENT_PENDING, self::APPOINTMENT_COMPLETE ],
			"date_range" => [ $request["date_from"], $request["date_to"] ],
			"schedules_id" => array_map(function($element) { return $element['id']; }, $schedules )
		];
		
		//Gets the taken appointments
		$taken_appointments = json_decode( AppointmentSearch::apply_filters( $appointment_filter ), true );	
		
		return array_combine( array_map(function($element) { return date('Y-m-d', strtotime($element['appointment_date'])).'-'.$element['clinic_appointment_schedule_id']; }, $taken_appointments), $taken_appointments); 	
	}
	
    public function search_available(array $request)
	{
		//get the validator for the search
		$validator = $this->validateRequestAppointmentAvailableSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get the current date
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$current_date = date("Y-m-d H:i:00");
			
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
				//Get the appointment date
				$appointment_date  = date('Y-m-d ', strtotime($date));
				$appointment_date .= date('H:i', strtotime($schedule['appointment_hour'] ) );
				$appointment_date .= ':00';
								
				//If the schedule dont have a appointment associated
				//and the appointment_date isnt expired
				if( !array_key_exists( $date.'-'.$schedule["id"], $taken_appointments ) && ( $appointment_date > $current_date ) )
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
	
	public function schedule_appointment(User $user, array $request)
	{
		$patient = $user->patient()->firstOrFail();
		
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
		$appointment = Appointment::where( "clinic_appointment_schedule_id", $request["clinic_appointment_schedule_id"])
									->where("appointment_date", $appointment_date)
									->whereIn("appointment_status_id", [ self::APPOINTMENT_COMPLETE, self::APPOINTMENT_PENDING ])
									->first();

		if( $appointment["id"] != null)
			return response()->json( [ "msg" => 'an appointment was already taken for the date '. $appointment_date ], 403);					
		
		// loads the fields for the appoinment
		$appointment = new Appointment;
		
		$appointment->appointment_date = $appointment_date;
		$appointment->clinic_appointment_schedule_id = $request["clinic_appointment_schedule_id"];
		$appointment->patient_id = $patient->id;
		$appointment->appointment_status_id = self::APPOINTMENT_PENDING;
		
		$appointment->save();
		
		//Returns the created appointment
		return response()->json(['appointment' => AppointmentSearch::apply_filters( ["appointment_id" => $appointment->id] ) ], 200);
		
	}
		
	public function all_status(array $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
	
	protected function is_appointment_of_user( $appointment, $user_profile)
	{
		if( $user_profile["user"]["user_type_id"] == User::USER_TYPE_PATIENT )
			return $user_profile["patient"]["id"] == $appointment->patient_id;
		else if( $user_profile["user"]["user_type_id"] == User::USER_TYPE_HCP )
			return $user_profile["hcp"]["id"] == $appointment->hcp_id;
		else if( $user_profile["user"]["user_type_id"] == User::USER_TYPE_CLINIC )
			return $user_profile["clinic"]["id"] == $appointment->hcp_id;
		
		return false;
	}
	
	public function cancel_appointment(User $user, array $request)
	{
		//get the validator for the appointment to be taken
		$validator = $this->validateRequestCancelAppointment( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the user profile to validate if its a member of the scheduled appointment
		$profile = $user->get_profile();
		
		$appointment_id = $request["appointment_id"];
		
		//Get the appointment by id
		$appointment = AppointmentSearch::apply_filters( [
																"appointment_id" => $appointment_id, 
																"statuses_id" => [ self::APPOINTMENT_PENDING ] 
														] );
		
		//if the appointment dont exists
		if( count( $appointment ) == 0)
			return response()->json( [ "msg" => "you cant cancel an appointment that isnt pending" ], 403);
		
		//There should be only one with that id
		$appointment = $appointment[0];
		
		//checks if the appointment is of the current user
		if( !$this->is_appointment_of_user($appointment, $profile))
			return response()->json( [ "msg" => "you cant cancel an appointment that isnt yours" ], 403);		
		
		//make the cancelation of the appointment
		$appointment = Appointment::find($appointment_id);
		$appointment->appointment_status_id = self::APPOINTMENT_CANCELLED;
		$appointment->save();
		
		return response()->json(['appointment' => AppointmentSearch::apply_filters( ["appointment_id" => $appointment_id] ) ], 200);
	}
	
	public function add_record(User $user, array $request)
	{
		//get the validator for the appointment to be taken
		$validator = $this->validateRequestAddRecord( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);
		
		//Get the user profile to validate if its a member of the scheduled appointment
		$profile = $user->hcp()->firstOrFail();
		
		$appointment_id = $request["appointment_id"];
		
		//Get the appointment by id
		$appointment = AppointmentSearch::apply_filters([
																"appointment_id" => $appointment_id, 
																"statuses_id" => [ self::APPOINTMENT_PENDING, self::APPOINTMENT_COMPLETE,  ] 
														] );
		
		//if the appointment dont exists
		if( count( $appointment ) == 0)
			return response()->json( [ "msg" => "you cant add a record to an appointment that isnt pending." ], 403);
		
		//There should be only one with that id
		$appointment = $appointment[0];
		
		//checks if the appointment is of the current hcp
		if( $appointment->hcp_id != $profile->id )
			return response()->json( [ "msg" => "you cant add a record to an appointment that isnt yours" ], 403);		
		
		//completes the appointment if its pending
		if( $appointment->appointment_status_id != self::APPOINTMENT_COMPLETE )
		{
			$appointment = Appointment::find($appointment_id);
			$appointment->appointment_status_id = self::APPOINTMENT_COMPLETE;
			$appointment->save();
		}
		
		//Adds the record to the appointment
		PatientMedicalRecord::create([
			"appointment_id" => $appointment_id,
			"description" => $request["description"]
		]);
		
		return response()->json(['medical_records' => MedicalRecordSearch::apply_filters( ["appointment_id" => $appointment_id] ) ], 200);
	}
}
