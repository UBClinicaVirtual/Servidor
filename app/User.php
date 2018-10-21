<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'active', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }	
	
	public function deactivate()
	{
        //I revoke the api_token to the deactivated user
        $this->api_token = null;
        $this->active = 0;
        $this->save();
    }

	public function revokeToken()
	{
        $this->api_token = null;
        $this->save();
	}

	public function clinic()
	{
		return $this->hasOne('App\Clinic' );
  }
  
	public function hcp()
	{
		return $this->hasOne('App\HCP' );
	}    
	
	public function patient()
	{
		return $this->hasOne('App\Patient' );
	}
	
	public function get_profile()
	{
		$profile = array();
		
		$profile = array_merge($profile, [ "user" => $this ] );
		
		if( $this->patient()->exists() )
			$profile = array_merge( $profile, [ "patient" => $this->patient()->first() ] );

		if( $this->hcp()->exists() )
			$profile = array_merge( $profile, [ "hcp" => $this->hcp()->first() ] );
		
		if( $this->clinic()->exists() )
			$profile = array_merge( $profile, [ "clinic" => $this->clinic()->first() ] );
		
		return $profile;
	}
}
