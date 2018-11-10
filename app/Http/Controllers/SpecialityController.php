<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Searchers\SpecialitySearch\SpecialitySearch as SpecialitySearch;
use App\Speciality as Speciality;
use App\Managers\SpecialityManager as  SpecialityManager;

class SpecialityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Speciality Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the functions for the specialities
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
		return new SpecialityManager();
	}
	
	public function create(Request $request)
	{
		return $this->manager()->create( $request->all() );
	}	
	
	public function speciality(Request $request, Speciality $speciality)
	{
		return $this->manager()->search( $request->all(), $speciality );
	}
	
	public function update(Request $request, Speciality $speciality)
	{	
		return  $this->manager()->update( $request->all(), $speciality );
	}
	
	public function search(Request $request)
	{
		return  $this->manager()->search( $request->all() );
	}	
}
