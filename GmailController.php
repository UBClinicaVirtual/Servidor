<?php

namespace App\Http\Controllers\Auth;

//Google includes
use Google_Client; 
use Google_Service_Gmail;
use Google_Service_Oauth2;
use Illuminate\Http\Request;

class GmailController{

    /*
    |--------------------------------------------------------------------------
    | GmailController
    |--------------------------------------------------------------------------
    |
    | This controller handles the access_token send by the client application 
    | to validate it and get the user info of his gmail account.
    |
    */    

    protected $CLIENT_ID = '434044579908-ehq17fbr1utt08noe8u4kb2f66isdf4e.apps.googleusercontent.com';
    
    function __construct( )
    {
        
    }

    public function get_user_info( $access_token )
    {
        $client = new Google_Client(['client_id' => $this->CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($access_token);
        
        if ($payload)         
            return [ "first_name" => $payload['given_name'], 
					"last_name" => $payload['family_name'], 
					"email" => $payload['email'] ];
            
        return null;
    }
}

?>