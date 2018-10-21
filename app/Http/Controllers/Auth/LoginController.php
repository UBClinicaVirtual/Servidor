<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

//Includes the gmail controller to validate the access_token
use App\Http\Controllers\Auth\GmailController;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout','deactivate']]);
    }
	
	public function login(Request $request)
	{
		// TODO: we need to override the validateLogin method to check if it has an access_token field [2018/09/20 wduartes]
//		$this->validateLogin($request);
		
		//Get de gmail info to do the login
        $gc = new GmailController( $request['access_token'] );
		$userInfo = $gc->get_user_info( $request['access_token'] );
			
//		$request->merge(['email' => $userInfo->email]);
			
		// calls to the laravel default function to login
		// It ll create a new api_token for each call to login
		// ill send the gmail mail only to the laravel login function
		
		if ($this->guard()->attempt( ['email' => $userInfo['email'] ] ) ) {
		
			$user = $this->guard()->user();
			$user->generateToken();

			return response()->json( $user->get_profile() );
		}
	
		return $this->sendFailedLoginResponse($request);
	}
	
	public function logout(Request $request)
	{
		$user = Auth::guard('api')->user();

		if ($user) {
			$user->revokeToken();
		}

		return response()->json(['data' => 'User logged out.'], 200);
	}	
	
	public function deactivate(Request $request )
	{
		$user = Auth::guard('api')->user();

		if ($user) {			
			$user->deactivate();
		}
		
		return response()->json(['data' => 'User deactivated.'], 200);
	}
}
