<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
		return DB::table('hcps')
				->select('hcps.*')
				->join('hcp_specialities','hcps.id', '=', 'hcp_specialities.hcp_id')
				->join('clinic_hcp_specialities','clinic_hcp_specialities.hcp_speciality_id', '=', 'hcp_specialities.id' )
				->where('clinic_hcp_specialities.clinic_id',$this->id)
				->distinct();
	}		
	
	/*
	* The Specialities that belong to the Clinic
	*/
	
	public function specialities()
	{
		return DB::table('specialities')
				->select('specialities.*')
				->join('hcp_specialities','specialities.id', '=', 'hcp_specialities.speciality_id')
				->join('clinic_hcp_specialities','clinic_hcp_specialities.hcp_speciality_id', '=', 'hcp_specialities.id' )
				->where('clinic_hcp_specialities.clinic_id',$this->id)
				->distinct();
	}	

	public function hcp_specialities()
	{		
		return $this->belongsToMany('App\HCPSpeciality', 'clinic_hcp_specialities', 'clinic_id', 'hcp_speciality_id' );
	}
	
	public function clinic_hcp_specialities( )
	{
		return $this->hasMany('App\ClinicHCPSpeciality');
	}	
} 
