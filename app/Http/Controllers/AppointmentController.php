<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    static public function search(Request $request)
	{
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
	}
}
