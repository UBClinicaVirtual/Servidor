<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class HCP extends Model
{
	protected $table = 'HCPs';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'registration_number', 'identification_number', 
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
	
	/*
	* The specialities that belong to the HCP
	*/
	
	public function specialities()
	{
		return $this->belongsToMany('App\Speciality', 'HCPSpecialities', 'id_hcp', 'id_speciality' );
	}
}
