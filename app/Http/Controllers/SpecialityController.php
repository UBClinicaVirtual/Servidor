<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClinicSearch\ClinicSearch as ClinicSearch;
use App\Speciality as Speciality;

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
		$speciality = Speciality::create(["name" => $request["name"]]);		
		return response()->json( $speciality, 201);
	}	
	
	public function speciality(Request $request)
	{
		return response()->json( Auth::guard('api')->user(), 201);
	}
	
	public function update(Request $request, Speciality $speciality)
	{
		return response()->json( $speciality, 201);
	}
}
