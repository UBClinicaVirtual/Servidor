<?php

namespace App\Http\Controllers\Auth;

//Google includes
use Google_Client; 
use Google_Service_Gmail;
use Google_Service_Oauth2;

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

    protected $_access_token = '';
    
    function __construct( $access_token )
    {
        $_access_token = $access_token;
    }

    public function get_user_info( )
    {
		//Get from google the profile info to register
		$client = new Google_Client();
		$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
		$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);		
		$client->setAccessToken( $this->_access_token );

		$oauth2 = new Google_Service_Oauth2($client);	
        $userInfo = $oauth2->userinfo_v2_me->get();	
        
        return [ "name" => $userInfo->name, 'email' => $userInfo->email ];
    }

}

?>