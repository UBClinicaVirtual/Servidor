<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

require __DIR__ . '/../../../../vendor/autoload.php';

use Google_Client; 
use Google_Service_Gmail;
use Google_Service_Oauth2;

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
        $this->middleware('guest')->except('logout');
    }
	
	public function login(Request $request)
	{
		$this->validateLogin($request);
		
		$client = new Google_Client();
		$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
		$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);		
		$client->setAccessToken( $request['access_token'] );

		$oauth2 = new Google_Service_Oauth2($client);	
		$userInfo = $oauth2->userinfo_v2_me->get();
		
		$service = new Google_Service_Gmail($client);

		// Print the labels in the user's account.
		$user = 'me';
		$results = $service->users_labels->listUsersLabels($user);
			
		// calls to the laravel default function to login
		//It ll create a new api_token for each call to login
		if ($this->attemptLogin($request)) {
		
			$user = $this->guard()->user();
			$user->generateToken();

			return response()->json([
				'data' => $user->toArray(),
				'gmail' => $userInfo,
			]);
		}
	
		return $this->sendFailedLoginResponse($request);
	}
	
	public function logout(Request $request)
	{
		$user = Auth::guard('api')->user();

		if ($user) {
			$user->api_token = null;
			$user->save();
		}

		return response()->json(['data' => 'User logged out.'], 200);
	}	
}
