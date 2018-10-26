<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\AppointmentStatus;

class AppointmentController extends Controller
{
    static public function search(Request $request)
	{
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
/*		
		return [ [ 	
					"id_appointment" => 753, 
					"id_clinic" => 123, 
					"clinic_name" => "Clinica de la trinidad",
					"id_speciality" => 789, 
					"speciality_name" => "Guardia de ginecologia",
					"id_hcp" => 8560, 
					"hcp_name" => "Juan Jose Ingenieros",					
					"id_patient" => 1425, 
					"patient_name" => "Jesus de Nazaret",
					"appointment_date" => "2018/01/02 12:57",
					"appointment_state" => 1,
					"appointment_state_label" => "Pending",
				], [ 	
					"id_appointment" => 8820, 
					"id_clinic" => 123, 
					"clinic_name" => "Clinica de la trinidad",
					"id_speciality" => 124, 
					"speciality_name" => "Traumatologo",
					"id_hcp" => 9988, 
					"hcp_name" => "Bernabe Marquez",					
					"id_patient" => 1024, 
					"patient_name" => "Garcia Marquez",
					"appointment_date" => "2018/04/01 16:90",
					"appointment_state" => 1,
					"appointment_state_label" => "Pending",
					],
				];
*/				
	}
	
	public function all_status(Request $request)
	{
		return response()->json(['appointment_statuses' => AppointmentStatus::all() ], 200);
	}
}
