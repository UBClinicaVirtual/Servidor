<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\AppointmentStatus;

class AppointmentController extends Controller
{
	protected function validateRequestAppointmentSearch(Request $request)
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
		$validator = $this->validateRequestAppointmentSearch( $request );
		
		if( $validator->fails() ) 
			return response()->json( [ "msg" => $validator->errors() ], 403);

		//Get all the records that match the filter sent		
		return AppointmentSearch::apply( $request );
		
/*		
		$appointments = DB::table('appointments')
						->select(
						'appointments.id',
						'hcps.id as hcp_id', 'hcps.first_name as hcp_first_name', 'hcps.last_name as hcp_last_name', 
						'clinics.id as clinic_id', 'clinics.business_name as clinic_business_name', 
						'specialities.id as speciality_id', 'specialities.name as speciality_name', 
						'patients.id as patient_id', 'patients.first_name as patient_first_name', 'patients.last_name as patient_last_name', 
						'appointment_date',
						'appointment_status_id',
						'appointment_statuses.name as appointment_status_name'
						)						
						->join('clinic_appointment_schedule', 'clinic_appointment_schedule.id', '=', 'appointments.clinic_appointment_schedule_id')
						->join('clinic_hcp_specialities', 'clinic_hcp_specialities.id', '=', 'clinic_appointment_schedule.clinic_hcp_speciality_id' )
						->join('clinics', 'clinics.id', '=', 'clinic_hcp_specialities.clinic_id')
						->join('hcp_specialities', 'hcp_specialities.id', '=',  'clinic_hcp_specialities.hcp_speciality_id')
						->join('hcps', 'hcps.id', '=', 'hcp_specialities.hcp_id')
						->join('specialities', 'specialities.id','=','hcp_specialities.speciality_id')
						->join('patients', 'patients.id','=','appointments.patient_id')
						->join('appointment_statuses', 'appointment_statuses.id','=','appointments.appointment_status_id')
						->get();
		
		return $appointments;				
*/		
	}
	
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
