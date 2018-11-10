<?php
 
namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Searchers\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Clinic as Clinic;

use App\HCP as HCP;
use App\Speciality as Speciality;

use App\Managers\ClinicManager as ClinicManager;

class ClinicController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Clinic Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the clinic
	| profile.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('auth:api');
    }
	
	protected function manager()
	{
		return new ClinicManager();
	}
	
	public function search(Request $request )
	{		
		return $this->manager()->search( $request->all() );
	}
	
	public function get_profile(Request $request )
	{
		return $this->manager()->get_profile( Auth::guard('api')->user(), $request->all() );
	}
	
	public function update_profile(Request $request )
	{
		return $this->manager()->update_profile( Auth::guard('api')->user(), $request->all() );
	}
	
	/*
	* Adds to the current clinic the list of hcp and specialities
	*/
	public function add_hcp_specialities( Request $request)
	{
		return $this->manager()->add_hcp_specialities( Auth::guard('api')->user(), $request->all() );
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter
	*/
	public function search_hcp_specialities(Request $request)
	{
		return $this->manager()->search_hcp_specialities( $request->all() );
	}	
	
	/*
	* Adds to the current clinic schedule a hcp and speciality
	*/
	public function add_schedule( Request $request)
	{
		return $this->manager()->add_schedule( Auth::guard('api')->user(), $request->all() );
	}
	
	/*
	* Gets all the hcps and their specialities that meet the filter in the schedule
	*/
	public function search_schedule(Request $request)
	{
		return $this->manager()->search_schedule( $request->all() );
	}
	
	/*
	* Gets all the appointmens in the current clinic
	*/
	
	public function search_appointments( Request $request){
		return $this->manager()->search_appointments( Auth::guard('api')->user(), $request->all() );
	}		
	
	/*
	* Gets all the medical records in the current clinic
	*/
	
	public function search_medical_records( Request $request){
		return $this->manager()->search_medical_records( Auth::guard('api')->user(), $request->all() );
	}	
}
