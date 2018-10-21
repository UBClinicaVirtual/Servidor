<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'business_name', 'business_number', 'address', 'phone', 
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
	
	/**
     * Searchs clinics with a likely business_name
     *
     * @var array
     */
	 
	public function scopeBusiness_name( $query, $business_name )
	{
		return $query->where( 'business_name', 'like', '%' . $business_name . '%' );
	}
		
	/*
	* The hcps that belong to the Clinic
	*/
	
	public function hcps()
	{		
		return $this->joins_specialities( $this->joins_hcps( $this->hcpspecialities() ) )
					->select('hcps.*' );									
	}		
	
	/*
	* The Specialities that belong to the Clinic
	*/
	
	public function specialities()
	{
		return $this->joins_specialities( $this->hcpspecialities() )					
					->select('specialities.*');		
	}	

	public function hcp_specialities()
	{		
		return $this->belongsToMany('App\HCPSpeciality', 'clinic_hcp_specialities', 'clinic_id', 'hcp_speciality_id2' );
	}

	protected function joins_hcps( $query )
	{
		return $query->join('hcps', 'hcps.id', '=', 'HCPSpecialities.hcp_id');
	}

	protected function joins_specialities( $query )
	{
		return $query->join('specialities', 'specialities.id', '=', 'hcp_specialities.speciality_id');
	}	
} 
