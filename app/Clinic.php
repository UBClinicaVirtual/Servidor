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
        'id', 'business_name', 'business_number', 
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
		return $this->belongsToMany('App\HCPSpeciality', 'ClinicHCPSpecialities', 'id_clinic', 'id_hcp_speciality' )
					->withPivot('id_hcp_speciality')
					->join('HCPs', 'HCPs.id', '=', 'HCPSpecialities.id_hcp')
					->select('HCPs.*');		
/*
		return $this->hasManyThrough('App\HCPSpeciality', 
									'App\ClinicHCPSpeciality', 
									'id_clinic', 
									'id', 
									'id', 
									'id_hcp_speciality')->belongsToMany('App\HCP', 'HCPSpecialities', 'id', 'id_hcp' );
*/									
	}		
	
	/*
	* The Specialities that belong to the Clinic
	*/
	
	public function specialities()
	{
		return $this->belongsToMany('App\HCPSpeciality', 'ClinicHCPSpecialities', 'id_clinic', 'id_hcp_speciality' )
					->withPivot('id_hcp_speciality')
					->join('Specialities', 'Specialities.id', '=', 'HCPSpecialities.id_speciality')
					->select('Specialities.*');		
	}	
} 
