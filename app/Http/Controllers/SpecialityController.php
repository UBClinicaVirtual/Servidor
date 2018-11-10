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
	
	public function create(Request $request)
	{
		return SpecialityManager::create( $request->all() );
	}	
	
	public function speciality(Request $request, Speciality $speciality)
	{
		return SpecialityManager::search( $request->all(), $speciality );
	}
	
	public function update(Request $request, Speciality $speciality)
	{	
		return SpecialityManager::update( $request->all(), $speciality );
	}
	
	public function search(Request $request)
	{
		return SpecialityManager::search( $request->all() );
	}	
}
