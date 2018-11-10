<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Managers\PatientManager as PatientManager;

class PatientController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Patient Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the patient
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
		return new PatientManager();
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

	public function search_appointments( Request $request){
		return $this->manager()->search_appointments( Auth::guard('api')->user(), $request->all() );
	}	
	
	public function search_medical_records( Request $request){
		return $this->manager()->search_medical_records( Auth::guard('api')->user(), $request->all() );
	}		
}
