<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'active',
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
		'pivot', 
    ];
	
	/**
     * Searchs active Specialities
     *
     * @var array
    */
	 
	public function scopeActive( $query )
	{
		return $query->where( 'active', 1);
	}	
}
