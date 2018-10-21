<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class HCP extends Model
{
	protected $table = 'hcps';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'id', 'first_name', 'last_name', 'identification_number', 'gender_id', 'user_id', 'birth_date', 'address', 'phone', 'register_number',        
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 
		'pivot', 
    ];
	
	/*
	* The specialities that belong to the HCP
	*/
	
	public function specialities()
	{
		return $this->belongsToMany('App\Speciality', 'hcp_specialities', 'hcp_id', 'speciality_id' );
	}
	
	/*
	* The clinics that belong to the HCP
	*/
	
	public function clinics()
	{
		return $this->belongsToMany('App\Clinic', 'clinic_hcp_specialities', 'hcp_id', 'clinic_id' );
	}
}
