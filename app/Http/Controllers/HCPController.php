<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HCP as HCP;

use App\Managers\HCPManager as HCPManager;

class HCPController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HCP Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions aviable for the users with the HCP
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
	
	public function manager()
	{
		return new HCPManager();
	}
			
	public function search(Request $request )
	{			
		return $this->manager()->search( $request->all() );
	}			
	
	public function add_specialities( HCP $hcp, array $specialities )
	{
		return $this->manager()->add_specialities( $hcp, $request->all() );
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
