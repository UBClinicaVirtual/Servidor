<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\Http\Controllers\Controller;

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
        $this->middleware('guest', ['except' => ['update_profile']]);
    }
	
	public function update_profile(Request $request )
	{
		$user = Auth::guard('api')->user();

		if ($user) {			
		}
		
		return response()->json(['clinic' => $user ], 200);
	}
}
