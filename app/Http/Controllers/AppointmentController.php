<?php

namespace App\Http\Controllers;

use Auth;
use App\User as User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Searchers\AppointmentSearch\AppointmentSearch as AppointmentSearch;
use App\Searchers\ScheduleSearch\ScheduleSearch as ScheduleSearch;
use App\Searchers\MedicalRecordSearch\MedicalRecordSearch as MedicalRecordSearch;
use App\ClinicAppointmentSchedule as Schedule;
use App\Appointment as Appointment;
use App\Patient as Patient;
use App\PatientMedicalRecord as PatientMedicalRecord;
use App\AppointmentStatus;

use App\Managers\AppointmentManager as AppointmentManager;

class AppointmentController extends Controller
{		
	protected function manager()
	{
		return new AppointmentManager();
	}
	
    public function search(Request $request)
	{
		return $this->manager()->search( $request->all() );
	}	
	
	public function search_available(Request $request)
	{
		return $this->manager()->search_available( $request->all() );
	}
	
	public function schedule_appointment(Request $request)
	{		
		return $this->manager()->schedule_appointment( Auth::guard('api')->user(), $request->all() );
	}
		
	public function all_status(Request $request)
	{
		return $this->manager()->all_status( $request->all() );
	}	
	
	public function cancel_appointment(Request $request)
	{
		return $this->manager()->cancel_appointment( Auth::guard('api')->user(), $request->all() );
	}
	
	public function add_record(Request $request)
	{
		return $this->manager()->add_record( Auth::guard('api')->user(), $request->all() );
	}
}
